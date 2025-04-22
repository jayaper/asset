<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlasanStockOpname extends Controller
{
    public function index() 
    {
        $reasonso = DB::table('m_reason_so')->select('m_reason_so.*')->paginate(10);

        return view("master_data.reasonso", ['reasonso' => $reasonso]);
    }
}
