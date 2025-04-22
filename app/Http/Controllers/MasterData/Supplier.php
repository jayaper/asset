<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Supplier extends Controller
{
    public function index() 

    {

        $suppliers = DB::table('m_supplier')->select('m_supplier.*')->paginate(10);
        return view("master_data.supplier", ['suppliers' => $suppliers]);

    }
}
