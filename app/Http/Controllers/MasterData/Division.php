<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Division extends Controller
{
    public function index() 
    {
        $divisions = DB::table('m_division')->select('m_division.*')->paginate(10);

        return view("master_data.division", ['divisions' => $divisions]);
    }
}
