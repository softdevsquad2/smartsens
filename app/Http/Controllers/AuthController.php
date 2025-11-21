<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            // dd($user->role);
            // Redirect berdasarkan role
            switch ($user->role) {
                case 'admin':
                    return redirect('/admin/dashboard');
                case 'guru':
                    return redirect('/guru/dashboard');
                case 'uks':
                    return redirect('/uks/dashboard');
                case 'siswa':
                    return redirect('/siswa/dashboard');
                case 'operator':
                    return redirect('/operator/dashboard');
                case 'ketua':
                    return redirect('/siswa/dashboard');
                case 'piket':
                    return redirect('/piket/dashboard');
                case 'toolman':
                    return redirect('/toolman/dashboard');
                default:
                    return redirect('/');
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah',
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }
}
