<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Models\User;
use App\Support\Peran;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class SirkulasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'pustakawan') {
            $aktif = Peminjaman::aktif()->with(['buku.kategori', 'user'])
                ->when($request->filter === 'terlambat', fn ($q) => $q->where('tanggal_jatuh_tempo', '<', today()))
                ->when($request->q, fn ($q, $v) => $q->where(fn ($w) => $w
                    ->where('nama_peminjam', 'like', "%{$v}%")
                    ->orWhere('nim_peminjam', 'like', "%{$v}%")
                    ->orWhere('kode_pinjam', 'like', "%{$v}%")
                    ->orWhereHas('buku', fn ($b) => $b->where('judul', 'like', "%{$v}%"))))
                ->orderBy('tanggal_jatuh_tempo')
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
            'aktif' => Peminjaman::aktif()->with('buku.kategori')->where('user_id', $user->id)->orderBy('tanggal_jatuh_tempo')->get(),
            'anggota' => collect(),
            'hasil' => $request->filled('cari')
                ? Buku::with('kategori')->where(fn ($w) => $w
                    ->where('judul', 'like', '%'.$request->cari.'%')
                    ->orWhere('isbn', 'like', '%'.$request->cari.'%'))->orderBy('judul')->take(6)->get()
                : collect(),
        ]);
    }

    public function pinjam(int $id)
    {
        [$pinjaman, $error] = $this->jalankanPinjam(Auth::user(), $id);

        if ($error) {
            return back()->with('error', $error);
        }

        return back()->with('success', "Berhasil meminjam \"{$pinjaman->buku->judul}\" (kode {$pinjaman->kode_pinjam}).");
    }

    public function catat(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'isbn' => 'required|exists:bukus,isbn',
        ]);

        $anggota = User::findOrFail($data['user_id']);
        $bukuId = Buku::where('isbn', $data['isbn'])->value('id');

        [$pinjaman, $error] = $this->jalankanPinjam($anggota, $bukuId);

        if ($error) {
            return back()->withInput()->with('error', $error);
        }

        session(['peminjam_terakhir' => "{$pinjaman->nama_peminjam} ({$pinjaman->nim_peminjam})"]);
        Cookie::queue('del_peminjam_terakhir', $pinjaman->nim_peminjam, 60 * 24 * 7);

        return back()->with('success', "Peminjaman \"{$pinjaman->buku->judul}\" untuk {$pinjaman->nama_peminjam} berhasil dicatat ({$pinjaman->kode_pinjam}).");
    }

    public function kembali(int $id)
    {
        $user = Auth::user();

        try {
            $pinjaman = DB::transaction(function () use ($id, $user) {
                $p = Peminjaman::where('id', $id)->lockForUpdate()->firstOrFail();

                abort_unless($user->role === 'pustakawan' || $p->user_id === $user->id, 403);

                if ($p->status === 'dikembalikan') {
                    throw new RuntimeException('Buku ini sudah dikembalikan.');
                }

                $buku = Buku::where('id', $p->buku_id)->lockForUpdate()->firstOrFail();

                $p->update([
                    'status' => 'dikembalikan',
                    'tanggal_kembali' => today(),
                ]);
                $buku->increment('stok');

                return $p;
            }, 3);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (ModelNotFoundException) {
            return back()->with('error', 'Data peminjaman tidak ditemukan.');
        } catch (QueryException $e) {
            Log::error('[DB EXCEPTION][kembali] '.$e->getMessage());

            return back()->with('error', 'Pengembalian gagal diproses, transaksi dibatalkan (rollback).');
        }

        $pinjaman->load('buku');
        $judul = $pinjaman->buku->judul;

        if ($pinjaman->total_denda > 0) {
            Notifikasi::kirim(
                $pinjaman->user_id,
                'Denda keterlambatan',
                "Pengembalian \"{$judul}\" terlambat {$pinjaman->hari_terlambat} hari. Denda Rp ".number_format($pinjaman->total_denda, 0, ',', '.').'.',
                'bi-exclamation-triangle-fill',
                route('denda.index', absolute: false)
            );
            $pesan = "Buku dikembalikan. Terlambat {$pinjaman->hari_terlambat} hari, denda Rp ".number_format($pinjaman->total_denda, 0, ',', '.').'.';
        } else {
            Notifikasi::kirim($pinjaman->user_id, 'Pengembalian berhasil', "\"{$judul}\" telah dikembalikan tepat waktu.", 'bi-check-circle-fill', route('riwayat.index', absolute: false));
            $pesan = 'Buku berhasil dikembalikan tepat waktu. Stok bertambah.';
        }

        return back()->with('success', $pesan);
    }

    public function perpanjang(Peminjaman $peminjaman)
    {
        $user = Auth::user();
        abort_unless($user->role === 'pustakawan' || $peminjaman->user_id === $user->id, 403);

        $aturan = Peran::DATA[$peminjaman->user->role];

        if ($peminjaman->status === 'dikembalikan') {
            return back()->with('error', 'Buku ini sudah dikembalikan.');
        }
        if ($peminjaman->hari_terlambat > 0) {
            return back()->with('error', 'Tidak dapat diperpanjang karena sudah terlambat.');
        }
        if ($peminjaman->jumlah_perpanjang >= $aturan['perpanjang']) {
            return back()->with('error', "Batas perpanjangan {$aturan['perpanjang']}x sudah tercapai.");
        }

        $peminjaman->update([
            'tanggal_jatuh_tempo' => $peminjaman->tanggal_jatuh_tempo->copy()->addDays($aturan['hari']),
            'jumlah_perpanjang' => $peminjaman->jumlah_perpanjang + 1,
        ]);

        Notifikasi::kirim($peminjaman->user_id, 'Perpanjangan berhasil', "\"{$peminjaman->buku->judul}\" diperpanjang sampai ".$peminjaman->tanggal_jatuh_tempo->translatedFormat('d M Y').'.', 'bi-arrow-repeat', route('sirkulasi.index', absolute: false));

        return back()->with('success', 'Peminjaman berhasil diperpanjang.');
    }

    public function riwayat(Request $request)
    {
        $user = Auth::user();
        $staf = in_array($user->role, ['pustakawan', 'staf_akademik']);

        $riwayat = Peminjaman::with(['buku.kategori', 'user'])
            ->when(! $staf, fn ($q) => $q->where('user_id', $user->id))
            ->when($request->status === 'aktif', fn ($q) => $q->aktif())
            ->when($request->status === 'selesai', fn ($q) => $q->where('status', 'dikembalikan'))
            ->when($request->status === 'terlambat', fn ($q) => $q->berdenda())
            ->when($staf && $request->q, fn ($q, $v) => $q->where(fn ($w) => $w
                ->where('nama_peminjam', 'like', "%{$v}%")
                ->orWhere('nim_peminjam', 'like', "%{$v}%")
                ->orWhereHas('buku', fn ($b) => $b->where('judul', 'like', "%{$v}%"))))
            ->latest('tanggal_pinjam')->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('riwayat.index', compact('riwayat', 'staf'));
    }

    private function jalankanPinjam(User $anggota, int $bukuId): array
    {
        if (! in_array($anggota->role, ['mahasiswa', 'dosen'])) {
            return [null, 'Hanya mahasiswa dan dosen yang dapat meminjam buku.'];
        }

        $aturan = Peran::DATA[$anggota->role];
        $nim = substr((string) ($anggota->nomor_induk ?: $anggota->username), 0, 20);

        try {
            $pinjaman = DB::transaction(function () use ($anggota, $bukuId, $aturan, $nim) {
                $buku = Buku::where('id', $bukuId)->lockForUpdate()->firstOrFail();

                if ($buku->stok < 1) {
                    throw new RuntimeException("Stok buku \"{$buku->judul}\" habis (0). Peminjaman ditolak dan dibatalkan.");
                }
                if (Peminjaman::aktif()->where('nim_peminjam', $nim)->where('buku_id', $buku->id)->exists()) {
                    throw new RuntimeException('NIM/ID ini masih meminjam buku yang sama dan belum mengembalikannya.');
                }
                if ($anggota->pinjamanAktif() >= $aturan['maks']) {
                    throw new RuntimeException("Batas peminjaman {$aturan['maks']} buku sudah tercapai.");
                }
                if ($anggota->dendaBelumLunas() > 0) {
                    throw new RuntimeException('Lunasi denda terlebih dahulu sebelum meminjam buku lagi.');
                }

                $buku->decrement('stok');

                $p = Peminjaman::create([
                    'kode_pinjam' => Peminjaman::buatKode(),
                    'buku_id' => $buku->id,
                    'user_id' => $anggota->id,
                    'nim_peminjam' => $nim,
                    'nama_peminjam' => $anggota->name,
                    'tanggal_pinjam' => today(),
                    'tanggal_jatuh_tempo' => today()->addDays($aturan['hari']),
                    'status' => 'dipinjam',
                ]);
                $p->setRelation('buku', $buku);

                return $p;
            }, 3);
        } catch (RuntimeException $e) {
            return [null, $e->getMessage()];
        } catch (ModelNotFoundException) {
            return [null, 'Buku tidak ditemukan.'];
        } catch (QueryException $e) {
            Log::error('[DB EXCEPTION][pinjam] '.$e->getMessage());

            return [null, 'Peminjaman gagal diproses, transaksi dibatalkan (rollback).'];
        }

        Notifikasi::kirim(
            $anggota->id,
            'Peminjaman berhasil',
            "\"{$pinjaman->buku->judul}\" dipinjam. Jatuh tempo ".$pinjaman->tanggal_jatuh_tempo->translatedFormat('d M Y').'.',
            'bi-book-half',
            route('sirkulasi.index', absolute: false)
        );

        return [$pinjaman, null];
    }
}