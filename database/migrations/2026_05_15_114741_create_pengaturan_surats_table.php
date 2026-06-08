<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengaturan_surats', function (Blueprint $table) {
            $table->id();
            
            // 1. HEADER / KOP SURAT
            $table->string('logo_daerah')->nullable(); // Menyimpan path file logo jika diupload
            $table->string('instansi_1')->default('PEMERINTAH KOTA YOGYAKARTA');
            $table->string('instansi_2')->default('KECAMATAN UMBULHARJO');
            $table->string('instansi_3')->default('KELURAHAN ARGOMULYO');
            $table->string('alamat_instansi')->default('Jl. Argomulyo Raya No. 123 Yogyakarta 55167');
            $table->string('kontak_instansi')->default('Telp. (0274) 555xxx | Email: kel.argomulyo@jogjakota.go.id');
            
            // 2. ATRIBUT NOMOR SURAT
            $table->string('kode_pola_surat')->default('470/{NUMBER}/Kel-Argo/2026');

            // 3. FOOTER & TANDA TANGAN
            $table->enum('jumlah_tdd', ['1', '2'])->default('1'); 
            $table->string('jabatan_pejabat')->default('LURAH ARGOMULYO');
            $table->string('nama_pejabat')->default('SUGIRAN, S.IP');
            $table->string('nip_pejabat')->default('197405122002121003');
            $table->text('kalimat_penutup')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_surats');
    }
};