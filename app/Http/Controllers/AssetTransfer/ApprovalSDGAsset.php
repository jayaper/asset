<?php

namespace App\Http\Controllers\AssetTransfer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\MasterMoveOut;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ApprovalSDGAsset extends Controller
{
    function index()
    {
        $user = Auth::User();
        $from_loc = auth()->user()->location_now;
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();
        $approvals = DB::table('mc_approval')->select('approval_id', 'approval_name')->get();
        $assets = DB::table('table_registrasi_asset')->select('id', 'asset_name')->get();
        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();
        $moveouts = DB::table('t_out')
        ->join('mc_approval', 't_out.appr_3', '=', 'mc_approval.approval_id')
        ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
        ->join('master_resto_v2 as fromResto', 't_out.from_loc', '=', 'fromResto.id') // Alias for from_loc
        ->join('master_resto_v2 as toResto', 't_out.dest_loc', '=', 'toResto.id')   // Alias for dest_loc
        ->join('t_out_detail', 't_out.out_id', '=', 't_out_detail.out_id') 
        ->select('t_out.*', DB::RAW('SUM(t_out_detail.qty) as qty'), 'm_reason.reason_name', 'mc_approval.approval_name', 
                'fromResto.name_store_street as from_location', 
                'toResto.name_store_street as dest_location',
        )
        ->whereIn('appr_1', ['1', '2', '3', '4'])
        ->groupBy('t_out.out_id', 'm_reason.reason_name', 'mc_approval.approval_name', 'from_location', 'dest_location');
        if (!$user->hasRole('Admin')) {
            $moveouts->where(function($q) use ($from_loc){
                $q->where('t_out.from_loc', $from_loc);
            });
        }

        $moveouts = $moveouts->orderBy('t_out.created_at', 'desc')->paginate(10);

        return view("asset_transfer.apprmoveout-sdgasset", [
            'user' => $user,
            'reasons' => $reasons,
            'assets' => $assets,
            'conditions' => $conditions,
            'approvals' => $approvals,
            'moveouts' => $moveouts
        ]);
    }

    public function updateDataApprSDG(Request $request, $id)
    {
        try {
            // Validate request input
            $request->validate([
                'appr_3' => 'required|string|max:255',
            ]);
    
            // Find the MasterMoveOut record
            $moveout = MasterMoveOut::find($id);
    
            if (!$moveout) {
                return response()->json(['status' => 'error', 'message' => 'MoveOut not found.'], 404);
            }
    
            // Get transaction codes
            $trx_code = DB::table('t_trx')->where('trx_name', 'Confirmation Movement')->value('trx_code');
            $trx_code_1 = DB::table('t_trx')->where('trx_name', 'Transfer')->value('trx_code');
            $today = Carbon::now()->format('ymd');
            $transaction_number = 1;
    
            // Update the moveout data
            $moveout->appr_3_date = Carbon::now();
            $moveout->appr_3 = $request->appr_3;
            $moveout->appr_3_user = auth()->user()->username;
    
            // Generate new in_id and tf_code
            $new_in_id = null;
            $new_tf_code = null;
            do {
                $transaction_number_str = str_pad($transaction_number, 3, '0', STR_PAD_LEFT);
                $new_in_id = "{$trx_code}.{$today}.{$request->input('reason_id')}.{$request->input('from_loc_id')}.{$transaction_number_str}";
                $new_tf_code = "{$trx_code_1}.{$today}.{$request->input('reason_id')}.{$request->input('from_loc_id')}.{$transaction_number_str}";
    
                $existing_in_id = DB::table('t_in')->where('in_id', $new_in_id)->exists();
                $existing_tf_code = DB::table('t_out')->where('tf_code', $new_tf_code)->exists();
    
                if ($existing_in_id && $existing_tf_code) {
                    $transaction_number++;
                }
            } while ($existing_in_id && $existing_tf_code);
    
            $moveout->in_id = $new_in_id;
            $moveout->tf_code = $new_tf_code;
    
            // Check approval and process accordingly
            if ($request->appr_3 == '2' && $moveout->appr_2 == '2') {
                // Insert into t_in
                DB::table('t_in')->insert([
                    'in_id' => $new_in_id,
                    'out_id' => $moveout->out_id,
                    'in_date' => $moveout->out_date,
                    'from_loc' => $moveout->dest_loc,
                    'out_desc' => $moveout->out_desc,
                    'reason_id' => $moveout->reason_id,
                    'appr_1' => 1,
                    'create_date' => now(),
                    'modified_date' => now()
                ]);
    
                // Get the moveout details
                $moveoutDetails = DB::table('t_out_detail')->where('out_id', $moveout->out_id)->get();
    
                $previous_asset_tag = null;
                foreach ($moveoutDetails as $index => $detail) {
                    if ($previous_asset_tag !== $detail->asset_tag) {
                        $transaction_number++;
                        $transaction_number_str = str_pad($transaction_number, 3, '0', STR_PAD_LEFT);
                        $new_in_id = "{$trx_code}.{$today}.{$request->input('reason_id')}.{$request->input('from_loc_id')}.{$transaction_number_str}";
                        $previous_asset_tag = $detail->asset_tag;
                    }
    
                    $existing_detail = DB::table('t_in_detail')
                        ->where('in_id', $new_in_id)
                        ->where('asset_tag', $detail->asset_tag)
                        ->exists();
    
                    if (!$existing_detail) {
                        DB::table('t_in_detail')->insert([
                            'in_id' => $new_in_id,
                            'in_det_id' => $detail->out_det_id,
                            'asset_tag' => $detail->asset_tag,
                            'asset_id' => $detail->asset_id,
                            'serial_number' => $detail->serial_number,
                            'brand' => $detail->brand,
                            'qty' => $detail->qty,
                            'uom' => $detail->uom,
                            'condition' => $detail->condition,
                            'image' => $detail->image,
                        ]);
                    }
                }
            } elseif ($request->appr_3 == '4') {
                $moveout->is_confirm = '4';
                $moveout->in_id = null;
    
                DB::table('t_out_detail')
                    ->where('out_id', $id)
                    ->update(['status' => 4]);
    
                $details = DB::table('t_out_detail')
                    ->where('out_id', $id)
                    ->get();
    
                foreach ($details as $detail) {
    
                    $newQty = max(0, $detail->qty - 1);
                    DB::table('t_out_detail')
                        ->where('out_id', $detail->out_id)
                        ->update([
                            'qty' => $newQty,
                        ]);
    
                    $t_regist = DB::table('table_registrasi_asset')
                        ->where('register_code', $detail->asset_tag)->get();
        
                    foreach($t_regist as $table){
                        DB::table('table_registrasi_asset')->where('register_code', $table->register_code)
                        ->update([
                            'location_now' => $moveout->from_loc,
                            'qty' => 1
                        ]);
                    }
                }
    
                $transactions = DB::table('t_transaction_qty')
                    ->where('out_id', $id)
                    ->get();
    
                foreach ($transactions as $transaction) {
                    DB::table('t_transaction_qty')
                        ->where('id', $transaction->id)
                        ->update([
                            'qty' => $transaction->qty_continue,
                            'qty_continue' => 0,
                            'qty_disposal' => 0
                        ]);
                }
            }
    
            if ($moveout->save()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'MoveOut updated successfully.',
                    'redirect_url' => url('/asset-transfer/approval-sdg-asset'),
                ]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Failed to update MoveOut.'], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Error updating MoveOut: ' . $e->getMessage());
    
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating MoveOut.',
                'error_details' => $e->getMessage(),
            ], 500);
        }
    }
}
