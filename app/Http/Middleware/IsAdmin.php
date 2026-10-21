<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login DAN role-nya adalah 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Silakan masuk bos!
        }

        // Jika bukan admin, tendang kembali ke dashboard pembeli
        return redirect('/dashboard')->with('error', 'Akses Ditolak! Anda bukan Admin.');
    }
}