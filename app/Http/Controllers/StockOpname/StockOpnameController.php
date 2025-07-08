<?php

namespace App\Http\Controllers\StockOpname;

use App\Exports\ExportFormat;
use App\Http\Controllers\Controller;
use App\Imports\MasterRegistrasiImport;
use App\Imports\MasterStockOpnameImport;
use App\Imports\StockOpnameImport;
use App\Models\Master\MasterMoveOut;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StockOpnameController extends Controller
{

    function index(Request $request){

        $user = Auth::user();
        
        $conditions = DB::table('m_condition')->get();
        $tStockopname = DB::table('t_stockopname AS a')
                        ->select(
                            'a.*',
                            'b.reason_name',
                            'c.name_store_street',
                            'e.approval_name',
                            'iv.approval_name AS verify_name',
                        )
                        ->leftJoin('m_reason AS b', 'b.reason_id', '=', 'a.reason')
                        ->leftJoin('miegacoa_keluhan.master_resto AS c', 'c.id', '=', 'a.location')
                        ->leftJoin('mc_approval AS iv', 'iv.approval_id', '=', 'a.is_verify')
                        ->leftJoin('mc_approval AS e', 'e.approval_id', '=', 'a.is_confirm')
                        ->leftJoin('miegacoa_keluhan.master_resto AS f', 'f.id', '=', 'a.location');
                        if($user->hasRole('SM')){
                            $tStockopname->where(function($query) use ($user) {
                                $query->where('f.id', $user->location_now);
                            });
                        }else if($user->hasRole('AM')){
                            $tStockopname->where(function($query) use ($user) {
                                $query->where('f.kode_city', $user->location_now);
                            });
                        }else if($user->hasRole('RM')){
                            $tStockopname->where(function($query) use ($user) {
                                $query->where('f.id_regional', $user->location_now);
                            });
                        }
                        if($request->filled('start_date') && $request->filled('end_date')){
                            $tStockopname->whereBetween(DB::raw('DATE(a.create_date)'), [$request->input('start_date').' 00:00:00', $request->input('end_date'),' 23:59:59']);
                        }
                        $TSO = $tStockopname->get();
        return view('stockopname.index',[
            'stockopnames' => $TSO,
            'conditions' => $conditions
        ]);
    }

    function add(){

        $stockopname_id = 2;

        $date_now = Carbon::now()->format('Y-m-d');

        $user = Auth::user();

        $locations = DB::table('miegacoa_keluhan.master_resto')->where('id', $user->location_now)->first();

        $reasons = DB::table('m_reason')->where('reason_id', $stockopname_id)->first();

        $id_reason = $reasons->reason_id;

        $name_reason = $reasons->reason_name;

        $conditions = DB::table('m_condition')->get();

        $location_user_display = $locations->name_store_street;

        $location_user_id = $locations->id;


        return view('stockopname.add_stock_opname', [
            'user' => $user,
            'date_now' => $date_now,
            'id_reason' => $id_reason,
            'name_reason' => $name_reason,
            'conditions' => $conditions,
            'location_user_display' => $location_user_display,
            'location_user_id' => $location_user_id
        ]);
        
    }

    public function insert(Request $request)
    {
        try {
            $request->validate([
                'out_date' => 'required|date',
                'from_loc_id' => 'required|string|max:255',
                'description' => 'required|string',
                'reason_id' => 'required',
                'asset_id' => 'required|array',
                'register_code' => 'required|array',
                'condition_id' => 'required|array',
            ]);

            $now = Carbon::now();
            $cek = DB::table('t_stockopname_detail AS sodet')
                ->leftJoin('t_stockopname AS so', 'so.code', '=', 'sodet.so_code')
                ->where('sodet.asset_tag', $request->register_code)
                ->whereNull('so.deleted_at')
                ->whereDate('sodet.created_at', $now->format('Y-m-d'))
                ->count();

            if ($cek > 0) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Asset sudah pernah diregistrasi hari ini. Silahkan untuk melakukan stock opname esok hari!'
                ], 400);
            }

            $tRegist = DB::table('table_registrasi_asset')->where('register_code', $request->register_code)->first();
            $outDetail = DB::table('t_out_detail')->where('asset_tag', $request->register_code)->orderBy('id', 'DESC')->first();

            $trx_code = DB::table('t_trx')->where('trx_name', 'Stock Opname')->value('trx_code');
            $today = Carbon::now()->format('ymd');
            $todayCount = DB::table('t_stockopname')->whereDate('create_date', Carbon::now())->count() + 1;
            $transaction_number = str_pad($todayCount, 3, '0', STR_PAD_LEFT);
            $stockopname_code = "{$trx_code}.{$today}.{$request->reason_id}.{$request->from_loc_id}.{$transaction_number}";

            $so_id = DB::table('t_stockopname')->insertGetId([
                'code' => $stockopname_code,
                'reason' => $request->reason_id,
                'location' => $request->from_loc_id,
                'description' => $request->description,
                'is_verify' => 1,
                'qty' => count($request->asset_id),
                'create_date' => Carbon::now(),
                'create_by' => Auth::user()->username,
                'is_confirm' => 1,
                'created_at' => Carbon::now()
            ]);

            foreach ($request->input('asset_id') as $index => $assetId) {

                DB::table('table_registrasi_asset')
                    ->where('id', $assetId)
                    ->update([
                        'qty' => 0,
                        'status_asset' => 5
                    ]);
    
                DB::table('t_stockopname_detail')->insert([
                    'so_code' => $stockopname_code,
                    'asset_tag' => $request->input('register_code')[$index],
                    'condition' => $request->input('condition_id')[$index],
                    'created_at' => Carbon::now(),
                ]);

            }

            return response()->json([
                'status' => 'Success',
                'message' => 'Stock opname berhasil disimpan.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    function edit($so_code)
    {

        $user = Auth::user();

        $conditions = DB::table('m_condition')->get();

        $t_so = DB::table('t_stockopname AS a')
                    ->select(
                        DB::raw('DATE(a.create_date) AS create_date'),
                        'a.id',
                        'a.code',
                        'a.description',
                        'a.location',
                        'a.reason',
                        'resto.name_store_street AS lokasi',
                        'reason.reason_name AS alasan',
                    )
                    ->leftJoin('miegacoa_keluhan.master_resto AS resto', 'resto.id', 'a.location')
                    ->leftJoin('m_reason AS reason', 'reason.reason_id', 'a.reason')
                    ->where('code', $so_code)
                    ->first();

        $t_so_det = DB::table('t_stockopname_detail AS a')
                        ->select(
                            'a.id',
                            'a.condition',
                            'c.asset_model',
                            'e.brand_name',
                            'd.uom_name',
                            'b.serial_number',
                            'b.register_code',
                        )
                        ->leftJoin('table_registrasi_asset AS b', 'b.register_code', '=', 'a.asset_tag')
                        ->leftJoin('m_assets AS c', 'c.asset_id', '=', 'b.asset_name')
                        ->leftJoin('m_uom AS d', 'd.uom_id', '=', 'b.satuan')
                        ->leftJoin('m_brand AS e', 'e.brand_id', '=', 'b.merk')
                        ->where('a.so_code', $so_code)
                        ->get();
        return view('stockopname.edit', [
            'user' => $user,
            'tso' => $t_so,
            't_so_det' => $t_so_det,
            'conditions' => $conditions,
        ]);
        
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
            'asset_id' => 'required|array',
            'register_code' => 'required|array',
            'condition_id' => 'required|array'
        ]);

        $stockOpname = DB::table('t_stockopname')->where('id', $id)->first();

        if (!$stockOpname) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found.'
            ], 404);
        }

        $now = Carbon::now();

        // Update main t_stockopname table
        $updateData = [
            'description' => $request->description,
            'updated_at' => $now
        ];

        if (is_null($stockOpname->updated_1)) {
            $updateData['updated_1'] = $now;
        } elseif (is_null($stockOpname->updated_2)) {
            $updateData['updated_2'] = $now;
        } elseif (is_null($stockOpname->updated_3)) {
            $updateData['updated_3'] = $now;
        }

        // Update stock opname detail per asset
        foreach ($request->asset_id as $index => $assetId) {
            $registerCode = $request->register_code[$index] ?? null;
            $conditionId = $request->condition_id[$index] ?? null;

            if ($registerCode && $conditionId) {
                DB::table('t_stockopname_detail')
                    ->where('so_code', $stockOpname->code)
                    ->where('asset_tag', $registerCode)
                    ->update([
                        'condition' => $conditionId,
                        'updated_at' => $now
                    ]);
            }
        }

        // Apply update to main header table
        $updated = DB::table('t_stockopname')->where('id', $id)->update($updateData);

        if ($updated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Stock opname updated successfully.',
                'redirect_url' => '/stockopname',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update stock opname.'
            ], 500);
        }
    }


    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $so_code = DB::table('t_stockopname')->where('id', $id)->value('code');

            if (!$so_code) {
                return response()->json(['status' => 'Error', 'message' => 'Data tidak ditemukan'], 404);
            }

            $deletedData = [
                'is_confirm' => 4,
                'confirm_date' => Carbon::now(),
                'deleted_at' => Carbon::now()
            ];

            $deleted = DB::table('t_stockopname')->where('id', $id)->update($deletedData);

            if ($deleted) {
                // Ambil semua asset dari detail
                $tso = DB::table('t_stockopname_detail')->where('so_code', $so_code)->get();

                foreach ($tso as $item) {
                    DB::table('table_registrasi_asset')
                        ->where('register_code', $item->asset_tag)
                        ->update([
                            'status_asset' => 1,
                            'qty' => 1,
                        ]);

                    DB::table('t_stockopname_detail')
                        ->where('so_code', $item->so_code)
                        ->update([
                            'deleted_at' => Carbon::now()
                        ]);
                }

                DB::commit();

                return response()->json(['status' => 'Success', 'message' => 'Data Asset Berhasil Terhapus']);
            } else {
                DB::rollBack();
                return response()->json(['status' => 'Error', 'message' => 'Gagal menghapus data.']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'Error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }


    function approvalSM() {

        $user = Auth::user();
        $approvals = DB::table('mc_approval')->get();
        $tStockopname = DB::table('t_stockopname AS a')
                        ->select(
                            'a.*',
                            'b.reason_name',
                            'c.name_store_street',
                            'e.approval_name',
                        )
                        ->leftJoin('m_reason AS b', 'b.reason_id', '=', 'a.reason')
                        ->leftJoin('miegacoa_keluhan.master_resto AS c', 'c.id', '=', 'a.location')
                        ->leftJoin('mc_approval AS e', 'e.approval_id', '=', 'a.is_verify')
                        ->leftJoin('miegacoa_keluhan.master_resto AS f', 'f.id', '=', 'a.location');
                        if($user->hasRole('SM')){
                            $tStockopname->where(function($query) use ($user) {
                                $query->where('f.id', $user->location_now);
                            });
                        }else if($user->hasRole('AM')){
                            $tStockopname->where(function($query) use ($user) {
                                $query->where('f.kode_city', $user->location_now);
                            });
                        }else if($user->hasRole('RM')){
                            $tStockopname->where(function($query) use ($user) {
                                $query->where('f.id_regional', $user->location_now);
                            });
                        }
                        $stockopnames = $tStockopname->get();
        return view('stockopname.approval-sm',[
            'stockopnames' => $stockopnames,
            'approvals' => $approvals
        ]);

    }
    function approvalSMUpdate(Request $request, $id) {

        $request->validate([
        'confirm_so' => 'required'
        ]);

        $stockOpname = DB::table('t_stockopname')->where('id', $id)->first();

        if (!$stockOpname) {
            return response()->json(['status' => 'error', 'message' => 'Data not found.'], 404);
        }

        $now = Carbon::now();
        $user = Auth::user();

        if($request->confirm_so == 2) {

            $updateData = [
                'is_verify' => $request->confirm_so,
            ];

        }else{

            $updateData = [
                'is_verify' => $request->confirm_so,
            ];

        }

        $updated = DB::table('t_stockopname')->where('id', $id)->update($updateData);

        if ($updated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Moveout updated successfully.',
                'redirect_url' => '/stockopname/approval-sm',
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update moveout.'], 500);
        }

    }

    function approvalSDG() {

        $user = Auth::user();
        $approvals = DB::table('mc_approval')->get();
        $tStockopname = DB::table('t_stockopname AS a')
                        ->select(
                            'a.*',
                            'b.reason_name',
                            'c.name_store_street',
                            'e.approval_name',
                        )
                        ->leftJoin('m_reason AS b', 'b.reason_id', '=', 'a.reason')
                        ->leftJoin('miegacoa_keluhan.master_resto AS c', 'c.id', '=', 'a.location')
                        ->leftJoin('mc_approval AS e', 'e.approval_id', '=', 'a.is_confirm')
                        ->leftJoin('miegacoa_keluhan.master_resto AS f', 'f.id', '=', 'a.location')
                        ->where('a.is_verify', 2);
                        if($user->hasRole('SM')){
                            $tStockopname->where(function($query) use ($user) {
                                $query->where('f.id', $user->location_now);
                            });
                        }else if($user->hasRole('AM')){
                            $tStockopname->where(function($query) use ($user) {
                                $query->where('f.kode_city', $user->location_now);
                            });
                        }else if($user->hasRole('RM')){
                            $tStockopname->where(function($query) use ($user) {
                                $query->where('f.id_regional', $user->location_now);
                            });
                        }
                        $stockopnames = $tStockopname->get();
        return view('stockopname.approval-sdg',[
            'stockopnames' => $stockopnames,
            'approvals' => $approvals
        ]);

    }

    function approvalSDGUpdate(Request $request, $id) {

        $request->validate([
        'confirm_so' => 'required'
        ]);

        $stockOpname = DB::table('t_stockopname')->where('id', $id)->first();

        if (!$stockOpname) {
            return response()->json(['status' => 'error', 'message' => 'Data not found.'], 404);
        }

        $now = Carbon::now();
        $user = Auth::user();

        if($request->confirm_so == 3) {

            $updateData = [
                'is_confirm' => $request->confirm_so,
                'confirm_date' => $now,
                'user_confirm' => $user->username
            ];

            $tso_det = DB::table('t_stockopname_detail')->where('so_code', $stockOpname->code)->get();

            foreach($tso_det as $item){
                DB::table('asset_tracking')->insert([
                    'start_date' => $stockOpname->created_at,
                    'from_loc' => $stockOpname->location,
                    'end_date' => $now,
                    'dest_loc' => $stockOpname->location,
                    'reason' => $stockOpname->reason,
                    'condition' => $item->condition,
                    'description' => $stockOpname->description,
                    'register_code' => $item->asset_tag,
                    'out_id' => $stockOpname->code,
                ]);

                DB::table('table_registrasi_asset')->where('register_code', $item->asset_tag)->update([
                    'condition' => $item->condition,
                    'qty' => 1,
                    'status_asset' => 1
                ]);
            }

        }else{

            $updateData = [
                'is_confirm' => $request->confirm_so,
                'is_verify' => 1,
                'confirm_date' => $now,
                'user_confirm' => $user->username
            ];

        }

        $updated = DB::table('t_stockopname')->where('id', $id)->update($updateData);

        if ($updated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Moveout updated successfully.',
                'redirect_url' => '/stockopname/approval-sdg',
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update moveout.'], 500);
        }

    }

    function printPDF($so_code) {
        $tStockopname = DB::table('t_stockopname AS a')
                        ->select(
                            'a.*',
                            'codeResto.cabang_new AS origin_site',
                            'c.approval_name',
                        )
                        ->leftJoin('miegacoa_keluhan.master_resto AS b', 'b.id', '=', 'a.location')
                        ->leftJoin('miegacoa_keluhan.master_barcode_resto as codeResto', 'codeResto.id', '=', 'b.store_code')
                        ->leftJoin('mc_approval AS c', 'c.approval_id', '=', 'a.is_confirm')
                        ->where('a.code', $so_code);
                        
        $stockopnames = $tStockopname->first();

        $tso_det = DB::table('t_stockopname_detail AS a')->where('so_code', $so_code)
                        ->select(
                            'a.asset_tag',
                            'c.asset_model',
                            'e.description',
                            'd.condition_name',
                        )
                        ->leftJoin('table_registrasi_asset AS b', 'b.register_code', '=', 'a.asset_tag')
                        ->leftJoin('m_assets AS c', 'c.asset_id', '=', 'b.asset_name')
                        ->leftJoin('m_condition AS d', 'd.condition_id', '=', 'a.condition')
                        ->leftJoin('t_stockopname AS e', 'e.code', '=', 'a.so_code')
                        ->get();

        if (!$stockopnames) {

            abort(404, 'MoveOut not found');

        }



        // Buat PDF menggunakan data yang ditemukan

        $pdf = Pdf::loadView('stockopname.print_pdf', compact('stockopnames', 'tso_det'));


        return response($pdf->output(), 200)->header('Content-Type', 'application/pdf');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $import = new StockOpnameImport();
            Excel::import($import, $request->file('file'));

            if (!empty($import->errors)) {
                return redirect()->back()->with([
                    'partial_success' => true,
                    'errors_import' => $import->errors,
                ]);
            }

            return redirect()->back()->with('success', 'Semua data berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function formatExcel()
    {
        return Excel::download(new ExportFormat, Carbon::now()->format('d-m-Y').' - format-stock-opname.xlsx');
    }

}
