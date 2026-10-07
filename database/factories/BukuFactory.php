<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class BukuFactory extends Factory
{
    private array $penerbit = [
        'Erlangga', 'Gramedia Pustaka Utama', 'Andi Offset', 'Informatika Bandung',
        'Mizan', 'Salemba Empat', 'Deepublish', 'Penerbit Del Press',
        'Elex Media Komputindo', 'Pustaka Pelajar', 'Rajawali Pers', 'Graha Ilmu',
    ];

    public function definition(): array
    {
        return [
            'isbn' => fake()->unique()->isbn13(),
            'judul' => ucwords(fake()->words(rand(2, 5), true)),
            'penulis' => $this->faker->name(),
            'penerbit' => fake()->randomElement($this->penerbit),
            'tahun_terbit' => fake()->numberBetween(2005, 2025),
            'kategori_id' => Kategori::inRandomOrder()->value('id') ?? Kategori::factory(),
            'stok' => fake()->numberBetween(0, 30),
            'sinopsis' => $this->faker->paragraph(3),
        ];
    }
}