<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Prefix "FK-" agar tidak bentrok dengan kode kategori dari ProdiKategoriSeeder
            'kode_kategori' => 'FK-' . fake()->unique()->numerify('###'),
            'nama_kategori' => ucfirst(fake()->unique()->words(2, true)),
            'bidang' => fake()->randomElement(['Teknologi', 'Ekonomi', 'Kesehatan', 'Lingkungan', 'Hukum']),
        ];
    }
}