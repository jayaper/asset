<?php

namespace App\Http\Controllers\AssetTransfer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Master\MasterMoveOut;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ApprovalOpsAM extends Controller
{
    public function index() 
    {
        $user = Auth::user();
        $from_loc =  auth()->user()->location_now;  

        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();
        $approvals = DB::table('mc_approval')->select('approval_id', 'approval_name')->get();
        $assets = DB::table('table_registrasi_asset')->select('id', 'asset_name')->get();
        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();
        $moveouts = DB::table('t_out')
            ->select(
                't_out.*',
                'b.qty',
                'm_reason.reason_name',
                'mc_approval.approval_name',
                'fromResto.name_store_street as from_location',
                'fromResto.kode_city as kode_city',
                'toResto.name_store_street as dest_location'
            )
            ->leftJoin(
                DB::RAW('(
                SELECT
                    b.out_id,
                    SUM(b.qty) as qty
                FROM t_out_detail AS b
                GROUP BY b.out_id
                ) AS b'), 'b.out_id', '=', 't_out.out_id')
            ->leftJoin('mc_approval', 'mc_approval.approval_id', '=', 't_out.appr_1')
            ->leftJoin('m_reason', 'm_reason.reason_id', '=', 't_out.reason_id')
            ->leftJoin('miegacoa_keluhan.master_resto as fromResto', 'fromResto.id', '=', 't_out.from_loc')
            ->leftJoin('miegacoa_keluhan.master_resto as toResto', 'toResto.id', '=', 't_out.dest_loc')
            ->whereIn('appr_1', ['1', '2', '3', '4'])
            ->whereNull('t_out.deleted_at')
            ->where('t_out.out_id', 'like', 'AM%');

            if (Auth::user()->hasRole('SM')){
                $moveouts->where(function($q){
                    $q->where('t_out.from_loc', Auth::user()->location_now);
                });
            }else if(Auth::user()->hasRole('AM')){
                $moveouts->where(function($q){
                    $q->where('fromResto.kode_city', Auth::user()->location_now);
                });
            }else if(Auth::user()->hasRole('RM')){
                $moveouts->where(function($q){
                    $q->where('fromResto.id_regional', Auth::user()->location_now);
                });
            }

        $moveouts = $moveouts->orderBy('t_out.created_at', 'desc')->paginate(10);
        

        return view("asset_transfer.apprmoveout-am", [
            'user' => $user,
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

                // Reduce qty and store in qty_final
                $newQty = max(0, $detail->qty - 1);
                DB::table('t_out_detail')
                    ->where('out_id', $detail->out_id)
                    ->update([
                        'qty' => $newQty
                    ]);

                // Increment qty in table_registrasi_asset
                $t_regist = DB::table('table_registrasi_asset')
                    ->where('register_code', $detail->asset_tag)->get();

                foreach($t_regist as $table){
                    DB::table('table_registrasi_asset')->where('register_code', $table->register_code)->update([
                        'location_now' => $moveout->from_loc,
                        'qty' => 1
                    ]);
                }
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
