<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('is_admin') || !Auth::check()) {
            Auth::logout();
            session()->forget('is_admin');
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}