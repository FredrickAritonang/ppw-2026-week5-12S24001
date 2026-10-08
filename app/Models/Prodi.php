<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Prodi extends Model
{
    protected $fillable = ['kode', 'nama', 'jenjang'];

    public function bukus(): BelongsToMany
    {
        return $this->belongsToMany(Buku::class, 'buku_prodi');
    }
}