<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Repair extends Controller
{
    public function index() 
    {
        $repairs = DB::table('m_repair')->select('m_repair.*')->paginate(10);

        return view("master_data.repair", ['repairs' => $repairs]);
    }
}
