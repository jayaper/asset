<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterCity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class City extends Controller
{
    public function index()
    {
        $citys = DB::table('miegacoa_keluhan.master_city')->select('miegacoa_keluhan.master_city.*')->paginate(25);
        $city_count = DB::table('miegacoa_keluhan.master_city')->select('miegacoa_keluhan.master_city.*')->count();
        return view("master_data.city", [
            'citycount' => $city_count,
            'citys' => $citys,
        ]);
    }
}
