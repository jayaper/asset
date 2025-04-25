<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterDivision;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Division extends Controller
{
    public function index()
    {
        $divisions = DB::table('m_division')->select('m_division.*')->paginate(10);

        return view("master_data.division", ['divisions' => $divisions]);
    }

    public function NewAddDataDivision(Request $request)
    {
        $request->validate([
           'division_name' => 'required|string|max:255'
        ]);

        try {
            $division = new MasterDivision();
            $division->division_name = $request->input('division_name');
            $division->create_by = Auth::user()->username;

            $maxDivisionId = MasterDivision::max('division_id');
            $division->division_id = $maxDivisionId ? $maxDivisionId + 1 : 1;

            $division->create_date = Carbon::now();
            $division->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Ditambahkan',
                'redirect_url' => '/master-data/division'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function NewUpdateDataDivision(Request $request, $id) {
        $request->validate([
            'division_name' => 'required|string|max:255'
        ]);

        $division = MasterDivision::find($id);

        if (!$division) {
            return response()->json(['status' => 'error', 'message' => 'Division not found.'], 404);
        }

        $division->division_name = $request->division_name;

        if ($division->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Update data katergori berhasil',
                'redirect_url' => '/master-data/division'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Division.'], 500);
        }
    }

    public function NewDeleteDataDivision($id) {
        $division = MasterDivision::find($id);

        if ($division) {
            $division->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Division Berhasil Dihapus',
                'redirect_url' => '/master-data/division'
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Division Gagal Terhapus'], 404);
        }
    }
}
