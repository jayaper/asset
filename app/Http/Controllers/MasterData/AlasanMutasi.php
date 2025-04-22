<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlasanMutasi extends Controller
{
    public function index() 

    {
        $reasons = DB::table('m_reason')->select('m_reason.*')->paginate(10);
        return view("master_data.reason", ['reasons' => $reasons]);
    }
}
