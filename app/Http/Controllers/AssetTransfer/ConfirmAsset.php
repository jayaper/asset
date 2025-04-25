<?php

namespace App\Http\Controllers\AssetTransfer;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterMoveOut;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ConfirmAsset extends Controller
{
    public function index() 
    {
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();
        $approvals = DB::table('mc_approval')->select('approval_id', 'approval_name')->get();
        $assets = DB::table('table_registrasi_asset')->select('id', 'asset_name')->get();
        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();
    
        $user = Auth::user();
        $lokasi_user = auth()->user()->location_now;

        $query = DB::table('t_out')
            ->distinct()
            ->select(
                't_out.*',
                't_out_detail.*',
                'm_reason.reason_name',
                'mc_approval.approval_name',
                'fromResto.name_store_street AS from_location',
                'toResto.name_store_street AS destination_location'
            )
            ->join('t_out_detail', 't_out.out_id', '=', 't_out_detail.out_id')
            ->join('m_reason', DB::raw('t_out.reason_id COLLATE utf8mb4_unicode_ci'), '=', DB::raw('m_reason.reason_id COLLATE utf8mb4_unicode_ci'))
            ->join('mc_approval', DB::raw('t_out.is_confirm COLLATE utf8mb4_unicode_ci'), '=', DB::raw('mc_approval.approval_id COLLATE utf8mb4_unicode_ci'))
            ->join('master_resto_v2 AS fromResto', DB::raw('t_out.from_loc COLLATE utf8mb4_unicode_ci'), '=', DB::raw('fromResto.id COLLATE utf8mb4_unicode_ci'))
            ->join('master_resto_v2 AS toResto', DB::raw('t_out.dest_loc COLLATE utf8mb4_unicode_ci'), '=', DB::raw('toResto.id COLLATE utf8mb4_unicode_ci'))
            ->where('t_out.appr_1', '=', '2')
            ->where('t_out.appr_2', '=', '2')
            ->where('t_out.appr_3', '=', '2');
            if (!$user->hasRole('Admin')) {
                // Logika untuk role SM
                $query->where('t_out.from_loc', $lokasi_user)->orWhere('t_out.dest_loc', $lokasi_user);
            }
        $moveins = $query->paginate(10);
    
        return view("asset_transfer.confirm", [
            'user' => $user,
            'reasons' => $reasons,
            'assets' => $assets,
            'conditions' => $conditions,
            'approvals' => $approvals,
            'moveins' => $moveins,
        ]);
    }
    public function updateDataConfirm(Request $request, $id)
    {
        $request->validate([
            'is_confirm' => 'required|in:1,2,3',
        ]);

        $moveout = MasterMoveOut::find($id);

        if (!$moveout) {
            return response()->json(['status' => 'error', 'message' => 'MoveOut not found.'], 404);
        }

        $moveout->is_confirm = $request->is_confirm;

        // Update `t_out_detail` quantities based on confirmation
        if ($request->is_confirm == 3) {
            $details = DB::table('t_out_detail')->where('out_id', $id)->get();
            foreach ($details as $detail) {
                $newQtyContinue = max(0, $detail->qty_continue - $detail->qty_continue);
        
                DB::table('t_out_detail')->where('out_det_id', $detail->out_det_id)->update([
                    'qty_continue' => $newQtyContinue,
                    'updated_at' => Carbon::now(),
                ]);
        
                DB::table('table_registrasi_asset')->where('register_code', $detail->asset_tag)->update([
                    'qty' => 1,
                    'location_now' => $moveout->dest_loc
                ]);
        
                DB::table('asset_tracking')->insert([
                    'start_date' => $moveout->created_at,
                    'from_loc' => $moveout->from_loc,
                    'end_date' => Carbon::now(),
                    'dest_loc' => $moveout->dest_loc,
                    'register_code' => $detail->asset_tag,
                    'out_id' => $id,
                ]);
            }
        }

        if ($moveout->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'MoveOut updated successfully.',
                'redirect_url' => '/asset-transfer/confirm-asset',
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Failed to update MoveOut.'], 500);
    }
}
