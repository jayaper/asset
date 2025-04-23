<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterJobLevel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobLevel extends Controller
{
    public function index()
    {

        $filterJobLevels = ['Senior Manager', 'Staff', 'Manager', 'Supervisor'];

        // Query the database with the filter applied
        $joblevels = DB::table('emp_employee')
            ->select('emp_employee.Job_Level')
            ->whereIn('emp_employee.Job_Level', $filterJobLevels) // Apply the filter
            ->distinct()
            ->paginate(10);

        // Pass the filtered results to the view
        return view("master_data.joblevel", ['joblevels' => $joblevels]);

    }

    public function NewAddDataJobLevel(Request $request) {
        $request->validate([
           'joblevel_name' => 'required|string|max:255',
        ]);

        try {
            $jobLevel = new MasterJobLevel();
            $jobLevel->joblevel_name = $request->input('joblevel_name');
            $jobLevel->create_by = Auth::user()->username;

            $maxJobLevelId = MasterJobLevel::max('joblevel_id');
            $jobLevel->joblevel_id = $maxJobLevelId ? $maxJobLevelId + 1 : 1;

            $jobLevel->create_date = Carbon::now();
            $jobLevel->save();

            return response()->json([
               'status' => 'success',
               'message' => 'Data Berhasil Ditambahkan',
                'redirect_url' => '/master-data/job-level'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function NewUpdateDataJobLevel(Request $request, $id) {
        $request->validate([
            'joblevel_name' => 'required|string|max:255',
        ]);

        $jobLevel = MasterJobLevel::find($id);

        if (!$jobLevel) {
            return response()->json(['status' => 'error', 'message' => 'Job Level not found.'], 404);
        }

        $jobLevel->joblevel_name = $request->joblevel_name;

        if ($jobLevel->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Update data katergori berhasil',
                'redirect_url' => '/master-data/job-level'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Job Level.'], 500);
        }
    }

    public function NewDeleteDataJobLevel($id) {
        $jobLevel = MasterJobLevel::find($id);

        if ($jobLevel) {
            $jobLevel->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Job Level Berhasil Dihapus',
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Job Level Gagal Terhapus'], 404);
        }
    }
}
