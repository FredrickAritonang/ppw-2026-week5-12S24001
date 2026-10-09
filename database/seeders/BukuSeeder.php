<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $prodi = Prodi::pluck('id', 'kode')->all();
        $kat = Kategori::pluck('id', 'nama_kategori')->all();
        $cover = fn ($isbn) => "https://covers.openlibrary.org/b/isbn/{$isbn}-L.jpg?default=false";
        $pivot = [];

        DB::transaction(function () use (&$pivot, $prodi, $kat, $cover) {
            // ===== 1. Buku asli (isbn, judul, penulis, penerbit, tahun, kategori, prodi, halaman, file_url, sinopsis) =====
            $nyata = [
                ['9780132350884', 'Clean Code', 'Robert C. Martin', 'Prentice Hall', 2008, 'Rekayasa Perangkat Lunak', ['SI', 'IF', 'RPL'], 464, null, 'Panduan menulis kode yang bersih, mudah dibaca, dan mudah dirawat.'],
                ['9780262046305', 'Introduction to Algorithms (4th Ed.)', 'Thomas H. Cormen dkk.', 'MIT Press', 2022, 'Pemrograman', ['IF', 'AI'], 1312, null, 'Rujukan utama algoritma dan struktur data beserta analisisnya.'],
                ['9780201633610', 'Design Patterns', 'Erich Gamma dkk.', 'Addison-Wesley', 1994, 'Rekayasa Perangkat Lunak', ['IF', 'RPL'], 416, null, 'Katalog pola desain berorientasi objek yang dapat dipakai ulang.'],
                ['9780133943030', 'Software Engineering (10th Ed.)', 'Ian Sommerville', 'Pearson', 2015, 'Rekayasa Perangkat Lunak', ['SI', 'IF', 'RPL'], 816, null, 'Konsep rekayasa perangkat lunak dari proses hingga pengujian.'],
                ['9780078022159', 'Database System Concepts (7th Ed.)', 'Abraham Silberschatz dkk.', 'McGraw-Hill', 2019, 'Basis Data', ['SI', 'IF', 'TI'], 1376, null, 'Dasar sistem basis data relasional, SQL, dan transaksi.'],
                ['9780136681557', 'Computer Networking: A Top-Down Approach (8th Ed.)', 'James Kurose, Keith Ross', 'Pearson', 2021, 'Jaringan Komputer', ['IF', 'TE', 'TK'], 800, null, 'Jaringan komputer dijelaskan mulai dari lapisan aplikasi.'],
                ['9781119800361', 'Operating System Concepts (10th Ed.)', 'Abraham Silberschatz dkk.', 'Wiley', 2021, 'Sistem Operasi', ['IF', 'TK'], 1000, null, 'Konsep sistem operasi: proses, memori, penyimpanan, dan keamanan.'],
                ['9781118008188', 'HTML and CSS: Design and Build Websites', 'Jon Duckett', 'Wiley', 2011, 'Pengembangan Web', ['SI', 'TI', 'RPL'], 490, null, 'Pengantar membangun halaman web dengan HTML dan CSS.'],
                ['9781491939369', 'Think Python (2nd Ed.)', 'Allen B. Downey', "O'Reilly Media", 2015, 'Pemrograman', ['SI', 'IF', 'TI'], 292, 'https://greenteapress.com/thinkpython2/thinkpython2.pdf', 'Belajar pemrograman dengan Python dari dasar. E-book terbuka.'],
                ['9781593279509', 'Eloquent JavaScript (3rd Ed.)', 'Marijn Haverbeke', 'No Starch Press', 2018, 'Pengembangan Web', ['SI', 'TI', 'RPL'], 472, 'https://eloquentjavascript.net/Eloquent_JavaScript.pdf', 'Pemrograman JavaScript modern. E-book terbuka.'],
                ['9781484200773', 'Pro Git (2nd Ed.)', 'Scott Chacon, Ben Straub', 'Apress', 2014, 'Rekayasa Perangkat Lunak', ['IF', 'RPL', 'TK'], 456, 'https://git-scm.com/book/en/v2', 'Panduan lengkap Git untuk pengelolaan versi kode. E-book terbuka.'],
                ['9781593279929', 'Automate the Boring Stuff with Python (2nd Ed.)', 'Al Sweigart', 'No Starch Press', 2019, 'Pemrograman', ['SI', 'TI', 'MR'], 592, 'https://automatetheboringstuff.com/', 'Mengotomatiskan pekerjaan sehari-hari dengan Python. E-book terbuka.'],
                ['9780134610993', 'Artificial Intelligence: A Modern Approach (4th Ed.)', 'Stuart Russell, Peter Norvig', 'Pearson', 2020, 'Machine Learning', ['IF', 'AI'], 1136, null, 'Buku teks AI yang paling banyak dipakai di perguruan tinggi.'],
                ['9780262035613', 'Deep Learning', 'Ian Goodfellow dkk.', 'MIT Press', 2016, 'Deep Learning', ['IF', 'AI'], 800, null, 'Landasan matematika dan teknik deep learning.'],
                ['9780262046824', 'Probabilistic Machine Learning: An Introduction', 'Kevin P. Murphy', 'MIT Press', 2022, 'Machine Learning', ['AI'], 864, null, 'Pengantar machine learning dari sudut pandang probabilistik.'],
                ['9781098125974', 'Hands-On Machine Learning (3rd Ed.)', 'Aurélien Géron', "O'Reilly Media", 2022, 'Machine Learning', ['IF', 'AI', 'SI'], 856, null, 'Praktik machine learning dengan Scikit-Learn, Keras, dan TensorFlow.'],
                ['9781071614174', 'An Introduction to Statistical Learning (2nd Ed.)', 'Gareth James dkk.', 'Springer', 2021, 'Data Mining', ['SI', 'IF', 'AI'], 607, 'https://www.statlearning.com/s/ISLRv2_website.pdf', 'Pengantar statistical learning dengan contoh R. E-book terbuka.'],
                ['9780078028229', 'Fundamentals of Electric Circuits (5th Ed.)', 'Charles Alexander, Matthew Sadiku', 'McGraw-Hill', 2012, 'Teknik Elektro', ['TE'], 992, null, 'Analisis rangkaian listrik untuk mahasiswa teknik elektro.'],
                ['9780199339136', 'Microelectronic Circuits (7th Ed.)', 'Adel Sedra, Kenneth Smith', 'Oxford University Press', 2014, 'Elektronika', ['TE'], 1488, null, 'Rangkaian elektronika dari dioda hingga penguat operasional.'],
                ['9781119405498', 'Materials Science and Engineering: An Introduction (10th Ed.)', 'William D. Callister', 'Wiley', 2018, 'Material & Metalurgi', ['TM'], 992, null, 'Ilmu dan rekayasa material, termasuk logam dan paduan.'],
                ['9780122208515', 'Bioprocess Engineering Principles (2nd Ed.)', 'Pauline M. Doran', 'Academic Press', 2012, 'Bioproses & Bioteknologi', ['TB', 'BT'], 926, null, 'Prinsip rekayasa bioproses dan fermentasi.'],
                ['9781319228002', 'Lehninger Principles of Biochemistry (8th Ed.)', 'David Nelson, Michael Cox', 'W. H. Freeman', 2021, 'Biokimia', ['BT', 'TB'], 1328, null, 'Dasar biokimia untuk bioteknologi dan ilmu kesehatan.'],
                ['9781305585126', 'Principles of Economics (8th Ed.)', 'N. Gregory Mankiw', 'Cengage', 2017, 'Pengantar Ekonomi', ['MR', 'SI'], 888, null, 'Pengantar mikro dan makro ekonomi.'],
                ['9780134527604', 'Management (14th Ed.)', 'Stephen Robbins, Mary Coulter', 'Pearson', 2017, 'Manajemen', ['MR'], 768, null, 'Dasar-dasar manajemen: perencanaan, organisasi, dan pengendalian.'],
            ];

            foreach ($nyata as [$isbn, $judul, $penulis, $penerbit, $tahun, $sub, $prodis, $hal, $file, $sinopsis]) {
                $buku = Buku::create([
                    'isbn' => $isbn, 'judul' => $judul, 'penulis' => $penulis, 'penerbit' => $penerbit,
                    'tahun_terbit' => $tahun, 'kategori_id' => $kat[$sub], 'stok' => fake()->numberBetween(2, 12),
                    'sinopsis' => $sinopsis, 'file_url' => $file, 'sampul' => $cover($isbn), 'jumlah_halaman' => $hal,
                ]);
                foreach ($prodis as $p) {
                    $pivot[] = ['buku_id' => $buku->id, 'prodi_id' => $prodi[$p]];
                }
            }

            // ===== 2. Buku contoh: 10 judul untuk setiap kategori =====
            $template = ['Pengantar %s', 'Dasar-Dasar %s', 'Panduan Praktis %s', 'Konsep dan Penerapan %s', '%s untuk Pemula',
                         'Teori dan Praktik %s', 'Studi Kasus %s', 'Memahami %s', 'Analisis %s', '%s Tingkat Lanjut'];
            $kata1 = ['Senja', 'Rindu', 'Jejak', 'Bayang', 'Hujan', 'Langit', 'Rahasia', 'Pelabuhan', 'Lentera', 'Mimpi', 'Surat', 'Perjalanan'];
            $kata2 = ['di Tepi Toba', 'yang Hilang', 'Terakhir', 'di Ujung Jalan', 'Pertama', 'Sang Penjaga', 'dari Seberang', 'di Balik Kabut', 'Tanpa Nama', 'Kampung Halaman'];
            $penerbitList = ['Erlangga', 'Gramedia Pustaka Utama', 'Andi Offset', 'Informatika Bandung', 'Mizan', 'Salemba Empat',
                             'Penerbit Del Press', 'Elex Media Komputindo', 'Pustaka Pelajar', 'Rajawali Pers', 'Graha Ilmu', 'Deepublish'];
            $semuaProdi = array_keys($prodi);
            $poolBidang = [
                'Teknologi' => ['SI', 'IF', 'TI', 'TK', 'RPL', 'TE'],
                'Artificial Intelligence (AI)' => ['IF', 'AI', 'SI'],
                'Kesehatan' => ['TB', 'BT'], 'Kesehatan & Medis' => ['TB', 'BT'],
                'Lingkungan' => ['TM', 'TB', 'BT', 'MR'], 'Sumber Daya Alam' => ['TB', 'BT', 'MR'],
                'Ekonomi' => ['MR', 'SI'], 'Ekonomi & Pembangunan Nasional' => ['MR', 'SI'],
                'Hukum' => ['SI', 'MR'], 'Politik, Hukum & Keamanan' => ['MR', 'SI'],
            ];
            $poolKhusus = ['Teknik Elektro' => ['TE'], 'Elektronika' => ['TE'], 'Material & Metalurgi' => ['TM'], 'Bioproses & Bioteknologi' => ['TB', 'BT']];

            $urut = 1;
            foreach (Kategori::where('bidang', '!=', 'Tugas Akhir')->orderBy('id')->get() as $k) {
                $fiksi = $k->bidang === 'Fiksi & Sastra Populer';
                $awalan = match (true) {
                    str_starts_with($k->nama_kategori, 'Komik') => 'Komik ',
                    str_starts_with($k->nama_kategori, 'Cergam') => 'Cergam ',
                    str_starts_with($k->nama_kategori, 'Dongeng') => 'Dongeng ',
                    default => '',
                };

                for ($j = 0; $j < 10; $j++) {
                    $judul = $fiksi
                        ? $awalan.$kata1[($j * 5 + $k->id) % count($kata1)].' '.$kata2[($j * 3 + $k->id) % count($kata2)]
                        : sprintf($template[$j], $k->nama_kategori);

                    $buku = Buku::create([
                        'isbn' => sprintf('97860200%05d', $urut++),
                        'judul' => $judul,
                        'penulis' => fake()->name(),
                        'penerbit' => fake()->randomElement($penerbitList),
                        'tahun_terbit' => fake()->numberBetween(2008, 2025),
                        'kategori_id' => $k->id,
                        'stok' => fake()->numberBetween(0, 15),
                        'sinopsis' => $fiksi
                            ? "Karya {$k->nama_kategori} yang ringan dan menghibur, cocok untuk mengisi waktu luang di perpustakaan."
                            : "Buku ini membahas {$k->nama_kategori} secara sistematis, mulai dari konsep dasar hingga penerapannya. Cocok untuk mahasiswa dan pembaca umum yang ingin memperdalam bidang {$k->bidang}.",
                        'jumlah_halaman' => $fiksi ? fake()->numberBetween(40, 360) : fake()->numberBetween(120, 520),
                    ]);

                    if (! $fiksi) {
                        $pool = $poolKhusus[$k->nama_kategori] ?? $poolBidang[$k->bidang] ?? $semuaProdi;
                        foreach (fake()->randomElements($pool, min(count($pool), rand(1, 3))) as $p) {
                            $pivot[] = ['buku_id' => $buku->id, 'prodi_id' => $prodi[$p]];
                        }
                    }
                }
            }

            // ===== 3. Tugas Akhir 2001-2026 (data contoh) =====
            $taSub = [
                'SI' => 'Tugas Akhir S1 Sistem Informasi', 'IF' => 'Tugas Akhir S1 Informatika', 'TE' => 'Tugas Akhir S1 Teknik Elektro',
                'MR' => 'Tugas Akhir S1 Manajemen Rekayasa', 'TM' => 'Tugas Akhir S1 Teknik Metalurgi', 'TB' => 'Tugas Akhir S1 Teknik Bioproses',
                'BT' => 'Tugas Akhir S1 Bioteknologi', 'TI' => 'Tugas Akhir D3 Teknologi Informasi', 'TK' => 'Tugas Akhir D3 Teknologi Komputer',
                'RPL' => 'Tugas Akhir D4 Teknologi Rekayasa Perangkat Lunak', 'AI' => 'Tugas Akhir S2 Artificial Intelligence',
            ];
            $topik = [
                'SI' => ['Sistem Informasi Akademik', 'Aplikasi Peminjaman Aset', 'Dashboard Pelaporan Keuangan', 'Sistem Informasi Desa', 'Aplikasi Pemesanan Layanan'],
                'IF' => ['Klasifikasi Citra Daun', 'Chatbot Layanan Kampus', 'Deteksi Hoaks Berita', 'Aplikasi Mobile Pembelajaran', 'Sistem Rekomendasi Buku'],
                'TE' => ['Monitoring Daya Listrik', 'Sistem Kontrol Suhu Otomatis', 'Inverter Panel Surya', 'Sensor Kelembapan Tanah', 'Pengendali Motor Servo'],
                'MR' => ['Efisiensi Rantai Pasok', 'Perencanaan Produksi', 'Analisis Biaya Proyek', 'Manajemen Risiko Operasional', 'Penjadwalan Tenaga Kerja'],
                'TM' => ['Ketahanan Korosi Baja', 'Paduan Aluminium Ringan', 'Proses Pengecoran Logam', 'Perlakuan Panas Baja', 'Analisis Struktur Mikro'],
                'TB' => ['Fermentasi Biogas', 'Pengolahan Limbah Cair', 'Produksi Bioetanol', 'Ekstraksi Senyawa Alami', 'Pengeringan Hasil Pertanian'],
                'BT' => ['Kultur Jaringan Tanaman', 'Isolasi Bakteri Pengurai', 'Enzim dari Mikroba Lokal', 'Bioremediasi Tanah', 'Pembuatan Biofertilizer'],
                'TI' => ['Jaringan Komputer Kampus', 'Website Profil Sekolah', 'Aplikasi Inventaris', 'Sistem Absensi Digital', 'Server Virtualisasi'],
                'TK' => ['Perakitan dan Perawatan Komputer', 'Jaringan Wireless Asrama', 'Sistem Keamanan CCTV', 'Instalasi Server Linux', 'Pemantauan Jaringan'],
                'RPL' => ['Aplikasi Manajemen Proyek', 'API Layanan Terintegrasi', 'Pengujian Otomatis Aplikasi', 'Aplikasi E-Commerce UMKM', 'Sistem Tiket Layanan'],
                'AI' => ['Model Bahasa untuk Bahasa Daerah', 'Prediksi Curah Hujan', 'Deteksi Anomali Jaringan', 'Pengenalan Ucapan Bahasa Daerah', 'Segmentasi Citra Medis'],
            ];
            $tmplTA = ['Rancang Bangun %s', 'Analisis dan Implementasi %s', 'Pengembangan %s', 'Optimasi %s', 'Studi Kasus %s'];
            $kodeProdi = array_keys($taSub);

            for ($th = 2001; $th <= 2026; $th++) {
                for ($i = 0; $i < 8; $i++) {
                    $kode = $kodeProdi[($th + $i) % 11];
                    $isi = $topik[$kode][($th + $i) % 5];
                    $nama = $taSub[$kode];

                    $buku = Buku::create([
                        'isbn' => sprintf('TA-%d-%s-%02d', $th, $kode, $i + 1),
                        'judul' => sprintf($tmplTA[$i % 5], $isi),
                        'penulis' => fake()->name(),
                        'penerbit' => 'Institut Teknologi Del',
                        'tahun_terbit' => $th,
                        'kategori_id' => $kat[$nama],
                        'stok' => 1,
                        'sinopsis' => "{$nama} tahun {$th}. Dokumen tugas akhir mahasiswa Institut Teknologi Del.",
                        'jumlah_halaman' => fake()->numberBetween(60, 180),
                    ]);
                    $pivot[] = ['buku_id' => $buku->id, 'prodi_id' => $prodi[$kode]];
                }
            }
        });

        foreach (array_chunk($pivot, 500) as $potongan) {
            DB::table('buku_prodi')->insert($potongan);
        }
    }
}