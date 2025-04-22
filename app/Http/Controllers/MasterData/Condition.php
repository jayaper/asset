<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Condition extends Controller
{
    public function index() 
    {
        $conditions = DB::table('m_condition')->select('m_condition.*')->paginate(10);

        return view("master_data.condition", ['conditions' => $conditions]);
    }
}
