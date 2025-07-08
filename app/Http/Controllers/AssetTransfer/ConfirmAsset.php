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
            ->select(
                't_out.*',
                'b.qty',
                'm_reason.reason_name',
                'mc_approval.approval_name',
                'fromResto.name_store_street AS from_location',
                'toResto.name_store_street AS destination_location'
            )
            ->leftJoin(
                DB::RAW('(
                SELECT
                    b.out_id,
                    SUM(b.qty) as qty
                FROM t_out_detail AS b
                GROUP BY b.out_id
                ) AS b'), 'b.out_id', '=', 't_out.out_id')
            ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
            ->join('mc_approval', 't_out.is_confirm', '=', 'mc_approval.approval_id')
            ->join('miegacoa_keluhan.master_resto AS fromResto', 't_out.from_loc', '=', 'fromResto.id')
            ->join('miegacoa_keluhan.master_resto AS toResto', 't_out.dest_loc', '=', 'toResto.id')
            ->where('t_out.appr_1', '=', '2')
            ->where('t_out.appr_2', '=', '2')
            ->where('t_out.appr_3', '=', '2');
            if (Auth::user()->hasRole('SM')){
                $query->where(function($q) use ($lokasi_user){
                    $q->where('t_out.from_loc', $lokasi_user)
                      ->orWhere('t_out.dest_loc', $lokasi_user);
                });
            }else if(Auth::user()->hasRole('AM')){
                $query->where(function($q) use ($lokasi_user){
                    $q->where('fromResto.kode_city', $lokasi_user);
                });
            }else if(Auth::user()->hasRole('RM')){
                $query->where(function($q) use ($lokasi_user){
                    $q->where('fromResto.id_regional', $lokasi_user);
                });
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
            'is_confirm' => 'required|in:3,4',
        ]);

        $moveout = MasterMoveOut::find($id);

        if (!$moveout) {
            return response()->json(['status' => 'error', 'message' => 'MoveOut not found.'], 404);
        }

        $moveout->confirm_date = Carbon::now();

        // Update `t_out_detail` quantities based on confirmation
        if ($request->is_confirm == 3) {
            $moveout->is_confirm = 3;
            $details = DB::table('t_out_detail')->where('out_id', $id)->get();
            foreach ($details as $detail) {
                $newQtyContinue = max(0, $detail->qty_continue - $detail->qty_continue);
        
                DB::table('t_out_detail')->where('out_det_id', $detail->out_det_id)->update([
                    'qty_continue' => $newQtyContinue,
                    'updated_at' => Carbon::now(),
                ]);
        
                DB::table('table_registrasi_asset')->where('register_code', $detail->asset_tag)->update([
                    'qty' => 1,
                    'status_asset' => 1,
                    'location_now' => $moveout->dest_loc,
                    'last_transaction_code' => $id
                ]);
        
                DB::table('asset_tracking')->insert([
                    'start_date' => $moveout->created_at,
                    'from_loc' => $moveout->from_loc,
                    'end_date' => Carbon::now(),
                    'dest_loc' => $moveout->dest_loc,
                    'reason' => $moveout->reason_id,
                    'condition' => $detail->condition,
                    'description' => $moveout->out_desc,
                    'register_code' => $detail->asset_tag,
                    'out_id' => $id,
                ]);
            }
        }else if($request->is_confirm == 4){

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
                        'status_asset' => 1,
                        'qty' => 1
                    ]);
                }
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
