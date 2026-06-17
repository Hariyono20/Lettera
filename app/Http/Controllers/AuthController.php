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
            'nik' => 'required|digits:16|unique:users,nik', // Validasi NIK wajib 16 digit angka
            'email' => 'required|email|unique:users,email|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'no_wa' => 'required|string|regex:/^[0-9]{10,15}$/', // Validasi nomor WA 10-15 digit
            'password' => 'required|string|min:8|confirmed', // Password minimal 8 karakter
        ], [
            // Pesan Error Kustom Bahasa Indonesia yang Detail
            'nama.required' => 'Nama lengkap wajib diisi sesuai KTP.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus tepat berisikan 16 digit angka.',
            'nik.unique' => 'NIK ini sudah terdaftar dalam sistem.',
            'email.required' => 'Alamat email aktif wajib diisi.',
            'email.email' => 'Format alamat email tidak valid (contoh: nama@email.com).',
            'email.unique' => 'Alamat email ini sudah digunakan.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before' => 'Tanggal lahir tidak masuk akal (harus sebelum hari ini).',
            'jenis_kelamin.required' => 'Silakan pilih jenis kelamin Anda.',
            'alamat.required' => 'Alamat rumah lengkap wajib diisi.',
            'no_wa.required' => 'Nomor WhatsApp wajib diisi.',
            'no_wa.regex' => 'Nomor WhatsApp harus berupa angka dengan panjang 10 sampai 15 digit.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter demi keamanan data Anda.',
            'password.confirmed' => 'Konfirmasi password yang Anda masukkan tidak cocok.',
        ]);

        try {
            User::create([
                'nama' => $r->nama,
                'nik' => $r->nik,
                'email' => $r->email,
                'tanggal_lahir' => $r->tanggal_lahir,
                'jenis_kelamin' => $r->jenis_kelamin,
                'alamat' => $r->alamat,
                'no_wa' => $r->no_wa,
                'password' => Hash::make($r->password),
                'role' => 'penduduk',
            ]);

            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan masuk menggunakan akun Anda.');
        } catch (\Exception $e) {
            return back()
                ->withInput($r->except('password', 'password_confirmation'))
                ->with('error', 'Terjadi masalah pada sistem. Silakan coba beberapa saat lagi.');
        }
    }

    public function login(Request $r)
    {
        $r->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email salah.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('email', $r->email)->first();

        if (!$user) {
            return back()
                ->withInput($r->only('email'))
                ->with('error', 'Email tidak terdaftar!');
        }

        if (Auth::attempt($r->only('email', 'password'), $r->filled('remember'))) {
            $r->session()->regenerate();

            $user = auth()->user();

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
            ->with('error', 'Kata sandi/Password salah!');
    }

    public function logout(Request $r)
    {
        $userName = auth()->user()->nama;

        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah keluar. Sampai jumpa kembali, ' . $userName . '!');
    }
}