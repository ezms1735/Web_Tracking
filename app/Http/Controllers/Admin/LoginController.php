<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // tampilkan halaman login
    public function index()
    {
        return view('admin.login');
    }

    // proses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email','password');

        if (Auth::attempt($credentials)) {

            if (Auth::user()->peran !== 'admin') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun ini bukan admin'
                ]);
            }

            return redirect('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah'
        ]);
    }

    // logout
    public function logout()
    {
        Auth::logout();
        return redirect('/admin/login');
    }
}
