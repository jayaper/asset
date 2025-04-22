<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubCategory extends Controller
{
    public function index() 
    {
        $subcategorys = DB::table('m_subcategory')->select('m_subcategory.*')->paginate(10);
        return view("master_data.subcategory", ['subcategorys' => $subcategorys]);
    }
}
