<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterLayout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Layout extends Controller
{
    public function index()
    {
        $layouts = DB::table('m_layout')->select('m_layout.*')->paginate(10);

        return view("master_data.layout", ['layouts' => $layouts]);
    }

    public function NewAddDataLayout(Request $request)
    {
       $request->validate([
          'layout_code' => 'required|string|max:255',
          'layout_name' => 'required|string|max:255',
       ]);

       try {
           $Layout = new MasterLayout();
           $Layout->layout_code = $request->input('layout_code');
           $Layout->layout_name = $request->input('layout_name');
           $Layout->create_by = Auth::user()->username;

           $maxLayoutId = MasterLayout::max('layout_id');
           $Layout->layout_id = $maxLayoutId ? $maxLayoutId + 1 : 1;

           $Layout->create_date = Carbon::now();
           $Layout = $Layout->save();

           return response()->json([
                'status' => 'success',
               'message' => 'Data Berhasil Ditambahkan',
               'redirect_url' => '/master-data/layout'
           ]);
       }  catch (\Exception $e) {
           return response()->json([
               'status' => 'error',
               'message' => $e->getMessage()
           ]);
       }
    }

    public function NewUpdateDataLayout(Request $request, $id) {
        $request->validate([
           'layout_name' => 'required|string|max:255',
        ]);

        $Layout = MasterLayout::find($id);

        if (!$Layout) {
            return response()->json(['status' => 'error', 'message' => 'Layout not found.'], 404);
        }

        $Layout->layout_name = $request->layout_name;

        if ($Layout->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Update data katergori berhasil',
                'redirect_url' => '/master-data/layout'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Layout.'], 500);
        }
    }

    public function NewDeleteDataLayout($id) {
        $Layout = MasterLayout::find($id);

        if ($Layout) {
            $Layout->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Layout Berhasil Dihapus',
                'redirect_url' => '/master-data/layout'
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Layout Gagal Terhapus'], 404);
        }
    }
}
