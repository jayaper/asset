<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterRepair;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Repair extends Controller
{
    public function index()
    {
        $repairs = DB::table('m_repair')->select('m_repair.*')->paginate(10);

        return view("master_data.repair", ['repairs' => $repairs]);
    }

    public function NewAddDataRepair(Request $request) {
        $request->validate([
            'repair_name' => 'required|string|max:255',
        ]);

        try {
            $repair = new MasterRepair();
            $repair->repair_name = $request->input('repair_name');
            $repair->create_by = Auth::user()->username;

            $maxRepairId = MasterRepair::max('repair_id');
            $repair->repair_id = $maxRepairId ? $maxRepairId + 1 : 1;

            $repair->create_date = Carbon::now();
            $repair->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menambahkan data baru',
                'redirect_url' => '/master-data/repair'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function NewUpdateDataRepair(Request $request, $id)
    {
        $request->validate([
           'repair_name' => 'required|string|max:255',
        ]);

        $repair = MasterRepair::find($id);

        if (!$repair) {
            return response()->json(['status' => 'error', 'message' => 'Repair not found.'], 404);
        }

        $repair->repair_name = $request->repair_name;

        if ($repair->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah data repair',
                'redirect_url' => '/master-data/repair'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Repair.'], 500);
        }
    }

    public function NewDeleteDataRepair($id) {
        $repair = MasterRepair::find($id);

        if ($repair) {
            $repair->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menghapus data repair',
                'redirect_url' => '/master-data/repair'
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Repair Gagal Terhapus'], 404);
        }
    }
}
