<?php

namespace App\Support;

class ProfilContoh
{
    public static function untuk(string $username, string $role, string $nama): array
    {
        $h = crc32($username);
        $pilih = fn (array $a) => $a[$h % count($a)];

        $prodi = [
            'S1-Sistem Informasi', 'S1-Informatika', 'S1-Teknik Elektro', 'S1-Manajemen Rekayasa',
            'S1-Teknik Metalurgi', 'S1-Teknik Bioproses', 'S1-Bioteknologi', 'D3-Teknologi Informasi',
            'D3-Teknologi Komputer', 'D4-Teknologi Rekayasa Perangkat Lunak', 'S2-Artificial Intelligence',
        ];
        $telepon = '0812'.sprintf('%08d', $h % 100000000);

        return match ($role) {
            'mahasiswa' => [
                'nomor_induk' => '12S24'.substr($username, -3),
                'prodi' => 'S1-Sistem Informasi',
                'status_kerja' => 'Aktif',
                'telepon' => $telepon,
            ],
            'dosen' => [
                'nuptk' => sprintf('%016d', $h * 1000003),
                'nidn' => sprintf('%010d', $h),
                'prodi' => $pilih($prodi),
                'jabatan' => str_starts_with($nama, 'Prof.') ? 'Guru Besar' : $pilih(['Asisten Ahli', 'Lektor', 'Lektor Kepala']),
                'golongan' => str_starts_with($nama, 'Prof.') ? 'IV D' : $pilih(['III A', 'III B', 'III C', 'III D', 'IV A']),
                'status_kerja' => $pilih(['Dosen Non-PNS', 'Dosen Tetap Yayasan']),
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
}