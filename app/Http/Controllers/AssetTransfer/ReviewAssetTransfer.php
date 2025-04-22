<?php

namespace App\Http\Controllers\AssetTransfer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\support\Facades\DB;

class ReviewAssetTransfer extends Controller
{
    public function head() 
    {
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();
        $approvals = DB::table('mc_approval')->select('approval_id', 'approval_name')->get();
        $assets = DB::table('table_registrasi_asset')->select('id', 'asset_name')->get();
        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();
        $delivery = DB::table('t_transit')->select('transit_id')->get();
        $moveins = DB::table('t_out')
        ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
        ->join('t_in', 't_out.out_id', '=', 't_in.out_id')
        ->join('mc_approval', 't_in.is_confirm', '=', 'mc_approval.approval_id')
        ->join('t_in_detail', 't_in_detail.in_id', '=', 't_in.in_id')
        ->join('t_transit', 't_in_detail.in_det_id', '=', 't_transit.in_det_id')
        ->select('t_out.*', 't_in.*', 't_in_detail.*', 't_transit.*', 'm_reason.reason_name', 'mc_approval.approval_name', 't_in.is_confirm')
        ->where('t_out.appr_3', '=', '2')
        ->paginate(10);

        return view("asset_transfer.rev-head", [
            'reasons' => $reasons,
            'assets' => $assets,
            'conditions' => $conditions,
            'approvals' => $approvals,
            'moveins' => $moveins,
            'delivery' => $delivery
        ]);
    }
    public function mnr() 
    {
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();
        $approvals = DB::table('mc_approval')->select('approval_id', 'approval_name')->get();
        $assets = DB::table('table_registrasi_asset')->select('id', 'asset_name')->get();
        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();
        $delivery = DB::table('t_transit')->select('transit_id')->get();
        $moveins = DB::table('t_out')
        ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
        ->join('t_in', 't_out.out_id', '=', 't_in.out_id')
        ->join('mc_approval', 't_in.is_confirm', '=', 'mc_approval.approval_id')
        ->join('t_in_detail', 't_in_detail.in_id', '=', 't_in.in_id')
        ->join('t_transit', 't_in_detail.in_det_id', '=', 't_transit.in_det_id')
        ->select('t_out.*', 't_in.*', 't_in_detail.*', 't_transit.*', 'm_reason.reason_name', 'mc_approval.approval_name', 't_in.is_confirm')
        ->where('t_out.appr_3', '=', '2')
        ->paginate(10);

        return view("asset_transfer.rev-mnr", [
            'reasons' => $reasons,
            'assets' => $assets,
            'conditions' => $conditions,
            'approvals' => $approvals,
            'moveins' => $moveins,
            'delivery' => $delivery
        ]);
    }
    
    public function taf() 
    {
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();
        $approvals = DB::table('mc_approval')->select('approval_id', 'approval_name')->get();
        $assets = DB::table('table_registrasi_asset')->select('id', 'asset_name')->get();
        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();
        $delivery = DB::table('t_transit')->select('transit_id')->get();
        $moveins = DB::table('t_out')
        ->join('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
        ->join('t_in', 't_out.out_id', '=', 't_in.out_id')
        ->join('mc_approval', 't_in.is_confirm', '=', 'mc_approval.approval_id')
        ->join('t_in_detail', 't_in_detail.in_id', '=', 't_in.in_id')
        ->join('t_transit', 't_in_detail.in_det_id', '=', 't_transit.in_det_id')
        ->select('t_out.*', 't_in.*', 't_in_detail.*', 't_transit.*', 'm_reason.reason_name', 'mc_approval.approval_name', 't_in.is_confirm')
        ->where('t_out.appr_3', '=', '2')
        ->paginate(10);

        return view("asset_transfer.rev-taf", [
            'reasons' => $reasons,
            'assets' => $assets,
            'conditions' => $conditions,
            'approvals' => $approvals,
            'moveins' => $moveins,
            'delivery' => $delivery
        ]);
    }
}
