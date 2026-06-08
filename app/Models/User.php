<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nik',            // <-- Tambahkan ini
        'nama',
        'username',       // <-- Tambahkan ini
        'email',
        'password',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'foto',
        'no_wa',
        'role',
        'bio'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function surats()
    {
        return $this->hasMany(Surat::class);
    }

    public function isAdmin()
    {
        return in_array($this->role, ['admin', 'pegawai']);
    }

    public function isPimpinan()
    {
        return $this->role === 'pimpinan';
    }

    public function isPenduduk()
    {
        return $this->role === 'penduduk';
    }
}