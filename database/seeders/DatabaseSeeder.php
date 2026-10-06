<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['INF', 'Informatika'],
            ['SIS', 'Sistem Informasi'],
            ['ELK', 'Teknik Elektro'],
            ['MAT', 'Matematika'],
            ['UMM', 'Umum & Referensi'],
        ];

        foreach ($kategoris as [$kode, $nama]) {
            Kategori::create(['kode_kategori' => $kode, 'nama_kategori' => $nama]);
        }

        Buku::factory(20)->create();
    }
}