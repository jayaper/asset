<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Resto extends Controller
{
    public function index()
    {
        $datas = DB::table('master_resto_v2')
            ->select('master_resto_v2.*')
            ->paginate(10);
        return view('master_data.resto', compact('datas'));
    }
}
