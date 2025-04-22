<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobLevel extends Controller
{
    public function index() 
    {

        $filterJobLevels = ['Senior Manager', 'Staff', 'Manager', 'Supervisor'];

        // Query the database with the filter applied
        $joblevels = DB::table('emp_employee')
            ->select('emp_employee.Job_Level')
            ->whereIn('emp_employee.Job_Level', $filterJobLevels) // Apply the filter
            ->distinct()
            ->paginate(10);
    
        // Pass the filtered results to the view
        return view("master_data.joblevel", ['joblevels' => $joblevels]);

    }
}
