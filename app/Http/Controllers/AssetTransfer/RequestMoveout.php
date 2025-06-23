<?php

namespace App\Http\Controllers\AssetTransfer;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Master\MasterAsset;

use App\Models\Master\MasterMoveOut;

use App\Models\Master\MasterRegistrasiModel;

use App\Models\Master\TOutDetail;

use Barryvdh\DomPDF\Facade\Pdf;


use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class RequestMoveout extends Controller
{

    public function Index()

    {

        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();

        $restos = DB::table('miegacoa_keluhan.master_resto')->select('store_code', 'name_store_street')->get();

        $approvals = DB::table('mc_approval')->select('approval_id', 'approval_name')->get();

        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();



        $username = auth()->user()->username;

        $fromLoc = DB::table('m_people')

            ->where('nip', $username)

            ->value('loc_id');



        $registerLocation = DB::table('master_resto')

            ->where('store_code', $fromLoc)

            ->value('resto');



        // Filter assets based on the register_location matching the fetched resto

        $assets = DB::table('table_registrasi_asset')

            ->select('id', 'asset_name')

            // ->where('location_now', $registerLocation)

            ->where('qty', '>', 0)

            ->get();



        $moveouts = DB::table('t_out')

            ->select('t_out.*', 'm_reason.reason_name', 'mc_approval.approval_name')

            ->leftjoin('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')

            ->leftjoin('mc_approval', 't_out.is_confirm', '=', 'mc_approval.approval_id')

            ->where('t_out.is_active', 1)


            ->paginate(10);



        return view("asset_transfer.lihat_data_movement", [

            'fromLoc' => $fromLoc,

            'reasons' => $reasons,

            'assets' => $assets,

            'conditions' => $conditions,

            'moveouts' => $moveouts,

            'restos' => $restos

        ]);
    }



    public function HalamanMoveOut(Request $request)
    {
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();
        $restos = DB::table('miegacoa_keluhan.master_resto')->select('id', 'store_code', 'name_store_street')->get();
        $approvals = DB::table('mc_approval')->select('approval_id', 'approval_name')->get();
        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();


        $username = Auth::user()->username;


        $user = DB::table('m_user')->where('username', $username)->first();
        $fromLoc = $user->location_now;


        $assets = DB::table('table_registrasi_asset')
            ->select('id', 'asset_name')
            ->where('qty', '>', 0)
            ->get();


        $moveoutsQuery = DB::table('t_out as a')
            ->select(
                'a.*',
                DB::raw("
                    CASE
                        WHEN LENGTH(a.out_desc) > 50
                            THEN CONCAT(SUBSTRING(a.out_desc, 1, 50), '...')
                        ELSE a.out_desc
                    END as out_desc
                "),
                'b.qty',
                'c.reason_name',
                'd.approval_name',
                'e.name_store_street as from_location',
                'f.name_store_street as dest_location',
                'b.total_qr as total_qr'
            )
            ->leftJoin(DB::raw('(
                SELECT 
                    b.out_id, 
                    SUM(b.qty) AS qty,
                    count(c.qr_code_path) as total_qr
                FROM t_out_detail AS b
                LEFT JOIN table_registrasi_asset AS c ON c.register_code = b.asset_tag
                GROUP BY b.out_id
            ) as b'), 'b.out_id', '=', 'a.out_id')
            ->leftJoin('m_reason as c', 'c.reason_id', '=', 'a.reason_id')
            ->leftJoin('mc_approval as d', 'd.approval_id', '=', 'a.is_confirm')
            ->leftJoin('miegacoa_keluhan.master_resto as e', 'e.id', '=', 'a.from_loc')
            ->leftJoin('miegacoa_keluhan.master_resto as f', 'f.id', '=', 'a.dest_loc')
            ->where('a.out_id', 'like', 'AM%')
            ->orderBy('a.out_id', 'DESC');
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $moveoutsQuery->whereBetween('a.out_date', [
                    $request->input('start_date') . ' 00:00:00',
                    $request->input('end_date') . ' 23:59:59'
                ]);
            }
            
            // Filter lokasi berdasarkan role user
            if (Auth::user()->hasRole('SM')){
                $moveoutsQuery->where(function($q){
                    $q->where('a.from_loc', Auth::user()->location_now);
                });
            }else if(Auth::user()->hasRole('AM')){
                $moveoutsQuery->where(function($q){
                    $q->where('e.kode_city', Auth::user()->location_now);
                });
            }else if(Auth::user()->hasRole('RM')){
                $moveoutsQuery->where(function($q){
                    $q->where('e.id_regional', Auth::user()->location_now);
                });
            }
            
            $moveouts = $moveoutsQuery->paginate(10);

        // dd($fromLoc);

        return view("asset_transfer.lihat_data_movement", [
            'fromLoc' => $fromLoc,
            'reasons' => $reasons,
            'assets' => $assets,
            'conditions' => $conditions,
            'moveouts' => $moveouts,
            'restos' => $restos
        ]);
    }




    public function LihatFormMoveOut()
    {

        $movement_id = 1;

        $user = Auth::user();

        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->where('reason_id', $movement_id)->first();

        $id_reason = $reasons->reason_id;

        $name_reason = $reasons->reason_name;

        $restos = DB::table('miegacoa_keluhan.master_resto')->select('store_code', 'name_store_street')->get();

        $approvals = DB::table('mc_approval')->select('approval_id', 'approval_name')->get();

        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();



        $username = auth()->user()->username;
        

        $fromLoc = DB::table('m_user')

            ->where('username', $username)

            ->value('location_now');

        
        $id_user = auth()->user()->id;

        $lokasi_user = DB::table('m_user')

            ->where('id', $id_user)

            ->value('location_now');

        $lokasi_user_display = DB::table('miegacoa_keluhan.master_resto')

            ->where('id', $lokasi_user)

            ->value('name_store_street');

        $assets = DB::table('table_registrasi_asset')

            ->select('id', 'asset_name')

            // ->where('register_location', $registerLocation)

            ->where('qty', '>', 0)

            ->get();



        $moveoutsQuery = DB::table('t_out')

            ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')

            ->join('mc_approval', 't_out.is_confirm', '=', 'mc_approval.approval_id')

            ->where('t_out.is_active', 1)

            ->select('t_out.*', 'm_reason.reason_name', 'mc_approval.approval_name');


        $moveouts = $moveoutsQuery->paginate(10);



        return view('asset_transfer.add_data_movement', [

            'user' => $user,

            'lokasi_user' => $lokasi_user,

            'lokasi_user_display' => $lokasi_user_display,

            'fromLoc' => $fromLoc,

            'id_reason' => $id_reason,

            'name_reason' => $name_reason,

            'assets' => $assets,

            'conditions' => $conditions,

            'moveouts' => $moveouts,

            'restos' => $restos
        ]);
    }



    public function printPDF($id)

    {

        // Ambil data dari tabel t_out dan t_out_detail berdasarkan ID

        $moveout = MasterMoveOut::with('details')->findOrFail($id);



        // Generate PDF

        $pdf = Pdf::loadView('Admin.pdf-moveout', compact('moveout'));



        // Return PDF response untuk di-download atau dilihat

        return $pdf->download('moveout_' . $moveout->out_id . '.pdf');
    }



    public function previewPDF($out_id)
    {

        // Ambil data berdasarkan out_id

        $data = DB::table('t_out')
            ->select(
                't_out.*',
                't_out_detail.*',
                'codeFormResto.cabang_new as origin_site',
                'codeDestResto.cabang_new as destination_site',
                'table_registrasi_asset.asset_name',
                'm_assets.asset_model',
                'm_brand.brand_name',
                'm_category.cat_name',
                'm_reason.reason_name',
                'm_condition.condition_name',
                'mc_approval.approval_name'
            )
            ->join('t_out_detail', 't_out.out_id', '=', 't_out_detail.out_id',)
            ->leftJoin('mc_approval', 'mc_approval.approval_id', '=', 't_out.is_confirm')
            ->leftJoin('miegacoa_keluhan.master_resto as fromResto', 't_out.from_loc', '=', 'fromResto.id')
            ->leftJoin('miegacoa_keluhan.master_resto as destResto', 't_out.dest_loc', '=', 'destResto.id')
            ->leftJoin('miegacoa_keluhan.master_barcode_resto as codeFormResto', 'codeFormResto.id', '=', 'fromResto.store_code')
            ->leftJoin('miegacoa_keluhan.master_barcode_resto as codeDestResto', 'codeDestResto.id', '=', 'destResto.store_code')
            ->leftJoin('miegacoa_keluhan.master_resto as toResto', 't_out.dest_loc', '=', 'toResto.id')
            ->join('table_registrasi_asset', 't_out_detail.asset_id', '=', 'table_registrasi_asset.id')
            ->join('m_assets', 'table_registrasi_asset.asset_name', '=', 'm_assets.asset_id')
            ->join('m_category', 'table_registrasi_asset.category_asset', '=', 'm_category.cat_code')
            ->join('m_brand', 't_out_detail.brand', '=', 'm_brand.brand_id')
            ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
            ->join('m_condition', 't_out_detail.condition', '=', 'm_condition.condition_id')
            ->where('t_out.out_id', $out_id)
            ->get();

        $firstRecord = $data->first();

        foreach ($data as $record) {
        }



        // $data = DB::table('t_out')
        // ->select(
        //     't_out.*',
        //     't_out_detail.*',
        //     'fromResto.store_code as origin_site', 
        //     'toResto.store_code as destination_site',
        //     'table_registrasi_asset.asset_name',
        //     'table_registrasi_asset.category_asset',
        //     'table_registrasi_asset.serial_number',
        //     'table_registrasi_asset.type_asset',
        //     'm_condition.condition_name',
        //     'm_type.type_name',
        //     'm_category.cat_name'
        // )
        // ->join('t_out_detail', 't_out.out_id', '=', 't_out_detail.out_id')
        // ->leftJoin('miegacoa_keluhan.master_resto as fromResto', 't_out.from_loc', '=', 'fromResto.id')
        // ->leftJoin('miegacoa_keluhan.master_resto as toResto', 't_out.dest_loc', '=', 'toResto.id')
        // ->leftJoin('table_registrasi_asset', 't_out_detail.asset_id', '=', 'table_registrasi_asset.id')
        // ->leftJoin('m_condition', 't_out_detail.condition', '=', 'm_condition.condition_id')
        // ->leftJoin('m_category', 'table_registrasi_asset.category_asset', '=', 'm_category.cat_id')
        // ->leftJoin('m_type', 'table_registrasi_asset.type_asset', '=', 'm_type.type_id')
        // ->where('t_out.out_id', $out_id)
        // ->first();




        // Jika data tidak ditemukan, tampilkan halaman 404

        if (!$data) {

            abort(404, 'MoveOut not found');
        }



        // Buat PDF menggunakan data yang ditemukan

        $pdf = PDF::loadView('asset_transfer.pdf-moveout', compact('data', 'firstRecord'));



        return response($pdf->output(), 200)->header('Content-Type', 'application/pdf');
    }



    public function downloadPDF()

    {

        $data = $this->getData();

        $pdf = PDF::loadView('Admin.pdf-moveout', compact('data'));



        // Unduh PDF

        return $pdf->download('moveout.pdf');
    }



    // public function showPutForm($outId)

    // {

    //     $moveout = MasterMoveOut::where('out_id', $outId)->first(); // Corrected to match the relationship name



    //     if (!$moveout) {

    //         return response()->json(['message' => 'Moveout not found'], 404);

    //     }



    //     return response()->json($moveout);

    // }



    // public function showPutFormDetail($outId)

    // {

    //     // Ambil hanya kolom yang diperlukan

    //     $moveoutDetails = TOutDetail::where('out_id', $outId)

    //         ->select('brand', 'qty', 'condition', 'uom', 'serial_number', 'asset_tag')

    //         ->get();



    //     if ($moveoutDetails->isEmpty()) {

    //         // Return a 404 response jika data tidak ditemukan

    //         return response()->json(['message' => 'Moveout Detail not found'], 404);

    //     }



    //     // Kembalikan detail moveout sebagai JSON

    //     return response()->json($moveoutDetails);

    // }



    public function showPutFormMoveout($outId)

    {

        $moveout = MasterMoveOut::find('out_id', $outId)->first();



        if (!$moveout) {

            return response()->json(['message' => 'Moveout not found'], 404);
        }



        return response()->json($moveout);
    }



    public function showPutFormMoveoutDetail($outId)
    {
        try {
            Log::info("showPutFormMoveoutDetail called with out_id: $outId");

            // Fetch the moveout details
            $moveout = TOutDetail::where('out_id', $outId)->first();

            if (!$moveout) {
                // Log a warning for missing data
                Log::warning("No details found for out_id: $outId");
                return response()->json(['error' => true, 'message' => 'Moveout not found'], 404);
            }

            // Log successful retrieval
            Log::info("Moveout details retrieved successfully for out_id: $outId", [
                'details' => $moveout
            ]);

            // Return the moveout details
            return response()->json([
                'error' => false,
                'data' => $moveout
            ], 200);
        } catch (\Exception $e) {
            // Log any unexpected errors
            Log::error("Error in showPutFormMoveoutDetail for out_id: $outId", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return a generic error response
            return response()->json([
                'error' => true,
                'message' => 'An unexpected error occurred. Please try again later.'
            ], 500);
        }
    }



    public function getMoveOut()

    {

        // Mengambil semua data dari tabel t_out

        $moveouts = MasterMoveOut::all();

        return response()->json($moveouts); // Mengembalikan data dalam format JSON

    }



    public function getAssetDetails($id)

    {

        $asset = DB::table('table_registrasi_asset')
            ->select(
                'table_registrasi_asset.id',
                'table_registrasi_asset.register_code',
                'table_registrasi_asset.serial_number',
                'table_registrasi_asset.register_date',
                'table_registrasi_asset.purchase_date',
                'table_registrasi_asset.approve_status',
                'm_assets.asset_model',
                'm_type.type_name',
                'm_category.cat_name',
                'm_priority.priority_name',
                'm_brand.brand_name',
                'm_uom.uom_name',
                'miegacoa_keluhan.master_resto.name_store_street',
                'm_layout.layout_name',
                'm_supplier.supplier_name',
                'm_condition.condition_name',
                'm_warranty.warranty_name',
                'm_periodic_mtc.periodic_mtc_name',
                'table_registrasi_asset.deleted_at'
            )
            ->leftJoin('m_assets', 'table_registrasi_asset.asset_name', '=', 'm_assets.asset_id')
            ->leftJoin('m_type', 'table_registrasi_asset.type_asset', '=', 'm_type.type_code')
            ->leftJoin('m_category', 'table_registrasi_asset.category_asset', '=', 'm_category.cat_code')
            ->leftJoin('m_priority', 'table_registrasi_asset.prioritas', '=', 'm_priority.priority_code')
            ->leftJoin('m_brand', 'table_registrasi_asset.merk', '=', 'm_brand.brand_id')
            ->leftJoin('m_uom', 'table_registrasi_asset.satuan', '=', 'm_uom.uom_id')
            ->leftJoin('miegacoa_keluhan.master_resto', 'table_registrasi_asset.register_location', '=', 'miegacoa_keluhan.master_resto.id')
            ->leftJoin('m_layout', 'table_registrasi_asset.layout', '=', 'm_layout.layout_id')
            ->leftJoin('m_supplier', 'table_registrasi_asset.supplier', '=', 'm_supplier.supplier_code')
            ->leftJoin('m_condition', 'table_registrasi_asset.condition', '=', 'm_condition.condition_id')
            ->leftJoin('m_warranty', 'table_registrasi_asset.warranty', '=', 'm_warranty.warranty_id')
            ->leftJoin('m_periodic_mtc', 'table_registrasi_asset.periodic_maintenance', '=', 'm_periodic_mtc.periodic_mtc_id')
            ->get();



        if ($asset) {

            return response()->json([

                'asset_name' => $asset->asset_model,

                'merk' => $asset->merk,

                'qty' => $asset->qty,

                'satuan' => $asset->uom_name,

                'serial_number' => $asset->serial_number,

                'register_code' => $asset->register_code,

            ]);
        } else {

            return response()->json([], 404); // Not found

        }
    }



    public function getMoveoutDetails($id)

    {

        // Retrieve moveout and related details

        $moveout = MasterMoveOut::find($id);

        $details = $moveout ? $moveout->details : [];



        return response()->json([

            'moveout' => $moveout,

            'details' => $details

        ]);
    }



    // public function getOutData($out_id)

    // {

    //     $outData = DB::table('t_out')

    //                 ->where('out_id', $out_id)

    //                 ->first();



    //     $outDetailData = DB::table('t_out_detail')

    //                 ->where('out_id', $out_id)

    //                 ->first();



    //     if ($outData && $outDetailData) {

    //         return response()->json([

    //             'out' => $outData,

    //             'detail' => $outDetailData,

    //         ]);

    //     } else {

    //         return response()->json(['message' => 'Data not found'], 404);

    //     }

    // }



    // // Fetch asset details based on asset_id

    // public function getAssetData($asset_id)

    // {

    //     $assetData = DB::table('table_registrasi_asset')

    //                 ->where('id', $asset_id)

    //                 ->first();



    //     if ($assetData) {

    //         return response()->json($assetData);

    //     } else {

    //         return response()->json(['message' => 'Data not found'], 404);

    //     }

    // }



    public function getDetails($id)

    {

        // Fetch data from t_out and t_out_detail based on the out_id

        $moveOut = DB::table('t_out')

            ->where('out_id', $id)

            ->first();



        $moveOutDetails = DB::table('t_out_detail')

            ->where('out_id', $id)

            ->get(); // Assuming you want to retrieve all details related to this out_id



        $firstDetail = $moveOutDetails->first();

        // Combine the results (if necessary)

        $response = [

            'out_id' => $moveOut->out_id,

            'id' => $moveOut->id,

            'out_date' => $moveOut->out_date,

            'from_loc' => $moveOut->from_loc,

            'dest_loc' => $moveOut->dest_loc,

            'in_id' => $moveOut->in_id,

            'reason_id' => $moveOut->reason_id,

            'out_desc' => $moveOut->out_desc,

            'asset_id' => $firstDetail->asset_id ?? '',

            'asset_tag' => $firstDetail->asset_tag ?? '',

            'serial_number' => $firstDetail->serial_number ?? '',

            'brand' => $firstDetail->brand ?? '',

            'qty' => $firstDetail->qty ?? '',

            'uom' => $firstDetail->uom ?? '',

            'condition' => $firstDetail->condition ?? '',

        ];



        return response()->json($response);
    }



    public function getMoveOutById($id)

    {

        $moveout = MasterMoveOut::find($id); // Fetch the moveout entry by ID



        if ($moveout) {

            return response()->json($moveout); // Return the moveout data as JSON

        }



        return response()->json(['message' => 'MoveOut not found'], 404); // Handle not found case

    }




    // public function AddDataMoveOut(Request $request)
    // {
    //     // Validation remains the same
    //     $request->validate([
    //         'out_date' => 'required|date',
    //         'from_loc_id' => 'required|string|max:255',
    //         'dest_loc' => 'required|string|max:255',
    //         'out_desc' => 'required|string|max:255',
    //         'reason_id' => 'required|string|max:255',
    //         'asset_id' => 'required|array',
    //         'register_code' => 'required|array',
    //         'serial_number' => 'required|array',
    //         'merk' => 'required|array',
    //         'qty' => 'required|array',
    //         'satuan' => 'required|array',
    //         'condition_id' => 'required|array',
    //         'image' => 'required|array'
    //     ]);

    //     // Location check remains the same
    //     if ($request->input('from_loc') === $request->input('dest_loc')) {
    //         return redirect()->back()->with('error', 'Lokasi Asal dan Lokasi Tujuan tidak boleh sama!');
    //     }

    //     // Generate the out_id ONCE for both master and detail records
    //     $trx_code = DB::table('t_trx')->where('trx_name', 'Asset Movement')->value('trx_code');
    //     $today = Carbon::now()->format('ymd');
    //     $todayCount = MasterMoveOut::whereDate('create_date', Carbon::now())->count() + 1;
    //     $transaction_number = str_pad($todayCount, 3, '0', STR_PAD_LEFT);
    //     $out_id = "{$trx_code}.{$today}.{$request->input('reason_id')}.{$request->input('from_loc_id')}.{$transaction_number}";

    //     // Create master record
    //     $moveout = new MasterMoveOut();
    //     $moveout->out_date = $request->input('out_date');
    //     $moveout->from_loc = $request->input('from_loc_id');
    //     $moveout->dest_loc = $request->input('dest_loc');
    //     $moveout->out_desc = $request->input('out_desc');
    //     $moveout->reason_id = $request->input('reason_id');
    //     $moveout->appr_1 = '1';
    //     $moveout->is_active = '1';
    //     $moveout->is_verify = '1';
    //     $moveout->is_confirm = '1';
    //     $moveout->create_by = Auth::user()->username;

    //     // Set the id
    //     $maxMoveoutId = MasterMoveOut::max('id');
    //     $id_base = $maxMoveoutId ? $maxMoveoutId + 1 : 1;
    //     $moveout->id = $id_base;


    //     $moveout->out_id = $out_id;


    //     $moveout->save();

    //     foreach ($request->input('asset_id') as $index => $assetId) {
    //         $imagePath = null; 

    //         if ($request->hasFile("image.$index") && $request->file("image.$index")->isValid()) {
    //             $imagePath = $request->file("image.$index")->store('moveout_item/images', 'public');
    //         }

    //         $moveoutQty = $request->input('qty')[$index];
    //         $registerCode = $request->input('register_code')[$index];
    //         $locationResto = $request->input('dest_loc');

    //         // Check if a record exists in table_bank_qty
    //         $existingRecord = DB::table('table_bank_qty')
    //             ->where('register_code', $registerCode)
    //             ->where('location_resto', $locationResto)
    //             ->first();

    //         if ($existingRecord) {
    //             $updatedQtyIn = $existingRecord->total_qty_in + $moveoutQty;
    //             $remainingQty = max(0, $moveoutQty - $existingRecord->total_qty_in);

    //             DB::table('table_bank_qty')
    //                 ->where('id', $existingRecord->id)
    //                 ->update([
    //                     'total_qty_in' => $updatedQtyIn,
    //                     'updated_at' => Carbon::now(),
    //                 ]);

    //             // Update the qty in t_out_detail
    //             $moveoutQty = $remainingQty;
    //         } else {
    //             // Insert a new record in table_bank_qty
    //             DB::table('table_bank_qty')->insert([
    //                 'register_code' => $registerCode,
    //                 'location_resto' => $locationResto,
    //                 'total_qty_in' => $moveoutQty,

    //                 'created_at' => Carbon::now(),
    //                 'updated_at' => Carbon::now(),
    //             ]);
    //         }

    //         // Insert into t_out_detail
    //         DB::table('t_out_detail')->insert([
    //             'out_det_id' => $moveout->id, 
    //             'out_id' => $out_id,
    //             'asset_id' => $assetId,
    //             'asset_tag' => $registerCode,
    //             'serial_number' => $request->input('serial_number')[$index],
    //             'brand' => $request->input('merk')[$index],
    //             'qty' => $moveoutQty, // Adjusted qty if part of it was added to table_bank_qty
    //             'uom' => $request->input('satuan')[$index],
    //             'condition' => $request->input('condition_id')[$index],
    //             'image' => $imagePath,
    //             'created_at' => Carbon::now(),
    //             'updated_at' => Carbon::now(),
    //         ]);

    //         // Decrease the asset quantity in the registration table
    //         $currentQty = DB::table('table_registrasi_asset')
    //             ->where('id', $assetId)
    //             ->value('qty');

    //         $newQty = max(0, $currentQty - $moveoutQty);

    //         DB::table('table_registrasi_asset')
    //             ->where('id', $assetId)
    //             ->update(['qty' => $newQty]);
    //     }

    //     return redirect()->back()->with('success', 'Asset Movement recorded successfully');
    // }



    public function AddDataMoveOut(Request $request)
    {


        $request->validate([
            'out_date' => 'required|date',
            'from_loc_id' => 'required|string|max:255',
            'dest_loc' => 'required|string|max:255',
            'out_desc' => 'required|string|max:255',
            'reason_id' => 'required|string|max:255',
            'asset_id' => 'required|array',
            'register_code' => 'required|array',
            'serial_number' => 'required|array',
            'merk' => 'required|array',
            'qty' => 'required|array',
            'satuan' => 'required|array',
            'condition_id' => 'required|array',
            'image' => 'required|array'
        ]);


        if ($request->input('from_loc_id') === $request->input('dest_loc')) {
            return redirect()->back()->with('error', 'Lokasi Asal dan Lokasi Tujuan tidak boleh sama!');
        }


        $trx_code = DB::table('t_trx')->where('trx_name', 'Asset Movement')->value('trx_code');
        $today = Carbon::now()->format('ymd');
        $todayCount = MasterMoveOut::whereDate('create_date', Carbon::now())->count() + 1;
        $transaction_number = str_pad($todayCount, 3, '0', STR_PAD_LEFT);
        $out_id = "{$trx_code}.{$today}.{$request->input('reason_id')}.{$request->input('from_loc_id')}.{$transaction_number}";


        $moveout = new MasterMoveOut();
        $moveout->out_date = $request->input('out_date');
        $moveout->from_loc = $request->input('from_loc_id');
        $moveout->dest_loc = $request->input('dest_loc');
        $moveout->out_desc = $request->input('out_desc');
        $moveout->reason_id = $request->input('reason_id');
        $moveout->appr_1 = '1';
        $moveout->is_active = '1';
        $moveout->is_verify = '1';
        $moveout->is_confirm = '1';
        $moveout->create_by = Auth::user()->username;


        $maxMoveoutId = MasterMoveOut::max('id');
        $id_base = $maxMoveoutId ? $maxMoveoutId + 1 : 1;
        $moveout->id = $id_base;


        $moveout->out_id = $out_id;


        $moveout->save();


        foreach ($request->input('asset_id') as $index => $assetId) {
            $imagePath = null;

            if ($request->hasFile("image.$index") && $request->file("image.$index")->isValid()) {
                $imagePath = $request->file("image.$index")->store('/moveout_item/images', 'public');
            }

            $moveoutQty = $request->input('qty')[$index];
            $registerCode = $request->input('register_code')[$index];


            $currentQty = DB::table('table_registrasi_asset')
                ->where('register_code', $registerCode)
                ->value('qty');

            if ($currentQty === null || $currentQty < $moveoutQty) {
                return redirect()->back()->with('error', "Insufficient stock for asset with register code: $registerCode");
            }


            $newQty = $currentQty - $moveoutQty;


            DB::table('table_registrasi_asset')
                ->where('register_code', $registerCode)
                ->update([
                    'qty' => $newQty,
                    'status_asset' => 2
                ]);


            // Insert a new detail record into `t_out_detail`
            DB::table('t_out_detail')->insert([
                'out_det_id' => $moveout->id,
                'out_id' => $out_id,
                'asset_id' => $assetId,
                'asset_tag' => $registerCode,
                'serial_number' => $request->input('serial_number')[$index],
                'brand' => $request->input('merk')[$index],
                'qty' => $moveoutQty,
                'qty_continue' => 0,
                'qty_total' => 0,
                'uom' => $request->input('satuan')[$index],
                'condition' => $request->input('condition_id')[$index],
                'image' => $imagePath,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $moveoutData = DB::table('t_out')->where('out_id', $out_id)->first();
        }



        return redirect()->back()->with('success', 'Asset Movement recorded successfully');
    }





    // public function AddDataMoveOut(Request $request)
    // {
    //     // Validasi data yang dikirimkan
    //     $request->validate([
    //         'out_date' => 'required|date',
    //         'from_loc' => 'required|string|max:255',
    //         'dest_loc' => 'required|string|max:255',
    //         'out_desc' => 'required|string|max:255',
    //         'reason_id' => 'required|string|max:255',
    //         'asset_id' => 'required|array',
    //         'register_code' => 'required|array',
    //         'serial_number' => 'required|array',
    //         'merk' => 'required|array',
    //         'qty' => 'required|array',
    //         'satuan' => 'required|array',
    //         'condition_id' => 'required|array',
    //     ], [
    //         'dest_loc.different' => 'Lokasi Asal dan Lokasi Tujuan tidak boleh sama!',
    //     ]);

    //     // Manual check for same values before proceeding
    //     if ($request->input('from_loc') === $request->input('dest_loc')) {
    //         return redirect()->back()->with('error', 'Lokasi Asal dan Lokasi Tujuan tidak boleh sama!');
    //     }

    //     // Buat instance dari model MasterMoveOut
    //     $moveout = new MasterMoveOut();
    //     $moveout->out_date = $request->input('out_date');
    //     $moveout->from_loc = $request->input('from_loc');
    //     $moveout->dest_loc = $request->input('dest_loc');
    //     $moveout->out_desc = $request->input('out_desc');
    //     $moveout->reason_id = $request->input('reason_id');
    //     $moveout->appr_1 = '1';
    //     $moveout->is_active = '1';
    //     $moveout->is_verify = '1';
    //     $moveout->is_confirm = '1';
    //     $moveout->create_by = Auth::user()->username;

    //     // Menghasilkan id secara otomatis untuk setiap aset
    //     $maxMoveoutId = MasterMoveOut::max('id');
    //     $id_base = $maxMoveoutId ? $maxMoveoutId + 1 : 1;
    //     $moveout->id = $id_base;

    //     // Format out_id
    //     $trx_code = DB::table('t_trx')->where('trx_name', 'Asset Movement')->value('trx_code');
    //     $today = Carbon::now()->format('ymd');
    //     $todayCount = MasterMoveOut::whereDate('create_date', Carbon::now())->count() + 1;
    //     $transaction_number = str_pad($todayCount, 3, '0', STR_PAD_LEFT); // Format as 001, 002, etc.
    //     $moveout->out_id = "{$trx_code}.{$today}.{$request->input('reason_id')}.{$request->input('from_loc')}.{$transaction_number}";


    //     // Simpan moveout
    //     $moveout->save();

    //     // Loop melalui aset untuk menyimpan detail
    //     foreach ($request->input('asset_id') as $index => $assetId) {
    //         $trx_code = DB::table('t_trx')->where('trx_name', 'Asset Movement')->value('trx_code');
    //         $today = Carbon::now()->format('ymd');
    //         $lastTransaction = DB::table('t_out_detail')
    //             ->where('out_id', 'like', "{$trx_code}.{$today}.%")
    //             ->orderBy('out_id', 'desc')
    //             ->first();

    //         // Calculate the next transaction number
    //         if (isset($lastTransaction->opname_id)) {
    //             $lastOpnameId = $lastTransaction->opname_id;
    //             preg_match('/\.(\d{3})$/', $lastOpnameId, $matches);
    //             $transaction_number = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
    //         } else {
    //             $transaction_number = 1;
    //         }

    //         $transaction_number_str = str_pad($transaction_number, 3, '0', STR_PAD_LEFT);
    //         $out_id = "{$trx_code}.{$today}.{$request->input('reason_id')}.{$request->input('from_loc')}.{$transaction_number_str}";

    //         // Simpan data detail untuk aset
    //         DB::table('t_out_detail')->insert([
    //             'out_det_id' => $moveout->id,  
    //             'out_id' => $out_id,
    //             'asset_id' => $assetId,
    //             'asset_tag' => $request->input('register_code')[$index],
    //             'serial_number' => $request->input('serial_number')[$index],
    //             'brand' => $request->input('merk')[$index],
    //             'qty' => $request->input('qty')[$index],
    //             'uom' => $request->input('satuan')[$index],
    //             'condition' => $request->input('condition_id')[$index],
    //             'created_at' => Carbon::now(), // Current timestamp for creation
    //             'updated_at' => Carbon::now(), // Current timestamp for update
    //         ]);


    //     }

    //     return redirect()->route('Admin.moveout')->with('success', 'Data berhasil ditambahkan!');
    // }






    // Example push notification function

    private function sendPushNotification($expoPushToken, $title, $body)

    {

        $url = 'https://exp.host/--/api/v2/push/send';

        $data = [

            'to' => $expoPushToken,

            'sound' => 'default',

            'title' => $title,

            'body' => $body,

            'data' => ['MoveOutId' => '12345']

        ];



        $options = [

            'http' => [

                'header' => "Content-type: application/json\r\n",

                'method' => 'POST',

                'content' => json_encode($data)

            ]

        ];



        $context = stream_context_create($options);

        $result = file_get_contents($url, false, $context);



        return $result;
    }



    // public function updateDataMoveOut(Request $request, $out_id)
    // {
    //     // Validate input
    //     $request->validate([
    //         'out_date' => 'required|date',
    //         'from_loc_id' => 'required|integer',
    //         'out_desc' => 'required|string',
    //         'reason_id' => 'required|integer',
    //         'asset_id.*' => 'required|integer',
    //         'qty.*' => 'required|integer',
    //         'condition_id.*' => 'required|integer|exists:m_condition,condition_id',
    //         // Add other validation rules as necessary
    //     ]);

    //     // Check if the MoveOut entry exists
    //     $moveout = DB::table('t_out')->where('out_id', $out_id)->first();

    //     if (!$moveout) {
    //         return response()->json(['status' => 'error', 'message' => 'MoveOut record not found.'], 404);
    //     }

    //     $updated = DB::table('t_out')->where('out_id', $out_id)->update([
    //         'out_date' => $request->input('out_date'),
    //         'from_loc' => $request->input('from_loc_id'),
    //         'out_desc' => $request->input('out_desc'),
    //         'reason_id' => $request->input('reason_id'),
    //         'updated_at' => Carbon::now(),
    //     ]);

    //     if ($updated == 0) {
    //         return response()->json(['status' => 'error', 'message' => 'No changes were made to the MoveOut record.'], 500);
    //     }

    //     foreach ($request->input('asset_id') as $index => $assetId) {
    //         $imagePath = null;

    //         // Handle file upload for image
    //         if ($request->hasFile("image.$index") && $request->file("image.$index")->isValid()) {
    //             $imagePath = $request->file("image.$index")->store('moveout_item/images', 'public');
    //         }

    //         $moveoutQty = $request->input('qty')[$index];

    //         $asset = DB::table('table_registrasi_asset')->where('id', $assetId)->first();

    //         // Check if the asset has already been moved out to avoid duplicates
    //         $existingDetail = DB::table('t_out_detail')
    //             ->where('asset_id', $assetId)
    //             // ->where('out_id', $out_id)  // Ensure that we don't update if already updated
    //             ->first();

    //         // If no existing detail is found, proceed to add a new detail
    //         if (!$existingDetail) {
    //             // Insert the new detail for the MoveOut
    //             DB::table('t_out_detail')->insert([
    //                 'out_id' => $out_id,
    //                 'asset_id' => $assetId,
    //                 'serial_number' => $request->input('serial_number')[$index],
    //                 'asset_tag' => $request->input('register_code')[$index],
    //                 'brand' => $request->input('merk')[$index],
    //                 'qty' => $moveoutQty,
    //                 'uom' => $request->input('satuan')[$index],
    //                 'condition' => $request->input('condition_id')[$index],
    //                 'image' => $imagePath,
    //                 'created_at' => Carbon::now(),
    //                 'updated_at' => Carbon::now(),
    //             ]);

    //             // Decrease the quantity of the asset in table_registrasi_asset
    //             DB::table('table_registrasi_asset')
    //                 ->where('id', $assetId)
    //                 ->decrement('qty', $moveoutQty);
    //         } else {
    //             // If the asset already exists in the details, just update it
    //             DB::table('t_out_detail')
    //                 ->where('out_det_id', $existingDetail->out_det_id)
    //                 ->update([
    //                     'asset_id' => $assetId,
    //                     'serial_number' => $request->input('serial_number')[$index],
    //                     'asset_tag' => $request->input('register_code')[$index],
    //                     'brand' => $request->input('merk')[$index],
    //                     'qty' => $moveoutQty,
    //                     'uom' => $request->input('satuan')[$index],
    //                     'condition' => $request->input('condition_id')[$index],
    //                     'image' => $imagePath ?: $existingDetail->image, // Use existing image if no new image is provided
    //                     'updated_at' => Carbon::now(),
    //                 ]);

    //             // Decrease the quantity of the asset in table_registrasi_asset
    //             DB::table('table_registrasi_asset')
    //                 ->where('id', $assetId)
    //                 ->decrement('qty', $moveoutQty);
    //         }
    //     }

    //     return redirect()->to('/admin/moveout')->with('success', 'MoveOut record updated successfully.');
    // }





    public function updateDataMoveOut(Request $request, $out_id)
    {
        // Validate input
        $request->validate([
            'out_date' => 'required|date',
            'from_loc' => 'required|integer',
            'out_desc' => 'required|string',
            'reason_id' => 'required|integer',
            'det_id.*' => 'required|integer',
            'qty.*' => 'required|integer',
            'condition_id.*' => 'required',
            'image.*' => 'nullable'
            // Add other validation rules as necessary
        ]);

        // Check if the MoveOut entry exists
        $moveout = DB::table('t_out')->where('out_id', $out_id)->first();

        if (!$moveout) {
            return response()->json(['status' => 'error', 'message' => 'MoveOut record not found.'], 404);
        }

        // Update the main MoveOut record
        $updated = DB::table('t_out')->where('out_id', $out_id)->update([
            'out_date' => $request->out_date,
            'from_loc' => $request->from_loc,
            'out_desc' => $request->out_desc,
            'reason_id' => $request->reason_id,
            'updated_at' => Carbon::now(),
        ]);

        if ($updated == 0) {
            return response()->json(['status' => 'error', 'message' => 'No changes were made to the MoveOut record.'], 500);
        }

        foreach ($request->det_id as $index => $id) {
            $imagePath = null;

            if ($request->hasFile("image.$index") && $request->file("image.$index")->isValid()) {
                $imagePath = $request->file("image.$index")->store('moveout_item/images', 'public');
            } else {
                $oldRecord = DB::table('t_out_detail')->where('id', $id)->first();
                $imagePath = $oldRecord->image ?? null;
            }

            DB::table('t_out_detail')
                ->where('id', $id)
                ->update([
                    'condition'  => $request->input('condition_id')[$index],
                    'image'      => $imagePath,
                    'updated_at' => now(),
                ]);
        }




        return redirect()->to('/asset-transfer/request-moveout')->with('success', 'MoveOut record updated successfully.');
    }




    public function edit($id)

    {

        $moveout = MasterMoveOut::with('asset')->findOrFail($id); // Assuming MoveOut has a relationship with Asset

        return response()->json($moveout);
    }



    public function deleteDataMoveOut($id)
    {
        // Find the moveout record
        $moveout = MasterMoveOut::find($id);

        if ($moveout) {
            if(is_null($moveout->deleted_at)){
                $moveout->is_active = 0;
                $moveout->deleted_at = Carbon::now();
                $moveout->save();
            }else{
                $moveout->is_active = 1;
                $moveout->deleted_at = null;
                $moveout->save();
            }

            // Soft delete related details
            // $moveout->details()->update(['deleted_at' => Carbon::now()]);

            return response()->json([
                'status' => 'success',
                'message' => 'MoveOut and related details deactivated successfully.',
                'redirect_url' => '/asset-transfer/request-moveout'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Data MoveOut not found.'], 404);
        }
    }







    public function details($MoveOutId)

    {

        $moveout = MasterMoveOut::where('out_id', $MoveOutId)->first();



        if (!$moveout) {

            abort(404, 'move$moveout not found');
        }



        return view('move$moveout.details', ['asset' => $moveout]);
    }

    public function getFromLocations(Request $request)
    {
        $search = $request->input('search'); // Retrieve the search term

        $locations = DB::table('miegacoa_keluhan.master_resto')
            ->select('id', 'store_code', 'name_store_street')
            ->when($search, function ($query, $search) {
                return $query->where('name_store_street', 'LIKE', "%{$search}%");
            })
            ->get();

        return response()->json($locations);
    }



    public function getDestLocations(Request $request)
    {
        $search = $request->input('search'); // Retrieve the search term

        $locations = DB::table('miegacoa_keluhan.master_resto')
            ->select('id', 'store_code', 'name_store_street')
            ->when($search, function ($query, $search) {
                return $query->where('name_store_street', 'LIKE', "%{$search}%");
            })
            ->get();

        return response()->json($locations);
    }

    public function getAjaxDataAssets()
    {


        $assets = DB::table('table_registrasi_asset')
            ->select(
                'table_registrasi_asset.id',
                'table_registrasi_asset.register_code',
                'table_registrasi_asset.serial_number',
                'table_registrasi_asset.register_date',
                'table_registrasi_asset.purchase_date',
                'table_registrasi_asset.approve_status',
                'table_registrasi_asset.serial_number',
                'm_assets.asset_model',
                'm_type.type_name',
                'm_category.cat_name',
                'm_priority.priority_name',
                'm_brand.brand_name',
                'm_brand.brand_id',
                'm_uom.uom_name',
                'm_uom.uom_id',
                'miegacoa_keluhan.master_resto.name_store_street',
                'm_layout.layout_name',
                'm_supplier.supplier_name',
                'm_condition.condition_name',
                'm_warranty.warranty_name',
                'm_periodic_mtc.periodic_mtc_name',
                'table_registrasi_asset.deleted_at'
            )
            ->leftJoin('m_assets', 'table_registrasi_asset.asset_name', '=', 'm_assets.asset_id')
            ->leftJoin('m_type', 'table_registrasi_asset.type_asset', '=', 'm_type.type_code')
            ->leftJoin('m_category', 'table_registrasi_asset.category_asset', '=', 'm_category.cat_code')
            ->leftJoin('m_priority', 'table_registrasi_asset.prioritas', '=', 'm_priority.priority_code')
            ->leftJoin('m_brand', 'table_registrasi_asset.merk', '=', 'm_brand.brand_id')
            ->leftJoin('m_uom', 'table_registrasi_asset.satuan', '=', 'm_uom.uom_id')
            ->leftJoin('miegacoa_keluhan.master_resto', 'table_registrasi_asset.register_location', '=', 'miegacoa_keluhan.master_resto.id')
            ->leftJoin('m_layout', 'table_registrasi_asset.layout', '=', 'm_layout.layout_id')
            ->leftJoin('m_supplier', 'table_registrasi_asset.supplier', '=', 'm_supplier.supplier_code')
            ->leftJoin('m_condition', 'table_registrasi_asset.condition', '=', 'm_condition.condition_id')
            ->leftJoin('m_warranty', 'table_registrasi_asset.warranty', '=', 'm_warranty.warranty_id')
            ->leftJoin('m_periodic_mtc', 'table_registrasi_asset.periodic_maintenance', '=', 'm_periodic_mtc.periodic_mtc_id')
            ->where('table_registrasi_asset.qty', '>', 0)
            ->get();

        return response()->json($assets);
    }

    public function getAjaxAssetDetails($id)
    {
        $asset =  DB::table('table_registrasi_asset')
            ->select(
                'table_registrasi_asset.id',
                'table_registrasi_asset.register_code',
                'table_registrasi_asset.serial_number',
                'table_registrasi_asset.register_date',
                'table_registrasi_asset.purchase_date',
                'table_registrasi_asset.approve_status',
                'table_registrasi_asset.serial_number',
                'table_registrasi_asset.qty',
                'm_assets.asset_model',
                'm_type.type_name',
                'm_category.cat_name',
                'm_priority.priority_name',
                'm_brand.brand_name',
                'm_brand.brand_id',
                'm_uom.uom_name',
                'm_uom.uom_id',
                'miegacoa_keluhan.master_resto.name_store_street',
                'm_layout.layout_name',
                'm_supplier.supplier_name',
                'm_condition.condition_name',
                'm_warranty.warranty_name',
                'm_periodic_mtc.periodic_mtc_name',
                'table_registrasi_asset.deleted_at'
            )
            ->leftJoin('m_assets', 'table_registrasi_asset.asset_name', '=', 'm_assets.asset_id')
            ->leftJoin('m_type', 'table_registrasi_asset.type_asset', '=', 'm_type.type_code')
            ->leftJoin('m_category', 'table_registrasi_asset.category_asset', '=', 'm_category.cat_code')
            ->leftJoin('m_priority', 'table_registrasi_asset.prioritas', '=', 'm_priority.priority_code')
            ->leftJoin('m_brand', 'table_registrasi_asset.merk', '=', 'm_brand.brand_id')
            ->leftJoin('m_uom', 'table_registrasi_asset.satuan', '=', 'm_uom.uom_id')
            ->leftJoin('miegacoa_keluhan.master_resto', 'table_registrasi_asset.register_location', '=', 'miegacoa_keluhan.master_resto.id')
            ->leftJoin('m_layout', 'table_registrasi_asset.layout', '=', 'm_layout.layout_id')
            ->leftJoin('m_supplier', 'table_registrasi_asset.supplier', '=', 'm_supplier.supplier_code')
            ->leftJoin('m_condition', 'table_registrasi_asset.condition', '=', 'm_condition.condition_id')
            ->leftJoin('m_warranty', 'table_registrasi_asset.warranty', '=', 'm_warranty.warranty_id')
            ->leftJoin('m_periodic_mtc', 'table_registrasi_asset.periodic_maintenance', '=', 'm_periodic_mtc.periodic_mtc_id')
            ->where('table_registrasi_asset.qty', '>', 0)
            ->where('table_registrasi_asset.id', $id)
            ->first();

        if ($asset) {
            return response()->json($asset);
        } else {
            return response()->json(['error' => 'Asset not found'], 404);
        }
    }


    public function getLocationUser()
    {
        $username = auth()->user()->username;

        // Fetch the current location and its ID
        $userLocation = DB::table('m_user')
            ->select('miegacoa_keluhan.master_resto.id', 'miegacoa_keluhan.master_resto.name_store_street')
            ->join('miegacoa_keluhan.master_resto', 'miegacoa_keluhan.master_resto.id', '=', 'm_user.location_now')
            ->where('m_user.username', $username)
            ->first();
        // Return the location ID and name as JSON
        return response()->json($userLocation);
    }


    public function getCondition()
    {
        $condition = DB::table('m_condition')
            ->select('condition_id', 'condition_name')
            ->get();

        return response()->json($condition);
    }


    public function searchRegisterAsset(Request $request)
    {
        $query = MasterRegistrasiModel::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('register_code', 'like', "%{$search}%")
                ->orWhere('asset_name', 'like', "%{$search}%")
                ->orWhere('serial_number', 'like', "%{$search}%");
        }

        $data = $query->paginate(10); // You can adjust the pagination limit
        return response()->json($data);
    }


    public function ajaxGetDataRegistAsset(Request $request)
    {
        $loc_user = $request->input('user_loc');
        $assets =  DB::table('table_registrasi_asset')
            ->select(
                'table_registrasi_asset.id',
                'table_registrasi_asset.register_code',
                'table_registrasi_asset.serial_number',
                'table_registrasi_asset.register_date',
                'table_registrasi_asset.purchase_date',
                'table_registrasi_asset.approve_status',
                'table_registrasi_asset.serial_number',
                'table_registrasi_asset.width',
                'table_registrasi_asset.height',
                'table_registrasi_asset.depth',
                'table_registrasi_asset.qty',
                'm_assets.asset_model',
                'm_type.type_name',
                'm_category.cat_name',
                'm_priority.priority_name',
                'm_brand.brand_name',
                'm_brand.brand_id',
                'm_uom.uom_name',
                'm_uom.uom_id',
                'miegacoa_keluhan.master_resto.name_store_street',
                'm_layout.layout_name',
                'm_supplier.supplier_name',
                'm_condition.condition_name',
                'm_warranty.warranty_name',
                'm_periodic_mtc.periodic_mtc_name',
                't_out_detail.image',
                'table_registrasi_asset.deleted_at'
            )
            ->leftJoin('m_assets', 'table_registrasi_asset.asset_name', '=', 'm_assets.asset_id')
            ->leftJoin('m_type', 'table_registrasi_asset.type_asset', '=', 'm_type.type_code')
            ->leftJoin('m_category', 'table_registrasi_asset.category_asset', '=', 'm_category.cat_code')
            ->leftJoin('m_priority', 'table_registrasi_asset.prioritas', '=', 'm_priority.priority_code')
            ->leftJoin('m_brand', 'table_registrasi_asset.merk', '=', 'm_brand.brand_id')
            ->leftJoin('m_uom', 'table_registrasi_asset.satuan', '=', 'm_uom.uom_id')
            ->leftJoin('miegacoa_keluhan.master_resto', 'table_registrasi_asset.register_location', '=', 'miegacoa_keluhan.master_resto.id')
            ->leftJoin('m_layout', 'table_registrasi_asset.layout', '=', 'm_layout.layout_id')
            ->leftJoin('m_supplier', 'table_registrasi_asset.supplier', '=', 'm_supplier.supplier_code')
            ->leftJoin('m_condition', 'table_registrasi_asset.condition', '=', 'm_condition.condition_id')
            ->leftJoin('m_warranty', 'table_registrasi_asset.warranty', '=', 'm_warranty.warranty_id')
            ->leftJoin('m_periodic_mtc', 'table_registrasi_asset.periodic_maintenance', '=', 'm_periodic_mtc.periodic_mtc_id')
            ->leftJoin('t_out_detail', 'table_registrasi_asset.id', '=', 't_out_detail.asset_id')
            ->when($loc_user != NULL, function ($query) use ($loc_user) {
                return $query->where('table_registrasi_asset.register_location', $loc_user);
            })
            ->where('table_registrasi_asset.qty', '>', 0)
            ->get();
        $data = [];
        foreach ($assets as $asset) {
            $data[] = [
                'id' => $asset->id,
                'register_code' => $asset->register_code,
                'asset_name' => $asset->asset_model,
                'merk' => $asset->brand_name,
                'qty' => $asset->qty,
                'satuan' => $asset->uom_name,
                'serial_number' => $asset->serial_number,
                'register_code' => $asset->register_code,
                'condition' => $asset->condition_name,
                'type_asset' => $asset->type_name,
                'category_asset' => $asset->cat_name,
                'condition' => $asset->condition_name,
                'width' => $asset->width,
                'height' => $asset->height,
                'depth' => $asset->depth,
                'brand_id' => $asset->brand_id,
                'uom_id' => $asset->uom_id,

                // 'serial_number' => $asset->serial_number,
            ];
        }

        $datas = collect($data)->unique('id')->values();

        return response()->json([
            'data' => $datas,
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
        ]);
    }


    public function dataDetailMovement($id)
    {
        $user = Auth::User();
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();
        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();

        $assets = DB::table('t_out_detail AS a')
            ->select(
                'a.id',
                'a.asset_tag',
                'c.asset_model',
                'd.brand_name',
                'a.qty',
                'e.uom_name',
                'b.serial_number',
                'a.condition',
                'a.image'
            )
            ->leftJoin('table_registrasi_asset AS b', 'b.register_code', 'a.asset_tag')
            ->leftJoin('m_assets AS c', 'c.asset_id', '=', 'b.asset_name')
            ->leftJoin('m_brand AS d', 'd.brand_id', '=', 'b.merk')
            ->leftJoin('m_uom AS e', 'e.uom_id', '=', 'b.satuan')
            ->where('a.out_id', $id)
            ->get();

        $moveOutAssets = DB::table('t_out')
            ->select(
                't_out.*',
                't_out_detail.*',
                'm_reason.*',
                'from_loc.name_store_street AS from_location', // Fetch specific fields if needed
                'dest_loc.name_store_street AS destination_location'
            )
            ->join('t_out_detail', 't_out.out_id', '=', 't_out_detail.out_id')
            ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
            ->join('miegacoa_keluhan.master_resto AS from_loc', 't_out.from_loc', '=', 'from_loc.id')
            ->join('miegacoa_keluhan.master_resto AS dest_loc', 't_out.dest_loc', '=', 'dest_loc.id')
            ->where('t_out.out_id', $id)
            ->first();


        // dd($assets);
        return view('asset_transfer.detail_data_movement', compact('moveOutAssets', 'reasons', 'conditions', 'assets', 'user'));
    }



    public function editDataDetailMovement($id)
    {

        $user = Auth::User();
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();
        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();

        $assets = DB::table('t_out_detail AS a')
            ->select(
                'a.id',
                'a.asset_tag',
                'c.asset_model',
                'd.brand_name',
                'a.qty',
                'e.uom_name',
                'b.serial_number',
                'a.condition',
                'a.image'
            )
            ->leftJoin('table_registrasi_asset AS b', 'b.register_code', 'a.asset_tag')
            ->leftJoin('m_assets AS c', 'c.asset_id', '=', 'b.asset_name')
            ->leftJoin('m_brand AS d', 'd.brand_id', '=', 'b.merk')
            ->leftJoin('m_uom AS e', 'e.uom_id', '=', 'b.satuan')
            ->where('a.out_id', $id)
            ->get();

        $moveOutAssets = DB::table('t_out')
            ->select(
                't_out.*',
                't_out_detail.*',
                'm_reason.*',
                'from_loc.name_store_street AS from_location', // Fetch specific fields if needed
                'dest_loc.name_store_street AS destination_location'
            )
            ->join('t_out_detail', 't_out.out_id', '=', 't_out_detail.out_id')
            ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
            ->join('miegacoa_keluhan.master_resto AS from_loc', 't_out.from_loc', '=', 'from_loc.id')
            ->join('miegacoa_keluhan.master_resto AS dest_loc', 't_out.dest_loc', '=', 'dest_loc.id')
            ->where('t_out.out_id', $id)
            ->first();


        // dd($assets);
        return view('asset_transfer.edit_data_movement', compact('moveOutAssets', 'reasons', 'conditions', 'assets', 'user'));
    }

    public function filter(Request $request)
    {
        // Validate input
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Extract and format dates
        $startDate = \Carbon\Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = \Carbon\Carbon::parse($request->input('end_date'))->endOfDay();

        // Filter query
        $moveoutsQuery = DB::table('t_out')
            ->select(
                't_out.*',
                'm_reason.reason_name',
                'mc_approval.approval_name',
                'fromResto.name_store_street as from_location',
                'toResto.name_store_street as dest_location'
            )
            ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
            ->join('mc_approval', 't_out.is_confirm', '=', 'mc_approval.approval_id')
            ->join('miegacoa_keluhan.master_resto as fromResto', 't_out.from_loc', '=', 'fromResto.id') // Alias for from_loc
            ->join('miegacoa_keluhan.master_resto as toResto', 't_out.dest_loc', '=', 'toResto.id')   // Alias for dest_loc
            ->whereBetween('t_out.out_date', [$startDate, $endDate]);

        $moveouts = $moveoutsQuery->paginate(10);

        // Other necessary data
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();
        $username = auth()->user()->username;
        $fromLoc = DB::table('m_user')->where('username', $username)->value('location_now');
        $restos = DB::table('miegacoa_keluhan.master_resto')->select('store_code', 'name_store_street')->get();
        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();
        $assets = DB::table('table_registrasi_asset')->select('id', 'asset_name')->where('qty', '>', 0)->get();

        // Return view with data
        return view('asset_transfer.lihat_data_movement', compact('moveouts', 'reasons', 'restos', 'conditions', 'assets', 'fromLoc', 'username'));
    }



    public function getOutDetails($outId)
    {
        $details = DB::table('t_out as t_out_main')
            ->select(
                't_out_main.*',
                't_out_detail.*',
                'table_registrasi_asset.asset_name',
                'table_registrasi_asset.register_code',
                'table_registrasi_asset.qty',
                'm_condition.condition_name',
                'm_condition.condition_id',
                'm_assets.asset_model',
                'm_brand.brand_name',
                'm_brand.brand_id',
                'm_uom.uom_name',
                'm_uom.uom_id',
            )
            ->join('t_out_detail', 't_out_main.out_id', '=', 't_out_detail.out_id')
            ->join('table_registrasi_asset', 't_out_detail.asset_id', '=', 'table_registrasi_asset.id')
            ->join('m_assets', 'table_registrasi_asset.asset_name', '=', 'm_assets.asset_id')
            ->leftJoin('m_condition', 't_out_detail.condition', '=', 'm_condition.condition_id')
            ->join('m_brand', 't_out_detail.brand', '=', 'm_brand.brand_id')
            ->join('m_uom', 't_out_detail.uom', '=', 'm_uom.uom_id')
            ->where('t_out_main.out_id', $outId)
            ->get();

        $transformedDetails = $details->map(function ($item) {
            $item->image = $item->image;
            return $item;
        });

        return response()->json($transformedDetails);
    }


    public function update(Request $request, $out_id)
    {
        $validatedData = $request->validate([
            'out_date' => 'required|date',
            'reason_id' => 'required|integer',
            'asset_id' => 'array|required',
            'merk' => 'array',
            'qty' => 'array',
            'satuan' => 'array',
            'serial_number' => 'array',
            'register_code' => 'array',
            'condition_id' => 'array|required',
            'image.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // Update the main movement out data
            DB::table('t_out')
                ->where('out_id', $out_id)
                ->update([
                    'out_date' => $validatedData['out_date'],
                    'from_loc' => $validatedData['from_loc_id'],
                    'dest_loc' => $validatedData['dest_loc'],
                    'out_desc' => $validatedData['out_desc'],
                    'reason_id' => $validatedData['reason_id'],
                    'updated_at' => now()
                ]);

            // First, delete existing details for this move out
            DB::table('t_out_detail')
                ->where('out_id', $out_id)
                ->delete();

            // Now insert new details
            foreach ($validatedData['asset_id'] as $index => $assetId) {
                $imagePath = null;

                // Handle image upload
                if (isset($validatedData['image'][$index])) {
                    $image = $validatedData['image'][$index];
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('uploads/out_details'), $imageName);
                    $imagePath = 'uploads/out_details/' . $imageName;
                }

                // Create new detail record
                DB::table('t_out_detail')->insert([
                    'out_id' => $out_id,
                    'asset_id' => $assetId,
                    'merk' => $validatedData['merk'][$index] ?? null,
                    'qty' => $validatedData['qty'][$index] ?? 1,
                    'satuan' => $validatedData['satuan'][$index] ?? null,
                    'serial_number' => $validatedData['serial_number'][$index] ?? null,
                    'register_code' => $validatedData['register_code'][$index] ?? null,
                    'condition_id' => $validatedData['condition_id'][$index],
                    'image' => $imagePath,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            return redirect()->to('/asset-transfer/request-moveout')->with('success', 'Move out updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Failed to update move out: ' . $e->getMessage());
        }
    }


    public function getEditOutDetails($out_id)
    {
        $details = DB::table('t_out as t_out_main')
            ->select(
                't_out_main.*',
                't_out_detail.*',
                'table_registrasi_asset.asset_name',
                'm_condition.condition_name'
            )
            ->join('t_out_detail', 't_out_main.out_id', '=', 't_out_detail.out_id')
            ->join('table_registrasi_asset', 't_out_detail.asset_id', '=', 'table_registrasi_asset.id')
            ->leftJoin('m_condition', 't_out_detail.condition', '=', 'm_condition.condition_id')
            ->where('t_out_main.out_id', $outId)
            ->get();

        // Transform the details to include image filename
        $transformedDetails = $details->map(function ($item) {
            // Assuming you have an 'image' column in t_out_detail
            // If the image is stored in a different table or column, adjust accordingly
            $item->image = $item->image; // The filename of the image
            return $item;
        });

        return response()->json($transformedDetails);
    }


    public function checkLocationRelation($fromLoc)
    {
        $exists = DB::table('m_user')
            ->where('location_now', $fromLoc)
            ->exists();

        return response()->json(['exists' => $exists]);
    }
}
