<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Control extends Controller
{
    public function index() 
    {
        $controls = DB::table('m_control')->select('m_control.*')->paginate(10);

        return view("master_data.control", ['controls' => $controls]);
    }
}
