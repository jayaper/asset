<?php



namespace App\Http\Controllers\Auth;



use Illuminate\Http\Request;

use App\Models\User;

use App\Http\Controllers\Controller;

use App\Http\Controllers\Controller\Auth\Redirect;

use App\Http\Controllers\Auth\View;
use App\Models\Master\MasterUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller

{

    public function IndexLogin()
    {

        return view('login');
    }



    public function showLoginForm()
    {

        return view('login');
    }



    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    
        // Retrieve user by username
        $user = MasterUser::where('username', $request->username)->first();
    
        // Cek hash password
        if ($user && is_null($user->deleted_at) && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            return redirect()->intended('/dashboard')->with('message', 'Admin login successful');
        }
    
        return redirect()->back()->with('error', 'Login Gagal, Silahkan login kembali !!');
    }

    public function logout()

    {

        Auth::logout();

        return redirect()->to('/');
    }
}
