<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pinjam', 25)->unique();
            $table->foreignId('buku_id')->constrained('bukus')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nim_peminjam', 20)->index();
            $table->string('nama_peminjam', 150);
            $table->date('tanggal_pinjam');
            $table->date('tanggal_jatuh_tempo');
            $table->date('tanggal_kembali')->nullable();
            $table->string('status', 15)->default('dipinjam')->index(); // dipinjam | dikembalikan
            $table->unsignedTinyInteger('jumlah_perpanjang')->default(0);
            $table->boolean('denda_lunas')->default(false);
            $table->date('tgl_bayar')->nullable();
            $table->timestamps();

            $table->index(['nim_peminjam', 'buku_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};