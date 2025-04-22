<?php

namespace App\Http\Controllers\AssetTransfer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Master\MasterMoveOut;
use Illuminate\Support\Facades\DB;

class ApprovalOpsAM extends Controller
{
    public function index() 
    {
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();
        $approvals = DB::table('mc_approval')->select('approval_id', 'approval_name')->get();
        $assets = DB::table('table_registrasi_asset')->select('id', 'asset_name')->get();
        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();
        $moveouts = DB::table('t_out')
        ->join('mc_approval', 't_out.appr_1', '=', 'mc_approval.approval_id')
        ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
        ->join('master_resto_v2 as fromResto', 't_out.from_loc', '=', 'fromResto.id') // Alias for from_loc
        ->join('master_resto_v2 as toResto', 't_out.dest_loc', '=', 'toResto.id')   // Alias for dest_loc
        ->join('t_out_detail', 't_out.out_id', '=', 't_out_detail.out_id') 
        ->select('t_out.*', 't_out_detail.*', 'm_reason.reason_name', 'mc_approval.approval_name', 
                'fromResto.name_store_street as from_location', 
                'toResto.name_store_street as dest_location',
        )
        ->whereIn('appr_1', ['1', '2', '3', '4'])
        ->whereNull('t_out.deleted_at')
        ->orderBy('t_out.created_at', 'desc')
        ->paginate(10);

        return view("asset_transfer.apprmoveout-am", [
            'reasons' => $reasons,
            'approvals' => $approvals,
            'assets' => $assets,
            'conditions' => $conditions,
            'moveouts' => $moveouts
        ]);
    }

    public function updateApprovalAmAM(Request $request, $id)
    {
        $moveout = MasterMoveOut::find($id);

        if (!$moveout) {
            return response()->json(['status' => 'error', 'message' => 'Moveout not found.'], 404);
        }


        $moveout->appr_1_date = Carbon::now();
        $moveout->appr_1 = $request->appr_1;
        $moveout->appr_1_user = auth()->user()->username;

        if ($request->appr_1 == '2') {
            $moveout->appr_2 = '1';
        } elseif ($request->appr_1 == '4') {
            $moveout->is_confirm = '4';


            DB::table('t_out_detail')
                ->where('out_id', $id)
                ->update(['status' => 4]);

            // Fetch details
            $details = DB::table('t_out_detail')
                ->where('out_id', $id)
                ->get();

            foreach ($details as $detail) {
                if (!isset($detail->id)) {
                    continue;
                }

                // Reduce qty and store in qty_final
                $newQty = max(0, $detail->qty - 1);
                DB::table('t_out_detail')
                    ->where('id', $detail->id)
                    ->update([
                        'qty' => $newQty,
                        'qty_final' => $detail->qty,
                    ]);

                // Increment qty in table_registrasi_asset
                DB::table('table_registrasi_asset')
                    ->where('id', $detail->asset_id)
                    ->increment('qty', 1);
            }

            // Update t_transaction_qty
            $transactions = DB::table('t_transaction_qty')
                ->where('out_id', $id)
                ->get();

            foreach ($transactions as $transaction) {
                // If appr_1 is 4, move qty_continue back to qty
                if ($request->appr_1 == '4') {
                    DB::table('t_transaction_qty')
                        ->where('id', $transaction->id)
                        ->update([
                            'qty' => $transaction->qty_continue,
                            'qty_continue' => 0,
                            'qty_disposal' => 0
                        ]);
                }
            }
        } elseif ($request->appr_1 == '2') {
            
        }

        if ($moveout->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Moveout updated successfully.',
                'redirect_url' => url('/asset-transfer/approval-ops-am'),
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update moveout.'], 500);
        }
    }
}
