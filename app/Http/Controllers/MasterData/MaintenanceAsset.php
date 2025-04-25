<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterTipeMaintenance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaintenanceAsset extends Controller
{
    public function index()
    {
        $typesMaintenance = DB::table('m_mtc_type')->select('m_mtc_type.*')->paginate(10);

        return view("master_data.tipe_maintenance", ['typesMaintenance' => $typesMaintenance]);
    }

    public function NewAddDataTypeMaintenance(Request $request) {
        $request->validate([
            'mtc_type_name' => 'required|string|max:255'
        ]);

        try {
            $typeMaintenance = new MasterTipeMaintenance();
            $typeMaintenance->mtc_type_name = $request->input('mtc_type_name');
            $typeMaintenance->create_by = Auth::user()->username;

            $maxMtcTypeId = MasterTipeMaintenance::max('mtc_type_id');
            $typeMaintenance->mtc_type_id = $maxMtcTypeId ? $maxMtcTypeId + 1 : 1;

            $typeMaintenance->create_date = Carbon::now();
            $typeMaintenance->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menambahkan data tipe maintenance',
                'redirect_url' => '/master-data/type-maintenance-asset'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function NewUpdateDataTypeMaintenance(Request $request, $id) {
        $request->validate([
            'mtc_type_name' => 'required|string|max:255'
        ]);

        $typeMaintenance = MasterTipeMaintenance::find($id);

        if (!$typeMaintenance) {
            return response()->json(['status' => 'error', 'message' => 'Tipe Maintenance not found.'], 404);
        }

        $typeMaintenance->mtc_type_name = $request->mtc_type_name;

        if ($typeMaintenance->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah data tipe maintenance',
                'redirect_url' => '/master-data/type-maintenance-asset'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Tipe Maintenance.'], 500);
        }
    }

    public function NewDeleteDataTypeMaintenance($id) {

        $typeMaintenance = MasterTipeMaintenance::find($id);

        if ($typeMaintenance) {
            $typeMaintenance->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menghapus tipe maintenance',
                'redirect_url' => '/master-data/type-maintenance-asset'
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Tipe Maintenance Gagal Terhapus'], 404);
        }
    }
}
