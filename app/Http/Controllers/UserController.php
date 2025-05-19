<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\Master\MasterUser;

use Carbon\Carbon;

use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;

use Spatie\Permission\Models\Permission;



class UserController extends Controller

{
    public function Index()
    {
        
        $users = DB::table('m_user')
            ->select('m_user.*', 'miegacoa_keluhan.master_resto.name_store_street', 'miegacoa_keluhan.master_resto.id as resto_id')
            ->leftJoin('miegacoa_keluhan.master_resto', 'miegacoa_keluhan.master_resto.id', '=', 'm_user.location_now') // Left join to include null values
            ->paginate(10);
    
        $users1 = DB::table('m_user')
            ->select('m_user.*', 'miegacoa_keluhan.master_resto.name_store_street', 'miegacoa_keluhan.master_resto.id as resto_id')
            ->leftJoin('miegacoa_keluhan.master_resto', 'm_user.location_now', '=', 'miegacoa_keluhan.master_resto.id') // Left join
            ->first();
    
        $restos = DB::table('miegacoa_keluhan.master_resto')->select('id', 'name_store_street')->get();
    
        $permission = DB::table('permissions')->select('permissions.*')->get();
    
        return view("users.user", [
            'users' => $users,
            'restos' => $restos,
            'permission' => $permission,
            'users1' => $users1
        ]);
    }
    
    public function HalamanUser()
    {
        $users = DB::table('m_user')
        ->select(
            'm_user.*',
            'miegacoa_keluhan.master_resto.name_store_street',
            'miegacoa_keluhan.master_resto.id as resto_id',
            'roles.name as role_name',
            'roles.id as role_id'
        )
        ->leftJoin('miegacoa_keluhan.master_resto', 'miegacoa_keluhan.master_resto.id', '=', 'm_user.location_now')
        ->leftJoin('model_has_roles', 'm_user.id', '=', 'model_has_roles.model_id')
        ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
        ->where('model_has_roles.model_type', '=', \App\Models\Master\MasterUser::class)
        ->paginate(20);
    
        $users1 = DB::table('m_user')
            ->select('m_user.*', 'miegacoa_keluhan.master_resto.name_store_street', 'miegacoa_keluhan.master_resto.id as resto_id')
            ->leftJoin('miegacoa_keluhan.master_resto', 'm_user.location_now', '=', 'miegacoa_keluhan.master_resto.id') // Left join
            ->first();
    
        $restos = DB::table('miegacoa_keluhan.master_resto')->select('id', 'name_store_street')->get();
    
        $permission = DB::table('permissions')->select('permissions.*')->get();
        $rolesUser = Role::all();
    
        return view("users.user", [
            'rolesUser' => $rolesUser,
            'users' => $users,
            'restos' => $restos,
            'permission' => $permission,
            'users1' => $users1
        ]);
    }
    



    public function getUser()

    {

        // Mengambil semua data dari tabel m_user

        $users = MasterUser::all();

        return response()->json($users); // Mengembalikan data dalam format JSON

    }



    public function AddDataUser(Request $request)

    {

        $request->validate([
            'username' => 'required|string|max:255|unique:m_user,username',
            'password' => 'required|string',
            'email' => 'required|string|email|max:255|unique:m_user,email',
            'role' => 'required|exists:roles,id',
            'location_now' => 'nullable'
        ]);
        
        // Buat instance dari model MasterUser
        $user = new MasterUser();
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->email = $request->email;
        $user->created_at = Carbon::now();
        $user->updated_at = Carbon::now();

        if($request->role != 1){
            $user->location_now = $request->location_now;
        }

        // Simpan data user
        $user->save();
        // Ambil berdasarkan ID, bukan nama
        $role = Role::findById($request->role);

        // Lalu assign role-nya ke user
        $user->assignRole($role);

        // Redirect ke halaman user
        return redirect()->back()->with('success', 'User berhasil ditambahkan');
    }
    // Example push notification function

    private function sendPushNotification($expoPushToken, $title, $body)

    {

        $url = 'https://exp.host/--/api/v2/push/send';

        $data = [

            'to' => $expoPushToken,

            'sound' => 'default',

            'title' => $title,

            'body' => $body,

            'data' => ['UserId' => '12345']

        ];



        $options = [

            'http' => [

                'header' => "Content-type: application/json\r\n",

                'method' => 'POST',

                'content' => json_encode($data)

            ]

        ];



        $context = stream_context_create($options);

        $result = file_get_contents($url, false, $context);



        return $result;

    }



    public function updateDataUser(Request $request, $id)

    {
        // Validasi input

        $request->validate([

            'username' => 'required|string|max:255',

            'email' => 'required|string|max:255',

            'roleedit' => 'required|string|max:255'


        ]);



        // Cek apakah user dengan id yang benar ada

        $user = MasterUser::find($id); // Langsung gunakan find jika ID adalah primary key



        if (!$user) {

            return response()->json(['status' => 'error', 'message' => 'user not found.'], 404);

        }



        // Update data user

        $user->email = $request->email;

        $user->username = $request->username;

        if($request->filled('password')){
            $user->password = Hash::make($request->password);
        }

        $user->location_now = $request->location_now;

        // Ambil berdasarkan ID, bukan nama
        $role = Role::findById($request->roleedit);

        $user->syncRoles([$role]);
        

        if ($user->update()) { // Menggunakan save() yang lebih aman daripada update()

            return response()->json([

                'status' => 'success',

                'message' => 'user updated successfully.',

                'redirect_url' => '/user', // Sesuaikan dengan route index Anda

            ]);

        } else {

            return response()->json(['status' => 'error', 'message' => 'Failed to update user.'], 500);

        }

    }



    public function deleteDataUser($id)

    {

        $user = MasterUser::find($id);

        if ($user) {

            // Hapus semua role yang dimiliki user
            $user->roles()->detach();

            // Hapus user dari database
            $user->delete();
            
            return response()->json([

                'status' => 'success', 

                'message' => 'User Deleted Successfully!',

                'redirect_url' => '/user'

            ]);

        } else {

            return response()->json(['status' => 'Error', 'message' => 'Data user Gagal Terhapus'], 404);

        }

    }

    public function userGetLocation(){
        $location = DB::table('miegacoa_keluhan.master_resto')->select('id', 'name_store_street')->get();
        return response()->json($location);
    }

    public function userGetArea(){
        $city = DB::table('miegacoa_keluhan.master_city')->select('id', 'city')->get();
        return response()->json($city);
    }

    public function userGetRegion(){
        $region = DB::table('miegacoa_keluhan.master_regional')->select('region_id', 'region_name')->get();
        return response()->json($region);
    }




    public function details($UserId)

    {

        $user = MasterUser::where('id', $UserId)->first();



        if (!$user) {

            abort(404, 'user not found');

        }



        return view('user.details', ['asset' => $user]);

    }


    public function dashboard() {
        return view('User.user_dashboard');
    }

    public function userPermission() {
        
        // Ambil semua permissions
        $permissions = $permissions = Permission::orderBy('id', 'desc')->get();        ;
        $rolesUser = Role::all();

        // Inisialisasi array untuk menyimpan data permission dan roles
        $permissionRoles = [];

        // Loop melalui setiap permission dan ambil roles yang memiliki permission tersebut
        foreach ($permissions as $permission) {
            $roles_id = $permission->roles->pluck('id')->toArray();
            $roles_name = $permission->roles->pluck('name')->toArray();
            $permissionRoles[] = [
                'permission' => $permission,
                'roles_id' => $roles_id,
                'roles_name' => $roles_name
            ];
        }

        // Kirim data ke view
        return view('users.permission', compact('permissionRoles', 'rolesUser'));
    }

    public function addPermission(Request $request){
        // Validasi form
        $request->validate([
            'permission_name' => 'required|string|unique:permissions,name',
            'guard' => 'required|string',
            'role.*' => 'exists:roles,id', // Pastikan role yang dipilih ada
        ]);

        // Membuat permission baru
        $permission = Permission::create([
            'name' => $request->permission_name,
            'guard_name' => $request->guard
        ]);

        // Mengaitkan permission dengan roles yang dipilih
        if ($request->has('role')) {
            $roles = Role::whereIn('id', $request->role)->get();
            foreach ($roles as $role_give) {
                $role_give->givePermissionTo($permission);
            }
        }

        // Redirect atau memberikan respons sesuai kebutuhan
        return redirect()->back()->with('success', 'Permission berhasil ditambahkan dan dihubungkan ke role.');
    }

    public function updatePermission(Request $request, $id)
    {
        // Validasi input form
        $validated = $request->validate([
            'permission_name' => 'required|string|max:255',
            'guard' => 'required|string|max:255',
            'role' => 'array',  // Array role yang akan disinkronkan dengan permission
        ]);

        // Cari permission berdasarkan ID
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['message' => 'Permission not found!'], 404);
        }

        // Update nama permission dan guard jika ada
        $permission->name = $validated['permission_name'];
        $permission->guard_name = $validated['guard']; // Pastikan guard_name sesuai dengan kebutuhan aplikasi Anda
        $permission->save();

        // Sinkronkan role yang dipilih dengan permission (menambahkan hubungan antara permission dan role)
        if (isset($validated['role'])) {
            $permission->roles()->sync($validated['role']);  // Sync dengan role yang dipilih
        }

        return response()->json([
            'message' => 'Permission updated successfully!'
        ]);
    }


    public function userRole() {
        
        // Ambil semua permissions
        $roles = Role::all();

        // Kirim data ke view
        return view('users.role', compact('roles'));
    }

}

