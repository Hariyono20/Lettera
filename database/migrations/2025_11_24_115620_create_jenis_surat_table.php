<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_surats', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | INFORMASI SURAT
            |--------------------------------------------------------------------------
            */

            // Nama surat
            $table->string('nama_surat');

            // Kategori surat
            $table->string('jenis')->nullable();

            // Deskripsi singkat
            $table->text('deskripsi')->nullable();

            /*
            |--------------------------------------------------------------------------
            | TEMPLATE SURAT
            |--------------------------------------------------------------------------
            */

            // Template text surat
            // contoh:
            // Nama : {{nama}}
            $table->longText('template_surat')->nullable();

            /*
            |--------------------------------------------------------------------------
            | FORM DINAMIS
            |--------------------------------------------------------------------------
            */

            // JSON dynamic fields
            $table->json('fields')->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            // aktif / nonaktif
            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_surats');
    }
};