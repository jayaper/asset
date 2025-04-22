<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $total_asset = DB::table('t_transaction_qty')
            ->sum('qty');

        $bad_asset = DB::table('t_transaction_qty')
            ->whereIn('condition', [2, 4])
            ->sum('qty');


        $good_asset = DB::table('t_transaction_qty')
            ->whereIn('condition', [1, 3])
            ->sum('qty');


        $total_resto = DB::table('master_resto_v2')
            ->count();

        return view("dashboard.index", [
            'totalAsset' => $total_asset,
            'badAsset' => $bad_asset,
            'goodAsset' => $good_asset,
            'totalResto' => $total_resto
        ]);
    }

    public function getDataResto(Request $request)
    {
        $dataQuery = DB::table('t_transaction_qty')
        
        ->leftjoin('m_assets', 't_transaction_qty.asset_id', '=', 'm_assets.asset_id')

        ->leftjoin('master_resto_v2', 't_transaction_qty.from_loc', '=', 'master_resto_v2.id')

        ->leftjoin('t_out', 't_transaction_qty.out_id', '=', 't_out.out_id')

        ->leftjoin('t_out_detail', 't_out.out_id', '=', 't_out_detail.out_id')
        
        ->leftjoin('m_condition', 't_out_detail.condition', '=', 'm_condition.condition_id')

        ->select('master_resto_v2.id AS id_resto','master_resto_v2.name_store_street', 'm_assets.asset_model', 'm_condition.condition_name', 'm_condition.condition_id', 't_transaction_qty.qty', 't_transaction_qty.out_id', 't_transaction_qty.created_at');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->input('start_date') . ' 00:00:00'; 
            $endDate = $request->input('end_date') . ' 23:59:59'; 
            $dataQuery->whereBetween('t_transaction_qty.created_at', [$startDate, $endDate]);
        }

        // Execute the query and get the paginated result
        
        $data = $dataQuery->get();

        return response()->json($data);
    }
}
