<?php

namespace App\Http\Controllers;

use App\Models\BebasPustaka;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Usulan;
use App\Support\Peran;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();
        $peran = Peran::DATA[$user->role];

        $g = [
            'ungu' => 'linear-gradient(135deg,#4C1D95,#7C3AED)',
            'biru' => 'linear-gradient(135deg,#0369A1,#0EA5E9)',
            'hijau' => 'linear-gradient(135deg,#047857,#10B981)',
            'oranye' => 'linear-gradient(135deg,#B45309,#F59E0B)',
            'merah' => 'linear-gradient(135deg,#B91C1C,#F97316)',
        ];

        $stat = match ($user->role) {
            'pustakawan' => [
                ['Sedang Dipinjam', Peminjaman::aktif()->count(), 'bi-arrow-left-right', $g['ungu'], 'sirkulasi.index'],
                ['Terlambat', Peminjaman::aktif()->where('tgl_jatuh_tempo', '<', today())->count(), 'bi-alarm-fill', $g['merah'], 'sirkulasi.index'],
                ['Usulan Menunggu', Usulan::where('status', 'menunggu')->count(), 'bi-lightbulb-fill', $g['oranye'], 'usulan.index'],
                ['Bebas Pustaka Diajukan', BebasPustaka::where('status', 'diajukan')->count(), 'bi-patch-check-fill', $g['hijau'], 'bebas-pustaka.index'],
            ],
            'staf_it' => [
                ['Total Pengguna', User::count(), 'bi-people-fill', $g['ungu'], 'pengguna.index'],
                ['Total Buku', Buku::count(), 'bi-book-fill', $g['biru'], 'buku.index'],
                ['Total Kategori', Kategori::count(), 'bi-tags-fill', $g['oranye'], 'kategori.index'],
                ['E-Book Online', Buku::whereNotNull('file_url')->count(), 'bi-laptop', $g['hijau'], 'katalog.index'],
            ],
            default => [
                ['Pinjaman Aktif', $user->pinjamanAktif().' / '.$peran['maks'], 'bi-book-half', $g['ungu'], 'sirkulasi.index'],
                ['Segera Jatuh Tempo (≤3 hari)', Peminjaman::aktif()->where('user_id', $user->id)->whereBetween('tgl_jatuh_tempo', [today(), today()->addDays(3)])->count(), 'bi-alarm-fill', $g['oranye'], 'sirkulasi.index'],
                ['Denda Belum Lunas', 'Rp '.number_format($user->dendaBelumLunas(), 0, ',', '.'), 'bi-cash-coin', $g['merah'], 'denda.index'],
                ['Notifikasi Baru', Notifikasi::where('user_id', $user->id)->where('dibaca', false)->count(), 'bi-bell-fill', $g['biru'], 'notifikasi.index'],
            ],
        };

        return view('dashboard', [
            'stat' => $stat,
            'peran' => $peran,
            'terbaru' => Buku::with('kategori')->latest('id')->take(8)->get(),
            'ebooks' => Buku::with('kategori')->whereNotNull('file_url')->orderBy('judul')->take(5)->get(),
            'totalBuku' => Buku::count(),
            'totalKategori' => Kategori::count(),
        ]);
    }
}