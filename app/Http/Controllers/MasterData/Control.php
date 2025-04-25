<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterControl;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Control extends Controller
{
    public function index()
    {
        $controls = DB::table('m_control')->select('m_control.*')->paginate(10);

        return view("master_data.control", ['controls' => $controls]);
    }

    public function NewAddDataControlNameList(Request $request) {
        $request->validate([
            'control_name' => 'required|string|max:255',
        ]);

        try {
            $control = new MasterControl();
            $control->control_name = $request->input('control_name');
            $control->create_by = Auth::user()->username;

            $maxControlId = MasterControl::max('control_id');
            $control->control_id = $maxControlId ? $maxControlId + 1 : 1;

            $control->create_date = Carbon::now();
            $control->save();

            return response()->json([
               'status' => 'success',
                'message' => 'Berhasil menambahkan data control',
                'redirect_url' => '/master-data/control-checklist'
            ]);
        } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
        }
    }

    public function NewUpdateDataControlNameList(Request $request, $id) {
        $request->validate([
            'control_name' => 'required|string|max:255',
        ]);

        $control = MasterControl::find($id);

        if (!$control) {
            return response()->json(['status' => 'error', 'message' => 'Control not found.'], 404);
        }

        $control->control_name = $request->control_name;

        if ($control->save()) {
            return response()->json([
               'status' => 'success',
               'message' => 'Berhasil mengubah data control',
               'redirect_url' => '/master-data/control-checklist'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Control.'], 500);
        }
    }

    public function NewDeleteDataControlNameList($id) {

        $control = MasterControl::find($id);

        if ($control) {
            $control->delete();
            return response()->json([
               'status' => 'success',
               'message' => 'Berhasil menghapus control',
               'redirect_url' => '/master-data/control-checklist'
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Control Gagal Terhapus'], 404);
        }
    }
}
