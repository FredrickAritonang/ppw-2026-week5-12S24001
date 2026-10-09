<?php

namespace App\Support;

class Peran
{
    public const DATA = [
        'mahasiswa' => ['label' => 'Mahasiswa', 'icon' => 'bi-mortarboard-fill', 'maks' => 3, 'hari' => 7, 'perpanjang' => 1, 'aturan' => 'Maks. 3 buku · 7 hari · perpanjang 1x'],
        'dosen' => ['label' => 'Dosen', 'icon' => 'bi-person-workspace', 'maks' => 10, 'hari' => 30, 'perpanjang' => 2, 'aturan' => 'Maks. 10 buku · 30 hari · perpanjang 2x'],
        'pustakawan' => ['label' => 'Pegawai Perpustakaan', 'icon' => 'bi-person-badge-fill', 'maks' => 0, 'hari' => 0, 'perpanjang' => 0, 'aturan' => 'Mengelola sirkulasi, usulan, denda, bebas pustaka, dan koleksi'],
        'staf_akademik' => ['label' => 'Staf Akademik', 'icon' => 'bi-person-lines-fill', 'maks' => 0, 'hari' => 0, 'perpanjang' => 0, 'aturan' => 'Mengelola data akademik, pengguna, dan data master'],
    ];

    private const JUDUL_INDUK = [
        'mahasiswa' => 'Data Induk Mahasiswa',
        'dosen' => 'Data Induk Dosen',
        'pustakawan' => 'Data Induk Pegawai',
        'staf_akademik' => 'Data Induk Staf',
    ];

    /** Menu sidebar per peran: [label, ikon, nama-rute, [pola-url-aktif], (opsional) submenu] */
    public static function menu(string $role): array
    {
        $home = ['Home', 'bi-house-door-fill', 'dashboard', ['/']];
        $profil = [self::JUDUL_INDUK[$role] ?? 'Data Induk', 'bi-people-fill', null, ['profil*'], [
            ['Data Diri', 'bi-person-fill', 'profil.index', ['profil*']],
        ]];
        $katalog = ['Katalog Buku & E-Book', 'bi-journal-bookmark-fill', 'katalog.index', ['katalog*', 'baca/*']];
        $sirkulasi = ['Sirkulasi (Peminjaman & Pengembalian)', 'bi-arrow-left-right', 'sirkulasi.index', ['sirkulasi*']];
        $riwayat = ['Riwayat Peminjaman', 'bi-clock-history', 'riwayat.index', ['riwayat*']];
        $usulan = ['Usulan Buku Baru', 'bi-lightbulb-fill', 'usulan.index', ['usulan*']];
        $denda = ['Denda & Pembayaran', 'bi-cash-coin', 'denda.index', ['denda*']];
        $bebas = ['Layanan Bebas Pustaka', 'bi-patch-check-fill', 'bebas-pustaka.index', ['bebas-pustaka*']];
        $kelolaBuku = ['Kelola Buku', 'bi-book-fill', 'buku.index', ['buku*']];
        $kelolaKat = ['Kelola Kategori', 'bi-tags-fill', 'kategori.index', ['kategori*']];
        $pengguna = ['Kelola Pengguna', 'bi-people-fill', 'pengguna.index', ['pengguna*']];

        return match ($role) {
            'dosen' => [
                ['label' => 'Main Navigation', 'items' => [$home, $profil, $katalog, $sirkulasi, $riwayat, $usulan, $denda]],
            ],
            'pustakawan' => [
                ['label' => 'Main Navigation', 'items' => [$home, $profil, $katalog, $sirkulasi, $riwayat, $usulan, $denda, $bebas]],
                ['label' => 'Pengelolaan Koleksi', 'items' => [$kelolaBuku, $kelolaKat]],
            ],
            'staf_akademik' => [
                ['label' => 'Main Navigation', 'items' => [$home, $profil, $katalog, $riwayat]],
                ['label' => 'Administrasi Sistem', 'items' => [$kelolaBuku, $kelolaKat, $pengguna]],
            ],
            default => [
                ['label' => 'Main Navigation', 'items' => [$home, $profil, $katalog, $sirkulasi, $riwayat, $usulan, $denda, $bebas]],
            ],
        };
    }
}