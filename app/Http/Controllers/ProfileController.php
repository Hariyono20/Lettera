<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('layouts.profil', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Konversi input form ("laki-laki"/"perempuan") menjadi inisial ("L"/"P") agar lolos CHECK constraint
        if ($request->filled('jenis_kelamin')) {
            $jkInput = strtolower($request->jenis_kelamin);
            $jkInisial = match ($jkInput) {
                'laki-laki', 'l' => 'L',
                'perempuan', 'p' => 'P',
                default => null
            };

            $request->merge([
                'jenis_kelamin' => $jkInisial
            ]);
        }

   
        $validated = $request->validate([
            'nik'            => 'required|numeric|digits:16|unique:users,nik,' . $user->id,
            'nama'           => 'required|string|min:3|max:255|regex:/^[a-zA-Z\s\`,.]*$/',
            'jenis_kelamin'  => 'required|in:L,P',
            'tanggal_lahir'  => 'required|date|before:today',

            // 🔥 UBAH KODE USERNAME MENJADI SEPERTI INI:
            'username'       => 'nullable|string|alpha_num|min:4|max:255|unique:users,username,' . $user->id,

            'email'          => 'required|email|max:255|unique:users,email,' . $user->id,
            'no_wa'          => 'required|numeric|digits_between:10,15',
            'alamat'         => 'required|string|min:10|max:255',
            'bio'            => 'nullable|string|max:500',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password'       => 'nullable|string|min:6|confirmed',
        ], [
            // Pesan error kustom untuk username (pesan 'required' dihapus karena sudah tidak wajib)
            'username.alpha_num'     => 'Username hanya boleh berisi huruf dan angka tanpa spasi.',
            'username.min'           => 'Jika diisi, username minimal terdiri dari 4 karakter.',
            'username.unique'        => 'Username ini sudah digunakan orang lain.',

            // ... pesan kustom lainnya tetap biarkan seperti sebelumnya
            'nik.required'           => 'NIK wajib diisi.',
            'nik.numeric'            => 'NIK harus berupa angka.',
            'nik.digits'             => 'NIK harus terdiri dari tepat 16 digit.',
            'nik.unique'             => 'NIK ini sudah terdaftar di sistem.',
            'nama.required'          => 'Nama lengkap wajib diisi.',
            'nama.min'               => 'Nama lengkap minimal 3 karakter.',
            'nama.regex'             => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
            'jenis_kelamin.required' => 'Silakan pilih jenis kelamin Anda.',
            'jenis_kelamin.in'       => 'Pilihan jenis kelamin tidak valid.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date'     => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.before'   => 'Tanggal lahir tidak logis (harus sebelum hari ini).',
            'email.required'         => 'Alamat email wajib diisi.',
            'email.email'            => 'Format alamat email tidak valid.',
            'email.unique'           => 'Email ini sudah terdaftar di sistem.',
            'no_wa.required'         => 'Nomor WhatsApp wajib diisi untuk keperluan koordinasi.',
            'no_wa.numeric'          => 'Nomor WhatsApp harus berupa angka.',
            'no_wa.digits_between'   => 'Nomor WhatsApp tidak valid (harus antara 10 sampai 15 digit).',
            'alamat.required'        => 'Alamat lengkap rumah wajib diisi.',
            'alamat.min'             => 'Tuliskan alamat secara lengkap (minimal 10 karakter).',
            'foto.image'             => 'Berkas yang diunggah harus berupa gambar.',
            'foto.mimes'             => 'Format gambar yang diperbolehkan hanya JPG, JPEG, dan PNG.',
            'foto.max'               => 'Ukuran foto maksimal adalah 2 MB.',
            'password.min'           => 'Sandi baru minimal terdiri dari 6 karakter.',
            'password.confirmed'     => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        // 3. Ambil data hasil validasi
        $userData = $validated;
        unset($userData['password'], $userData['password_confirmation'], $userData['foto']);

        $user->fill($userData);

        // 4. Update file foto profil jika ada
        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists('foto/' . $user->foto)) {
                Storage::disk('public')->delete('foto/' . $user->foto);
            }

            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('foto', $filename, 'public');
            $user->foto = $filename;
        }

        // 5. Update password baru jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 6. Simpan perubahan ke database SQLite
        $user->save();

        return redirect()->route('profil.saya')->with('success', 'Profil berhasil diperbarui!');
    }
}