<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterWarranty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Waranty extends Controller
{
    public function index()

    {
        $warrantys = DB::table('m_warranty')->select('m_warranty.*')->paginate(10);
        return view("master_data.warranty", ['warrantys' => $warrantys]);
    }

    public function NewAddDataWarranty(Request $request) {
        $request->validate([
            'warranty_name' => 'required|string|max:255',
            'warranty_day' => 'required|string|max:255',
        ]);

        try {
            $warranty = new MasterWarranty();

            $warranty->warranty_name = $request->input('warranty_name');
            $warranty->warranty_day = $request->input('warranty_day');
            $warranty->create_by = Auth::user()->username;

            $maxWarrantyId = MasterWarranty::max('warranty_id');
            $warranty->warranty_id = $maxWarrantyId ? $maxWarrantyId + 1 : 1;

            $warranty->create_date = Carbon::now();
            $warranty->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menambahkan data baru',
                'redirect_url' => '/master-data/warranty'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function NewUpdateDataWarranty(Request $request, $id) {
        $request->validate([
            'warranty_name' => 'required|string|max:255',
            'warranty_day' => 'required|string|max:255',
        ]);

        $warranty = MasterWarranty::find($id);

        if (!$warranty) {
            return response()->json(['status' => 'error', 'message' => 'Warranty not found.'], 404);
        }

        $warranty->warranty_name = $request->warranty_name;
        $warranty->warranty_day = $request->warranty_day;

        if ($warranty->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah data warranty',
                'redirect_url' => '/master-data/warranty'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Warranty.'], 500);
        }
    }

    public function NewDeleteDataWarranty($id) {
        $warranty = MasterWarranty::find($id);

        if ($warranty) {
            $warranty->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menghapus data warranty',
                'redirect_url' => '/master-data/warranty'
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Warranty Gagal Terhapus'], 404);
        }
    }
}
