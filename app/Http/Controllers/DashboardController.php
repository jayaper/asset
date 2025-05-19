<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::User();

        $asset_move = DB::table('t_out')

                    ->join('t_out_detail AS detail', 'detail.out_id', 't_out.out_id')
                    ->leftjoin('miegacoa_keluhan.master_resto as resto', 'resto.id', '=', 't_out.from_loc')

                    ->where('t_out.out_id', 'like', 'AM%')
                    ->where('t_out.is_confirm', 3);

                    if(Auth::User()->hasRole('SM')){
                        $asset_move->where(function($q){
                            $q->where('t_out.from_loc', Auth::User()->location_now);
                        });
                    }else if(Auth::User()->hasRole('AM')){
                        $asset_move->where(function($q) {
                            $q->where('resto.kode_city', Auth::User()->location_now);
                        });
                    }else if(Auth::User()->hasRole('AM')){
                        $asset_move->where(function($q) {
                            $q->where('resto.id_regional', Auth::User()->location_now);
                        });
                    }

        $asset_movement = $asset_move->count();
              
        $asset_in = DB::table('t_out')

                    ->join('t_out_detail AS detail', 'detail.out_id', 't_out.out_id')
                    ->leftjoin('miegacoa_keluhan.master_resto as resto', 'resto.id', '=', 't_out.dest_loc')

                    ->where('t_out.out_id', 'like', 'AM%')
                    ->where('t_out.is_confirm', 3);

                    if(Auth::User()->hasRole('SM')){
                        $asset_in->where(function($q){
                            $q->where('t_out.dest_loc', Auth::User()->location_now);
                        });
                    }else if(Auth::User()->hasRole('AM')){
                        $asset_in->where(function($q) {
                            $q->where('resto.kode_city', Auth::User()->location_now);
                        });
                    }else if(Auth::User()->hasRole('AM')){
                        $asset_in->where(function($q) {
                            $q->where('resto.id_regional', Auth::User()->location_now);
                        });
                    }

        $asset_movement_in = $asset_in->count();
        
        $good_assets = DB::table('t_out')
            ->join('t_out_detail AS a', 'a.out_id', 't_out.out_id')
            ->leftjoin('miegacoa_keluhan.master_resto as resto', 'resto.id', '=', 't_out.from_loc')
                    ->where(function($q) {
                        $q->where('a.condition', 1)->orWhere('a.condition', 3);
                    })

                    ->where('t_out.out_id', 'like', 'AM%')
                    ->where('t_out.is_confirm', 3);

                    if(Auth::User()->hasRole('SM')){
                        $good_assets->where(function($q){
                            $q->where('t_out.from_loc', Auth::User()->location_now);
                        });
                    }else if(Auth::User()->hasRole('AM')){
                        $good_assets->where(function($q) {
                            $q->where('resto.kode_city', Auth::User()->location_now);
                        });
                    }else if(Auth::User()->hasRole('AM')){
                        $good_assets->where(function($q) {
                            $q->where('resto.id_regional', Auth::User()->location_now);
                        });
                    }
        $good_asset = $good_assets->count();

        $bad_assets = DB::table('t_out')

                    ->join('t_out_detail AS a', 'a.out_id', 't_out.out_id')
                    ->leftjoin('miegacoa_keluhan.master_resto as resto', 'resto.id', '=', 't_out.from_loc')
                    ->where(function($q) {
                        $q->where('a.condition', 2)->orWhere('a.condition', 4);
                    })

                    ->where('t_out.out_id', 'like', 'AM%')
                    ->where('t_out.is_confirm', 3);

                    if(Auth::User()->hasRole('SM')){
                        $bad_assets->where(function($q){
                            $q->where('t_out.from_loc', Auth::User()->location_now);
                        });
                    }else if(Auth::User()->hasRole('AM')){
                        $bad_assets->where(function($q) {
                            $q->where('resto.kode_city', Auth::User()->location_now);
                        });
                    }else if(Auth::User()->hasRole('AM')){
                        $bad_assets->where(function($q) {
                            $q->where('resto.id_regional', Auth::User()->location_now);
                        });
                    }
        $bad_asset = $bad_assets->count();

        $asset_dis = DB::table('t_out')

                     ->join('t_out_detail AS a', 'a.out_id', 't_out.out_id')
                    ->leftjoin('miegacoa_keluhan.master_resto as resto', 'resto.id', '=', 't_out.from_loc')
                    ->where('t_out.out_id', 'like', 'DA%')
                    ->where('t_out.is_confirm', 3);
                    
                    if(Auth::User()->hasRole('SM')){
                        $asset_dis->where(function($q){
                            $q->where('t_out.from_loc', Auth::User()->location_now);
                        });
                    }else if(Auth::User()->hasRole('AM')){
                        $asset_dis->where(function($q) {
                            $q->where('resto.kode_city', Auth::User()->location_now);
                        });
                    }else if(Auth::User()->hasRole('AM')){
                        $asset_dis->where(function($q) {
                            $q->where('resto.id_regional', Auth::User()->location_now);
                        });
                    }

        $asset_disposal = $asset_dis->count();

        $good_asset_disp = DB::table('t_out')
                ->join('t_out_detail AS a', 'a.out_id', 't_out.out_id')
                ->leftjoin('miegacoa_keluhan.master_resto as resto', 'resto.id', '=', 't_out.from_loc')
                ->where(function($q) {
                $q->where('a.condition', 1)->orWhere('a.condition', 3);
                })
                ->where('t_out.out_id', 'like', 'DA%')
                ->where('t_out.is_confirm', 3);

                    if(Auth::User()->hasRole('SM')){
                        $good_asset_disp->where(function($q){
                            $q->where('t_out.from_loc', Auth::User()->location_now);
                        });
                    }else if(Auth::User()->hasRole('AM')){
                        $good_asset_disp->where(function($q) {
                            $q->where('resto.kode_city', Auth::User()->location_now);
                        });
                    }else if(Auth::User()->hasRole('AM')){
                        $good_asset_disp->where(function($q) {
                            $q->where('resto.id_regional', Auth::User()->location_now);
                        });
                    }
        $good_asset_dis = $good_asset_disp->count();

        $bad_asset_disp = DB::table('t_out')
                ->join('t_out_detail AS a', 'a.out_id', 't_out.out_id')
                ->leftjoin('miegacoa_keluhan.master_resto as resto', 'resto.id', '=', 't_out.from_loc')
                ->where(function($q) {
                $q->where('a.condition', 2)->orWhere('a.condition', 4);
                })
                ->where('t_out.out_id', 'like', 'DA%')
                ->where('t_out.is_confirm', 3);
                if(Auth::User()->hasRole('SM')){
                    $bad_asset_disp->where(function($q){
                        $q->where('t_out.from_loc', Auth::User()->location_now);
                    });
                }else if(Auth::User()->hasRole('AM')){
                    $bad_asset_disp->where(function($q) {
                        $q->where('resto.kode_city', Auth::User()->location_now);
                    });
                }else if(Auth::User()->hasRole('AM')){
                    $bad_asset_disp->where(function($q) {
                        $q->where('resto.id_regional', Auth::User()->location_now);
                    });
                }
        $bad_asset_dis = $bad_asset_disp->count();

        
        $t_regist = DB::table('table_registrasi_asset')
                ->join('miegacoa_keluhan.master_resto as resto', 'resto.id', '=', 'table_registrasi_asset.register_location');
                if(Auth::User()->hasRole('SM')){
                    $t_regist->where(function($q){
                        $q->where('resto.id', Auth::User()->location_now);
                    });
                }else if(Auth::User()->hasRole('AM')){
                    $t_regist->where(function($q) {
                        $q->where('resto.kode_city', Auth::User()->location_now);
                    });
                }else if(Auth::User()->hasRole('AM')){
                    $t_regist->where(function($q) {
                        $q->where('resto.id_regional', Auth::User()->location_now);
                    });
                }
        $total_registered = $t_regist->count();


        $total_resto = DB::table('miegacoa_keluhan.master_resto')
            ->count();

        $total_assets = DB::table('m_assets');
                    if(Auth::user()->hasRole('SM')){
                        $total_assets->join('table_registrasi_asset','table_registrasi_asset.asset_name', 'm_assets.asset_id')
                        ->where(function($q){
                            $q->where('table_registrasi_asset.qty', '>', 0);
                            $q->where('table_registrasi_asset.location_now', Auth::User()->location_now);
                        });
                    }else if(Auth::user()->hasRole('AM')){
                        $total_assets->join('table_registrasi_asset','table_registrasi_asset.asset_name', 'm_assets.asset_id')
                        ->join('miegacoa_keluhan.master_resto AS resto', 'resto.id', '=', 'table_registrasi_asset.location_now')
                        ->where(function($q){
                            $q->where('table_registrasi_asset.qty', '>', 0);
                            $q->where('resto.kode_city', Auth::User()->location_now);
                        });
                    }else if(Auth::user()->hasRole('RM')){
                        $total_assets->join('table_registrasi_asset','table_registrasi_asset.asset_name', 'm_assets.asset_id')
                        ->join('miegacoa_keluhan.master_resto AS resto', 'resto.id', '=', 'table_registrasi_asset.location_now')
                        ->where(function($q){
                            $q->where('table_registrasi_asset.qty', '>', 0);
                            $q->where('resto.id_regional', Auth::User()->location_now);
                        });
                    }

        $total_asset = $total_assets->count();

        return view("dashboard.index", [
            'user' => $user,
            'totalAsset' => $total_asset,
            'totalRegistered' => $total_registered,
            'assetMove' => $asset_movement,
            'assetIn' => $asset_movement_in,
            'badAsset' => $bad_asset,
            'goodAsset' => $good_asset,
            'assetDisposal' => $asset_disposal,
            'badAssetDis' => $good_asset_dis,
            'goodAssetDis' => $bad_asset_dis,
            'totalResto' => $total_resto
        ]);
    }

    public function getDataResto(Request $request)
    {
        $data_in_query = DB::table('t_out')
                ->select(
                    't_out.out_id',
                    'resto.id AS id_asal_lokasi',
                    'resto.name_store_street AS asal_lokasi',
                    't_out.created_at',
                    'resto2.id AS id_tujuan_lokasi',
                    'resto2.name_store_street AS tujuan_lokasi',
                    't_out.updated_at',
                    'a.qty'
                )
                
                ->leftjoin(DB::RAW('(
                    SELECT
                        a.out_id,
                        SUM(a.qty) as qty
                    FROM t_out_detail AS a
                    GROUP BY a.out_id
                ) AS a'), 'a.out_id', '=', 't_out.out_id')
                ->leftjoin('miegacoa_keluhan.master_resto AS resto', 'resto.id', '=', 't_out.from_loc')
                ->leftjoin('miegacoa_keluhan.master_resto AS resto2', 'resto2.id', '=', 't_out.dest_loc')
                ->where('t_out.is_confirm', 3)
                ->where('t_out.out_id', 'like', 'AM%');

                if(Auth::user()->hasRole('SM')){
                    $data_in_query->where(function($q){
                        $q->where('t_out.dest_loc', Auth::User()->location_now);
                    });
                }else if(Auth::user()->hasRole('AM')){
                    $data_in_query->where(function($q){
                        $q->where('resto2.kode_city', Auth::User()->location_now);
                    });
                }else if(Auth::user()->hasRole('RM')){
                    $data_in_query->where(function($q){
                        $q->where('resto2.id_regional', Auth::User()->location_now);
                    });
                }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->input('start_date') . ' 00:00:00'; 
            $endDate = $request->input('end_date') . ' 23:59:59'; 
            $data_in_query->whereBetween('t_out.created_at', [$startDate, $endDate]);
        }

        $data_out_query = DB::table('t_out')
                ->select(
                    't_out.out_id',
                    'resto.id AS id_asal_lokasi',
                    'resto.name_store_street AS asal_lokasi',
                    't_out.created_at',
                    'resto2.id AS id_tujuan_lokasi',
                    'resto2.name_store_street AS tujuan_lokasi',
                    't_out.updated_at',
                    'a.qty'

                )
                
                ->leftjoin(DB::RAW('(
                    SELECT
                        a.out_id, 
                        SUM(a.qty) as qty
                    FROM t_out_detail AS a
                    GROUP BY a.out_id
                ) AS a'), 'a.out_id', '=', 't_out.out_id')
                ->leftjoin('miegacoa_keluhan.master_resto AS resto', 'resto.id', '=', 't_out.from_loc')
                ->leftjoin('miegacoa_keluhan.master_resto AS resto2', 'resto2.id', '=', 't_out.dest_loc')
                ->where('t_out.is_confirm', 3)
                ->where('t_out.out_id', 'like', 'AM%');

                if(Auth::user()->hasRole('SM')){
                    $data_out_query->where(function($q){
                        $q->where('t_out.from_loc', Auth::User()->location_now);
                    });
                }else if(Auth::user()->hasRole('AM')){
                    $data_out_query->where(function($q){
                        $q->where('resto.kode_city', Auth::User()->location_now);
                    });
                }else if(Auth::user()->hasRole('RM')){
                    $data_out_query->where(function($q){
                        $q->where('resto.id_regional', Auth::User()->location_now);
                    });
                }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->input('start_date') . ' 00:00:00'; 
            $endDate = $request->input('end_date') . ' 23:59:59'; 
            $data_out_query->whereBetween('t_out.created_at', [$startDate, $endDate]);
        }

        // Execute the query and get the paginated result
        
        $data_in = $data_in_query->get();
        $data_out = $data_out_query->get();

        return response()->json([
            'dataIn' => $data_in,
            'dataOut' => $data_out
        ]);
    }
}
