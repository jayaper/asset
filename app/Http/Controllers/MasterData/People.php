<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterPeople;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class People extends Controller
{
    public function index()
    {

        $peoples = DB::table('emp_employee')->select('emp_employee.*')->paginate(10);

        return view("master_data.people", ['peoples' => $peoples]);

    }

    public function NewAddDataPeople(Request $request)
    {
        $request->validate([

            'people_nickname' => 'required|string|max:255',

            'people_fullname' => 'required|string|max:255',

            'people_email' => 'required|string|max:255',

            'people_whatsapp' => 'required|string|max:255',

            'division_id' => 'required|string|max:255',

            'dept_id' => 'required|string|max:255',

            'joblevel_id' => 'required|string|max:255',

            'region_id' => 'required|string|max:255',

            'loc_id' => 'required|string|max:255',

        ]);

        try {
            $people = new MasterPeople();

            $people->people_nickname = $request->input('people_nickname');
            $people->people_fullname = $request->input('people_fullname');
            $people->people_email = $request->input('people_email');
            $people->people_whatsapp = $request->input('people_whatsapp');
            $people->division_id = $request->input('division_id');
            $people->dept_id = $request->input('dept_id');
            $people->joblevel_id = $request->input('joblevel_id');
            $people->region_id = $request->input('region_id');
            $people->loc_id = $request->input('loc_id');
            $people->create_by = Auth::user()->username;


            $maxPeopleId = MasterPeople::max('people_id');

            $people->people_id = $maxPeopleId ? $maxPeopleId + 1 : 1;

            $people->create_date = Carbon::now();
            $people->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Ditambahkan',
                'redirect_url' => '/master-data/people'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function NewUpdateDataPeople(Request $request, $id) {
        $request->validate([
            'people_nickname' => 'required|string|max:255',

            'people_fullname' => 'required|string|max:255',

            'people_email' => 'required|string|max:255',

            'people_whatsapp' => 'required|string|max:255',

            'division_id' => 'required|string|max:255',

            'dept_id' => 'required|string|max:255',

            'joblevel_id' => 'required|string|max:255',

            'region_id' => 'required|string|max:255',

            'loc_id' => 'required|string|max:255',
        ]);

        $people = MasterPeople::find($id);

        if (!$people) {
            return response()->json(['status' => 'error', 'message' => 'People not found.'], 404);
        }

        $people->people_nickname = $request->people_nickname;

        $people->people_fullname = $request->people_fullname;

        $people->people_email = $request->people_email;

        $people->people_whatsapp = $request->people_whatsapp;

        $people->division_id = $request->division_id;

        $people->dept_id = $request->dept_id;

        $people->joblevel_id = $request->joblevel_id;

        $people->region_id = $request->region_id;

        $people->loc_id = $request->loc_id;

        if ($people->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Update data katergori berhasil',
                'redirect_url' => '/master-data/people'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update People.'], 500);
        }
    }

    public function NewDeleteDataPeople($id) {
        $people = MasterPeople::find($id);

        if ($people) {
            $people->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data People Berhasil Dihapus',
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data People Gagal Terhapus'], 404);
        }
    }
}
