<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
public function handle(Request $request, Closure $next, ...$roles): Response
{
    // Jika belum login atau role akun tidak masuk dalam daftar yang diizinkan
    if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
        
        // PENCEGAHAN KHUSUS KASIR: Kembalikan paksa ke halaman Aplikasi Kasir jika tersesat
        if (Auth::user() && Auth::user()->role === 'Kasir') {
            return redirect()->route('sales.create')->with('error', '⚠️ Akun Kasir hanya diizinkan menggunakan aplikasi Kasir.');
        }

        // Untuk user selain kasir (misal manajer tersesat ke area super admin) lempat ke dashboard
        return redirect()->route('dashboard')->with('error', '⚠️ Maaf, posisi akun Anda tidak memiliki akses.');
    }

    return $next($request);
}
}