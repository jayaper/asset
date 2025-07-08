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
use App\Exports\ReportStockopname;
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
            'a.register_date',
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
            'c.condition_name',
        )
        ->leftjoin('miegacoa_keluhan.master_resto', 'asset_tracking.from_loc', '=', 'miegacoa_keluhan.master_resto.id')
        ->leftjoin('miegacoa_keluhan.master_resto as des', 'asset_tracking.dest_loc', '=', 'des.id')
        ->leftjoin('m_reason AS r', 'asset_tracking.reason', '=', 'r.reason_id')
        ->leftjoin('m_condition AS c', 'c.condition_id', '=', 'asset_tracking.condition')
        ->where('register_code', $register_code);

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

        if ($request->filled('location')) {
            $tanggal  = $request->input('date') ?? Carbon::now()->format('Y-m-d');
            $jam      = $request->input('time') ?? '23:59';
            $datetime = $tanggal . ' ' . $jam . ':59';
            $lokasi   = $request->input('location');
            $T_regist = DB::table('t_out_detail as b')
                ->join('t_out as c', 'c.out_id', '=', 'b.out_id')
                ->where('c.confirm_date', '<=', $datetime)
                ->select(
                    'b.asset_tag',
                    'c.dest_loc',
                    'c.confirm_date',
                    DB::raw('ROW_NUMBER() OVER (PARTITION BY b.asset_tag ORDER BY c.confirm_date DESC) as rn')
                );

            $final = DB::table('table_registrasi_asset as a')
                ->leftJoinSub($T_regist, 'latest_movement', function ($join) {
                    $join->on('a.register_code', '=', 'latest_movement.asset_tag')
                        ->where('latest_movement.rn', '=', 1);
                })
                ->select(
                    DB::raw('COALESCE(latest_movement.confirm_date, a.created_at) as last_known_date'),
                    'a.register_date',
                    'a.register_code',
                    'b.asset_model',
                    'a.serial_number',
                    'a.last_transaction_code',
                    'c.uom_name',
                    'd.name AS status_asset',
                    'e.condition_name',
                    'f.type_name',
                    'g.cat_name',
                    'h.name_store_street AS lokasi_sekarang',
                    'i.layout_name'
                )
                ->leftJoin('m_assets AS b', 'b.asset_id', '=', 'a.asset_name')
                ->leftJoin('m_uom AS c', 'c.uom_id', '=', 'a.satuan')
                ->leftJoin('m_status_asset AS d', 'd.id', '=', 'a.status_asset')
                ->leftJoin('m_condition AS e', 'e.condition_id', '=', 'a.condition')
                ->leftJoin('m_type AS f', 'f.type_id', '=', 'a.type_asset')
                ->leftJoin('m_category AS g', 'g.cat_code', '=', 'a.category_asset')
                ->leftJoin('miegacoa_keluhan.master_resto AS h', function ($join) {
                    $join->on(DB::raw('COALESCE(latest_movement.dest_loc, a.register_location)'), '=', 'h.id');
                })

                ->leftJoin('m_layout AS i', 'i.layout_id', '=', 'a.layout')
                ->whereRaw('COALESCE(latest_movement.confirm_date, a.created_at) <= ?', [$datetime])
                ->whereRaw('COALESCE(latest_movement.dest_loc, a.register_location) = ?', [$lokasi])
                ->get();
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

    public function ExportStockAssetPerLocation(Request $request) {
        return Excel::download(new ReportStockAssetPerLocation($request), 'data_stock_asset_per_location.xlsx');
    }


    public function ReportGaransiAsset() {
        return view('Admin.report.report_garansi_asset');
    }

    public function ReportDisposalAsset(Request $request) {
        $user = Auth::user();
        $final = collect();

        if ($request->filled('location')) {
            $T_regist = DB::table('t_out_detail AS a')
                ->select(
                    'a.asset_tag',
                    'c.asset_model',
                    'd.cat_name',
                    'b.serial_number',
                    'b.register_date',
                    'e.out_date',
                    'e.out_desc',
                    'f.reason_name',
                    'e.confirm_date',
                    'e.appr_3_user',
                )
                ->leftJoin('table_registrasi_asset AS b', 'b.register_code', '=', 'a.asset_tag')
                ->leftJoin('m_assets AS c', 'c.asset_id', '=', 'b.asset_name')
                ->leftJoin('m_category AS d', 'd.cat_code', '=', 'b.category_asset')
                ->leftJoin('t_out AS e', 'e.out_id', '=', 'a.out_id')
                ->leftJoin('m_reason AS f', 'f.reason_id', '=', 'e.reason_id')
                ->where('e.out_id', 'like', 'DA%')
                ->where('e.is_confirm', 3)
                ->where('b.location_now', $request->input('location'));

                if($request->filled('start_date') && $request->filled('end_date')){
                    $T_regist->whereBetween('e.out_date', [
                        $request->input('start_date') . ' 00:00:00',
                        $request->input('end_date') . ' 23:59:59'
                    ]);
                }

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

        return view('report.report_disposal_asset', [
            'user' => $user,
            'selectLoc' => $select_loc,
            'tRegist' => $final
        ]);
    }

    public function ExportExcelDisposalAssetData(Request $request) {
        return Excel::download(new ReportDisposalData($request),'data_disposal_asset.xlsx');
    }

    public function ReportStockOpname(Request $request) {
        $user = Auth::user();
        $TSO = collect();

        if ($request->filled('location')) {
            $tStockopname = DB::table('t_stockopname AS a')
                        ->select(
                            'a.code',
                            'a.description',
                            'a.create_date',
                            'a.create_by',
                            'a.user_confirm',
                            'a.deleted_at',
                            'b.reason_name',
                            'c.name_store_street',
                            'd.asset_tag',
                            'd.condition',
                            'g.condition_name',
                            'e.approval_name',
                        )
                        ->leftJoin('m_reason AS b', 'b.reason_id', '=', 'a.reason')
                        ->leftJoin('miegacoa_keluhan.master_resto AS c', 'c.id', '=', 'a.location')
                        ->leftJoin('t_stockopname_detail AS d', 'd.so_code', '=', 'a.code')
                        ->leftJoin('mc_approval AS e', 'e.approval_id', '=', 'a.is_confirm')
                        ->leftJoin('m_condition AS g', 'g.condition_id', '=', 'd.condition')
                        ->where('a.is_confirm', 3)
                        ->where('a.location', $request->input('location'));
                        if ($request->filled('start_date') && $request->filled('end_date')) {
                            $tStockopname->whereBetween(DB::raw('DATE(a.create_date)'), [
                                $request->input('start_date'),
                                $request->input('end_date')
                            ]);
                        }

                        $TSO = $tStockopname->get();
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

        return view('report.report_stock_opname', [
            'user' => $user,
            'selectLoc' => $select_loc,
            'stockopnames' => $TSO
        ]);
    }

    public function ExportStockopname(Request $request) {
        return Excel::download(new ReportStockopname($request),'data_stock_opname.xlsx');
    }

    public function ReportTrendIssue() {
        return view('Admin.report.report_trend_issue');
    }
}