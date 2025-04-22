<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class People extends Controller
{
    public function index() 
    {

        $peoples = DB::table('emp_employee')->select('emp_employee.*')->paginate(10);

        return view("master_data.people", ['peoples' => $peoples]);

    }
}
