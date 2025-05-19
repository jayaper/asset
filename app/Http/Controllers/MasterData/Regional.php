<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterRegion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Regional extends Controller
{
    public function index()
    {
        $regions = DB::table('miegacoa_keluhan.master_regional')->select('miegacoa_keluhan.master_regional.*')->paginate(15);
        $regioncount = DB::table('miegacoa_keluhan.master_regional')->select('miegacoa_keluhan.master_regional.*')->count();

        return view("master_data.region", [
            'regions' => $regions,
            'regioncount' => $regioncount
        ]);
    }
}
