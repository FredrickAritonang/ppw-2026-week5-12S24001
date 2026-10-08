<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BebasPustaka extends Model
{
    protected $table = 'bebas_pustakas';

    protected $fillable = ['user_id', 'status', 'catatan', 'tgl_proses'];

    protected function casts(): array
    {
        return ['tgl_proses' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}