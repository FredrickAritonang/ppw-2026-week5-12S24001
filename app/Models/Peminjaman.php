<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    public const TARIF_DENDA = 1000;

    protected $table = 'peminjamans';

    protected $fillable = [
        'user_id', 'buku_id', 'tgl_pinjam', 'tgl_jatuh_tempo', 'tgl_kembali',
        'jumlah_perpanjang', 'denda_lunas', 'tgl_bayar',
    ];

    protected function casts(): array
    {
        return [
            'tgl_pinjam' => 'date',
            'tgl_jatuh_tempo' => 'date',
            'tgl_kembali' => 'date',
            'tgl_bayar' => 'date',
            'denda_lunas' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class);
    }

    public function scopeAktif(Builder $q): Builder
    {
        return $q->whereNull('tgl_kembali');
    }

    /** Pinjaman yang terlambat (sudah/belum dikembalikan) sehingga kena denda. */
    public function scopeBerdenda(Builder $q): Builder
    {
        return $q->where(function ($w) {
            $w->whereColumn('tgl_kembali', '>', 'tgl_jatuh_tempo')
              ->orWhere(fn ($x) => $x->whereNull('tgl_kembali')->where('tgl_jatuh_tempo', '<', today()));
        });
    }

    public function getHariTerlambatAttribute(): int
    {
        $akhir = $this->tgl_kembali ?? today();

        if ($akhir->lte($this->tgl_jatuh_tempo)) {
            return 0;
        }

        return (int) $this->tgl_jatuh_tempo->diffInDays($akhir, false);
    }

    public function getTotalDendaAttribute(): int
    {
        return $this->hari_terlambat * self::TARIF_DENDA;
    }

    public function getSisaHariAttribute(): int
    {
        return (int) today()->diffInDays($this->tgl_jatuh_tempo, false);
    }
}