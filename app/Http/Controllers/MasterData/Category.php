<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Category extends Controller
{
    public function index() 
    {
        $categorys = DB::table('m_category')->select('m_category.*')->paginate(10);

        return view("master_data.category", ['categorys' => $categorys]);
    }
}
