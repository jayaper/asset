<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TypeAsset extends Controller
{
    public function index() 

    {
        $types = DB::table('m_type')->select('m_type.*')->paginate(10);

        return view("master_data.type", ['types' => $types]);

    }
}
