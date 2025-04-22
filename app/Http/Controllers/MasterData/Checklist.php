<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Checklist extends Controller
{
    public function index() 
    {
        return view("master_data.checklist");
    }
}
