<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Category extends Controller
{
    public function index()
    {
        $categorys = DB::table('m_category')->select('m_category.*')->paginate(10);

        return view("master_data.category", ['categorys' => $categorys]);
    }

    public function NewAddDataCategory(Request $request) {
        $request->validate([
            'cat_name' => 'required|string|max:255',
            'cat_code' => 'required|string|max:255'
        ]);

        try {
            $category = new MasterCategory();
            $category->cat_name = $request->input('cat_name');
            $category->cat_code = $request->input('cat_code');
            $category->create_by = Auth::user()->username;

            $maxCategoryId = MasterCategory::max('cat_id');
            $category->cat_id = $maxCategoryId ? $maxCategoryId + 1 : 1;

            $category->create_date = Carbon::now();
            $category->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Data Category Berhasil Ditambahkan',
                'redirect_url' => '/master-data/category'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function NewUpdateDataCategory(Request $request, $id) {
        $request->validate([
            'cat_name' => 'required|string|max:255',
            'cat_code' => 'required|string|max:255'
        ]);

        $category = MasterCategory::find($id);

        if (!$category) {
            return response()->json(['status' => 'error', 'message' => 'Category not found.'], 404);
        }

        $category->cat_name = $request->cat_name;
        $category->cat_code = $request->cat_code;

        if ($category->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Update data katergori berhasil',
                'redirect_url' => '/master-data/category'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Category.'], 500);
        }
    }

    public function NewDeleteDataCategory($id)
    {
        $category = MasterCategory::find($id);

        if ($category) {
            $category->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Category Berhasil Dihapus',
                'redirect_url' => '/master-data/category'
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Category Gagal Terhapus'], 404);
        }
    }
}
