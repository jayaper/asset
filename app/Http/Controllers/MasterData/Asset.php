<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Asset extends Controller
{
    public function HalamanAssets() 
    {

        $priorities = DB::table('m_priority')->select('priority_id', 'priority_name')->get();

        $categories = DB::table('m_category')->select('cat_id', 'cat_name')->get();

        $tipies = DB::table('m_type')->select('type_id', 'type_name')->get();

        $uomies = DB::table('m_uom')->select('uom_id', 'uom_name')->get();

        

        $assets = DB::table('m_assets')
        ->select('m_assets.*', 'm_priority.priority_name', 'm_category.*', 'm_type.*', 'm_uom.*')
        ->leftjoin('m_priority', 'm_assets.priority_id', '=', 'm_priority.priority_id')
        ->leftjoin('m_category', 'm_assets.cat_id', '=', 'm_category.cat_id')
        ->leftjoin('m_type', 'm_assets.type_id', '=', 'm_type.type_id')
        ->leftjoin('m_uom', 'm_assets.uom_id', '=', 'm_uom.uom_id')
        ->where('m_assets.type_id', 1) // Filter for equipment
        ->get();



        return view("master_data.asset", [

            'priorities' => $priorities,

            'categories' => $categories,

            'tipies' => $tipies,

            'uomies' => $uomies,

            'assets' => $assets

        ]);

    }
}
