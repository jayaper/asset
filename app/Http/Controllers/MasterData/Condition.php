<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterCondition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Condition extends Controller
{
    public function index()
    {
        $conditions = DB::table('m_condition')->select('m_condition.*')->paginate(10);

        return view("master_data.condition", ['conditions' => $conditions]);
    }

    public function NewAddDataCondition(Request $request) {
        $request->validate([
            'condition_name' => 'required|string|max:255',
        ]);

        try {
            $condition = new MasterCondition();
            $condition->condition_name = $request->input('condition_name');
            $condition->create_by = Auth::user()->username;

            $maxConditionId = MasterCondition::max('condition_id');
            $condition->condition_id = $maxConditionId ? $maxConditionId + 1 : 1;

            $condition->create_date = Carbon::now();
            $condition->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menambahkan data baru',
                'redirect_url' => '/master-data/condition'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function NewUpdateDataCondition(Request $request, $id) {
        $request->validate([
            'condition_name' => 'required|string|max:255',
        ]);

        $condition = MasterCondition::find($id);

        if (!$condition) {
            return response()->json(['status' => 'error', 'message' => 'Condition not found.'], 404);
        }

        $condition->condition_name = $request->condition_name;

        if ($condition->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah data condition',
                'redirect_url' => '/master-data/condition'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Condition.'], 500);
        }
    }

    public function NewDeleteDataCondition($id) {
        $condition = MasterCondition::find($id);

        if ($condition) {
            $condition->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menghapus data condition',
                'redirect_url' => '/master-data/condition'
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Condition Gagal Terhapus'], 404);
        }
    }
}
