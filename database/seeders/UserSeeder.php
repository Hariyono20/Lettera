<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 🔥 ADMIN
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'nama' => 'Admin Sistem',
                'password' => Hash::make('admin123'),
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => 'L',
                'alamat' => 'Kantor Kelurahan',
                'no_wa' => '081234567890',
                'role' => 'admin',
                'bio' => 'Administrator sistem',
                'foto' => null
            ]
        );

        // 👤 PENDUDUK
        User::updateOrCreate(
            ['email' => 'penduduk@example.com'],
            [
                'nama' => 'Penduduk Demo',
                'password' => Hash::make('penduduk123'),
                'tanggal_lahir' => '1995-05-10',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Melati No. 5',
                'no_wa' => '081298765432',
                'role' => 'penduduk',
                'bio' => 'User biasa',
                'foto' => null
            ]
        );

        // 👑 PIMPINAN
        User::updateOrCreate(
            ['email' => 'pimpinan@example.com'],
            [
                'nama' => 'Pimpinan Kelurahan',
                'password' => Hash::make('pimpinan123'),
                'tanggal_lahir' => '1985-03-15',
                'jenis_kelamin' => 'L',
                'alamat' => 'Kantor Kelurahan',
                'no_wa' => '081234567891',
                'role' => 'pimpinan',
                'bio' => 'Pimpinan instansi',
                'foto' => null
            ]
        );
    }
}