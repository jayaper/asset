<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupUser extends Controller
{
    public function index() 
    {
        $groupusers = DB::table('m_groupuser')->select('m_groupuser.*')->paginate(10);

        return view("master_data.groupuser", ['groupusers' => $groupusers]);
    }
}
