<?php

namespace App\Http\Controllers\AssetTransfer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReviewAssetTransfer extends Controller
{
    public function head() 
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
                ->join('master_resto_v2 AS fromResto', 't_out.from_loc', '=', 'fromResto.id')
                ->join('master_resto_v2 AS toResto', 't_out.dest_loc', '=', 'toResto.id')
                ->where('t_out.is_confirm', '=', '3')
                ->where('t_out.out_id', 'like', 'AM%');
            if (!$user->hasRole('Admin')) {
                $query->where(function($q) use ($lokasi_user) {
                    $q->where('t_out.from_loc', $lokasi_user)
                      ->orWhere('t_out.dest_loc', $lokasi_user);
                });
            }
        $moveins = $query->paginate(10);
    
        return view("asset_transfer.rev-head", [
            'user' => $user,
            'reasons' => $reasons,
            'assets' => $assets,
            'conditions' => $conditions,
            'approvals' => $approvals,
            'moveins' => $moveins,
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
