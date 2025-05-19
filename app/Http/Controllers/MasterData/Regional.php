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
        $regions = DB::table('m_region')->select('m_region.*')->paginate(15);
        $regioncount = DB::table('m_region')->select('m_region.*')->count();

        return view("master_data.region", [
            'regions' => $regions,
            'regioncount' => $regioncount
        ]);
    }

    public function NewAddDataRegion(Request $request) {
        $request->validate([
            'region_code' => 'required|string|max:255',
            'region_name' => 'required|string|max:255',
        ]);

        try {
            $region = new MasterRegion();
            $region->region_code = $request->input('region_code');
            $region->region_name = $request->input('region_name');
            $region->create_by = Auth::user()->username;

            $maxRegionId = MasterRegion::max('region_id');
            $region->region_id = $maxRegionId ? $maxRegionId + 1 : 1;

            $region->create_date = Carbon::now();
            $region->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menambahkan data baru',
                'redirect_url' => '/master-data/regional'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function NewUpdateDataRegion(Request $request, $id) {
        $request->validate([
           'region_code' => 'required|string|max:255',
           'region_name' => 'required|string|max:255',
        ]);

        $region = MasterRegion::find($id);

        if (!$region) {
            return response()->json(['status' => 'error', 'message' => 'Region not found.'], 404);
        }

        $region->region_code = $request->region_code;
        $region->region_name = $request->region_name;

        if ($region->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah data region',
                'redirect_url' => '/master-data/regional'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Region.'], 500);
        }
    }

    public function NewDeleteDataRegion($id) {
        $region = MasterRegion::find($id);

        if ($region) {
            $region->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menghapus data region',
                'redirect_url' => '/master-data/regional'
                ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Region Gagal Terhapus'], 404);
        }
    }
}
