<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Layout extends Controller
{
    public function index() 
    {
        $layouts = DB::table('m_layout')->select('m_layout.*')->paginate(10);

        return view("master_data.layout", ['layouts' => $layouts]);
    }
}
