<?php

namespace App\Http\Controllers\Disposal;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterDisOut;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ApprovalOpsRM extends Controller
{
    public function index() 
    {
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();
        $approvals = DB::table('mc_approval')->select('approval_id', 'approval_name')->get();
        $assets = DB::table('table_registrasi_asset')->select('id', 'asset_name')->get();
        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();
        $moveouts = DB::table('t_out')
        ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
        ->join('mc_approval', 't_out.appr_2', '=', 'mc_approval.approval_id')
        ->join('master_resto_v2 AS from_loc', 't_out.from_loc', '=', 'from_loc.id')
        ->select('t_out.*', 'm_reason.reason_name', 'mc_approval.approval_name',
        'from_loc.name_store_street AS from_location'
        )
        ->where('t_out.out_id', 'like', 'DA%')
        ->whereIn('t_out.appr_2', ['1', '2', '3', '4'])
        ->whereNull('t_out.deleted_at')
        ->paginate(10);

        return view("disposal.apprdis-rm", [
            'reasons' => $reasons,
            'assets' => $assets,
            'conditions' => $conditions,
            'approvals' => $approvals,
            'moveouts' => $moveouts
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'appr_2' => 'required|string|max:255',
        ]);

        // Cek apakah MoveOut dengan id yang benar ada
        $moveout = MasterDisOut::find($id); // Langsung gunakan find jika ID adalah primary key

        if (!$moveout) {
            return response()->json(['status' => 'error', 'message' => 'moveout not found.'], 404);
        }

        // Update data moveout
        $moveout->appr_2_date = Carbon::now();
        $moveout->appr_2 = $request->appr_2;
        $moveout->appr_2_user = auth()->user()->username;
        if ($request->appr_2 == '2') {
            $moveout->appr_3 = '1';
        } elseif ($request->appr_1 == '4') {
            $moveout->is_confirm = '4';
    
            DB::table('t_out_detail')
                ->where('out_id', $id)
                ->update(['status' => 4]);
    
            $details = DB::table('t_out_detail')
                ->where('out_id', $id)
                ->get();
    
            foreach ($details as $detail) {
                DB::table('table_registrasi_asset')
                    ->where('id', $detail->asset_id)
                    ->increment('qty', 1); 
            }
        }
        
        if ($moveout->save()) { // Menggunakan save() yang lebih aman daripada update()
            return response()->json([
                'status' => 'success',
                'message' => 'moveout updated successfully.',
                'redirect_url' => 'disposal/approval-ops-rm', // Sesuaikan dengan route index Anda
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update moveout.'], 500);
        }
    }
    public function DetailApprovalRM($id) 
    {
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();

        $moveOutAssets = DB::table('t_out')
        ->select(
            't_out.*',
            't_out_detail.out_id AS detail_out_id',
            't_out_detail.qty',
            'm_reason.reason_name',
            'master_resto_v2.name_store_street AS from_location'
        )
        ->leftjoin('t_out_detail', 't_out.out_id', '=', 't_out_detail.out_id')
        ->leftjoin('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
        ->leftjoin('master_resto_v2', 't_out.from_loc', '=', 'master_resto_v2.id')
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
        ->get();

        // dd($moveOutAssets);

        return view('disposal.detail_data_disposal', compact('reasons', 'moveOutAssets', 'assets'));
    }
}
