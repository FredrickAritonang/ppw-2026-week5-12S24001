<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prodis', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();
            $table->string('nama', 100);
            $table->string('jenjang', 5);
            $table->timestamps();
        });

        Schema::create('buku_prodi', function (Blueprint $table) {
            $table->foreignId('buku_id')->constrained('bukus')->cascadeOnDelete();
            $table->foreignId('prodi_id')->constrained('prodis')->cascadeOnDelete();
            $table->primary(['buku_id', 'prodi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_prodi');
        Schema::dropIfExists('prodis');
    }
};