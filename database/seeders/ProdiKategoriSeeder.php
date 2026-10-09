<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Prodi;
use Illuminate\Database\Seeder;

class ProdiKategoriSeeder extends Seeder
{
    public function run(): void
    {
        $prodi = [
            ['SI', 'Sistem Informasi', 'S1'], ['IF', 'Informatika', 'S1'], ['TE', 'Teknik Elektro', 'S1'],
            ['MR', 'Manajemen Rekayasa', 'S1'], ['TM', 'Teknik Metalurgi', 'S1'], ['TB', 'Teknik Bioproses', 'S1'],
            ['BT', 'Bioteknologi', 'S1'], ['TI', 'Teknologi Informasi', 'D3'], ['TK', 'Teknologi Komputer', 'D3'],
            ['RPL', 'Teknologi Rekayasa Perangkat Lunak', 'D4'], ['AI', 'Artificial Intelligence', 'S2'],
        ];
        foreach ($prodi as [$kode, $nama, $jenjang]) {
            Prodi::create(['kode' => $kode, 'nama' => $nama, 'jenjang' => $jenjang]);
        }

        $bidang = [
            'Teknologi' => ['TEK', ['Pemrograman', 'Basis Data', 'Jaringan Komputer', 'Rekayasa Perangkat Lunak', 'Pengembangan Web', 'Aplikasi Mobile', 'Keamanan Siber', 'Sistem Operasi', 'Cloud Computing', 'Internet of Things', 'Data Science', 'Sistem Informasi', 'Teknik Elektro', 'Elektronika', 'Material & Metalurgi', 'Bioproses & Bioteknologi']],
            'Artificial Intelligence (AI)' => ['AIN', ['Machine Learning', 'Deep Learning', 'Computer Vision', 'Natural Language Processing', 'Robotika', 'Data Mining', 'Etika AI', 'Generative AI']],
            'Pendidikan' => ['PEN', ['Teori Pembelajaran', 'Kurikulum dan Pengajaran', 'Metodologi Penelitian', 'Bahasa Indonesia', 'Bahasa Inggris', 'Matematika', 'Fisika', 'Teknologi Pendidikan', 'Penulisan Ilmiah']],
            'Hukum' => ['HUK', ['Hukum Pidana', 'Hukum Perdata', 'Hukum Tata Negara', 'Hukum Bisnis', 'Hukum Siber dan ITE', 'Hak Kekayaan Intelektual', 'Hukum Ketenagakerjaan', 'Hukum Lingkungan']],
            'Ekonomi' => ['EKO', ['Pengantar Ekonomi', 'Ekonomi Mikro', 'Ekonomi Makro', 'Manajemen', 'Manajemen Proyek', 'Akuntansi', 'Keuangan dan Perbankan', 'Pemasaran Digital', 'Bisnis Digital']],
            'Kesehatan' => ['KES', ['Kesehatan Masyarakat', 'Gizi dan Pangan', 'Kesehatan Mental', 'Biokimia', 'Biologi Molekuler', 'Mikrobiologi', 'Farmasi Dasar', 'Keselamatan dan Kesehatan Kerja']],
            'Keluarga' => ['KEL', ['Pola Asuh Anak', 'Keharmonisan Rumah Tangga', 'Pendidikan Keluarga', 'Keuangan Keluarga', 'Kesehatan Keluarga', 'Parenting Digital', 'Keluarga dan Lansia']],
            'Sosial Budaya' => ['SBD', ['Antropologi', 'Sosiologi', 'Budaya Batak', 'Budaya Nusantara', 'Sejarah Indonesia', 'Komunikasi Sosial', 'Seni dan Desain', 'Sastra Indonesia']],
            'Keagamaan' => ['AGM', ['Etika Beragama', 'Teologi', 'Moralitas dan Karakter', 'Pendidikan Agama', 'Filsafat', 'Kerukunan Umat Beragama', 'Spiritualitas']],
            'Kepemudaan' => ['PMD', ['Kepemimpinan', 'Organisasi Kemahasiswaan', 'Kewirausahaan Muda', 'Pengembangan Diri', 'Soft Skill', 'Pelayanan Masyarakat']],
            'Lingkungan' => ['LNG', ['Ekologi', 'Perubahan Iklim', 'Energi Terbarukan', 'Pengelolaan Limbah', 'Konservasi Alam', 'Pertanian Berkelanjutan', 'Teknologi Hijau']],
            'Kebangsaan' => ['KBG', ['Pancasila', 'UUD 1945', 'Kewarganegaraan', 'Wawasan Nusantara', 'Demokrasi', 'Anti-Korupsi', 'Bela Negara']],

            // ===== Baru =====
            'Fiksi & Sastra Populer' => ['FIK', ['Cerpen', 'Komik', 'Novel', 'Cergam (Cerita Bergambar)', 'Dongeng & Fabel']],
            'Kesehatan & Medis' => ['KMD', ['Kedokteran Dasar', 'Keperawatan', 'Gizi Klinis', 'Farmasi Klinis', 'Kesehatan Reproduksi', 'Penyakit Menular', 'Gawat Darurat', 'Kesehatan Anak']],
            'Politik, Hukum & Keamanan' => ['PHK', ['Politik dan Keamanan', 'Hukum dan Peraturan Perundangan', 'Hak Asasi Manusia', 'Imigrasi', 'Pemasyarakatan', 'Kejaksaan', 'Tentara Nasional', 'Kepolisian', 'Intelijen Negara (BIN)', 'Pertahanan', 'Luar Negeri', 'Dalam Negeri', 'Sekretaris Negara']],
            'Ekonomi & Pembangunan Nasional' => ['EPN', ['Industri', 'Perekonomian', 'Keuangan Negara', 'Perdagangan', 'Investasi dan Hilirisasi', 'Koperasi', 'UMKM', 'BUMN', 'Ekonomi Nasional', 'Perencanaan Pembangunan Nasional', 'Pariwisata', 'Ekonomi Kreatif', 'Hilirisasi dan Transisi Energi', 'Pangan']],
            'Infrastruktur & Wilayah' => ['INF', ['Infrastruktur dan Pembangunan Kewilayahan', 'Pekerjaan Umum', 'Perumahan dan Kawasan Permukiman', 'Desa dan Pembangunan Daerah Tertinggal', 'Transmigrasi', 'Perhubungan', 'Agraria dan Tata Ruang']],
            'Sumber Daya Alam' => ['SDA', ['Pertanian', 'Kehutanan', 'Kelautan dan Perikanan', 'Lingkungan Hidup']],
            'Sosial & Kemasyarakatan' => ['SOS', ['Pembangunan Manusia dan Kebudayaan', 'Pemberdayaan Masyarakat', 'Agama dan Kehidupan Beragama', 'Pendidikan Dasar dan Menengah', 'Pendidikan Tinggi, Sains, dan Teknologi', 'Kebudayaan', 'Sosial', 'Ketenagakerjaan', 'Perlindungan Pekerja Migran', 'Kependudukan dan Pembangunan Keluarga', 'Pemberdayaan Perempuan dan Perlindungan Anak', 'Pemuda dan Olahraga', 'Haji dan Umrah']],
            'Tata Kelola & Digital' => ['TKD', ['Pendayagunaan Aparatur dan Reformasi Birokrasi', 'Komunikasi dan Digital', 'Komunikasi Publik']],
            'Tugas Akhir' => ['TGA', ['Tugas Akhir S1 Sistem Informasi', 'Tugas Akhir S1 Informatika', 'Tugas Akhir S1 Teknik Elektro', 'Tugas Akhir S1 Manajemen Rekayasa', 'Tugas Akhir S1 Teknik Metalurgi', 'Tugas Akhir S1 Teknik Bioproses', 'Tugas Akhir S1 Bioteknologi', 'Tugas Akhir D3 Teknologi Informasi', 'Tugas Akhir D3 Teknologi Komputer', 'Tugas Akhir D4 Teknologi Rekayasa Perangkat Lunak', 'Tugas Akhir S2 Artificial Intelligence']],
        ];

        foreach ($bidang as $namaBidang => [$kode, $subs]) {
            foreach ($subs as $i => $sub) {
                Kategori::create([
                    'kode_kategori' => sprintf('%s-%02d', $kode, $i + 1),
                    'nama_kategori' => $sub,
                    'bidang' => $namaBidang,
                ]);
            }
        }
    }
}