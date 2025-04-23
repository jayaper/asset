<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TypeAsset extends Controller
{
    public function index()

    {
        $types = DB::table('m_type')->select('m_type.*')->paginate(10);

        return view("master_data.type", ['types' => $types]);

    }

    public function NewAddDataAssetType(Request $request)  {
        $request->validate([
            'type_code' => 'required|string|max:255',
            'type_name' => 'required|string|max:255',
        ]);

        try {
            $type = new MasterType();
            $type->type_code = $request->input('type_code');
            $type->type_name = $request->input('type_name');
            $type->create_by = Auth::user()->username;

            $maxTypeId = MasterType::max('type_id');
            $type->type_id = $maxTypeId ? $maxTypeId + 1 : 1;

            $type->create_date = Carbon::now();
            $type->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menambahkan data baru',
                'redirect_url' => '/master-data/type-asset'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function NewUpdateDataAssetType(Request $request, $id) {
    $request->validate([
        'type_code' => 'required|string|max:255',
        'type_name' => 'required|string|max:255',
    ]);
        $type = MasterType::find($id);

        if (!$type) {
            return response()->json(['status' => 'error', 'message' => 'Type not found.'], 404);
        }

        $type->type_code = $request->type_code;
        $type->type_name = $request->type_name;

        if ($type->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah data type',
                'redirect_url' => '/master-data/type-asset'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Type.'], 500);
        }
    }

    public function NewDeleteDataAssetType($id) {
        $type = MasterType::find($id);

        if ($type) {
            $type->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menghapus data type',
                'redirect_url' => '/master-data/type-asset'
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Type Gagal Terhapus'], 404);
        }
    }
}



