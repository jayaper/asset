<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterReason;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlasanMutasi extends Controller
{
    public function index()

    {
        $reasons = DB::table('m_reason')->select('m_reason.*')->paginate(10);
        return view("master_data.reason", ['reasons' => $reasons]);
    }

    public function NewAddDataAlasanMutasi(Request $request) {
        $request->validate([
            'reason_name' => 'required|string|max:255',
        ]);

        try {
            $reason = new MasterReason();
            $reason->reason_name = $request->input('reason_name');
            $reason->create_by = Auth::user()->username;

            $maxReasonId = MasterReason::max('reason_id');
            $reason->reason_id = $maxReasonId ? $maxReasonId + 1 : 1;

            $reason->create_date = Carbon::now();
            $reason->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menambahkan data alasan mutasi',
                'redirect_url' => '/master-data/alasan-mutasi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
               'status' => 'error',
               'message' => 'Terjadi kesalahan : ', $e->getMessage()
            ]);
        }
    }

    public function NewUpdateDataAlasanMutasi(Request $request, $id) {
        $request->validate([
            'reason_name' => 'required|string|max:255',
        ]);

        $reason = MasterReason::find($id);

        if (!$reason) {
            return response()->json(['status' => 'error', 'message' => 'Alasan mutasi not found.'], 404);
        }

        $reason->reason_name = $request->reason_name;

        if ($reason->save()) {
            return response()->json([
               'status' => 'success',
               'message' => 'Berhasil mengubah data alasan mutasi',
               'redirect_url' => '/master-data/alasan-mutasi'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Alasan mutasi.'], 500);
        }
    }

    public function NewDeleteDataAlasanMutasi($id) {
        $reason = MasterReason::find($id);

        if ($reason) {
            $reason->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menghapus alasan mutasi',
                'redirect_url' => '/master-data/alasan-mutasi'
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Alasan mutasi Gagal Terhapus'], 404);
        }
    }
}
