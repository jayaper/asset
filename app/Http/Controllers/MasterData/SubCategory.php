<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SubCategoryController;
use App\Models\Master\MasterSubCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubCategory extends Controller
{
    public function index()
    {
        $subcategorys = DB::table('m_subcategory')->select('m_subcategory.*')->paginate(10);
        return view("master_data.subcategory", ['subcategorys' => $subcategorys]);
    }

    public function NewAddDataSubCategory(Request $request) {
        $request->validate([
            'subcat_code' => 'required|string|max:255',
            'subcat_name' => 'required|string|max:255',
        ]);

        try {
            $subcategory = new MasterSubCategory();

            $subcategory->subcat_name = $request->input('subcat_name');
            $subcategory->subcat_code = $request->input('subcat_code');
            $subcategory->create_by = Auth::user()->username;

            $maxSubCategoryId = MasterSubCategory::max('subcat_id');
            $subcategory->subcat_id = $maxSubCategoryId ? $maxSubCategoryId + 1 : 1;

            $subcategory->create_date = Carbon::now();
            $subcategory->save();

            return response()->json([
               'status' => 'success',
               'message' => 'Berhasil menambahkan subcategory baru',
               'redirect_url' => '/master-data/sub-category'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function NewUpdateDataSubCategory(Request $request, $id) {
        $request->validate([
            'subcat_code' => 'required|string|max:255',
            'subcat_name' => 'required|string|max:255',
        ]);

        $subcategory = MasterSubCategory::find($id);

        if (!$subcategory) {
            return response()->json(['status' => 'error', 'message' => 'Subcategory not found.'], 404);
        }

        $subcategory->subcat_code = $request->subcat_code;
        $subcategory->subcat_name = $request->subcat_name;

        if ($subcategory->save()) {
            return response()->json([
               'status' => 'success',
               'message' => 'Berhasil mengubah data subcategory',
               'redirect_url' => '/master-data/sub-category'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Subcategory.'], 500);
        }
    }

    public function NewDeleteDataSubCategory($id)
    {
        $subcategory = MasterSubCategory::find($id);

        if ($subcategory) {
            $subcategory->delete();
            return response()->json([
               'status' => 'success',
               'message' => 'Berhasil menghapus subcategory',
               'redirect_url' => '/master-data/sub-category'
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Subcategory Gagal Terhapus'], 404);
        }
    }
}
