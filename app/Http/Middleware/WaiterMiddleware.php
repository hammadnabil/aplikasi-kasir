<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaiterMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Pastikan pengguna sudah login dan memiliki peran waiter (role_id == 3, misalnya)
        if (Auth::check() && Auth::user()->role->name === 'waiter') {
            return $next($request);
        }

        // Jika bukan waiter, redirect ke dashboard
        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
