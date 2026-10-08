<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Usulan extends Model
{
    protected $table = 'usulans';

    protected $fillable = ['user_id', 'judul', 'penulis', 'penerbit', 'alasan', 'status', 'catatan'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}