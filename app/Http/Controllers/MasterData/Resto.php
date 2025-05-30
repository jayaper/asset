<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Resto extends Controller
{
    public function index()
    {
        $cities = DB::table('miegacoa_keluhan.master_city')->select('id', 'city')->get();

        $regions = DB::table('miegacoa_keluhan.master_regional')->select('id', 'regional')->get();

        $countresto = DB::table('miegacoa_keluhan.master_resto')
            ->select('miegacoa_keluhan.master_resto.*')
            ->count();

        $datas = DB::table('miegacoa_keluhan.master_resto AS a')
            ->select(
                'a.*',
                'b.city AS nama_city',
                'c.regional AS nama_regional',
                
            )
            ->leftjoin('miegacoa_keluhan.master_city AS b', 'b.id', '=', 'a.kode_city')
            ->leftjoin('miegacoa_keluhan.master_regional AS c', 'c.id', '=', 'a.id_regional')
            ->orderBy('kode_city', 'ASC')
            ->get();
        
        return view('master_data.resto', compact('datas', 'countresto', 'cities', 'regions'));
    }

    public function getCities()
    {

        $cities = DB::table('miegacoa_keluhan.master_city')->select('id', 'city')->get();
        return response()->json($cities);

    }

    public function getRegions()
    {

        $cities = DB::table('miegacoa_keluhan.master_regional')->select('id', 'regional')->get();
        return response()->json($cities);

    }
}
