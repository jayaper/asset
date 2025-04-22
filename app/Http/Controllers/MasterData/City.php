<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class City extends Controller
{
    public function index() 

    {
        // $provinsies = DB::table('m_city')->select('city_id', 'provinsi')->get();
        // $provinsies = $provinsies->unique('provinsi');
        $citys = DB::table('master_city')->select('master_city.*')->paginate(10);
        return view("master_data.city", [
            'citys' => $citys, 
        ]);

    }
}
