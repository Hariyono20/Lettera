<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanSurat extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_surats';

    protected $fillable = [
        'logo_daerah',
        'instansi_1',
        'instansi_2',
        'instansi_3',
        'alamat_instansi',
        'kontak_instansi',
        'kode_pola_surat',
        'jumlah_tdd',
        'jabatan_pejabat',
        'nama_pejabat',
        'nip_pejabat',
        'kalimat_penutup',
    ];
}