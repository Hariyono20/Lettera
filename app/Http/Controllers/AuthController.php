<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $r)
    {
        $r->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string|max:255',
            'no_wa' => 'required|string|regex:/^[0-9]{10,15}$/',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            User::create([
                'nama' => $r->nama,
                'email' => $r->email,
                'tanggal_lahir' => $r->tanggal_lahir,
                'jenis_kelamin' => $r->jenis_kelamin,
                'alamat' => $r->alamat,
                'no_wa' => $r->no_wa,
                'password' => Hash::make($r->password),
                'role' => 'penduduk',
            ]);

            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan login.');
        } catch (\Exception $e) {
            return back()
                ->withInput($r->except('password', 'password_confirmation'))
                ->with('error', 'Terjadi kesalahan. Coba lagi.');
        }
    }

    public function login(Request $r)
    {
        // Login menggunakan email sesuai instruksi
        $r->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $r->email)->first();

        if (!$user) {
            return back()
                ->withInput($r->only('email'))
                ->with('error', 'Email tidak ditemukan!');
        }

        if (Auth::attempt($r->only('email', 'password'), $r->filled('remember'))) {
            $r->session()->regenerate();

            $user = auth()->user();

            // ROLE BASED REDIRECT
            if (in_array($user->role, ['admin', 'pegawai'])) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->role === 'pimpinan') {
                return redirect()->route('pimpinan.dashboard');
            }

            return redirect()->route('user.dashboard');
        }

        return back()
            ->withInput($r->only('email'))
            ->with('error', 'Password salah!');
    }

    public function logout(Request $r)
    {
        $userName = auth()->user()->nama;

        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah logout. Sampai jumpa, ' . $userName . '!');
    }
}