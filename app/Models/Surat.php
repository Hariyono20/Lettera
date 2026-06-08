<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surats';

    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIKASI = 'verifikasi';
    const STATUS_DITOLAK = 'ditolak';
    const STATUS_PERSETUJUAN = 'persetujuan_pimpinan';
    const STATUS_DITOLAK_PIMPINAN = 'ditolak_pimpinan';
    const STATUS_DISPOSISI = 'disposisi';
    const STATUS_DIPROSES = 'diproses';
    const STATUS_SIAP_DIAMBIL = 'siap_diambil';
    const STATUS_SELESAI = 'selesai';

    protected $fillable = [
        'user_id',
        'jenis_surat_id',
        'data_surat',
        'status',

        // Catatan
        'catatan',
        'catatan_admin',
        'catatan_pimpinan',
        'disposisi_pimpinan', // <--- TAMBAHKAN INI AGAR BISA DISIMPAN KE DATABASE

        'file_surat',
        'tanggal_verifikasi',
        'tanggal_disetujui',
        'tanggal_selesai',
        'verified_by',
        'approved_by',
    ];

    protected $casts = [
        'data_surat' => 'array',
        'tanggal_verifikasi' => 'datetime',
        'tanggal_disetujui' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}