<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterBrand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Brand extends Controller
{
    public function index()
    {
        $brands = DB::table('m_brand')->select('m_brand.*')->paginate(10);

        return view("master_data.brand", ['brands' => $brands]);
    }

    public function NewAddBrand(Request $request) {
        $request->validate([
            'brand_name' => 'required|string|max:255',
        ]);

        try {
            $brand = new MasterBrand();
            $brand->brand_name = $request->input('brand_name');
            $brand->create_by = Auth::user()->username;

            $MaxBrandId = MasterBrand::max('brand_id');
            $brand->brand_id = $MaxBrandId ? $MaxBrandId + 1 : 1;

            $brand->create_date = Carbon::now();
            $brand->save();

            return response()->json([
               'status' => 'success',
               'message' => 'Berhasil menambahkan brand baru',
                'redirect_url' => '/master-data/brand'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }


    public function NewUpdateDataBrand(Request $request, $id) {
        $request->validate([
            'brand_name' => 'required|string|max:255',
        ]);

        $brand = MasterBrand::find($id);

        if (!$brand) {
            return response()->json(['status' => 'error', 'message' => 'Brand not found.'], 404);
        }

        $brand->brand_name = $request->brand_name;

        if ($brand->save()) {
            return response()->json([
               'status' => 'success',
               'message' => 'Berhasil mengubah data brand',
               'redirect_url' => '/master-data/brand'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Brand.'], 500);
        }
    }

    public function NewDeleteDataBrand($id) {
        $brand = MasterBrand::find($id);

        if ($brand) {
            $brand->delete();
            return response()->json([
               'status' => 'success',
               'message' => 'Berhasil menghapus brand',
               'redirect_url' => '/master-data/brand'
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Brand Gagal Terhapus'], 404);
        }
    }
}
