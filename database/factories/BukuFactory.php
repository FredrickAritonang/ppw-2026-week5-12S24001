<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class BukuFactory extends Factory
{
    public function definition(): array
    {
        return [
            'isbn' => fake()->unique()->isbn13(),
            'judul' => ucwords(fake()->words(rand(2, 5), true)),
            'penulis' => fake()->name(),
            'penerbit' => fake()->company(),
            'tahun_terbit' => fake()->numberBetween(2000, 2025),
            'kategori_id' => Kategori::inRandomOrder()->value('id') ?? Kategori::factory(),
            'stok' => fake()->numberBetween(0, 30),
            'sinopsis' => fake()->paragraph(3),
        ];
    }
}