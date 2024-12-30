<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginPage()
    {
        return view('login'); 
    }

    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',    
        ]);

        // Ambil data input
        $credentials = $request->only('email', 'password');

        // Autentikasi
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); 
            return redirect()->route('#')->with('success', 'Login berhasil!');
        }

        // Jika gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout(); 
        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 

        return redirect('/')->with('success', 'Logout berhasil!');
    }
}
