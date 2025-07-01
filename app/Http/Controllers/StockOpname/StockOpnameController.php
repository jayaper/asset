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
                            'd.condition_name',
                            'e.approval_name',
                        )
                        ->leftJoin('m_reason AS b', 'b.reason_id', '=', 'a.reason')
                        ->leftJoin('miegacoa_keluhan.master_resto AS c', 'c.id', '=', 'a.location')
                        ->leftJoin('m_condition AS d', 'd.condition_id', '=', 'a.condition')
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
                            $tStockopname->whereBetween('a.create_date', [$request->input('start_date').' 00:00:00', $request->input('end_date'),' 23:59:59']);
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
                'asset_id' => 'required',
                'register_code' => 'required',
                'condition_id' => 'required',
            ]);

            $now = Carbon::now();
            $cek = DB::table('t_stockopname')
                ->where('asset_tag', $request->register_code)
                ->whereDate('created_at', $now->format('Y-m-d'))
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
                'asset_tag' => $request->register_code,
                'out_code' => $tRegist->last_transaction_code ?? null,
                'out_det_id' => $outDetail->id ?? null,
                'reason' => $request->reason_id,
                'location' => $request->from_loc_id,
                'condition' => $request->condition_id,
                'description' => $request->description,
                'create_date' => Carbon::now(),
                'create_by' => Auth::user()->username,
                'is_confirm' => 1,
                'deleted_at' => $tRegist->deleted_at ?? null,
                'created_at' => Carbon::now()
            ]);

            DB::table('table_registrasi_asset')->where('register_code', $request->register_code)->update([
                'qty' => 0,
                'status_asset' => 5
            ]);

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



    function update(Request $request, $id){

        $request->validate([
        'condition_so' => 'required'
        ]);

        $stockOpname = DB::table('t_stockopname')->where('id', $id)->first();

        if (!$stockOpname) {
            return response()->json(['status' => 'error', 'message' => 'Data not found.'], 404);
        }

        $now = Carbon::now();
        $updateData = [
            'condition' => $request->condition_so,
            'updated_at' => $now
        ];

        if (is_null($stockOpname->updated_1)) {
            $updateData['updated_1'] = $now;
        } elseif (is_null($stockOpname->updated_2)) {
            $updateData['updated_2'] = $now;
        } elseif (is_null($stockOpname->updated_3)) {
            $updateData['updated_3'] = $now;
        }

        $updated = DB::table('t_stockopname')->where('id', $id)->update($updateData);

        if ($updated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Moveout updated successfully.',
                'redirect_url' => '/stockopname',
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update moveout.'], 500);
        }
    }

    function delete($id) {

        $stockOpname = DB::table('t_stockopname')->where('id', $id)->first();
        
        if (is_null($stockOpname->deleted_at)) {

            $deletedData['deleted_at'] = Carbon::now();

        } else {

            $deletedData['deleted_at'] = null;

        }

        $deleted = DB::table('t_stockopname')->where('id', $id)->update($deletedData);

        if ($deleted) {
            return response()->json(['status' => 'Success', 'message' => 'Data Asset Berhasil Terhapus']);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Gagal dihapus']);
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
                            'd.condition_name',
                            'e.approval_name',
                        )
                        ->leftJoin('m_reason AS b', 'b.reason_id', '=', 'a.reason')
                        ->leftJoin('miegacoa_keluhan.master_resto AS c', 'c.id', '=', 'a.location')
                        ->leftJoin('m_condition AS d', 'd.condition_id', '=', 'a.condition')
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

            DB::table('asset_tracking')->insert([
                    'start_date' => $stockOpname->created_at,
                    'from_loc' => $stockOpname->location,
                    'end_date' => $now,
                    'dest_loc' => null,
                    'reason' => $stockOpname->reason,
                    'description' => $stockOpname->description,
                    'register_code' => $stockOpname->asset_tag,
                    'out_id' => $stockOpname->code,
                ]);

            DB::table('table_registrasi_asset')->where('register_code', $stockOpname->asset_tag)->update([
                    'condition' => $stockOpname->condition,
                    'qty' => 1,
                    'status_asset' => 1
                ]);

        }else{

            $updateData = [
                'is_confirm' => $request->confirm_so,
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

    function printPDF($id) {
        $tStockopname = DB::table('t_stockopname AS a')
                        ->select(
                            'a.*',
                            'codeResto.cabang_new AS origin_site',
                            'c.approval_name',
                            'd.condition_name',
                            'f.asset_model',
                        )
                        ->leftJoin('miegacoa_keluhan.master_resto AS b', 'b.id', '=', 'a.location')
                        ->leftJoin('miegacoa_keluhan.master_barcode_resto as codeResto', 'codeResto.id', '=', 'b.store_code')
                        ->leftJoin('mc_approval AS c', 'c.approval_id', '=', 'a.is_confirm')
                        ->leftJoin('m_condition AS d', 'd.condition_id', '=', 'a.condition')
                        ->leftJoin('table_registrasi_asset AS e', 'e.register_code', '=', 'a.asset_tag')
                        ->leftJoin('m_assets AS f', 'f.asset_id', '=', 'e.asset_name')
                        ->where('a.id', $id);

        $stockopnames = $tStockopname->first();

        if (!$stockopnames) {

            abort(404, 'MoveOut not found');

        }



        // Buat PDF menggunakan data yang ditemukan

        $pdf = Pdf::loadView('stockopname.print_pdf', compact('stockopnames'));


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
