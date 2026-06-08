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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nik')) {
                $table->string('nik', 20)->nullable()->unique()->after('id');
            }

            // Kolom Username (Dibutuhkan untuk login/identitas unik)
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username', 255)->nullable()->unique()->after('nama');
            }

            if (!Schema::hasColumn('users', 'jenis_kelamin')) {
                $table->string('jenis_kelamin', 20)->nullable()->after('username');
            }

            if (!Schema::hasColumn('users', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('jenis_kelamin');
            }

            if (!Schema::hasColumn('users', 'no_wa')) {
                $table->string('no_wa', 20)->nullable()->after('email');
            }

            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('no_wa');
            }

            if (!Schema::hasColumn('users', 'foto')) {
                $table->string('foto')->nullable()->after('bio');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nik',
                'username',
                'jenis_kelamin',
                'tanggal_lahir',
                'no_wa',
                'bio',
                'foto'
            ]);
        });
    }
}; 