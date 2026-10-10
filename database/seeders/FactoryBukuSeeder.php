<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class FactoryBukuSeeder extends Seeder
{
    /**
     * Data contoh berbasis Factory + Faker:
     * 5 kategori dan 20 buku (4 buku per kategori).
     */
    public function run(): void
    {
        Kategori::factory()
            ->count(5)
            ->has(Buku::factory()->count(4), 'bukus')
            ->create();
    }
}