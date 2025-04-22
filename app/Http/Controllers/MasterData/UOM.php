<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UOM extends Controller
{
    public function index() 

    {

        $uoms = DB::table('m_uom')->select('m_uom.*')->paginate(10);
        return view("master_data.uom", ['uoms' => $uoms]);

    }
}
