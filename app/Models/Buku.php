<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buku extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn', 'judul', 'penulis', 'penerbit', 'tahun_terbit', 'kategori_id',
        'stok', 'sinopsis', 'file_url', 'sampul', 'jumlah_halaman',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function prodis(): BelongsToMany
    {
        return $this->belongsToMany(Prodi::class, 'buku_prodi');
    }

    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }
}