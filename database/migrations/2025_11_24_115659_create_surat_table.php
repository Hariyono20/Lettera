<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surats', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | RELASI
            |--------------------------------------------------------------------------
            */

            // User/pemohon
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Jenis surat
            $table->foreignId('jenis_surat_id')
                ->constrained('jenis_surats')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | DATA SURAT
            |--------------------------------------------------------------------------
            */

            // Data hasil input form dinamis
            $table->json('data_surat');

            /*
            |--------------------------------------------------------------------------
            | WORKFLOW STATUS
            |--------------------------------------------------------------------------
            */

            /*
            Status:
            - pending
            - verifikasi
            - ditolak
            - persetujuan_pimpinan
            - ditolak_pimpinan
            - disposisi
            - diproses
            - siap_diambil
            - selesai
            */

            $table->string('status')
                ->default('pending');

            /*
            |--------------------------------------------------------------------------
            | CATATAN
            |--------------------------------------------------------------------------
            */

            // Catatan umum user
            $table->text('catatan')
                ->nullable();

            // Catatan admin
            $table->text('catatan_admin')
                ->nullable();

            // Catatan pimpinan (Alasan Penolakan)
            $table->text('catatan_pimpinan')
                ->nullable();

            // Instruksi/Catatan Disposisi Pimpinan (Saat ACC)
            $table->text('disposisi_pimpinan')
                ->nullable(); // <--- MASUKKAN DI SINI AGAR STRUKTUR TABEL RAPI

            /*
            |--------------------------------------------------------------------------
            | FILE SURAT
            |--------------------------------------------------------------------------
            */

            // File PDF final
            $table->string('file_surat')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | TRACKING TANGGAL
            |--------------------------------------------------------------------------
            */

            // Diverifikasi admin
            $table->timestamp('tanggal_verifikasi')
                ->nullable();

            // Disetujui pimpinan
            $table->timestamp('tanggal_disetujui')
                ->nullable();

            // Surat selesai
            $table->timestamp('tanggal_selesai')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | PETUGAS
            |--------------------------------------------------------------------------
            */

            // Admin verifier
            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Pimpinan approver
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};