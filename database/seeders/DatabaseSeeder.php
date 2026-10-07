<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // [kode, nama kategori, topik untuk judul buku]
        $daftarKategori = [
            ['INF', 'Informatika', 'Informatika'],
            ['SIS', 'Sistem Informasi', 'Sistem Informasi'],
            ['ELK', 'Teknik Elektro', 'Teknik Elektro'],
            ['MAT', 'Matematika', 'Matematika'],
            ['UMM', 'Umum & Referensi', 'Ilmu Pengetahuan Umum'],
            ['PRG', 'Pemrograman', 'Pemrograman'],
            ['BDT', 'Basis Data', 'Basis Data'],
            ['JAR', 'Jaringan Komputer', 'Jaringan Komputer'],
            ['AIN', 'Kecerdasan Buatan', 'Kecerdasan Buatan'],
            ['KEA', 'Keamanan Siber', 'Keamanan Siber'],
            ['RPL', 'Rekayasa Perangkat Lunak', 'Rekayasa Perangkat Lunak'],
            ['WEB', 'Pengembangan Web', 'Pengembangan Web'],
            ['MOB', 'Aplikasi Mobile', 'Aplikasi Mobile'],
            ['DSC', 'Data Science', 'Data Science'],
            ['IOT', 'Internet of Things', 'Internet of Things'],
            ['FIS', 'Fisika', 'Fisika'],
            ['KIM', 'Kimia', 'Kimia'],
            ['BIO', 'Biologi', 'Biologi'],
            ['STA', 'Statistika', 'Statistika'],
            ['EKO', 'Ekonomi', 'Ekonomi'],
            ['MNJ', 'Manajemen', 'Manajemen'],
            ['AKT', 'Akuntansi', 'Akuntansi'],
            ['BIN', 'Bahasa Indonesia', 'Bahasa Indonesia'],
            ['BIG', 'Bahasa Inggris', 'Bahasa Inggris'],
            ['SEJ', 'Sejarah', 'Sejarah'],
            ['FIL', 'Filsafat', 'Filsafat'],
            ['PSI', 'Psikologi', 'Psikologi'],
            ['HUK', 'Hukum', 'Hukum'],
            ['SNI', 'Seni & Desain', 'Seni dan Desain'],
            ['AGM', 'Agama & Etika', 'Etika dan Moral'],
        ];

        $templateJudul = [
            'Pengantar %s',
            'Dasar-Dasar %s',
            'Konsep dan Penerapan %s',
            'Panduan Praktis %s',
            'Teori dan Praktik %s',
            'Studi Kasus %s',
            '%s Modern',
            'Memahami %s',
            'Strategi Belajar %s',
        ];

        // 30 kategori
        $kategoris = [];
        foreach ($daftarKategori as [$kode, $nama, $topik]) {
            $kategoris[] = [
                'model' => Kategori::create(['kode_kategori' => $kode, 'nama_kategori' => $nama]),
                'topik' => $topik,
            ];
        }

        // 3 buku per kategori = 90 buku
        foreach ($kategoris as $k) {
            $dipilih = collect($templateJudul)->shuffle()->take(3);
            foreach ($dipilih as $template) {
                Buku::factory()->create([
                    'kategori_id' => $k['model']->id,
                    'judul' => sprintf($template, $k['topik']),
                ]);
            }
        }

        // 10 buku tambahan acak = total 100 buku
        for ($i = 0; $i < 10; $i++) {
            $k = $kategoris[array_rand($kategoris)];
            Buku::factory()->create([
                'kategori_id' => $k['model']->id,
                'judul' => sprintf($templateJudul[array_rand($templateJudul)], $k['topik']) . ' Edisi ' . rand(2, 5),
            ]);
        }
    }
}