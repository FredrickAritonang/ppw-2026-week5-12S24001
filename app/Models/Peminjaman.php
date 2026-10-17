<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Peminjaman extends Model
{
    public const TARIF_DENDA = 1000;

    protected $table = 'peminjamans';

    protected $fillable = [
        'kode_pinjam', 'buku_id', 'user_id', 'nim_peminjam', 'nama_peminjam',
        'tanggal_pinjam', 'tanggal_jatuh_tempo', 'tanggal_kembali', 'status',
        'jumlah_perpanjang', 'denda_lunas', 'tgl_bayar',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_jatuh_tempo' => 'date',
            'tanggal_kembali' => 'date',
            'tgl_bayar' => 'date',
            'denda_lunas' => 'boolean',
        ];
    }

    public static function buatKode(): string
    {
        return 'PJM-'.now()->format('ymd').'-'.strtoupper(Str::random(6));
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
        return $q->where('status', 'dipinjam');
    }

    public function scopeBerdenda(Builder $q): Builder
    {
        return $q->where(function ($w) {
            $w->whereColumn('tanggal_kembali', '>', 'tanggal_jatuh_tempo')
              ->orWhere(fn ($x) => $x->where('status', 'dipinjam')->where('tanggal_jatuh_tempo', '<', today()));
        });
    }

    public function getHariTerlambatAttribute(): int
    {
        $akhir = $this->tanggal_kembali ?? today();

        if ($akhir->lte($this->tanggal_jatuh_tempo)) {
            return 0;
        }

        return (int) $this->tanggal_jatuh_tempo->diffInDays($akhir, false);
    }

    public function getTotalDendaAttribute(): int
    {
        return $this->hari_terlambat * self::TARIF_DENDA;
    }

    public function getSisaHariAttribute(): int
    {
        return (int) today()->diffInDays($this->tanggal_jatuh_tempo, false);
    }
}