<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Brand extends Controller
{
    public function index() 
    {
        $brands = DB::table('m_brand')->select('m_brand.*')->paginate(10);

        return view("master_data.brand", ['brands' => $brands]);
    }
}
