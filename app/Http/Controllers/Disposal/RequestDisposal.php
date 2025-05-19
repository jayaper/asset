<?php

namespace App\Http\Controllers\Disposal;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterDisOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class RequestDisposal extends Controller
{
    public function index() 
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

        $user_loc = auth()->user()->location_now;
        $username = auth()->user()->username;

        // Mulai query builder
        $query = DB::table('t_out')
            ->select(
                't_out.*',
                'b.qty',
                'm_reason.reason_name',
                'mc_approval.approval_name',
                'miegacoa_keluhan.master_resto.*'
            )
            ->leftjoin(DB::RAW('(
                SELECT
                    b.out_id, 
                    SUM(b.qty) AS qty
                FROM t_out_detail AS b
                GROUP BY b.out_id) AS b'), 'b.out_id', '=', 't_out.out_id')

            ->leftjoin('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
            ->leftjoin('mc_approval', 't_out.is_confirm', '=', 'mc_approval.approval_id')
            ->leftjoin(
                'miegacoa_keluhan.master_resto',
                DB::raw('CONVERT(t_out.from_loc USING utf8mb4) COLLATE utf8mb4_unicode_ci'),
                '=',
                DB::raw('CONVERT(miegacoa_keluhan.master_resto.id USING utf8mb4) COLLATE utf8mb4_unicode_ci')
            )
            ->where('t_out.out_id', 'like', 'DA%')
            ->orderBy('t_out.out_id', 'DESC');
            // Jika yang login bukan admin, tambahkan filter berdasarkan `user_loc`
            $user = Auth::User();
            if ($user->hasRole('SM')) {
                $query->where(function ($q){
                    $q->where('t_out.from_loc', Auth::User()->location_now);
                });
            }else if($user->hasRole('AM')) {
                $query->where(function ($q){
                    $q->where('miegacoa_keluhan.master_resto.kode_city', Auth::User()->location_now);
                });
            }else if($user->hasRole('RM')) {
                $query->where(function ($q){
                    $q->where('miegacoa_keluhan.master_resto.id_regional', Auth::User()->location_now);
                });
            }
            $moveouts = $query->paginate(10);
    

        return view("disposal.disout", [
            'fromLoc' => $fromLoc,
            'reasons' => $reasons,
            'assets' => $assets,
            'conditions' => $conditions,
            'approvals' => $approvals,
            'moveouts' => $moveouts,
            'restos' => $restos
        ]);
    }
    public function AddRequestDisposal() {

        $user = Auth::User();
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();
        $approvals = DB::table('mc_approval')->select('approval_id', 'approval_name')->get();
        $assets = DB::table('table_registrasi_asset')->select('id', 'asset_name')->get();
        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();
        $moveouts = DB::table('t_out')
        ->select('t_out.*', 'm_reason.reason_name', 'mc_approval.approval_name','fromResto.name_store_street as from_location', 
        'toResto.name_store_street as dest_location')
        ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
        ->join('mc_approval', 't_out.is_confirm', '=', 'mc_approval.approval_id')
        ->join('miegacoa_keluhan.master_resto as fromResto', 't_out.from_loc', '=', 'fromResto.id') // Alias for from_loc
        ->join('miegacoa_keluhan.master_resto as toResto', 't_out.dest_loc', '=', 'toResto.id')   // Alias for dest_loc
        ->get();

        $location_user = DB::table('miegacoa_keluhan.master_resto')->where('id', $user->location_now)->first();
        $location_user_display = $location_user->name_store_street;

        return view('disposal.add_data_disposal', [
            'location_user_display' => $location_user_display,
            'user' => $user,
            'reasons' => $reasons,
            'assets' => $assets, 
            'conditions' => $conditions,
            'moveouts' => $moveouts,
        ]);
    }
    public function AddDataDisOut(Request $request)
    {
        $request->validate([
            'out_date' => 'required|date',
            'from_loc_id' => 'required|string|max:255',
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
    
        try {
            $trx_code = DB::table('t_trx')->where('trx_name', 'Disposal Asset')->value('trx_code');
            $today = Carbon::now()->format('ymd');
            $todayCount = MasterDisOut::whereDate('create_date', Carbon::now())->count() + 1;
            $transaction_number = str_pad($todayCount, 3, '0', STR_PAD_LEFT);
            $out_id = "{$trx_code}.{$today}.{$request->input('reason_id')}.{$request->input('from_loc_id')}.{$transaction_number}";
    
            $moveout = new MasterDisOut();
            $moveout->out_date = $request->input('out_date');
            $moveout->from_loc = $request->input('from_loc_id');
            $moveout->out_desc = $request->input('out_desc');
            $moveout->reason_id = $request->input('reason_id');
            $moveout->appr_1 = '1';
            $moveout->is_active = '1';
            $moveout->is_verify = '1';
            $moveout->is_confirm = '1';
            $moveout->create_by = Auth::user()->username;
    
            $maxMoveoutId = MasterDisOut::max('out_no');
            $out_no_base = $maxMoveoutId ? $maxMoveoutId + 1 : 1;
            $moveout->out_no = $out_no_base;
    
            $moveout->out_id = $out_id;
            $moveout->save();
    
            foreach ($request->input('asset_id') as $index => $assetId) {
                $transaction_number_str = str_pad($transaction_number, 3, '0', STR_PAD_LEFT);
                $out = "{$trx_code}.{$today}.{$request->input('reason_id')}.{$request->input('from_loc')}.{$transaction_number_str}";
    
                $imagePath = null;
                if ($request->hasFile("image.$index") && $request->file("image.$index")->isValid()) {
                    // Store the uploaded file and get its path
                    $imagePath = $request->file("image.$index")->store('moveout_item/images', 'public');
                }
    
                $currentQty = DB::table('table_registrasi_asset')
                    ->where('id', $assetId)
                    ->value('qty');
                $moveoutQty = $request->input('qty')[$index];
                
                if ($currentQty === null) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Asset ID {$assetId} not found."
                    ], 404);
                }
    
                $newQty = max(0, $currentQty - $moveoutQty);
                DB::table('table_registrasi_asset')
                    ->where('id', $assetId)
                    ->update(['qty' => $newQty]);
    
                DB::table('t_out_detail')->insert([
                    'out_det_id' => $moveout->out_no,
                    'out_id' => $out_id,
                    'asset_id' => $assetId,
                    'asset_tag' => $request->input('register_code')[$index],
                    'serial_number' => $request->input('serial_number')[$index],
                    'brand' => $request->input('merk')[$index],
                    'qty' => $moveoutQty,
                    'uom' => $request->input('satuan')[$index],
                    'condition' => $request->input('condition_id')[$index],
                    'image' => $imagePath,
                ]);

                DB::table('t_transaction_qty')->insert([
                    'out_det_id' => $moveout->out_no,
                    'out_id' => $out_id, 
                    'asset_tag' => $request->input('register_code')[$index],
                    'asset_id' => $assetId,
                    'from_loc' => $request->input('from_loc_id')[$index],
                    'qty' => $moveoutQty,
                    'qty_continue' => 1,
                    'qty_total' => 0,
                    'qty_disposal' => 0,
                    'qty_difference' => 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

            }
            return response()->json([
                'status' => 'success',
                'message' => 'Data moveout berhasil ditambahkan',
                'redirect_url' => '/disposal/request-disposal'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function detailPageDataDisposalOut($id) 
    {
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();

        $moveOutAssets = DB::table('t_out')
        ->select(
            't_out.*',
            't_out_detail.out_id AS detail_out_id',
            't_out_detail.qty',
            'm_reason.reason_name',
            'miegacoa_keluhan.master_resto.name_store_street AS from_location'
        )
        ->join('t_out_detail', 't_out.out_id', '=', 't_out_detail.out_id')
        ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
        ->join('miegacoa_keluhan.master_resto', 't_out.from_loc', '=', 'miegacoa_keluhan.master_resto.id')
        ->where('t_out.out_id', '=', $id) // Ensure specific match
        ->where('t_out.out_id', 'like', 'DA%')
        ->first();

        $assets = DB::table('table_registrasi_asset')
        ->leftjoin('t_out_detail', 'table_registrasi_asset.register_code', 't_out_detail.asset_tag')
        ->leftjoin('t_transaction_qty', 't_out_detail.out_id', '=', 't_transaction_qty.out_id')
        ->leftjoin('t_out', 't_transaction_qty.out_id', 't_out.out_id')
        ->leftjoin('m_assets', 'table_registrasi_asset.asset_name', '=', 'm_assets.asset_id')
        ->leftjoin('m_brand', 'table_registrasi_asset.merk', '=', 'm_brand.brand_id')
        ->leftjoin('m_condition', 'table_registrasi_asset.condition', '=', 'm_condition.condition_id')
        ->leftjoin('m_uom', 'table_registrasi_asset.satuan', '=', 'm_uom.uom_id')
        ->select('m_assets.asset_model', 'm_brand.brand_name', 't_transaction_qty.qty', 'm_uom.uom_name', 'table_registrasi_asset.serial_number', 'table_registrasi_asset.register_code', 'm_condition.condition_name', 't_out_detail.image')
        ->where('t_out.out_id', 'like', 'DA%')
        ->where('t_out_detail.out_id', $id)
        ->get();

        // dd($moveOutAssets);

        return view('disposal.detail_data_disposal', compact('reasons', 'moveOutAssets', 'assets'));
    }

    public function previewPDF($out_id)
    {

        // Ambil data berdasarkan out_id

        $data = DB::table('t_out')
        ->select(
            't_out.*',
            't_out_detail.*',
            'fromResto.store_code as origin_site',
            'table_registrasi_asset.asset_name',
            'table_registrasi_asset.register_code',
            'm_assets.asset_model',
            'm_category.cat_name',
            'm_reason.reason_name',
            'm_condition.condition_name'
        )
        ->join('t_out_detail', 't_out.out_id', '=', 't_out_detail.out_id')
        ->leftJoin('miegacoa_keluhan.master_resto as fromResto', 't_out.from_loc', '=', 'fromResto.id')
        ->join('table_registrasi_asset', 't_out_detail.asset_id', '=', 'table_registrasi_asset.id')
        ->join('m_assets','table_registrasi_asset.asset_name', '=', 'm_assets.asset_id')
        // ->leftJoin('miegacoa_keluhan.master_resto as toResto', 't_out.dest_loc', '=', 'toResto.id')
        // ->leftJoin('table_registrasi_asset', 't_out_detail.asset_id', '=', 'table_registrasi_asset.id')
        ->join('m_condition','t_out_detail.condition', '=', 'm_condition.condition_id')
        ->join('m_category','table_registrasi_asset.category_asset', '=', 'm_category.cat_code')
        // ->leftJoin('m_type', 'table_registrasi_asset.type_asset', '=', 'm_type.type_id')
        ->leftJoin('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
        
        ->where('t_out.out_id', $out_id)
        ->where('t_out.out_id', 'like', 'DA%')
        ->get();

        $firstRecord = $data->first();


        if (!$firstRecord) {

            abort(404, 'MoveOut not found');

        }



        // Buat PDF menggunakan data yang ditemukan

        $pdf = PDF::loadView('disposal.pdf_disposal_out', compact('data', 'firstRecord'));



        return response($pdf->output(), 200)->header('Content-Type', 'application/pdf');

    }

    public function editDetailDataDisout($id) {
        $user = Auth::User();
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();
        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();
        
        $assets = DB::table('table_registrasi_asset')
        ->select('table_registrasi_asset.id'
                ,'table_registrasi_asset.register_code'    
                ,'table_registrasi_asset.serial_number'
                ,'table_registrasi_asset.register_date'
                ,'table_registrasi_asset.purchase_date'
                ,'table_registrasi_asset.approve_status'
                ,'table_registrasi_asset.serial_number'
                ,'table_registrasi_asset.width'
                ,'table_registrasi_asset.height'
                ,'table_registrasi_asset.depth'
                ,'table_registrasi_asset.qty'
                ,'m_assets.asset_model'
                ,'m_type.type_name'
                ,'m_category.cat_name'
                ,'m_priority.priority_name'
                ,'m_brand.brand_name'
                ,'m_brand.brand_id'
                ,'m_uom.uom_name'
                ,'m_uom.uom_id'
                ,'miegacoa_keluhan.master_resto.name_store_street'
                ,'m_layout.layout_name'
                ,'m_supplier.supplier_name'
                ,'m_condition.condition_name'
                ,'m_condition.condition_id'
                ,'m_warranty.warranty_name'
                ,'m_periodic_mtc.periodic_mtc_name'
                ,'table_registrasi_asset.deleted_at')
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

        $moveOutAssets = DB::table('t_out')
        ->select(
            't_out.*',
            't_out_detail.*',
            't_out.out_id',
            't_out_detail.out_id AS detail_out_id',
            't_out_detail.qty',
            'm_reason.reason_name',
            'miegacoa_keluhan.master_resto.name_store_street'
        )
        ->join('t_out_detail', 't_out.out_id', '=', 't_out_detail.out_id')
        ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
        ->join('miegacoa_keluhan.master_resto', 't_out.from_loc', '=', 'miegacoa_keluhan.master_resto.id')
        ->where('t_out.out_id', '=', $id) // Ensure specific match
        ->where('t_out.out_id', 'like', 'DA%')
        ->first();



        // dd($moveOutAssets);

            return view('disposal.edit_data_disposal', compact('moveOutAssets','reasons','conditions','assets', 'user'));
    }

    public function deleteDataDisOut($id)
    {
        $moveout = MasterDisOut::find($id);

        if ($moveout) {
            $moveout->is_active = 0;
            $moveout->deleted_at = Carbon::now();
            $moveout->delete();
            return response()->json([
                'status' => 'success', 
                'message' => 'Disposal deleted successfully.',
                'redirect_url' => '/disposal/request-disposal'
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data move$moveout Gagal Terhapus'], 404);
        }
    }
}
