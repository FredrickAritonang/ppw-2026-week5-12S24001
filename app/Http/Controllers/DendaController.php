<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DendaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $staf = $user->role === 'pustakawan';

        $dasar = fn () => Peminjaman::berdenda()
            ->when(! $staf, fn ($q) => $q->where('user_id', $user->id))
            ->when($staf && $request->q, fn ($q, $v) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$v}%")->orWhere('username', 'like', "%{$v}%")));

        return view('denda.index', [
            'staf' => $staf,
            'daftar' => $dasar()->with(['buku', 'user'])->latest('tanggal_jatuh_tempo')->paginate(10)->withQueryString(),
            'totalBelum' => (int) $dasar()->where('denda_lunas', false)->get()->sum('total_denda'),
            'totalLunas' => (int) $dasar()->where('denda_lunas', true)->get()->sum('total_denda'),
        ]);
    }

    public function bayar(Peminjaman $peminjaman)
    {
        $user = Auth::user();
        abort_unless($user->role === 'pustakawan' || $peminjaman->user_id === $user->id, 403);

        if (! $peminjaman->tanggal_kembali) {
            return back()->with('error', 'Kembalikan buku terlebih dahulu sebelum membayar denda.');
        }
        if ($peminjaman->denda_lunas || $peminjaman->total_denda <= 0) {
            return back()->with('error', 'Tidak ada denda yang perlu dibayar.');
        }

        $peminjaman->update(['denda_lunas' => true, 'tgl_bayar' => today()]);

        Notifikasi::kirim($peminjaman->user_id, 'Denda lunas', 'Denda Rp '.number_format($peminjaman->total_denda, 0, ',', '.')." untuk \"{$peminjaman->buku->judul}\" telah dilunasi.", 'bi-cash-coin', route('denda.index', absolute: false));

        return back()->with('success', 'Pembayaran denda berhasil dicatat.');
    }
}