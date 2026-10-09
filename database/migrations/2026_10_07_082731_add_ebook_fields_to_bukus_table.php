<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bukus', function (Blueprint $table) {
            $table->string('file_url', 300)->nullable()->after('sinopsis');
            $table->string('sampul', 300)->nullable()->after('file_url');
            $table->unsignedSmallInteger('jumlah_halaman')->nullable()->after('sampul');
        });
    }

    public function down(): void
    {
        Schema::table('bukus', function (Blueprint $table) {
            $table->dropColumn(['file_url', 'sampul', 'jumlah_halaman']);
        });
    }
};