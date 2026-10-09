<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PenggunaSeeder::class,
            ProdiKategoriSeeder::class,
            BukuSeeder::class,
            TransaksiSeeder::class,
        ]);
    }
}