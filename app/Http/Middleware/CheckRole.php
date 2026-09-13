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
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Cek apakah role name user saat ini ada di dalam array $roles yang diizinkan
        if (in_array(Auth::user()->role->name, $roles)) {
            return $next($request);
        }

        // Jika tidak berhak, tolak aksesnya
        abort(403, 'AKSES DITOLAK: Halaman ini bukan untuk Role Anda.');
    }
}