<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->unique()->after('name');
            $table->string('role', 20)->default('mahasiswa')->after('email');
            $table->string('nomor_induk', 30)->nullable();
            $table->string('nuptk', 20)->nullable();
            $table->string('nidn', 15)->nullable();
            $table->string('prodi', 80)->nullable();
            $table->string('jabatan', 60)->nullable();
            $table->string('golongan', 20)->nullable();
            $table->string('status_kerja', 40)->nullable();
            $table->string('telepon', 20)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username', 'role', 'nomor_induk', 'nuptk', 'nidn',
                'prodi', 'jabatan', 'golongan', 'status_kerja', 'telepon',
            ]);
        });
    }
};