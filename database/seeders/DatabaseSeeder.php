<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PenggunaSeeder::class,
            PustakawanUjiSeeder::class,
            ProdiKategoriSeeder::class,
            BukuSeeder::class,
            FactoryBukuSeeder::class,
            EbookSeeder::class,
            BukuUjiSeeder::class,
            TransaksiSeeder::class,
        ]);
    }
}