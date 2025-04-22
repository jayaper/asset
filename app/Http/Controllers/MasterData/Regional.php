<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Regional extends Controller
{
    public function index() 
    {
        $regions = DB::table('m_region')->select('m_region.*')->paginate(10);

        return view("master_data.region", ['regions' => $regions]);
    }
}
