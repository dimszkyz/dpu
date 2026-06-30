<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman form login
     */
    public function showLoginForm()
    {
        // Ganti 'welcome' dengan nama view yang berisi form login Anda (misal: 'auth.login' jika ada)
        return view('auth.login'); 
    }

    /**
     * Memproses data login
     */
    public function login(Request $request)
    {
        // 1. Validasi input form
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Cek kecocokan email dan password di database
        if (Auth::attempt($credentials)) {
            // Jika berhasil, perbarui sesi keamanan
            $request->session()->regenerate();
            
            // 3. Cek Role: Arahkan Admin ke Panel Admin, User biasa ke Dashboard
            if (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin') {
                return redirect()->intended('admin/dashboard');
            }

            return redirect()->intended('dashboard');
        }

        // 4. Jika login gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Memproses proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Hapus semua data sesi keamanan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan kembali ke halaman utama (welcome)
        return redirect('/');
    }
}