<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Departement extends Controller
{
    public function index() 
    {
        $depts = DB::table('m_dept')->select('m_dept.*')->paginate(10);

        return view("master_data.dept", ['depts' => $depts]);
    }
}
