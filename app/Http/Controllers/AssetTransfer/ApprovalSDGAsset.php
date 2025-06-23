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
            ->select(
                't_out.*',
                DB::raw("
                    CASE
                        WHEN LENGTH(t_out.out_desc) > 50
                            THEN CONCAT(SUBSTRING(t_out.out_desc, 1, 50), '...')
                        ELSE t_out.out_desc
                    END as out_desc
                "),
                'b.qty',
                'm_reason.reason_name',
                'mc_approval.approval_name',
                'fromResto.name_store_street as from_location',
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
            ->leftJoin('mc_approval', 'mc_approval.approval_id', '=', 't_out.appr_3')
            ->leftJoin('m_reason', 'm_reason.reason_id', '=', 't_out.reason_id')
            ->leftJoin('miegacoa_keluhan.master_resto as fromResto', 'fromResto.id', '=', 't_out.from_loc')
            ->leftJoin('miegacoa_keluhan.master_resto as toResto', 'toResto.id', '=', 't_out.dest_loc')
            ->whereIn('appr_3', ['1', '2', '3', '4'])
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

            $moveout->appr_3_date = Carbon::now();
            $moveout->appr_3 = $request->appr_3;
            $moveout->appr_3_user = auth()->user()->username;

            if ($request->appr_3 == '4' && $moveout->appr_2 == '2') {       

                $moveout->is_confirm = '4';
                $moveout->confirm_date = Carbon::now();
    
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
                            'qty' => 1,
                            'status_asset' => 1
                        ]);
                    }
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
