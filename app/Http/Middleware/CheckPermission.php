<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        // Cek jika izin 'dashboard' atau lainnya ada di dalam array database
        $userPermissions = $user->permissions ?? [];
        
        if (in_array($permission, $userPermissions)) {
            return $next($request);
        }

        abort(403, 'AKSES DITOLAK: Anda tidak diizinkan mengakses halaman/fitur ini.');
    }
}