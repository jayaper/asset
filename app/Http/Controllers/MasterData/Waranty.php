<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Waranty extends Controller
{
    public function index() 

    {

        $warrantys = DB::table('m_warranty')->select('m_warranty.*')->paginate(10);
        return view("master_data.warranty", ['warrantys' => $warrantys]);

    }
}
