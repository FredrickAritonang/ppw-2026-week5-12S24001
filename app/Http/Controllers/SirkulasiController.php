<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Models\User;
use App\Support\Peran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SirkulasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'pustakawan') {
            $aktif = Peminjaman::aktif()->with(['buku.kategori', 'user'])
                ->when($request->filter === 'terlambat', fn ($q) => $q->where('tgl_jatuh_tempo', '<', today()))
                ->when($request->q, fn ($q, $v) => $q->where(fn ($w) => $w
                    ->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$v}%")->orWhere('username', 'like', "%{$v}%"))
                    ->orWhereHas('buku', fn ($b) => $b->where('judul', 'like', "%{$v}%"))))
                ->orderBy('tgl_jatuh_tempo')
                ->paginate(10)
                ->withQueryString();

            return view('sirkulasi.index', [
                'mode' => 'staf',
                'aktif' => $aktif,
                'anggota' => User::whereIn('role', ['mahasiswa', 'dosen'])->orderBy('name')->get(),
                'hasil' => collect(),
            ]);
        }

        return view('sirkulasi.index', [
            'mode' => 'anggota',
            'aktif' => Peminjaman::aktif()->with('buku.kategori')->where('user_id', $user->id)->orderBy('tgl_jatuh_tempo')->get(),
            'anggota' => collect(),
            'hasil' => $request->filled('cari')
                ? Buku::with('kategori')->where(fn ($w) => $w
                    ->where('judul', 'like', '%'.$request->cari.'%')
                    ->orWhere('isbn', 'like', '%'.$request->cari.'%'))->orderBy('judul')->take(6)->get()
                : collect(),
        ]);
    }

    public function pinjam(Buku $buku)
    {
        $user = Auth::user();
        $err = $this->buatPinjaman($user, $buku);

        return back()->with($err ? 'error' : 'success', $err ?? "Berhasil meminjam \"{$buku->judul}\". Lihat di menu Sirkulasi.");
    }

    public function catat(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'isbn' => 'required|exists:bukus,isbn',
        ]);

        $anggota = User::findOrFail($data['user_id']);
        $buku = Buku::where('isbn', $data['isbn'])->firstOrFail();
        $err = $this->buatPinjaman($anggota, $buku);

        return back()->with($err ? 'error' : 'success', $err ?? "Peminjaman \"{$buku->judul}\" untuk {$anggota->name} berhasil dicatat.");
    }

    public function kembali(Peminjaman $peminjaman)
    {
        $user = Auth::user();
        abort_unless($user->role === 'pustakawan' || $peminjaman->user_id === $user->id, 403);

        if ($peminjaman->tgl_kembali) {
            return back()->with('error', 'Buku ini sudah dikembalikan.');
        }

        DB::transaction(function () use ($peminjaman) {
            $peminjaman->update(['tgl_kembali' => today()]);
            $peminjaman->buku()->increment('stok');
        });

        $peminjaman->refresh();
        $judul = $peminjaman->buku->judul;

        if ($peminjaman->total_denda > 0) {
            Notifikasi::kirim(
                $peminjaman->user_id,
                'Denda keterlambatan',
                "Pengembalian \"{$judul}\" terlambat {$peminjaman->hari_terlambat} hari. Denda Rp ".number_format($peminjaman->total_denda, 0, ',', '.').'.',
                'bi-exclamation-triangle-fill',
                route('denda.index', absolute: false)
            );
            $pesan = "Buku dikembalikan. Terlambat {$peminjaman->hari_terlambat} hari, denda Rp ".number_format($peminjaman->total_denda, 0, ',', '.').'.';
        } else {
            Notifikasi::kirim($peminjaman->user_id, 'Pengembalian berhasil', "\"{$judul}\" telah dikembalikan tepat waktu.", 'bi-check-circle-fill', route('riwayat.index', absolute: false));
            $pesan = 'Buku berhasil dikembalikan tepat waktu.';
        }

        return back()->with('success', $pesan);
    }

    public function perpanjang(Peminjaman $peminjaman)
    {
        $user = Auth::user();
        abort_unless($user->role === 'pustakawan' || $peminjaman->user_id === $user->id, 403);

        $aturan = Peran::DATA[$peminjaman->user->role];

        if ($peminjaman->tgl_kembali) {
            return back()->with('error', 'Buku ini sudah dikembalikan.');
        }
        if ($peminjaman->hari_terlambat > 0) {
            return back()->with('error', 'Tidak dapat diperpanjang karena sudah terlambat.');
        }
        if ($peminjaman->jumlah_perpanjang >= $aturan['perpanjang']) {
            return back()->with('error', "Batas perpanjangan {$aturan['perpanjang']}x sudah tercapai.");
        }

        $peminjaman->update([
            'tgl_jatuh_tempo' => $peminjaman->tgl_jatuh_tempo->copy()->addDays($aturan['hari']),
            'jumlah_perpanjang' => $peminjaman->jumlah_perpanjang + 1,
        ]);

        Notifikasi::kirim($peminjaman->user_id, 'Perpanjangan berhasil', "\"{$peminjaman->buku->judul}\" diperpanjang sampai ".$peminjaman->tgl_jatuh_tempo->translatedFormat('d M Y').'.', 'bi-arrow-repeat', route('sirkulasi.index', absolute: false));

        return back()->with('success', 'Peminjaman berhasil diperpanjang.');
    }

    public function riwayat(Request $request)
    {
        $user = Auth::user();
        $staf = in_array($user->role, ['pustakawan', 'staf_akademik']);

        $riwayat = Peminjaman::with(['buku.kategori', 'user'])
            ->when(! $staf, fn ($q) => $q->where('user_id', $user->id))
            ->when($request->status === 'aktif', fn ($q) => $q->aktif())
            ->when($request->status === 'selesai', fn ($q) => $q->whereNotNull('tgl_kembali'))
            ->when($request->status === 'terlambat', fn ($q) => $q->berdenda())
            ->when($staf && $request->q, fn ($q, $v) => $q->where(fn ($w) => $w
                ->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$v}%")->orWhere('username', 'like', "%{$v}%"))
                ->orWhereHas('buku', fn ($b) => $b->where('judul', 'like', "%{$v}%"))))
            ->latest('tgl_pinjam')->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('riwayat.index', compact('riwayat', 'staf'));
    }

    private function buatPinjaman(User $anggota, Buku $buku): ?string
    {
        if (! in_array($anggota->role, ['mahasiswa', 'dosen'])) {
            return 'Hanya mahasiswa dan dosen yang dapat meminjam buku.';
        }

        $aturan = Peran::DATA[$anggota->role];

        if ($buku->stok < 1) {
            return 'Stok buku ini sedang habis.';
        }
        if (Peminjaman::aktif()->where('user_id', $anggota->id)->where('buku_id', $buku->id)->exists()) {
            return 'Buku ini sedang dipinjam.';
        }
        if ($anggota->pinjamanAktif() >= $aturan['maks']) {
            return "Batas peminjaman {$aturan['maks']} buku sudah tercapai.";
        }
        if ($anggota->dendaBelumLunas() > 0) {
            return 'Lunasi denda terlebih dahulu sebelum meminjam buku lagi.';
        }

        DB::transaction(function () use ($anggota, $buku, $aturan) {
            Peminjaman::create([
                'user_id' => $anggota->id,
                'buku_id' => $buku->id,
                'tgl_pinjam' => today(),
                'tgl_jatuh_tempo' => today()->addDays($aturan['hari']),
            ]);
            $buku->decrement('stok');
        });

        Notifikasi::kirim(
            $anggota->id,
            'Peminjaman berhasil',
            "\"{$buku->judul}\" dipinjam. Jatuh tempo ".today()->addDays($aturan['hari'])->translatedFormat('d M Y').'.',
            'bi-book-half',
            route('sirkulasi.index', absolute: false)
        );

        return null;
    }
}