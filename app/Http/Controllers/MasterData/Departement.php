<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterDept;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Departement extends Controller
{
    public function index()
    {
        $depts = DB::table('m_dept')->select('m_dept.*')->paginate(10);

        return view("master_data.dept", ['depts' => $depts]);
    }

    public function NewAddDataDepartement(Request $request) {
        $request->validate([
            'dept_name' => 'required|string|max:255'
        ]);

        try {
            $department = new MasterDept();
            $department->dept_name = $request->input('dept_name');
            $department->create_by = Auth::user()->username;

            $maxDepartmentId = MasterDept::max('dept_id');
            $department->dept_id = $maxDepartmentId ? $maxDepartmentId + 1 : 1;

            $department->create_date = Carbon::now();
            $department->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menambahkan data departement',
                'redirect_url' => '/master-data/departement'
            ]);
        } catch (\Exception $e) {
            return response()->json([
               'status' => 'error',
               'message' => $e->getMessage()
            ]);
        }
    }


    public function NewUpdateDataDepartement(Request $request, $id) {
        $request->validate([
            'dept_name' => 'required|string|max:255'
        ]);

        $department = MasterDept::find($id);

        if (!$department) {
            return response()->json(['status' => 'error', 'message' => 'Departement not found.'], 404);
        }

        $department->dept_name = $request->dept_name;

        if ($department->save()) {
            return response()->json([
               'status' => 'success',
               'message' => 'Berhasil mengubah data departement',
               'redirect_url' => '/master-data/departement'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Departement.'], 500);
        }
    }

    public function NewDeleteDataDepartement($id) {
        $department = MasterDept::find($id);

        if ($department) {
            $department->delete();
            return response()->json([
               'status' => 'success',
               'message' => 'Berhasil menghapus departement',
               'redirect_url' => '/master-data/departement'
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Departement Gagal Terhapus'], 404);
        }
    }
}
