<?php

namespace App\Http\Controllers\Disposal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Review extends Controller
{
    public function index() 
    {
        $reasons = DB::table('m_reason')->select('reason_id', 'reason_name')->get();

        $restos = DB::table('miegacoa_keluhan.master_resto')->select('store_code', 'name_store_street')->get();

        $approvals = DB::table('mc_approval')->select('approval_id', 'approval_name')->get();

        $conditions = DB::table('m_condition')->select('condition_id', 'condition_name')->get();

        

        $username = auth()->user()->username;

        $fromLoc = DB::table('m_people')

                ->where('nip', $username)

                ->value('loc_id'); 



        $registerLocation = DB::table('master_resto')

                ->where('store_code', $fromLoc)

                ->value('resto');

    

        // Filter assets based on the register_location matching the fetched resto

        $assets = DB::table('table_registrasi_asset')

        ->select('id', 'asset_name')

        // ->where('location_now', $registerLocation)

        ->where('qty', '>', 0) 

        ->get();       

        $user_loc = auth()->user()->location_now;
        $username = auth()->user()->username;

        // Mulai query builder
        $query = DB::table('t_out')
            ->select(
                't_out.*',
                'b.qty',
                'm_reason.reason_name',
                'mc_approval.approval_name',
                'miegacoa_keluhan.master_resto.*'
            )
            ->leftjoin(DB::RAW('(
                SELECT
                    b.out_id, 
                    SUM(b.qty) AS qty
                FROM t_out_detail AS b
                GROUP BY b.out_id) AS b'), 'b.out_id', '=', 't_out.out_id')

            ->leftjoin('m_reason', 't_out.reason_id', '=', 'm_reason.reason_id')
            ->leftjoin('mc_approval', 't_out.is_confirm', '=', 'mc_approval.approval_id')
            ->leftjoin(
                'miegacoa_keluhan.master_resto',
                DB::raw('CONVERT(t_out.from_loc USING utf8mb4) COLLATE utf8mb4_unicode_ci'),
                '=',
                DB::raw('CONVERT(miegacoa_keluhan.master_resto.id USING utf8mb4) COLLATE utf8mb4_unicode_ci')
            )
            ->where('t_out.out_id', 'like', 'DA%')
            ->where('t_out.is_confirm', '3')
            ->orderBy('t_out.out_id', 'DESC');
            // Jika yang login bukan admin, tambahkan filter berdasarkan `user_loc`
            $user = Auth::User();
            if (!$user->hasRole('Admin')) {
                $query->where(function ($q){
                    $q->where('t_out.from_loc', Auth::User()->location_now);
                });
            }
        $moveouts = $query->paginate(10);
    

        return view("disposal.review-disposal", [
            'fromLoc' => $fromLoc,
            'reasons' => $reasons,
            'assets' => $assets,
            'conditions' => $conditions,
            'approvals' => $approvals,
            'moveouts' => $moveouts,
            'restos' => $restos
        ]);
    }

    public function DetailReview($id) 
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
