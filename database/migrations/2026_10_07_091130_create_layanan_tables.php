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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('buku_id')->constrained('bukus')->cascadeOnDelete();
            $table->date('tgl_pinjam');
            $table->date('tgl_jatuh_tempo');
            $table->date('tgl_kembali')->nullable();
            $table->unsignedTinyInteger('jumlah_perpanjang')->default(0);
            $table->boolean('denda_lunas')->default(false);
            $table->date('tgl_bayar')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'tgl_kembali']);
        });

        Schema::create('usulans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('judul', 200);
            $table->string('penulis', 150);
            $table->string('penerbit', 150)->nullable();
            $table->text('alasan')->nullable();
            $table->string('status', 15)->default('menunggu');
            $table->string('catatan', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('judul', 120);
            $table->string('pesan', 300);
            $table->string('ikon', 40)->default('bi-bell-fill');
            $table->string('url', 200)->nullable();
            $table->boolean('dibaca')->default(false);
            $table->timestamps();
            $table->index(['user_id', 'dibaca']);
        });

        Schema::create('bebas_pustakas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status', 15)->default('diajukan');
            $table->string('catatan', 255)->nullable();
            $table->date('tgl_proses')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bebas_pustakas');
        Schema::dropIfExists('notifikasis');
        Schema::dropIfExists('usulans');
        Schema::dropIfExists('peminjamans');
    }
};