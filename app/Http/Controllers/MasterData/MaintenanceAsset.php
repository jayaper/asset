<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenanceAsset extends Controller
{
    public function index() 
    {
        $typesMaintenance = DB::table('m_mtc_type')->select('m_mtc_type.*')->paginate(10);

        return view("master_data.tipe_maintenance", ['typesMaintenance' => $typesMaintenance]);
    }
}
