<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasis';

    protected $fillable = ['user_id', 'judul', 'pesan', 'ikon', 'url', 'dibaca'];

    protected function casts(): array
    {
        return ['dibaca' => 'boolean'];
    }

    public static function kirim(int $userId, string $judul, string $pesan, string $ikon = 'bi-bell-fill', ?string $url = null): void
    {
        static::create([
            'user_id' => $userId,
            'judul' => $judul,
            'pesan' => $pesan,
            'ikon' => $ikon,
            'url' => $url,
        ]);
    }

    public static function kirimKePeran(string $role, string $judul, string $pesan, string $ikon = 'bi-bell-fill', ?string $url = null): void
    {
        foreach (User::where('role', $role)->pluck('id') as $id) {
            static::kirim($id, $judul, $pesan, $ikon, $url);
        }
    }
}