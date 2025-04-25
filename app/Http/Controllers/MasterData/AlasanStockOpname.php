<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterReasonSo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlasanStockOpname extends Controller
{
    public function index()
    {
        $reasonso = DB::table('m_reason_so')->select('m_reason_so.*')->paginate(10);

        return view("master_data.reasonso", ['reasonso' => $reasonso]);
    }

    public function NewAddDataAlasanStockopname(Request $request) {
        $request->validate([
            'reason_so_name' => 'required|string|max:255',
        ]);

        try {
            $reasonso = new MasterReasonSo();
            $reasonso->reason_so_name = $request->input('reason_so_name');
            $reasonso->create_by =  Auth::user()->username;

            $maxReasonSoId = MasterReasonSo::max('reason_so_id');
            $reasonso->reason_so_id = $maxReasonSoId ? $maxReasonSoId + 1 : 1;

            $reasonso->create_date = Carbon::now();
            $reasonso->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menambahkan data alasan stock opname',
                'redirect_url' => '/master-data/alasan-stock-opname'
            ]);
        } catch (\Exception $e) {
            return response()->json([
               'status' => 'error',
               'message' => $e->getMessage()
            ]);
        }
    }

    public function NewUpdateDataAlasanStockopname(Request $request, $id) {
        $request->validate([
            'reason_so_name' => 'required|string|max:255',
        ]);

        $reasonso = MasterReasonSo::find($id);

        if (!$reasonso) {
            return response()->json(['status' => 'error', 'message' => 'Alasan Stock Opname not found.'], 404);
        }

        $reasonso->reason_so_name = $request->reason_so_name;

        if ($reasonso->save()) {
            return response()->json([
               'status' => 'success',
               'message' => 'Berhasil mengubah data alasan stock opname',
               'redirect_url' => '/master-data/alasan-stock-opname'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Alasan Stock Opname.'], 500);
        }
    }

    public function NewDeleteDataAlasanStockOpname($id) {
        $reasonso = MasterReasonSo::find($id);

        if ($reasonso) {
            $reasonso->delete();
            return response()->json([
               'status' => 'success',
               'message' => 'Berhasil menghapus alasan stock opname',
               'redirect_url' => '/master-data/alasan-stock-opname'
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Alasan Stock Opname Gagal Terhapus'], 404);
        }
    }
}
