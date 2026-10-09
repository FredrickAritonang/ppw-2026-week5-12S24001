<?php

namespace App\Support;

class ProfilContoh
{
    public const PRODI = [
        'SI' => 'S1-Sistem Informasi', 'IF' => 'S1-Informatika', 'TE' => 'S1-Teknik Elektro',
        'MR' => 'S1-Manajemen Rekayasa', 'TM' => 'S1-Teknik Metalurgi', 'TB' => 'S1-Teknik Bioproses',
        'BT' => 'S1-Bioteknologi', 'TI' => 'D3-Teknologi Informasi', 'TK' => 'D3-Teknologi Komputer',
        'MI' => 'D3-Manajemen Informatika', 'RPL' => 'D4-Teknologi Rekayasa Perangkat Lunak',
        'AI' => 'S2-Artificial Intelligence', 'UM' => 'Mata Kuliah Umum',
    ];

    public static function untuk(string $username, string $role, string $nama): array
    {
        $h = crc32($username);
        $telepon = '0812'.sprintf('%08d', $h % 100000000);

        return match ($role) {
            'mahasiswa' => [
                'nomor_induk' => '12S24'.substr($username, -3),
                'prodi' => self::PRODI['SI'],
                'status_kerja' => 'Aktif',
                'telepon' => $telepon,
            ],
            'dosen' => [
                'nuptk' => sprintf('%016d', $h * 1000003),
                'nidn' => sprintf('%010d', $h),
                'prodi' => self::prodiDosen($username),
                'jabatan' => str_starts_with($nama, 'Prof.') ? 'Guru Besar' : ['Asisten Ahli', 'Lektor', 'Lektor Kepala'][$h % 3],
                'golongan' => str_starts_with($nama, 'Prof.') ? 'IV D' : ['III A', 'III B', 'III C', 'III D', 'IV A'][$h % 5],
                'status_kerja' => ['Dosen Non-PNS', 'Dosen Tetap Yayasan'][$h % 2],
                'telepon' => $telepon,
            ],
            'pustakawan' => [
                'nomor_induk' => 'PEG-'.sprintf('%05d', $h % 100000),
                'prodi' => 'UPT Perpustakaan',
                'jabatan' => match ($username) {
                    'jesichaulinahutabarat' => 'Kepala Perpustakaan',
                    'enjelinalimbong' => 'Bidang Layanan Pemustaka',
                    'yuliasaritripena' => 'Bidang Layanan Informasi dan Administrasi',
                    default => 'Staf Perpustakaan',
                },
                'status_kerja' => 'Pegawai Tetap',
                'telepon' => $telepon,
            ],
            default => [
                'nomor_induk' => 'STF-'.sprintf('%05d', $h % 100000),
                'prodi' => 'Biro Akademik',
                'jabatan' => 'Staf Akademik',
                'status_kerja' => 'Pegawai Tetap',
                'telepon' => $telepon,
            ],
        };
    }

    private static function bersih(string $s): string
    {
        return preg_replace('/[^a-z0-9]/', '', strtolower($s));
    }

    private static function prodiDosen(string $username): string
    {
        static $peta = null;

        if ($peta === null) {
            $peta = [];
            $berkas = database_path('data/prodi-dosen.csv');
            if (is_file($berkas)) {
                foreach (file($berkas, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $i => $baris) {
                    if ($i === 0) {
                        continue;
                    }
                    [$u, $p] = array_pad(explode(',', $baris, 2), 2, '');
                    $peta[self::bersih($u)] = trim($p);
                }
            }
        }

        $kode = $peta[self::bersih($username)] ?? null;

        return $kode ? (self::PRODI[strtoupper($kode)] ?? $kode) : '-';
    }
}