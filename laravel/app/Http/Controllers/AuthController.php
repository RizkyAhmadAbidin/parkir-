<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //ke form login
    public function create()
    {
        return view('auth.login');
    }

    //login
    public function prosesLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {

            if (Auth::user()->status === 'nonaktif') {
                Auth::logout();
                return redirect()->back()->with('error', 'Akun Anda dinonaktifkan! Silakan hubungi Admin.');
            }

            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'ID atau Password yang dimasukkan salah.',
        ]);
    }

    //logout
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}