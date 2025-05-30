<?php

namespace App\Http\Controllers;

use App\Exports\ReportAssetRegist;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\JsonResponse;

use Maatwebsite\Excel\Facades\Excel;

use App\Exports\ReportMutasiStock;

use App\Exports\ReportDisposalData;
use App\Exports\ReportKartuStock;
use App\Exports\ReportStockAssetPerLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller {

    public function ReportRegistrasiAsset() {
        return view('report.report_registrasi_asset');
    }


    public function ReportGetDataRegistrasiAsset(): JsonResponse {
    
        $dataAssets = DB::table('table_registrasi_asset')
            ->select('table_registrasi_asset.id'
                    ,'table_registrasi_asset.register_code'    
                    ,'table_registrasi_asset.serial_number'
                    ,'table_registrasi_asset.register_date'
                    ,'table_registrasi_asset.purchase_date'
                    ,'table_registrasi_asset.approve_status'
                    ,'table_registrasi_asset.serial_number'
                    ,'table_registrasi_asset.qty'
                    ,'m_assets.asset_model'
                    ,'m_type.type_name'
                    ,'m_category.cat_name'
                    ,'m_priority.priority_name'
                    ,'m_brand.brand_name'
                    ,'m_uom.uom_name'
                    ,'miegacoa_keluhan.master_resto.name_store_street'
                    ,'m_layout.layout_name'
                    ,'m_supplier.supplier_name'
                    ,'m_condition.condition_name'
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
            ->leftJoin('m_periodic_mtc', 'table_registrasi_asset.periodic_maintenance', '=', 'm_periodic_mtc.periodic_mtc_id');
            $user = Auth::User();
            if($user->hasRole('SM')){
                $dataAssets->where(function($q) use ($user) {
                    $q->where('table_registrasi_asset.location_now', $user->location_now);
                });
            }else if($user->hasRole('AM')){
                $dataAssets->where(function($q) use ($user) {
                    $q->where('miegacoa_keluhan.master_resto.kode_city', $user->location_now);
                });
            }else if($user->hasRole('RM')){
                $dataAssets->where(function($q) use ($user) {
                    $q->where('miegacoa_keluhan.master_resto.id_regional', $user->location_now);
                });
            }
            $dataAsset = $dataAssets->get();



        foreach ($dataAsset as $Asset) {

            // Set data_registrasi_asset_status based on deleted_at

            $Asset->data_registrasi_asset_status = is_null($Asset->deleted_at) ? 'active' : 'nonactive';



            // Check if asset_code is not null before generating the QR code

            if (!empty($Asset->asset_code)) {

                // Define the file path for the QR code

                $qrCodeFileName = $Asset->asset_code . '.png';

                $qrCodeFilePath = public_path('qrcodes/' . $qrCodeFileName);



                // Check if the QR code already exists

                if (file_exists($qrCodeFilePath)) {

                    // Assign the QR code path to the asset object

                    $Asset->qr_code_path = asset('public/qrcodes/' . $qrCodeFileName);

                } else {

                    // Generate the QR code and save it to the defined path if it doesn't exist

                    QrCode::format('png')->size(300)->generate($Asset->asset_code, $qrCodeFilePath);

                    // Assign the newly generated QR code path to the asset object

                    $Asset->qr_code_path = asset('public/qrcodes/' . $qrCodeFileName);

                }

            }

        }

        return response()->json($dataAsset);

    }

    public function ExportAssetRegist() {
        return Excel::download(new ReportAssetRegist,'report_asset_registered.xlsx');
    }

    public function ReportMutasiStock(Request $request) {
        $t_out_det = DB::table('t_out_detail AS a')
                    ->select(
                        'a.asset_tag',
                        'a.out_id',
                        'c.appr_1_user',
                        'c.appr_1_date',
                        'c.appr_2_user',
                        'c.appr_2_date',
                        'c.appr_3_user',
                        'c.appr_3_date',
                        'd.asset_model',
                        'a.qty',
                        'e.uom_name',
                        'f.name_store_street AS lokasi_asal',
                        'g.name_store_street AS lokasi_akhir',
                        'h.condition_name',
                        'a.serial_number',
                        'i.reason_name',
                        'c.create_date',
                        'j.approval_name',
                        'c.confirm_date',
                        'c.out_date',
                    )
                    ->leftJoin('table_registrasi_asset AS b', 'b.register_code', '=', 'a.asset_tag')
                    ->leftJoin('t_out AS c', 'c.out_id', '=', 'a.out_id')
                    ->leftJoin('m_assets AS d', 'd.asset_id', '=', 'b.asset_name')
                    ->leftJoin('m_uom AS e', 'e.uom_id', '=', 'b.satuan')
                    ->leftJoin('miegacoa_keluhan.master_resto AS f', 'f.id', '=', 'c.from_loc')
                    ->leftJoin('miegacoa_keluhan.master_resto AS g', 'g.id', '=', 'c.dest_loc')
                    ->leftJoin('m_condition AS h', 'h.condition_id', '=', 'a.condition')
                    ->leftJoin('m_reason AS i', 'i.reason_id', '=', 'c.reason_id')
                    ->leftJoin('mc_approval AS j', 'j.approval_id', '=', 'c.is_confirm')
                    ->where('a.out_id', 'like', 'AM%');
                    if ($request->filled('start_date') && $request->filled('end_date')) {
                        $t_out_det->whereBetween('c.out_date', [
                            $request->input('start_date') . ' 00:00:00',
                            $request->input('end_date') . ' 23:59:59'
                        ]);
                    }
                    $final = $t_out_det->get();
        return view('report.report_mutasi_stock', [
            'tDetail' => $final
        ]);
    }

    public function detReportMutasi(Request $request, $register_code){
        $t_out_det = DB::table('t_out_detail AS a')
                    ->select(
                        'a.asset_tag',
                        'a.out_id',
                        'c.appr_1_user',
                        'c.appr_1_date',
                        'c.appr_2_user',
                        'c.appr_2_date',
                        'c.appr_3_user',
                        'c.appr_3_date',
                        'd.asset_model',
                        'a.qty',
                        'e.uom_name',
                        'f.name_store_street AS lokasi_asal',
                        'g.name_store_street AS lokasi_akhir',
                        'h.condition_name',
                        'a.serial_number',
                        'i.reason_name',
                        'c.create_date',
                        'j.approval_name',
                        'c.confirm_date',
                        'c.out_date',
                    )
                    ->leftJoin('table_registrasi_asset AS b', 'b.register_code', '=', 'a.asset_tag')
                    ->leftJoin('t_out AS c', 'c.out_id', '=', 'a.out_id')
                    ->leftJoin('m_assets AS d', 'd.asset_id', '=', 'b.asset_name')
                    ->leftJoin('m_uom AS e', 'e.uom_id', '=', 'b.satuan')
                    ->leftJoin('miegacoa_keluhan.master_resto AS f', 'f.id', '=', 'c.from_loc')
                    ->leftJoin('miegacoa_keluhan.master_resto AS g', 'g.id', '=', 'c.dest_loc')
                    ->leftJoin('m_condition AS h', 'h.condition_id', '=', 'a.condition')
                    ->leftJoin('m_reason AS i', 'i.reason_id', '=', 'c.reason_id')
                    ->leftJoin('mc_approval AS j', 'j.approval_id', '=', 'c.is_confirm')
                    ->where('a.out_id', 'like', 'AM%')
                    ->where('a.asset_tag', $register_code);
                    if ($request->filled('start_date') && $request->filled('end_date')) {
                        $t_out_det->whereBetween('c.out_date', [
                            $request->input('start_date') . ' 00:00:00',
                            $request->input('end_date') . ' 23:59:59'
                        ]);
                    }
                    $final = $t_out_det->get();
        return view('report.report_mutasi_stock_detail', [
            'registerCode' => $register_code,
            'tDetail' => $final
        ]);
    }


    public function ExportExcelMutasiStock(Request $request) {
        return Excel::download(new ReportMutasiStock($request),'data_mutasi_stock.xlsx');
    }

    public function ReportKartuStock(Request $request) {
        $user = Auth::user();
        $t_regist = DB::table('table_registrasi_asset AS a')
                        ->select(
                            'a.id',
                            'a.register_code',
                            'b.asset_model',
                            'c.name_store_street AS loc_asset'
                        )
                        ->leftJoin('m_assets AS b', 'b.asset_id', '=', 'a.asset_name')
                        ->leftJoin('miegacoa_keluhan.master_resto AS c', 'c.id', '=', 'a.location_now');
                        if($user->hasRole('SM')){
                            $t_regist->where(function($q) use ($user) {
                                $q->where('c.id', $user->location_now);
                            });
                        }else if($user->hasRole('AM')){
                            $t_regist->where(function($q) use ($user) {
                                $q->where('c.kode_city', $user->location_now);
                            });
                        }else if($user->hasRole('RM')){
                            $t_regist->where(function($q) use ($user) {
                                $q->where('c.id_regional', $user->location_now);
                            });
                        }
                        if($request->filled('search')){
                            $search = $request->input('search');
                            $t_regist->where(function($q) use ($search) {
                                $q->where('c.name_store_street', 'like', '%' . $search . '%')
                                    ->orWhere('b.asset_model', 'like', '%' . $search . '%');
                            });
                        }
        $table_regist = $t_regist->get();
        return view('report.report_kartu_stock', [
            'tRegist' => $table_regist
        ]);
    }

    public function detReportKartuStock(Request $request, $register_code){

        $t_regist = DB::table('table_registrasi_asset AS a')
        ->select(
            'b.name_store_street AS lokasi_sekarang',
            'c.name_store_street AS register_lokasi'
        )
        ->leftJoin('miegacoa_keluhan.master_resto AS b', 'b.id', 'a.location_now')
        ->leftJoin('miegacoa_keluhan.master_resto AS c', 'c.id', 'a.register_location')
        ->where('a.register_code', $register_code)
        ->first();

        $t_tracking = DB::table('asset_tracking')
        ->select(
            'asset_tracking.*',
            'miegacoa_keluhan.master_resto.name_store_street as asal',
            'des.name_store_street as menuju',
            'r.reason_name',
            'a.qty AS saldo'
        )
        ->leftjoin('miegacoa_keluhan.master_resto', 'asset_tracking.from_loc', '=', 'miegacoa_keluhan.master_resto.id')
        ->leftjoin('miegacoa_keluhan.master_resto as des', 'asset_tracking.dest_loc', '=', 'des.id')
        ->leftjoin('m_reason AS r', 'asset_tracking.reason', '=', 'r.reason_id')
        ->leftjoin('t_out_detail AS a', 'a.out_id', '=', 'asset_tracking.out_id')
        ->where('register_code', $register_code)->where('a.asset_tag', $register_code);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $t_tracking->whereBetween('asset_tracking.start_date', [
                $request->input('start_date') . ' 00:00:00',
                $request->input('end_date') . ' 23:59:59'
            ]);
        }
        $final = $t_tracking->get()
        ->map(function ($item) {
            $item->start_date_formatted = Carbon::parse($item->start_date)->format('d-m-Y, H:i');
            $item->end_date_formatted = Carbon::parse($item->end_date)->format('d-m-Y, H:i');
            return $item;
        });
        return view('report.report_kartu_stock_detail', [
            'registerCode' => $register_code,
            'tRegist' => $t_regist,
            'trackings' => $final
        ]);
    }


    public function ExportExcelKartuStock(Request $request, $register_code) {
        return Excel::download(new ReportKartuStock($request, $register_code), 'data_kartu_stock.xlsx');
    }


    public function ReportChecklistAsset() {
        return view('Admin.report.report_checklist_asset');
    }

    public function ReportMaintenaceAsset() {
        return view('Admin.report.report_maintenance_asset');
    }

    public function ReportHistoryMaintenace() {
        return view('Admin.report.report_history_maintenance');
    }

    
    public function ReportStockAssetPerLocation(Request $request) {
        $user = Auth::user();
        $final = collect();

        if ($request->filled('date') && $request->filled('location')) {
            $T_regist = DB::table('table_registrasi_asset AS a')
                ->select(
                    'a.register_date',
                    'a.register_code',
                    'b.asset_model',
                    'a.serial_number',
                    'a.qty',
                    'c.uom_name',
                    'd.name AS status_asset',
                    'e.condition_name',
                    'f.type_name',
                    'g.cat_name',
                    'h.name_store_street AS lokasi_sekarang',
                    'i.layout_name',
                )
                ->leftJoin('m_assets AS b', 'b.asset_id', '=', 'a.asset_name')
                ->leftJoin('m_uom AS c', 'c.uom_id', '=', 'a.satuan')
                ->leftJoin('m_status_asset AS d', 'd.id', '=', 'a.status_asset')
                ->leftJoin('m_condition AS e', 'e.condition_id', '=', 'a.condition')
                ->leftJoin('m_type AS f', 'f.type_id', '=', 'a.type_asset')
                ->leftJoin('m_category AS g', 'g.cat_code', '=', 'a.category_asset')
                ->leftJoin('miegacoa_keluhan.master_resto AS h', 'h.id', '=', 'a.location_now')
                ->leftJoin('m_layout AS i', 'i.layout_id', '=', 'a.layout')
                ->where('a.register_date', $request->input('date'))
                ->where('a.location_now', $request->input('location'));

            $final = $T_regist->get();
        }

        $q_loc = DB::table('miegacoa_keluhan.master_resto AS a');
        if($user->hasRole('SM')){
            $q_loc->where('a.id', $user->location_now);
        }else if($user->hasRole('AM')){
            $q_loc->where('a.kode_city', $user->location_now);
        }else if($user->hasRole('RM')){
            $q_loc->where('a.id_regional', $user->location_now);
        }
        $select_loc = $q_loc->get();

        return view('report.report_stock_asset_per_location', [
            'user' => $user,
            'selectLoc' => $select_loc,
            'tRegist' => $final
        ]);
    }

    public function GetDataStockAssetPerLocation() {
        $DataStockAssetPerLocation = DB::table('table_registrasi_asset AS a')
        ->select(
            'a.register_date',
            'a.register_code',
            'b.asset_model',
            'a.serial_number',
            'a.qty',
            'c.uom_name',
            'd.condition_name',
            'g.type_name',
            'h.cat_name',
            'e.name_store_street AS lokasi',
            'f.layout_name'
        )
        ->leftjoin('m_assets AS b', 'b.asset_id', '=', 'a.asset_name')
        ->leftjoin('m_uom AS c', 'c.uom_id', '=', 'a.satuan')
        ->leftjoin('m_condition AS d', 'd.condition_id', '=', 'a.condition')
        ->leftjoin('miegacoa_keluhan.master_resto AS e', 'e.id', '=', 'a.location_now')
        ->leftjoin('m_layout AS f', 'f.layout_id', '=', 'a.layout')
        ->leftjoin('m_type AS g', 'g.type_id', '=', 'a.type_asset')
        ->leftjoin('m_category AS h', 'h.cat_code', '=', 'a.category_asset')
        ->where('a.qty', '>', 0)
        ->get();


        return response()->json($DataStockAssetPerLocation);
    }

    public function ExportStockAssetPerLocation(Request $request) {
        return Excel::download(new ReportStockAssetPerLocation($request), 'data_stock_asset_per_location.xlsx');
    }


    public function ReportGaransiAsset() {
        return view('Admin.report.report_garansi_asset');
    }

    public function ReportDisposalAsset() {
        $wilayahs = DB::table('miegacoa_keluhan.master_resto')
                    ->select('id', 'name_store_street AS nama_wilayah')
                    ->get();
        return view('report.report_disposal_asset', [
            'wilayahs' => $wilayahs
        ]);
    }

    public function ReportDisposalAssetData() : JsonResponse {
        $DataDisposal = DB::table('t_out')
        ->select(
            't_out.*',
            't_out_detail.*',
            'm_reason.reason_name',
            'mc_approval.approval_name',
            'miegacoa_keluhan.master_resto.*',
            'table_registrasi_asset.*',
            'm_assets.*'
        )
        ->join('t_out_detail', 't_out.out_id', '=', 't_out_detail.out_id') // Added this join
        ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
        ->join('mc_approval', 't_out.is_confirm', '=', 'mc_approval.approval_id')
        ->join('miegacoa_keluhan.master_resto', 't_out.from_loc', '=', 'miegacoa_keluhan.master_resto.id')
        ->join('table_registrasi_asset', 't_out_detail.asset_id', '=', 'table_registrasi_asset.id')
        ->join('m_assets', 'table_registrasi_asset.asset_name', '=', 'm_assets.asset_id')
        ->where('t_out.out_id', 'like', 'DA%')
        ->where('is_confirm', 3);
        $user = Auth::User();
        if(!$user->hasRole('Admin')){
            $DataDisposal->where(function($q) use ($user){
                $q->where('t_out.from_loc', $user->location_now);
            });
        }

        $data = $DataDisposal->get();
    
    
        return response()->json($data);
    
    }

    public function ExportExcelDisposalAssetData() {
        return Excel::download(new ReportDisposalData,'data_disposal_asset.xlsx');
    }

    public function ReportStockOpname() {
        return view('report.report_stock_opname');
    }


    public function ReportStockOpnameData() : JsonResponse {
        $dataStockOpname = DB::table('t_opname_header')
        ->join('t_opname_detail', 't_opname_header.opname_id', '=', 't_opname_detail.opname_id')
        ->join('miegacoa_keluhan.master_resto', 't_opname_header.loc_id', '=', 'miegacoa_keluhan.master_resto.id')
        ->join('m_condition', 'm_condition.condition_id', '=', 't_opname_detail.condition')
        ->join('m_uom', 'm_uom.uom_id', '=', 't_opname_detail.uom')
        ->select(
            't_opname_header.opname_id', 
            't_opname_header.opname_desc',
            't_opname_header.deleted_at',
            't_opname_detail.*', 
            'm_condition.condition_name', 
            'm_uom.uom_name', 
            'miegacoa_keluhan.master_resto.name_store_street AS location_now')
        ->where('t_opname_header.is_active', '=', '1')
        ->get();

        return response()->json($dataStockOpname);
    }

    public function ReportTrendIssue() {
        return view('Admin.report.report_trend_issue');
    }
}