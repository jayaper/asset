<?php

namespace App\Http\Controllers\Disposal;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterMoveOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ApprovalOpsAM extends Controller
{
    public function index() 
    {
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();
        $approvals = DB::table('mc_approval')->select('approval_id', 'approval_name')->get();
        $assets = DB::table('table_registrasi_asset')->select('id', 'asset_name')->get();
        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();

        $query = DB::table('t_out')
            ->select(
                't_out.*',
                'b.qty',
                'm_reason.reason_name',
                'mc_approval.approval_name',
                'fromloc.name_store_street AS from_location',
                'fromloc.kode_city',
                'fromloc.id_regional'
            )
            ->leftjoin(DB::RAW('(
                SELECT
                    b.out_id, 
                    SUM(b.qty) AS qty
                FROM t_out_detail AS b
                GROUP BY b.out_id) AS b'), 'b.out_id', '=', 't_out.out_id')

            ->leftjoin('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
            ->leftjoin('mc_approval', 't_out.appr_1', '=', 'mc_approval.approval_id')
            ->leftjoin(
                'miegacoa_keluhan.master_resto AS fromloc',
                DB::raw('CONVERT(t_out.from_loc USING utf8mb4) COLLATE utf8mb4_unicode_ci'),
                '=',
                DB::raw('CONVERT(fromloc.id USING utf8mb4) COLLATE utf8mb4_unicode_ci')
            )
            ->whereIn('t_out.appr_1', ['1', '2', '3', '4'])
            ->whereNull('t_out.deleted_at')
            ->where('t_out.out_id', 'like', 'DA%')
            ->orderBy('t_out.out_id', 'DESC');
            // Jika yang login bukan admin, tambahkan filter berdasarkan `user_loc`
            $user = Auth::User();
            if ($user->hasRole('SM')) {
                $query->where(function ($q){
                    $q->where('t_out.from_loc', Auth::User()->location_now);
                });
            }else if($user->hasRole('AM')) {
                $query->where(function ($q){
                    $q->where('fromloc.kode_city', Auth::User()->location_now);
                });
            }else if($user->hasRole('RM')) {
                $query->where(function ($q){
                    $q->where('fromloc.regional', Auth::User()->location_now);
                });
            }
        $moveouts = $query->paginate(10);


        $user = Auth::user();

        return view("disposal.apprdis-am", [
            'reasons' => $reasons,
            'approvals' => $approvals,
            'assets' => $assets,
            'conditions' => $conditions,
            'moveouts' => $moveouts,
            'user' => $user
        ]);
    }
    public function updateApprovalAM(Request $request, $id) {
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
                    ]);

                // Increment qty in table_registrasi_asset
                $t_regist = DB::table('table_registrasi_asset')
                    ->where('register_code', $detail->asset_tag)
                    ->get();

                foreach($t_regist as $table){
                    DB::table('table_registrasi_asset')
                    ->where('id', $table->id)
                    ->update([
                        'qty' => 1,
                        'status_asset' => 1
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
        } elseif ($request->appr_1 == '2') {
            
        }

        if ($moveout->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Moveout updated successfully.',
                'redirect_url' => 'disposal/approval-ops-am',
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update moveout.'], 500);
        }
    }

    public function detailApprovalAM($id) 
    {
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();

        $moveOutAssets = DB::table('t_out')
        ->select(
            't_out.*',
            't_out_detail.out_id AS detail_out_id',
            't_out_detail.qty',
            'm_reason.reason_name',
            'miegacoa_keluhan.master_resto.name_store_street AS from_location'
        )
        ->join('t_out_detail', 't_out.out_id', '=', 't_out_detail.out_id')
        ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
        ->join('miegacoa_keluhan.master_resto', 't_out.from_loc', '=', 'miegacoa_keluhan.master_resto.id')
        ->where('t_out.out_id', '=', $id) // Ensure specific match
        ->where('t_out.out_id', 'like', 'DA%')
        ->first();

        $assets = DB::table('table_registrasi_asset')
        ->leftjoin('t_out_detail', 'table_registrasi_asset.register_code', 't_out_detail.asset_tag')
        ->leftjoin('t_transaction_qty', 't_out_detail.out_id', '=', 't_transaction_qty.out_id')
        ->leftjoin('t_out', 't_transaction_qty.out_id', 't_out.out_id')
        ->leftjoin('m_assets', 'table_registrasi_asset.asset_name', '=', 'm_assets.asset_id')
        ->leftjoin('m_brand', 'table_registrasi_asset.merk', '=', 'm_brand.brand_id')
        ->leftjoin('m_condition', 'table_registrasi_asset.condition', '=', 'm_condition.condition_id')
        ->leftjoin('m_uom', 'table_registrasi_asset.satuan', '=', 'm_uom.uom_id')
        ->select('m_assets.asset_model', 'm_brand.brand_name', 't_transaction_qty.qty', 'm_uom.uom_name', 'table_registrasi_asset.serial_number', 'table_registrasi_asset.register_code', 'm_condition.condition_name', 't_out_detail.image')
        ->where('t_out.out_id', 'like', 'DA%')
        ->where('t_out_detail.out_id', $id)
        ->get();

        // dd($moveOutAssets);

        return view('disposal.detail_data_disposal', compact('reasons', 'moveOutAssets', 'assets'));
    }
}
