<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Resto extends Controller
{
    public function index()
    {
        $cities = DB::table('master_city')->select('id', 'city')->get();

        $regions = DB::table('m_region')->select('region_id', 'region_name')->get();

        $countresto = DB::table('miegacoa_keluhan.master_resto')
            ->select('miegacoa_keluhan.master_resto.*')
            ->count();

        $datas = DB::table('miegacoa_keluhan.master_resto AS a')
            ->select(
                'a.*',
                'b.city AS nama_city',
                'c.region_name AS nama_regional',
                
            )
            ->leftjoin('master_city AS b', 'b.id', '=', 'a.kode_city')
            ->leftjoin('m_region AS c', 'c.region_id', '=', 'a.id_regional')
            ->paginate(25);
        
        return view('master_data.resto', compact('datas', 'countresto', 'cities', 'regions'));
    }

    public function addResto(Request $request){

        $request->validate([
            'kode_resto' => 'required',
            'resto' => 'required',
            'id_regional' => 'required',
            'kode_city' => 'required',
            'kom_resto' => 'required',
            'store_code' => 'required',
            'name_store_street' => 'required'
        ]);

        $cities = DB::table('master_city')->where('id', $request->kode_city)->first();
        $nama_city = $cities->city;

        $regions = DB::table('m_region')->where('region_id', $request->id_regional)->first();
        $nama_region = $regions->region_name;

        $resto = DB::table('miegacoa_keluhan.master_resto')
                ->insert([
                    'kode_resto' => $request->kode_resto,
                    'resto' => $request->resto,
                    'kode_city' => $request->kode_city,
                    'city' => $nama_city,
                    'kom_resto' => $request->kom_resto,
                    'rm' => $nama_region,
                    'id_regional' => $request->id_regional,
                    'store_code' => $request->store_code,
                    'name_store_street' => $request->name_store_street
                ]);
        if($resto){
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menambah data Resto!',
                'redirect_url' => '/master-data/resto'
            ]);
        }else{
            return response()->json(['status' => 'error', 'message' => 'Failed to update Resto.'], 500);
        }

    }

    public function updateResto(Request $request, $id){

        $request->validate([
            'kode_resto' => 'required',
            'resto' => 'required',
            'id_regional' => 'required',
            'kode_city' => 'required',
            'store_code' => 'nullable',
            'name_store_street' => 'required'
        ]);

        $resto = DB::table('miegacoa_keluhan.master_resto')
                ->where('id', $id)
                ->update([
                    'kode_resto' => $request->kode_resto,
                    'resto' => $request->resto,
                    'id_regional' => $request->id_regional,
                    'kode_city' => $request->kode_city,
                    'store_code' => $request->store_code,
                    'name_store_street' => $request->name_store_street
                ]);
        if($resto){
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah data Resto',
                'redirect_url' => '/master-data/resto'
            ]);
        }else{
            return response()->json(['status' => 'error', 'message' => 'Failed to update Resto.'], 500);
        }

    }

    public function getCities()
    {

        $cities = DB::table('master_city')->select('id', 'city')->get();
        return response()->json($cities);

    }

    public function getRegions()
    {

        $cities = DB::table('m_region')->select('region_id', 'region_name')->get();
        return response()->json($cities);

    }
}
