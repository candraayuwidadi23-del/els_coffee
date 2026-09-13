<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Cek role user yang login
        $role = Auth::user()->role->name;

        // Arahkan ke halaman yang sesuai dengan rolenya
        if ($role === 'kasir') {
            return redirect()->route('pos.index'); // Kasir langsung ke POS
        } 
        
        // Admin dan Pimpinan diarahkan ke Dashboard
        return redirect()->route('dashboard'); 
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
