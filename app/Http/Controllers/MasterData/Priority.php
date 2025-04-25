<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterPriority;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Priority extends Controller
{
    public function index()
    {
        $prioritys = DB::table('m_priority')->select('m_priority.*')->paginate(10);

        return view("master_data.priority", ['prioritys' => $prioritys]);
    }

    public function NewAddDataPriority(Request $request) {
        $request->validate([
            'priority_name' => 'required|string|max:255',
            'priority_code' => 'required|string|max:255',
        ]);

        try {
            $priority = new MasterPriority();
            $priority->priority_code = $request->input('priority_code');
            $priority->priority_name = $request->input('priority_name');
            $priority->create_by = Auth::user()->username;

            $maxPriorityId = MasterPriority::max('priority_id');
            $priority->priority_id = $maxPriorityId ? $maxPriorityId + 1 : 1;

            $priority->create_date = Carbon::now();
            $priority->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Data Priority berhasil ditambahkan',
                'redirect_url' => '/master-data/priority'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function NewUpdateDataPriority(Request $request, $id) {
        $request->validate([
            'priority_name' => 'required|string|max:255',
            'priority_code' => 'required|string|max:255',
        ]);

        $priority = MasterPriority::find($id);

        if (!$priority) {
            return response()->json(['status' => 'error', 'message' => 'Priority not found.'], 404);
        }

        $priority->priority_code = $request->priority_code;
        $priority->priority_name = $request->priority_name;

        if ($priority->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Update data priority berhasil',
                'redirect_url' => '/master-data/priority'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Priority.'], 500);
        }
    }

    public function NewDeleteDataPriority($id) {
        $priority = MasterPriority::find($id);

        if ($priority) {
            $priority->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Priority berhasil dihapus',
                'redirect_url' => '/master-data/priority'
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Priority Gagal Terhapus'], 404);
        }
    }
}
