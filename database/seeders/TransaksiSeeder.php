<?php

namespace Database\Seeders;

use App\Models\BebasPustaka;
use App\Models\Buku;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Usulan;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $u = User::whereIn('username', [
            'iss24001', 'iss24002', 'iss24003', 'adelinamanurung', 'adriantoprihartantyo',
            'jesichaulinahutabarat', 'stafakademik1',
        ])->get()->keyBy('username');

        $b = Buku::where('stok', '>=', 3)->whereNotNull('sampul')->inRandomOrder()->take(8)->get()
            ->concat(Buku::where('stok', '>=', 3)->whereNull('sampul')->inRandomOrder()->take(12)->get())->values();

        $this->pinjam($u['iss24001'], $b[0], 3, 7);
        $this->pinjam($u['iss24001'], $b[1], 12, 7);
        $this->pinjam($u['iss24001'], $b[2], 30, 7, 20);
        $this->pinjam($u['iss24001'], $b[3], 60, 7, 55);
        $this->pinjam($u['iss24001'], $b[4], 45, 7, 36, true);

        $this->pinjam($u['iss24002'], $b[5], 10, 7, 6);
        $this->pinjam($u['iss24002'], $b[6], 5, 7, 3);

        $this->pinjam($u['iss24003'], $b[7], 9, 7);
        $this->pinjam($u['iss24003'], $b[8], 20, 7, 10);

        $this->pinjam($u['adelinamanurung'], $b[9], 10, 30);
        $this->pinjam($u['adelinamanurung'], $b[10], 25, 30);
        $this->pinjam($u['adelinamanurung'], $b[11], 50, 30, 40);
        $this->pinjam($u['adriantoprihartantyo'], $b[12], 5, 30);

        Usulan::create(['user_id' => $u['iss24001']->id, 'judul' => 'Laravel: Up & Running', 'penulis' => 'Matt Stauffer', 'penerbit' => "O'Reilly Media", 'alasan' => 'Dibutuhkan untuk mata kuliah Pemrograman dan Pengujian Web.']);
        Usulan::create(['user_id' => $u['iss24002']->id, 'judul' => 'Pengantar Hukum Siber Indonesia', 'penulis' => 'Tim Penyusun', 'alasan' => 'Referensi tugas mata kuliah etika profesi.', 'status' => 'disetujui', 'catatan' => 'Akan diadakan bulan depan.']);
        Usulan::create(['user_id' => $u['adelinamanurung']->id, 'judul' => 'Deep Learning for Computer Vision', 'penulis' => 'Rajalingappaa Shanmugamani', 'status' => 'ditolak', 'catatan' => 'Judul serupa sudah tersedia di koleksi.']);

        BebasPustaka::create(['user_id' => $u['iss24002']->id]);

        $m = $u['iss24001']->id;
        Notifikasi::kirim($m, 'Selamat datang di SIPUS-Del', 'Jelajahi katalog buku dan e-book Perpustakaan IT Del.', 'bi-stars', route('katalog.index', absolute: false));
        Notifikasi::kirim($m, 'Jatuh tempo mendekati', "\"{$b[0]->judul}\" jatuh tempo dalam 4 hari.", 'bi-alarm-fill', route('sirkulasi.index', absolute: false));
        Notifikasi::kirim($m, 'Buku terlambat', "\"{$b[1]->judul}\" terlambat 5 hari. Denda berjalan Rp 5.000.", 'bi-exclamation-triangle-fill', route('sirkulasi.index', absolute: false));
        Notifikasi::kirim($m, 'Denda belum dibayar', "Denda Rp 3.000 untuk \"{$b[2]->judul}\" belum dilunasi.", 'bi-cash-coin', route('denda.index', absolute: false));

        Notifikasi::kirimKePeran('pustakawan', 'Usulan buku baru', 'Fredrick Laurensius Aritonang mengusulkan "Laravel: Up & Running".', 'bi-lightbulb-fill', route('usulan.index', absolute: false));
        Notifikasi::kirimKePeran('pustakawan', 'Pengajuan bebas pustaka', 'Ada pengajuan surat bebas pustaka yang menunggu diproses.', 'bi-patch-check-fill', route('bebas-pustaka.index', absolute: false));
        Notifikasi::kirim($u['adelinamanurung']->id, 'Selamat datang di SIPUS-Del', 'Masa pinjam dosen 30 hari dengan 2x perpanjangan.', 'bi-stars', route('katalog.index', absolute: false));
        Notifikasi::kirim($u['stafakademik1']->id, 'Sistem siap digunakan', 'Seluruh data master berhasil dimuat.', 'bi-person-lines-fill', route('pengguna.index', absolute: false));
    }

    private function pinjam(User $u, Buku $b, int $pinjamLalu, int $durasi, ?int $kembaliLalu = null, bool $lunas = false): void
    {
        $pinjam = today()->subDays($pinjamLalu);
        $kembali = $kembaliLalu !== null ? today()->subDays($kembaliLalu) : null;

        Peminjaman::create([
            'kode_pinjam' => Peminjaman::buatKode(),
            'buku_id' => $b->id,
            'user_id' => $u->id,
            'nim_peminjam' => substr((string) ($u->nomor_induk ?: $u->username), 0, 20),
            'nama_peminjam' => $u->name,
            'tanggal_pinjam' => $pinjam,
            'tanggal_jatuh_tempo' => $pinjam->copy()->addDays($durasi),
            'tanggal_kembali' => $kembali,
            'status' => $kembali ? 'dikembalikan' : 'dipinjam',
            'denda_lunas' => $lunas,
            'tgl_bayar' => $lunas ? $kembali : null,
        ]);

        if (! $kembali) {
            $b->decrement('stok');
        }
    }
}