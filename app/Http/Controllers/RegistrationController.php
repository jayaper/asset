<?php

namespace App\Http\Controllers;

use App\Models\Master\MasterRegistrasiModel;

use App\Models\Master\MasterPeriodicMtc;

use App\Models\Master\MasterType;

use Illuminate\Http\JsonResponse;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;

use App\Exports\AssetExport;

use Maatwebsite\Excel\Facades\Excel;

use App\Imports\MasterRegistrasiImport;
use App\Models\Master\MasterAsset;
use App\Models\Master\MasterBrand;
use App\Models\Master\MasterCategory;
use App\Models\Master\MasterCondition;
use App\Models\Master\MasterLayout;
use App\Models\Master\MasterPriority;
use App\Models\Master\MasterRestoModel;
use App\Models\Master\MasterSupplier;
use App\Models\Master\MasterUom;
use App\Models\Master\MasterWarranty;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{

    public function AssetsRegist()
    {

        $dataAssets = DB::table('table_registrasi_asset')
            ->select(
                'table_registrasi_asset.id',
                'table_registrasi_asset.register_code',
                'table_registrasi_asset.serial_number',
                'table_registrasi_asset.register_date',
                'table_registrasi_asset.purchase_date',
                'table_registrasi_asset.approve_status',
                'table_registrasi_asset.serial_number',
                'table_registrasi_asset.qr_code_path',
                DB::raw('COALESCE(table_registrasi_asset.qty, 0) as qty'),
                'm_assets.asset_model',
                'm_type.type_name',
                'm_category.cat_name',
                'm_priority.priority_name',
                'm_brand.brand_name',
                'm_uom.uom_name',
                'miegacoa_keluhan.master_resto.name_store_street',
                'restoo.name_store_street as location_now',
                'table_registrasi_asset.last_transaction_code',
                'm_layout.layout_name',
                'm_supplier.supplier_name',
                'm_condition.condition_name',
                'm_warranty.warranty_name',
                'm_periodic_mtc.periodic_mtc_name',
                'm_status_asset.name AS status_asset_name',
                'm_status_asset.id AS status_asset_id',
                'table_registrasi_asset.deleted_at'
            )
            ->leftJoin('m_status_asset', 'table_registrasi_asset.status_asset', '=', 'm_status_asset.id')
            ->leftJoin('m_assets', 'table_registrasi_asset.asset_name', '=', 'm_assets.asset_id')
            ->leftJoin('m_type', 'table_registrasi_asset.type_asset', '=', 'm_type.type_code')
            ->leftJoin('m_category', 'table_registrasi_asset.category_asset', '=', 'm_category.cat_code')
            ->leftJoin('m_priority', 'table_registrasi_asset.prioritas', '=', 'm_priority.priority_code')
            ->leftJoin('m_brand', 'table_registrasi_asset.merk', '=', 'm_brand.brand_id')
            ->leftJoin('m_uom', 'table_registrasi_asset.satuan', '=', 'm_uom.uom_id')
            ->leftJoin('miegacoa_keluhan.master_resto', 'table_registrasi_asset.register_location', '=', 'miegacoa_keluhan.master_resto.id')
            ->leftJoin('miegacoa_keluhan.master_resto as restoo', 'table_registrasi_asset.location_now', '=', 'restoo.id')
            ->leftJoin('m_layout', 'table_registrasi_asset.layout', '=', 'm_layout.layout_id')
            ->leftJoin('m_supplier', 'table_registrasi_asset.supplier', '=', 'm_supplier.supplier_code')
            ->leftJoin('m_condition', 'table_registrasi_asset.condition', '=', 'm_condition.condition_id')
            ->leftJoin('m_warranty', 'table_registrasi_asset.warranty', '=', 'm_warranty.warranty_id')
            ->leftJoin('m_periodic_mtc', 'table_registrasi_asset.periodic_maintenance', '=', 'm_periodic_mtc.periodic_mtc_id');
        if (Auth::User()->hasRole('SM')) {
            $dataAssets->where(function ($q) {
                $q->where('table_registrasi_asset.location_now', Auth::user()->location_now);
            });
        } else if (Auth::User()->hasRole('AM')) {
            $dataAssets->where(function ($q) {
                $q->where('restoo.kode_city', Auth::user()->location_now);
            });
        } else if (Auth::User()->hasRole('RM')) {
            $dataAssets->where(function ($q) {
                $q->where('restoo.id_regional', Auth::user()->location_now);
            });
        }

        $dataAsset = $dataAssets->get();


        foreach ($dataAsset as $Asset) {

            $Asset->data_registrasi_asset_status = is_null($Asset->deleted_at) ? 'active' : 'nonactive';
        }

        $q_so = DB::table('table_registrasi_asset')->where('status_asset', 5)
                ->leftJoin('miegacoa_keluhan.master_resto as restoo', 'table_registrasi_asset.location_now', '=', 'restoo.id');
                if (Auth::User()->hasRole('SM')) {
                    $q_so->where(function ($q) {
                        $q->where('table_registrasi_asset.location_now', Auth::user()->location_now);
                    });
                } else if (Auth::User()->hasRole('AM')) {
                    $q_so->where(function ($q) {
                        $q->where('restoo.kode_city', Auth::user()->location_now);
                    });
                } else if (Auth::User()->hasRole('RM')) {
                    $q_so->where(function ($q) {
                        $q->where('restoo.id_regional', Auth::user()->location_now);
                    });
                }
        $cek_so = $q_so->get();

        return view("registration.assets_regist.index", [
            'cek_so' => $cek_so,
            'assets' => $dataAsset
        ]);
    }

    public function trackingAsset($id)
    {
        $t_tracking = DB::table('asset_tracking')
            ->select(
                'asset_tracking.*',
                'miegacoa_keluhan.master_resto.name_store_street as asal',
                'des.name_store_street as menuju',
                'r.reason_name',
                'c.condition_name'
            )
            ->leftjoin('miegacoa_keluhan.master_resto', 'asset_tracking.from_loc', '=', 'miegacoa_keluhan.master_resto.id')
            ->leftjoin('miegacoa_keluhan.master_resto as des', 'asset_tracking.dest_loc', '=', 'des.id')
            ->leftjoin('m_reason AS r', 'asset_tracking.reason', '=', 'r.reason_id')
            ->leftJoin('m_condition AS c', 'c.condition_id', '=', 'asset_tracking.condition')
            ->where('register_code', $id)
            ->get()
            ->map(function ($item) {
                $item->start_date_formatted = Carbon::parse($item->start_date)->format('H:i, d-m-Y');
                $item->end_date_formatted = Carbon::parse($item->end_date)->format('H:i, d-m-Y');
                return $item;
            });
        return view("registration.assets_regist.tracking_asset", [
            'trackings' => $t_tracking
        ]);
    }


    public function GetDataRegistrasiAsset(): JsonResponse
    {

        // Fetch all assets including soft-deleted ones

        // $dataAssets = DB::table('table_registrasi_asset')
        //     ->select(
        //         'table_registrasi_asset.id',
        //         'table_registrasi_asset.register_code',
        //         'table_registrasi_asset.serial_number',
        //         'table_registrasi_asset.register_date',
        //         'table_registrasi_asset.purchase_date',
        //         'table_registrasi_asset.approve_status',
        //         'table_registrasi_asset.serial_number',
        //         DB::raw('COALESCE(table_registrasi_asset.qty, 0) as qty'),
        //         'm_assets.asset_model',
        //         'm_type.type_name',
        //         'm_category.cat_name',
        //         'm_priority.priority_name',
        //         'm_brand.brand_name',
        //         'm_uom.uom_name',
        //         'miegacoa_keluhan.master_resto.name_store_street',
        //         'restoo.name_store_street as location_now',
        //         'm_layout.layout_name',
        //         'm_supplier.supplier_name',
        //         'm_condition.condition_name',
        //         'm_warranty.warranty_name',
        //         'm_periodic_mtc.periodic_mtc_name',
        //         'm_status_asset.name AS status_asset_name',
        //         'table_registrasi_asset.deleted_at'
        //     )
        //     ->leftJoin('m_status_asset', 'table_registrasi_asset.status_asset', '=', 'm_status_asset.id')
        //     ->leftJoin('m_assets', 'table_registrasi_asset.asset_name', '=', 'm_assets.asset_id')
        //     ->leftJoin('m_type', 'table_registrasi_asset.type_asset', '=', 'm_type.type_code')
        //     ->leftJoin('m_category', 'table_registrasi_asset.category_asset', '=', 'm_category.cat_code')
        //     ->leftJoin('m_priority', 'table_registrasi_asset.prioritas', '=', 'm_priority.priority_code')
        //     ->leftJoin('m_brand', 'table_registrasi_asset.merk', '=', 'm_brand.brand_id')
        //     ->leftJoin('m_uom', 'table_registrasi_asset.satuan', '=', 'm_uom.uom_id')
        //     ->leftJoin('miegacoa_keluhan.master_resto', 'table_registrasi_asset.register_location', '=', 'miegacoa_keluhan.master_resto.id')
        //     ->leftJoin('miegacoa_keluhan.master_resto as restoo', 'table_registrasi_asset.location_now', '=', 'restoo.id')
        //     ->leftJoin('m_layout', 'table_registrasi_asset.layout', '=', 'm_layout.layout_id')
        //     ->leftJoin('m_supplier', 'table_registrasi_asset.supplier', '=', 'm_supplier.supplier_code')
        //     ->leftJoin('m_condition', 'table_registrasi_asset.condition', '=', 'm_condition.condition_id')
        //     ->leftJoin('m_warranty', 'table_registrasi_asset.warranty', '=', 'm_warranty.warranty_id')
        //     ->leftJoin('m_periodic_mtc', 'table_registrasi_asset.periodic_maintenance', '=', 'm_periodic_mtc.periodic_mtc_id');
        //     if(Auth::User()->hasRole('SM')){
        //         $dataAssets->where(function($q) {
        //             $q->where('table_registrasi_asset.location_now', Auth::user()->location_now);
        //         });
        //     }else if(Auth::User()->hasRole('AM')){
        //         $dataAssets->where(function($q) {
        //             $q->where('restoo.kode_city', Auth::user()->location_now);
        //         });
        //     }else if(Auth::User()->hasRole('RM')){
        //         $dataAssets->where(function($q) {
        //             $q->where('restoo.id_regional', Auth::user()->location_now);
        //         });
        //     }

        //     $dataAsset = $dataAssets->get();


        // foreach ($dataAsset as $Asset) {

        //     // Set data_registrasi_asset_status based on deleted_at

        //     $Asset->data_registrasi_asset_status = is_null($Asset->deleted_at) ? 'active' : 'nonactive';



        //     // Check if asset_code is not null before generating the QR code

        //     if (!empty($Asset->asset_code)) {

        //         // Define the file path for the QR code

        //         $qrCodeFileName = $Asset->asset_code . '.png';

        //         $qrCodeFilePath = public_path('qrcodes/' . $qrCodeFileName);



        //         // Check if the QR code already exists

        //         if (file_exists($qrCodeFilePath)) {

        //             // Assign the QR code path to the asset object

        //             $Asset->qr_code_path = asset('public/qrcodes/' . $qrCodeFileName);
        //         } else {

        //             // Generate the QR code and save it to the defined path if it doesn't exist

        //             QrCode::format('png')->size(300)->generate($Asset->asset_code, $qrCodeFilePath);

        //             // Assign the newly generated QR code path to the asset object

        //             $Asset->qr_code_path = asset('public/qrcodes/' . $qrCodeFileName);
        //         }
        //     }
        // }



        // Return the assets with QR code paths and status as a JSON response

        return response()->json($dataAsset);
    }


    public function addAssetsRegist()
    {
        return view('registration.assets_regist.add_assets_regist');
    }


    public function getAssetsJson()
    {

        try {

            // Fetch assets from the database

            $assets = DB::table('m_assets')
                ->select('m_assets.*')
                // ->select('asset_id','asset_code','asset_model','m_priority.priority_name', 'm_category.cat_name', 'm_type.type_name', 'm_uom.uom_name')
                // ->join('m_priority', 'm_assets.priority_id', '=', 'm_priority.priority_id')
                // ->join('m_category', 'm_assets.cat_id', '=', 'm_category.cat_id')
                // ->join('m_type', 'm_assets.type_id', '=' , 'm_type.type_id')
                // ->join('m_uom', 'm_assets.uom_id', '=' , 'm_uom.uom_id')
                ->get();



            return response()->json($assets);
        } catch (\Exception $e) {

            return response()->json([

                'status' => 'error',

                'message' => 'Failed to retrieve assets',

                'error' => $e->getMessage()

            ], 500);
        }
    }

    public function getTypeJson()
    {

        // Mengambil semua data dari tabel m_type

        $types = MasterType::all();

        return response()->json($types); // Mengembalikan data dalam format JSON

    }

    public function getCategoryJson()
    {
        // Mengambil semua data dari tabel m_category
        $categorys = MasterCategory::all();
        return response()->json($categorys); // Mengembalikan data dalam format JSON
    }

    public function getPriorityJson()
    {
        // Mengambil semua data dari tabel m_priority
        $prioritys = MasterPriority::all();
        return response()->json($prioritys); // Mengembalikan data dalam format JSON
    }

    public function getBrandJson()
    {
        // Mengambil semua data dari tabel m_brand
        $brands = MasterBrand::all();
        return response()->json($brands); // Mengembalikan data dalam format JSON
    }

    public function getUomJson()
    {
        // Mengambil semua data dari tabel m_uom
        $uoms = MasterUom::all();
        return response()->json($uoms); // Mengembalikan data dalam format JSON
    }

    public function getRestoJson()
    {

        $dataResto = MasterRestoModel::all();
        return response()->json($dataResto);
    }

    public function getLayoutJson()
    {
        // Mengambil semua data dari tabel m_layout
        $layouts = MasterLayout::all();
        return response()->json($layouts); // Mengembalikan data dalam format JSON
    }

    public function getConditionJson()
    {
        // Mengambil semua data dari tabel m_condition
        $conditions = MasterCondition::all();
        return response()->json($conditions); // Mengembalikan data dalam format JSON
    }

    public function getSupplierJson()
    {
        // Mengambil semua data dari tabel m_supplier
        $suppliers = MasterSupplier::all();
        return response()->json($suppliers); // Mengembalikan data dalam format JSON

    }

    public function getWarrantyJson()
    {
        $warranties = MasterWarranty::all();
        return response()->json($warranties);
    }

    public function getPeriodicMtcJson()

    {
        // Mengambil semua data dari tabel m_periodic_mtc
        $periodics = MasterPeriodicMtc::all();
        return response()->json($periodics); // Mengembalikan data dalam format JSON
    }

    public function get()
    {
        $assets = MasterAsset::select('asset_id', 'asset_model')
            ->where('is_active', 1)  // If you have an active status column
            ->orderBy('asset_model')
            ->get();

        return response()->json($assets);
    }

    public function getAssets()
    {
        try {
            // Fetch assets from the database
            $assets = DB::table('m_assets')
                ->select('m_assets.*')
                // ->select('asset_id','asset_code','asset_model','m_priority.priority_name', 'm_category.cat_name', 'm_type.type_name', 'm_uom.uom_name')
                // ->join('m_priority', 'm_assets.priority_id', '=', 'm_priority.priority_id')
                // ->join('m_category', 'm_assets.cat_id', '=', 'm_category.cat_id')
                // ->join('m_type', 'm_assets.type_id', '=' , 'm_type.type_id')
                // ->join('m_uom', 'm_assets.uom_id', '=' , 'm_uom.uom_id')
                ->get();

            return response()->json($assets);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve assets',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function InsertAssetsRegist(Request $request)
    {

        // Validate the request data
        // dd($request->all());
        try {
            $validatedData = $request->validate([
                'register_code' => 'required|string|max:255',
                'asset_name' => 'required|string|max:255',
                'serial_number' => 'required|string',
                'type_asset' => 'required|string|max:255',
                'category_asset' => 'required|string|max:255',
                'prioritas' => 'required|string|max:255',
                'merk' => 'required|string|max:255',
                'qty' => 'required',
                'satuan' => 'required|string|max:255',
                'register_location' => 'required|string',
                'layout' => 'required|string|max:255',
                'register_date' => 'required',
                'supplier' => 'required|string|max:255',
                'condition' => 'required|string|max:255',
                'purchase_number' => 'required|string|max:255',
                'purchase_date' => 'required',
                'warranty' => 'required',
                'periodic_maintenance' => 'required',
                'approve_status' => 'nullable|string|max:255',
                'width' => 'nullable|int',
                'height' => 'nullable|int',
                'depth' => 'nullable|int',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }



        // Retrieve validated input data

        $register_code = $validatedData['register_code'];

        $asset_name = $validatedData['asset_name'];

        $serial_number = $validatedData['serial_number'];

        $type_asset = $validatedData['type_asset'];

        $category_asset = $validatedData['category_asset'];

        $prioritas = $validatedData['prioritas'];

        $merk = $validatedData['merk'];

        $qty = $validatedData['qty'];

        $satuan = $validatedData['satuan'];

        $register_location = $validatedData['register_location'];

        $layout = $validatedData['layout'];

        $register_date = $validatedData['register_date'];

        $supplier = $validatedData['supplier'];

        $condition = $validatedData['condition'];

        $purchase_number = $validatedData['purchase_number'];

        $purchase_date = $validatedData['purchase_date'];

        $warranty = $validatedData['warranty'];

        $periodic_maintenance = $validatedData['periodic_maintenance'];

        $approve_status = $validatedData['approve_status'];

        $width = $validatedData['width'];

        $height = $validatedData['height'];

        $depth = $validatedData['depth'];

        $location_now = $validatedData['location_now'] ?? $register_location;


        // Generate URL
        $url = route('assets.details', ['register_code' => $register_code]);

        // Nama file QR
        $fileName = $register_code . '.svg';
        $storagePath = 'qrcodes/' . $fileName;

        // Jika file belum ada, buat
        if (!Storage::disk('public')->exists($storagePath)) {
            $qrCodeImage = QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->generate($url); // URL ke halaman detail asset

            // Simpan QR code ke storage/public/qrcodes
            Storage::disk('public')->put($storagePath, $qrCodeImage);
        }

        // Buat URL publik
        $qrCodeUrlPath = asset('storage/qrcodes/' . $fileName);


        // Store asset data in the database

        $asset = new MasterRegistrasiModel();

        $asset->register_code = $register_code;

        $asset->asset_name = $asset_name;

        $asset->serial_number = $serial_number;

        $asset->type_asset = $type_asset;

        $asset->category_asset = $category_asset;

        $asset->prioritas = $prioritas;

        $asset->merk = $merk;

        $asset->qty = $qty;

        $asset->satuan = $satuan;


        $asset->register_location = $register_location;

        $asset->layout = $layout;

        $asset->register_date = $register_date;

        $asset->supplier = $supplier;

        $asset->condition = $condition;

        $asset->purchase_number = $purchase_number;

        $asset->purchase_date = $purchase_date;

        $asset->warranty = $warranty;

        $asset->periodic_maintenance = $periodic_maintenance;

        $asset->approve_status = $approve_status;

        $asset->width = $width;

        $asset->height = $height;

        $asset->depth = $depth;

        $asset->created_at = Carbon::now();

        $asset->location_now = $location_now;



        // Update the asset's qr_code_path before saving

        $asset->qr_code_path = $qrCodeUrlPath;



        if ($asset->save()) {
            return redirect()->to('/registration/assets-registration');
        } else {
            return redirect()->back()->with('error', 'Gagal Input Ke database');
        }
    }




    public function DeleteDataRegistrasiAsset($id)
    {
        $registrasiAsset = MasterRegistrasiModel::find($id);

        if (is_null($registrasiAsset->deleted_at)) {
            $registrasiAsset->deleted_at = Carbon::now();
        } else {
            $registrasiAsset->deleted_at = null;
        }

        if ($registrasiAsset->save()) {
            return response()->json(['status' => 'Success', 'message' => 'Data Asset Berhasil Terhapus']);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Gagal dihapus']);
        }
    }


    public function EditAssetsRegist($id)
    {
        $restos = DB::table('miegacoa_keluhan.master_resto')->get();

        $asset = DB::table('table_registrasi_asset')
            ->select(
                'table_registrasi_asset.*',
                'm_assets.asset_id',
                'm_priority.priority_code',
                'm_brand.brand_id',
                'm_assets.asset_model',
                'm_type.type_name',
                'm_category.cat_id',
                'm_category.cat_name',
                'm_priority.priority_name',
                'm_brand.brand_name',
                'm_uom.uom_name',
                'm_uom.uom_id',
                'miegacoa_keluhan.master_resto.name_store_street',
                'miegacoa_keluhan.master_resto.id as master_resto_id',
                'loc_now.id as lokasi_sekarang',
                'm_layout.layout_name',
                'm_layout.layout_id',
                'm_supplier.supplier_name',
                'm_supplier.supplier_code',
                'm_condition.condition_name',
                'm_condition.condition_id',
                'm_warranty.warranty_name',
                'm_warranty.warranty_id',
                'm_periodic_mtc.periodic_mtc_name',
                'm_periodic_mtc.periodic_mtc_id'
            )
            ->leftJoin('m_assets', 'table_registrasi_asset.asset_name', '=', 'm_assets.asset_id')
            ->leftJoin('m_type', 'table_registrasi_asset.type_asset', '=', 'm_type.type_code')
            ->leftJoin('m_category', 'table_registrasi_asset.category_asset', '=', 'm_category.cat_code')
            ->leftJoin('m_priority', 'table_registrasi_asset.prioritas', '=', 'm_priority.priority_code')
            ->leftJoin('m_brand', 'table_registrasi_asset.merk', '=', 'm_brand.brand_id')
            ->leftJoin('m_uom', 'table_registrasi_asset.satuan', '=', 'm_uom.uom_id')
            ->leftJoin('miegacoa_keluhan.master_resto', 'table_registrasi_asset.register_location', '=', 'miegacoa_keluhan.master_resto.id')
            ->leftJoin('miegacoa_keluhan.master_resto as loc_now', 'table_registrasi_asset.location_now', '=', 'loc_now.id')
            ->leftJoin('m_layout', 'table_registrasi_asset.layout', '=', 'm_layout.layout_id')
            ->leftJoin('m_supplier', 'table_registrasi_asset.supplier', '=', 'm_supplier.supplier_code')
            ->leftJoin('m_condition', 'table_registrasi_asset.condition', '=', 'm_condition.condition_id')
            ->leftJoin('m_warranty', 'table_registrasi_asset.warranty', '=', 'm_warranty.warranty_id')
            ->leftJoin('m_periodic_mtc', 'table_registrasi_asset.periodic_maintenance', '=', 'm_periodic_mtc.periodic_mtc_id')
            ->where('table_registrasi_asset.register_code', $id)
            ->first();

        // $asset = MasterRegistrasiModel::findOrFail($id); // This will return null if not found

        if (!$asset) {
            return redirect()->back()->with('error', 'Asset not found.');
        }

        return view('registration.assets_regist.edit_assets_regist', compact('asset', 'restos'));
    }



    public function UpdateAssetsRegist(Request $request, $id)
    {
      $request->validate([
        'register_code' => 'required|string|max:255',
        'asset_name' => 'required|string|max:255',
        'serial_number' => 'nullable|string|max:255',
        'type_asset' => 'nullable|string|max:255',
        'category_asset' => 'nullable|string|max:255',
        'prioritas' => 'nullable|string|max:100',
        'merk' => 'nullable|string|max:255',
        'qty' => 'required|integer|min:1',
        'satuan' => 'nullable|string|max:100',
        'register_location' => 'required|string|max:255',
        'location_now' => 'required|string|max:255',
        'layout' => 'nullable|string|max:255',
        'register_date' => 'nullable|date',
        'supplier' => 'nullable|string|max:255',
        'condition' => 'nullable|string|max:100',
        'purchase_number' => 'nullable|string|max:255',
        'purchase_date' => 'nullable|date',
        'warranty' => 'nullable|string|max:255',
        'periodic_maintenance' => 'nullable|string|max:255',
    ]);

    // Retrieve the existing asset record
    $asset = MasterRegistrasiModel::findOrFail($id);

    // Update the asset data
    $updateData = $request->all();
    $asset->update($updateData);

    // Tidak perlu generate ulang QR code karena register_code tidak berubah
    return redirect()->to('/registration/assets-registration')->with('success', 'Data Asset berhasil ter-Update!');

    }





    public function ExportToExcel()
    {
        return Excel::download(new AssetExport, 'data_registrasi_asset.xlsx');
    }

    public function import(Request $request)
    {
        ini_set('max_execution_time', 3600);
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            // Import the Excel file and process each row
            Excel::import(new class extends MasterRegistrasiImport {
                public function model(array $row)
                {
                    // Extract data from the row
                    $registerCode = $row['register_code'];
                    $assetName = $row['asset_name'];
                    $serialNumber = $row['serial_number'];
                    $typeAsset = $row['type_asset'];
                    $categoryAsset = $row['category_asset'];
                    $priorityAsset = $row['prioritas'];
                    $merkAsset = $row['merk'];
                    $satuanAsset = $row['satuan'];
                    $layoutAsset = $row['layout'];
                    $supplierAsset = $row['supplier'];
                    $warrantyAsset = $row['warranty'];
                    $periodicMaintenanceAsset = $row['periodic_maintenance'];
                    $quantityAsset = $row['qty'];
                    $registerLocationAsset = $row['register_location'];
                    $registerDateAsset = $row['register_date'];
                    $conditionAsset = $row['condition'];
                    $purchaseNumberAsset = $row['purchase_number'];
                    $purchaseDateAsset = $row['purchase_date'];
                    $widthAsset = $row['width'];
                    $heightAsset = $row['height'];
                    $depthAsset = $row['depth'];

                    // Check for existence and get ID from various master tables
                    $checks = [
                        ['table' => 'm_assets', 'field' => 'asset_model', 'value' => $assetName, 'name' => 'Asset'],
                        ['table' => 'm_type', 'field' => 'type_name', 'value' => $typeAsset, 'name' => 'Type'],
                        ['table' => 'm_category', 'field' => 'cat_name', 'value' => $categoryAsset, 'name' => 'Category'],
                        ['table' => 'm_brand', 'field' => 'brand_name', 'value' => $merkAsset, 'name' => 'Brand'],
                        ['table' => 'm_uom', 'field' => 'uom_name', 'value' => $satuanAsset, 'name' => 'UOM'],
                        ['table' => 'm_layout', 'field' => 'layout_name', 'value' => $layoutAsset, 'name' => 'Layout'],
                        ['table' => 'miegacoa_keluhan.master_resto', 'field' => 'name_store_street', 'value' => $registerLocationAsset, 'name' => 'Register Location'],
                    ];

                    foreach ($checks as $check) {
                        $exists = DB::table($check['table'])
                            ->where($check['field'], $check['value'])
                            ->exists();
                        if (!$exists) {
                            throw new \Exception("Data {$check['name']} '{$check['value']}' Tidak ada pada Master Data '{$check['table']}'. Import Data Excel Dibatalkan.");
                        }
                    }


                    $assetNameData = DB::table('m_assets')
                        ->where('asset_model', $assetName)
                        ->first();

                    if (!$assetNameData) {
                        throw new \Exception("Priority '{$assetNameData}' Tidak ada pada Tabel Master Asset. Import Data Excel Dibatalkan.");
                    }

                    $assetNameDataId = $assetNameData->asset_id;


                    $priorityData = DB::table('m_priority')
                        ->where('priority_name', $priorityAsset)
                        ->first();

                    if (!$priorityData) {
                        throw new \Exception("Priority '{$priorityAsset}' Tidak ada pada Tabel Master Priority. Import Data Excel Dibatalkan.");
                    }

                    $priorityId = $priorityData->priority_code;


                    $typeAssetData = DB::table('m_type')
                        ->where('type_name', $typeAsset)
                        ->first();

                    if (!$typeAssetData) {
                        throw new \Exception("Type data '{$typeAssetData}' Tidak ada pada Tabel Master Type. Import Data Excel Dibatalkan.");
                    }

                    $typeAssetId = $typeAssetData->type_code;


                    $categoryAssetData = DB::table('m_category')
                        ->where('cat_name', $categoryAsset)
                        ->first();

                    if (!$categoryAsset) {
                        throw new \Exception("Type data '{$categoryAsset}' Tidak ada pada Tabel Master Category. Import Data Excel Dibatalkan.");
                    }

                    $categoryAssetId = $categoryAssetData->cat_code;


                    $brandData = DB::table('m_brand')
                        ->where('brand_name', $merkAsset)
                        ->first();

                    if (!$brandData) {
                        throw new \Exception("Type data '{$brandData}' Tidak ada pada Tabel Master Brand. Import Data Excel Dibatalkan.");
                    }

                    $brandDataId = $brandData->brand_id;

                    $uomData = DB::table('m_uom')
                        ->where('uom_name', $satuanAsset)
                        ->first();

                    if (!$uomData) {
                        throw new \Exception("Type data '{$uomData}' Tidak ada pada Tabel Master Uom. Import Data Excel Dibatalkan.");
                    }

                    $uomDataId = $uomData->uom_id;

                    $masterRestoData = DB::table('miegacoa_keluhan.master_resto')
                        ->where('name_store_street', $registerLocationAsset)
                        ->first();

                    if (!$masterRestoData) {
                        throw new \Exception("Type data '{$masterRestoData}' Tidak ada pada Tabel Master Resto. Import Data Excel Dibatalkan.");
                    }

                    $masterRestoDataId = $masterRestoData->id;


                    $masterLayoutData = DB::table('m_layout')
                        ->where('layout_name', $layoutAsset)
                        ->first();

                    if (!$masterLayoutData) {
                        throw new \Exception("Type data '{$masterLayoutData}' Tidak ada pada Tabel Master Layout. Import Data Excel Dibatalkan.");
                    }

                    $masterLayoutDataId = $masterLayoutData->layout_id;


                    $supplierData = DB::table('m_supplier')
                        ->where('supplier_name', $supplierAsset)
                        ->first();


                    if (!$supplierData) {
                        throw new \Exception("Type data '{$supplierData}' Tidak ada pada Tabel Master Supplier. Import Data Excel Dibatalkan.");
                    }

                    $supplierDataId = $supplierData->supplier_code;


                    $conditionData = DB::table('m_condition')
                        ->where('condition_name', $conditionAsset)
                        ->first();

                    if (!$conditionData) {
                        throw new \Exception("Type data '{$conditionData}' Tidak ada pada Tabel Master Condition. Import Data Excel Dibatalkan.");
                    }

                    $conditionDataId = $conditionData->condition_id;


                    $warrantyData = DB::table('m_warranty')
                        ->where('warranty_name', $warrantyAsset)
                        ->first();

                    if (!$warrantyData) {
                        throw new \Exception("Type data '{$warrantyData}' Tidak ada pada Tabel Master Warranty. Import Data Excel Dibatalkan.");
                    }

                    $warrantyDataId = $warrantyData->warranty_id;


                    $periodicMaintenanceData = DB::table('m_periodic_mtc')
                        ->where('periodic_mtc_name', $periodicMaintenanceAsset)
                        ->first();

                    if (!$periodicMaintenanceData) {
                        throw new \Exception("Type data '{$warrantyData}' Tidak ada pada Tabel Master Warranty. Import Data Excel Dibatalkan.");
                    }

                    $periodicMaintenanceDataId = $periodicMaintenanceData->periodic_mtc_id;

                    // Generate URL
                    $url = route('assets.details', ['register_code' => $registerCode]);

                    // Nama file QR
                    $fileName = $registerCode . '.svg';
                    $storagePath = 'qrcodes/' . $fileName;

                    // Jika file belum ada, buat
                    if (!Storage::disk('public')->exists($storagePath)) {
                        $qrCodeImage = QrCode::format('svg')
                            ->size(300)
                            ->margin(2)
                            ->generate($url); // URL ke halaman detail asset

                        // Simpan QR code ke storage/public/qrcodes
                        Storage::disk('public')->put($storagePath, $qrCodeImage);
                    }

                    // Buat URL publik
                    $qrCodeUrlPath = asset('storage/qrcodes/' . $fileName);


                    // ------------------------------------
                    // Check register_location vs dest_loc
                    $existingConflict = DB::table('table_registrasi_asset')
                        ->join('t_out', DB::raw('table_registrasi_asset.register_location COLLATE utf8mb4_unicode_ci'), '=', DB::raw('t_out.dest_loc COLLATE utf8mb4_unicode_ci'))
                        ->where(DB::raw('table_registrasi_asset.register_location COLLATE utf8mb4_unicode_ci'), $registerLocationAsset)
                        ->exists();

                    if ($existingConflict) {
                        throw new \Exception("Data tidak bisa di upload karena barang sudah di movement");
                    }

                    // Insert data
                    DB::table('table_registrasi_asset')->insert([
                        'register_code' => $registerCode,
                        'asset_name' => $assetNameDataId,
                        'serial_number' => $serialNumber,
                        'type_asset' => $typeAssetId,
                        'category_asset' => $categoryAssetId,
                        'prioritas' => $priorityId,
                        'merk' => $brandDataId,
                        'satuan' => $uomDataId,
                        'layout' => $masterLayoutDataId,
                        'supplier' => $supplierDataId,
                        'warranty' => $warrantyDataId,
                        'periodic_maintenance' => $periodicMaintenanceDataId,
                        'qty' => $quantityAsset,
                        'register_location' => $masterRestoDataId,
                        'location_now' => $masterRestoDataId,
                        'register_date' => $registerDateAsset,
                        'condition' => $conditionDataId,
                        'purchase_number' => $purchaseNumberAsset,
                        'purchase_date' => $purchaseDateAsset,
                        'width' => $widthAsset,
                        'height' => $heightAsset,
                        'depth' => $depthAsset,
                        'qr_code_path' => $qrCodeUrlPath,
                        'created_at' => Carbon::now()
                    ]);
                }
            }, $request->file('file'));

            // If import is successful, return a success message
            return redirect()->back()->with('success', 'Data imported successfully.');
        } catch (\Exception $e) {
            // Catch the exception and redirect with an error notification
            return redirect()->back()->with('error', $e->getMessage());
        }
    }





    // try {
    //     // Import the Excel file and process each row
    //     Excel::import(new class extends MasterRegistrasiImport {
    //         public function model(array $row)
    //         {
    //             $assetName = $row['asset_name'];
    //             $registerCode = $row['register_code'];
    //             $serialNumber = $row['serial_number'];
    //             $assetName = $row['asset_name'];
    //             $typeAsset = $row['type_asset'];
    //             $priorityAsset = $row['prioritas'];
    //             $categoryAsset = $row['category_asset'];
    //             $merkAsset = $row['merk'];
    //             $satuanAsset = $row['satuan'];
    //             $layoutAsset = $row['layout'];
    //             $supplierAsset = $row['supplier'];
    //             $warrantyAsset = $row['warranty'];
    //             $periodicMaintenanceAsset = $row['periodic_maintenance'];
    //             $quantityAsset  = $row['qty'];
    //             $registerLocationAsset = $row['register_location'];
    //             $registerDateAsset = $row['register_date'];
    //             $statusAsset = $row['status'];
    //             $purchaseNumberAsset = $row['purchase_number'];
    //             $purchaseDateAsset = $row['purchase_date'];
    //             $widthAsset = $row['width'];
    //             $heightAsset = $row['height'];
    //             $depthAsset = $row['depth'];




    //             // Check if the asset_name and register_code already exist in table_registrasi_asset
    //             $isDuplicate = DB::table('table_registrasi_asset')
    //                              ->where('register_code', $registerCode)
    //                              ->orWhere('asset_name', $assetName)
    //                              ->exists();

    //             if ($isDuplicate) {
    //                 throw new \Exception("Duplicate value found for asset name '{$assetName}' or register code '{$registerCode}'. Import aborted.");
    //             }


    //             $assetExist = DB::table('m_assets')
    //             ->where('asset_model', $assetName)
    //             ->exists();

    //             if (!$assetExist) {

    //             }


    //             $typeExists = DB::table('m_type')
    //                             ->where('type_name', $typeAsset)
    //                             ->exists();

    //             if (!$typeExists) {

    //             }


    //             $categoryExists = DB::table('m_category')
    //             ->where('cat_name', $categoryAsset)
    //             ->exists();

    //             if (!$categoryExists) {

    //             }

    //             $priorityExists = DB::table('m_priority')
    //             ->where('priority_name', $priorityAsset)
    //             ->exists();

    //             if (!$priorityExists) {

    //             }


    //             $merkExists = DB::table('m_brand')
    //             ->where('brand_name', $merkAsset)
    //             ->exists();

    //             if (!$merkExists) {

    //             }

    //             $satuanExists = DB::table('m_uom')
    //             ->where('uom_name', $satuanAsset)
    //             ->exists();

    //             if (!$satuanExists) {

    //             }



    //             $layoutExists = DB::table('m_layout')
    //             ->where('layout_name', $layoutAsset)
    //             ->exists();

    //             if (!$layoutExists) {

    //             }

    //             $supplierExists = DB::table('m_supplier')
    //             ->where('supplier_name', $supplierAsset)
    //             ->exists();

    //             if (!$supplierExists) {

    //             }

    //             $warrantyExists = DB::table('m_warranty')
    //             ->where('warranty_name', $warrantyAsset)
    //             ->exists();

    //             if (!$warrantyExists) {

    //             }

    //             $periodicMtcExists = DB::table('m_periodic_mtc')
    //             ->where('periodic_mtc_name', $periodicMaintenanceAsset)
    //             ->exists();

    //             if (!$periodicMtcExists) {

    //             }

    //               // Define the path where QR codes will be saved
    //             $qrCodeDir = storage_path('app/public/qrcodes');

    //         // Ensure the directory exists
    //         if (!file_exists($qrCodeDir)) {
    //             mkdir($qrCodeDir, 0777, true);
    //         }

    //         // Generate the QR code based on the asset's register code
    //         $qrCodeFileName = 'QR-' . $registerCode . '.png';
    //         $qrCodePath = $qrCodeDir . '/' . $qrCodeFileName;

    //         // Generate and save the QR code
    //         QrCode::format('png')->size(200)->generate($registerCode, $qrCodePath);

    //             // Insert data into table_registrasi_asset
    //             DB::table('table_registrasi_asset')->insert([
    //                 'register_code' => $registerCode,
    //                 'asset_name' => $assetName,
    //                 'serial_number' => $serialNumber,
    //                 'type_asset' => $typeAsset,
    //                 'category_asset' => $categoryAsset,
    //                 'prioritas' => $priorityAsset, 
    //                     'merk' => $merkAsset,
    //                     'satuan' => $satuanAsset,
    //                     'layout' => $layoutAsset,
    //                     'supplier' => $supplierAsset,
    //                     'warranty' => $warrantyAsset,
    //                     'periodic_maintenance' => $periodicMaintenanceAsset,
    //                 'qty' => $quantityAsset,
    //                 'register_location' => $registerLocationAsset,
    //                 'register_date' => $registerDateAsset,
    //                 'status' => $statusAsset,
    //                 'purchase_number' => $purchaseNumberAsset,
    //                 'purchase_date' => $purchaseDateAsset,
    //                 'width' => $widthAsset,
    //                 'height' => $heightAsset,
    //                 'depth' => $depthAsset,
    //                 'qr_code_path' => $qrCodePath,
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ]);  
    //         }
    //     }, $request->file('file'));
    // } catch (\Exception $e) {
    //     return back()->withErrors(['error' => $e->getMessage()]);
    // }

    // return back()->withSuccess('File imported successfully.');






    public function Trash()
    {
        $dataRegistrasiAsset = MasterRegistrasiModel::onlyTrashed()->get();
        return response()->json($dataRegistrasiAsset);
    }



    public function TampilDataQR($register_code)
    {
        $asset = DB::table('table_registrasi_asset')
            ->select(
                'table_registrasi_asset.*',
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
                'm_periodic_mtc.periodic_mtc_name'
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
            ->where('table_registrasi_asset.register_code', $register_code)
            ->first();

            if (!$asset) {
                return redirect()->route('assets.details')->with('error', 'Asset not found.');
            }

            $qrCodeUrl = $asset->qr_code_path;

        // Pass both asset data and QR code URL to the view
        return view('registration.assets_regist.qr_scan_registrasi_asset', compact('asset', 'qrCodeUrl'));
    }

    public function approve(Request $request)
    {
        $assetId = $request->input('id');

        $asset = MasterRegistrasiModel::find($assetId);

        if ($asset) {
            // Update the approve_status to 'sudah approve'
            $asset->approve_status = 'sudah approve';
            if ($asset->save()) {
                return response()->json(['status' => 'success', 'message' => 'Asset approved successfully']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Failed to approve asset']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Asset not found']);
        }
    }

    public function generatePdf($registerCode)
    {
        // Fetch specific data based on register_code
        $data = DB::table('table_registrasi_asset')
            ->select(
                'table_registrasi_asset.*',
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
                'm_periodic_mtc.periodic_mtc_name'
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
            ->where('table_registrasi_asset.register_code', $registerCode)
            ->get();




        // Load a view and pass the data to it, with landscape orientation
        $pdf = Pdf::loadView('registration.assets_regist.cetak_pdf', compact('data'))
            ->setPaper('a7', 'landscape');

        // Return the generated PDF for download
        return $pdf->download('registrasi_' . $registerCode . '.pdf');
    }


    public function DetailDataRegistrasiAsset($id)
    {
        try {

            $asset = DB::table('table_registrasi_asset')
                ->select(
                    'table_registrasi_asset.*',
                    'm_assets.asset_model',
                    'm_type.type_name',
                    'm_category.cat_name',
                    'm_priority.priority_name',
                    'm_brand.brand_name',
                    'm_uom.uom_name',
                    'q.name AS status_asset_name',
                    'miegacoa_keluhan.master_resto.name_store_street',
                    'loc_now.name_store_street AS lokasi_sekarang',
                    'm_layout.layout_name',
                    'm_supplier.supplier_name',
                    'm_condition.condition_name',
                    'm_warranty.warranty_name',
                    'm_periodic_mtc.periodic_mtc_name'
                )
                ->leftJoin('m_status_asset AS q', 'table_registrasi_asset.status_asset', '=', 'q.id')
                ->leftJoin('m_assets', 'table_registrasi_asset.asset_name', '=', 'm_assets.asset_id')
                ->leftJoin('m_type', 'table_registrasi_asset.type_asset', '=', 'm_type.type_code')
                ->leftJoin('m_category', 'table_registrasi_asset.category_asset', '=', 'm_category.cat_code')
                ->leftJoin('m_priority', 'table_registrasi_asset.prioritas', '=', 'm_priority.priority_code')
                ->leftJoin('m_brand', 'table_registrasi_asset.merk', '=', 'm_brand.brand_id')
                ->leftJoin('m_uom', 'table_registrasi_asset.satuan', '=', 'm_uom.uom_id')
                ->leftJoin('miegacoa_keluhan.master_resto', 'table_registrasi_asset.register_location', '=', 'miegacoa_keluhan.master_resto.id')
                ->leftJoin('miegacoa_keluhan.master_resto AS loc_now', 'table_registrasi_asset.location_now', '=', 'loc_now.id')
                ->leftJoin('m_layout', 'table_registrasi_asset.layout', '=', 'm_layout.layout_id')
                ->leftJoin('m_supplier', 'table_registrasi_asset.supplier', '=', 'm_supplier.supplier_code')
                ->leftJoin('m_condition', 'table_registrasi_asset.condition', '=', 'm_condition.condition_id')
                ->leftJoin('m_warranty', 'table_registrasi_asset.warranty', '=', 'm_warranty.warranty_id')
                ->leftJoin('m_periodic_mtc', 'table_registrasi_asset.periodic_maintenance', '=', 'm_periodic_mtc.periodic_mtc_id')
                ->where('table_registrasi_asset.register_code', $id)
                ->first();
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Data Registrasi Asset not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return view('registration.assets_regist.detail_data_asset', compact('asset'));
    }

    public function HalamanApproval()

    {

        $priorities = DB::table('m_priority')->select('priority_id', 'priority_name')->get();

        $categories = DB::table('m_category')->select('cat_id', 'cat_name')->get();

        $tipies = DB::table('m_type')->select('type_id', 'type_name')->get();

        $uomies = DB::table('m_uom')->select('uom_id', 'uom_name')->get();



        return view("registration.approval_ops_sm.index", [

            'priorities' => $priorities,

            'categories' => $categories,

            'tipies' => $tipies,

            'uomies' => $uomies,

        ]);
    }

    public function AddDataAssets(Request $request)

    {

        // Validate the incoming request data

        $request->validate([

            'asset_code' => 'required|string|max:255',

            'asset_model' => 'required|string|max:255',

            // 'asset_status' => 'required|string|max:100',

            // 'asset_quantity' => 'required|int|max:1000',

            'asset_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',

            'priority_id' => 'required|exists:m_priority,priority_id',

            'cat_id' => 'required|exists:m_category,cat_id',

            'type_id' => 'required|exists:m_type,type_id',

            'uom_id' => 'required|exists:m_uom,uom_id',

        ]);



        try {

            // Create an instance of the MasterAsset model

            $asset = new MasterAsset();

            $asset->asset_code = $request->input('asset_code');

            $asset->asset_model = $request->input('asset_model');

            $asset->asset_status = $request->input('asset_status');

            $asset->asset_quantity = $request->input('asset_quantity');

            // Handle the image upload

            if ($request->hasFile('asset_image')) {

                $image = $request->file('asset_image');

                $imageName = time() . '.' . $image->getClientOriginalExtension();

                $image->move(public_path('assets/images'), $imageName); // Move the file to public/assets/images

                $asset->asset_image = 'assets/images/' . $imageName; // Store relative path

            }

            $asset->priority_id = $request->input('priority_id');

            $asset->cat_id = $request->input('cat_id');

            $asset->type_id = $request->input('type_id');

            $asset->uom_id = $request->input('uom_id');

            $asset->create_by = Auth::user()->username; // Get the logged-in user's username



            // Generate asset_id automatically

            $maxAssetId = MasterAsset::max('asset_id'); // Get the maximum asset_id

            $asset->asset_id = $maxAssetId ? $maxAssetId + 1 : 1; // Set asset_id, starting from 1 if none



            $asset->create_date = Carbon::now(); // Set the current date

            $asset->save(); // Save the asset data



            return response()->json([

                'status' => 'success',

                'message' => 'Asset berhasil ditambahkan',

                'redirect_url' => url('/admin/regist')

            ]);
        } catch (\Exception $e) {

            return response()->json([

                'status' => 'error',

                'message' => 'Terjadi kesalahan: ' . $e->getMessage()

            ]);
        }
    }
}
