<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Admin credentials
    private const ADMIN_EMAIL = 'admin@uperfood.com';
    private const ADMIN_PASSWORD = 'admin123';

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

        // Check for admin login
        if ($request->email === self::ADMIN_EMAIL && $request->password === self::ADMIN_PASSWORD) {
            // Store admin session
            session(['is_admin' => true]);
            Auth::loginUsingId(1); // Assuming ID 1 is reserved for admin
            return redirect('/admin/dashboard');  // Direct URL redirect
        }

        // Regular user login
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->forget('is_admin');

        return redirect('/');
    }
}