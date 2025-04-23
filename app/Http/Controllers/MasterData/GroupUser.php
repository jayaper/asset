<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterGroupUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupUser extends Controller
{
    public function index()
    {
        $groupusers = DB::table('m_groupuser')->select('m_groupuser.*')->paginate(10);

        return view("master_data.groupuser", ['groupusers' => $groupusers]);
    }

    public function NewAddDataGroupUser(Request $request)
    {
        $request->validate([
           'group_name' => 'required|string|max:255',
            'group_roles' => 'required|string|max:255',
        ]);

        try {
            $groupUser = new MasterGroupUser();
            $groupUser->group_name = $request->input('group_name');
            $groupUser->group_roles = $request->input('group_roles');
            $groupUser->create_by = Auth::user()->username;

            $maxGroupUserId = MasterGroupUser::max('group_id');
            $groupUser->group_id = $maxGroupUserId ? $maxGroupUserId + 1 : 1;

            $groupUser->create_date = Carbon::now();
            $groupUser->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Disimpan',
                'redirect_url' => '/master-data/group-user'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function NewUpdateDataGroupUser(Request $request, $id) {
        $request->validate([
            'group_name' => 'required|string|max:255',
            'group_roles' => 'required|string|max:255',
        ]);

        $groupUser = MasterGroupUser::find($id);

        if (!$groupUser) {
            return response()->json(['status' => 'error', 'message' => 'Group User not found.'], 404);
        }

        $groupUser->group_name = $request->group_name;
        $groupUser->group_roles = $request->group_roles;

        if ($groupUser->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Update data katergori berhasil',
                'redirect_url' => '/master-data/group-user'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update Group User.'], 500);
        }
    }


    public function NewDeleteDataGroupUser($id)
    {
        $groupUser = MasterGroupUser::find($id);

        if ($groupUser) {
            $groupUser->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Group User Berhasil Dihapus',
                'redirect_url' => '/master-data/group-user'
            ]);
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Data Group User Gagal Terhapus'], 404);
        }
    }
}
