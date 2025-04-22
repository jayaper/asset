<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Priority extends Controller
{
    public function index() 
    {
        $prioritys = DB::table('m_priority')->select('m_priority.*')->paginate(10);

        return view("master_data.priority", ['prioritys' => $prioritys]);
    }
}
