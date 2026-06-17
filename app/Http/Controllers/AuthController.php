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
        // Aturan Validasi Ketat
        $rules = [
            'nama' => 'required|string|min:3|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email:dns|unique:users,email|max:255',
            'tanggal_lahir' => 'required|date|before_or_equal:' . now()->subYears(17)->format('Y-m-d'),
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string|min:10|max:500',
            'no_wa' => 'required|string|unique:users,no_wa|regex:/^08[0-9]{8,13}$/',
            'password' => 'required|string|min:8|confirmed',
        ];

        // Pesan Error Kustom Berbahasa Indonesia
        $messages = [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.min' => 'Nama lengkap minimal berisi 3 karakter.',
            'nama.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',

            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid (contoh: nama@domain.com).',
            'email.unique' => 'Alamat email ini sudah terdaftar di sistem kami.',

            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before_or_equal' => 'Pendaftar minimal harus berusia 17 tahun.',

            'jenis_kelamin.required' => 'Silakan pilih jenis kelamin Anda.',
            'jenis_kelamin.in' => 'Pilihan jenis kelamin tidak valid.',

            'alamat.required' => 'Alamat rumah wajib diisi lengkap.',
            'alamat.min' => 'Tuliskan alamat secara detail (minimal 10 karakter).',

            'no_wa.required' => 'Nomor WhatsApp wajib diisi.',
            'no_wa.unique' => 'Nomor WhatsApp ini sudah digunakan oleh akun lain.',
            'no_wa.regex' => 'Nomor WhatsApp harus diawali angka 08 dan berjumlah 10-15 digit angka.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password keamanan minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password di atas.',
        ];

        $r->validate($rules, $messages);

        try {
            User::create([
                'nama' => strip_tags($r->nama), // Keamanan tambahan XSS injection
                'email' => filter_var($r->email, FILTER_SANITIZE_EMAIL),
                'tanggal_lahir' => $r->tanggal_lahir,
                'jenis_kelamin' => $r->jenis_kelamin,
                'alamat' => strip_tags($r->alamat),
                'no_wa' => $r->no_wa,
                'password' => Hash::make($r->password),
                'role' => 'penduduk',
            ]);

            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan masuk menggunakan akun baru Anda.');
        } catch (\Exception $e) {
            return back()
                ->withInput($r->except('password', 'password_confirmation'))
                ->with('error', 'Terjadi gangguan pada sistem server. Silakan coba sesaat lagi.');
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