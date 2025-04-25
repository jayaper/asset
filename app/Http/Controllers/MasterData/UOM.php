<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterUom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UOM extends Controller
{
    public function index()

    {

        $uoms = DB::table('m_uom')->select('m_uom.*')->paginate(10);
        return view("master_data.uom", ['uoms' => $uoms]);

    }

    public function NewAddDataUOM(Request $request) {
        $request->validate([
           'uom_name' => 'required|string|max:255',
        ]);

        try {
            $uom = new MasterUom();
            $uom->uom_name = $request->input('uom_name');
            $uom->create_by = Auth::user()->username;

            $maxUomId = MasterUom::max('uom_id');
            $uom->uom_id = $maxUomId ? $maxUomId + 1 : 1;
            $uom->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menambahkan data baru',
                'redirect_url' => '/master-data/uom'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function NewUpdateDataUOM(Request $request, $id) {
        $request->validate([
            'uom_name' => 'required|string|max:255',
        ]);

        $uom = MasterUom::find($id);

        if (!$uom) {
            return response()->json(['status' => 'error', 'message' => 'UOM not found.'], 404);
        }

        $uom->uom_name = $request->uom_name;

        if ($uom->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah data uom',
                'redirect_url' => '/master-data/uom'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update UOM.'], 500);
        }
    }

    public function NewDeleteDataUom($id) {
        $uom = MasterUom::find($id);

        if ($uom) {
            $uom->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menghapus data uom',
                'redirect_url' => '/master-data/uom'
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data UOM Gagal Terhapus'], 404);
        }
    }
}
