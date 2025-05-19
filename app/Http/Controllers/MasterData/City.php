<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterCity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class City extends Controller
{
    public function index()
    {
        // $provinsies = DB::table('m_city')->select('city_id', 'provinsi')->get();
        // $provinsies = $provinsies->unique('provinsi');
        $citys = DB::table('master_city')->select('master_city.*')->paginate(25);
        $city_count = DB::table('master_city')->select('master_city.*')->count();
        return view("master_data.city", [
            'citycount' => $city_count,
            'citys' => $citys,
        ]);
    }

    public function NewAddDataCity(Request $request) {
        $request->validate([
            'city' => 'required|string|max:255',
        ]);

        try {
            $city = new MasterCity();

            $city->city = $request->input('city');
            $city->create_by = Auth::user()->username;

            $maxCityId = MasterCity::max('id');
            $city->id = $maxCityId ? $maxCityId + 1 : 1;

            $city->create_date = Carbon::now();
            $city->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menambahkan data baru',
                'redirect_url' => '/master-data/city'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }


    public function NewUpdateDataCity(Request $request, $id) {
        $request->validate([
            'city' => 'required|string|max:255',
        ]);

        $city = MasterCity::find($id);

        if (!$city) {
            return response()->json(['status' => 'error', 'message' => 'City not found.'], 404);
        }

        $city->city = $request->city;

        if ($city->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah data city',
                'redirect_url' => '/master-data/city'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update City.'], 500);
        }
    }


    public function NewDeleteDataCity($id) {
        $city = MasterCity::find($id);

        if ($city) {
            $city->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menghapus data city',
                'redirect_url' => '/master-data/city'
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data City Gagal Terhapus'], 404);
        }
    }
}
