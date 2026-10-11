# SIPUS-Del: Pembangunan Sistem Informasi Perpustakaan Kampus Del

**Judul Penugasan Mandiri:** Pembangunan Sistem Informasi Perpustakaan Kampus Del (SIPUS-Del): Implementasi Arsitektur MVC Lengkap, Relasi Eloquent Multi-Entitas, Validasi Form Server-Side, dan Audit Kueri Kinerja pada Laravel 11.x

Tugas Mandiri Minggu 5, Pemrograman & Pengujian Web (12S3101), Institut Teknologi Del.
Dibuat dengan Laravel 11 (arsitektur MVC), Eloquent ORM, Blade, dan Bootstrap 5.

**Mahasiswa:** Fredrick Laurensius Aritonang
**NIM:** 12S24001
**Prodi:** S1 Sistem Informasi

## Fitur
- CRUD Buku dan Kategori (`Route::resource`, 7 aksi, Implicit Route Model Binding)
- Relasi Eloquent multi-entitas (Kategori, Buku, Prodi, Peminjaman, User, Usulan, Notifikasi, Bebas Pustaka), termasuk Kategori hasMany Buku, Buku belongsTo Kategori, dan Buku belongsToMany Prodi
- Validasi server-side dan proteksi CSRF
- Layout Blade component `<x-layout>` tema Del Purple (#4C1D95), pagination, flash message, `@forelse`
- Seeder dan Factory dengan Faker:
  - `FactoryBukuSeeder` membuat 5 kategori dan 20 buku lewat `Kategori::factory()->has(Buku::factory()->count(4))`
  - `BukuSeeder` menambah koleksi realistis (30 kategori, sekitar 100 buku, dan data tugas akhir)
  - `EbookSeeder` menambah 100 e-book terbuka yang dapat dibaca online
- Audit kueri SQL untuk membuktikan Eager Loading

## Instalasi
```bash
git clone https://github.com/FredrickAritonang/ppw-2026-week5-12S24001.git
cd ppw-2026-week5-12S24001
composer install
copy .env.example .env
php artisan key:generate
```
Buat database MySQL kosong (contoh `week5`), lalu sesuaikan `DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` di `.env`.

### Jalankan aplikasi
```bash
php artisan migrate:fresh --seed
php artisan serve
```
Buka **http://sipus-del.test:8000**

## Akun Demo (Login)
Seluruh akun dibuat otomatis oleh `PenggunaSeeder` saat `php artisan migrate:fresh --seed`.

**Password untuk semua akun: `PustakaDel2026`**

Username diisi di kolom **Username** pada halaman login. Kolom itu juga menerima nama lengkap akun.

| Peran | Cara menentukan username | Contoh username |
|---|---|---|
| Mahasiswa | `iss` + 5 digit terakhir NIM (NIM `12S24001` menjadi `iss24001`) | `iss24001` |
| Dosen | nama tanpa gelar, huruf kecil, tanpa spasi atau titik | pilih dari saran di kolom username |
| Pegawai Perpustakaan (pustakawan) | nama tanpa gelar, huruf kecil, tanpa spasi | `jesichaulinahutabarat`, `enjelinalimbong`, `yuliasaritripena`, `tiurmalumbangaol` |
| Staf Akademik | `stafakademik` + nomor | `stafakademik1`, `stafakademik2` |

Hak akses tiap peran:
- **Mahasiswa**: mencari katalog dan membaca e-book online, meminjam maksimal 3 buku selama 7 hari (perpanjang 1x), mengusulkan buku, membayar denda, dan mengajukan surat bebas pustaka.
- **Dosen**: sama seperti mahasiswa, dengan batas 10 buku selama 30 hari (perpanjang 2x), tanpa pengajuan bebas pustaka.
- **Pegawai Perpustakaan**: mengelola buku dan kategori (CRUD), mencatat sirkulasi, memproses usulan, denda, dan bebas pustaka.
- **Staf Akademik**: mengelola buku, kategori, dan data pengguna.

Untuk mencoba CRUD Buku dan Kategori, login sebagai `jesichaulinahutabarat` atau `stafakademik1`.

Daftar lengkap akun juga ditulis ke `storage/app/akun-demo.csv` setelah seeding.

> Akun dan password ini hanya untuk keperluan demo tugas. Jangan dipakai di lingkungan produksi.

## Bukti Audit SQL (Eager Loading, tanpa N+1)
Setiap kueri pada request web dicatat oleh `DB::listen` di `AppServiceProvider` dengan awalan `[SQL AUDIT]`.
Log dari `storage/logs/laravel.log` saat membuka `/buku` (10 buku per halaman), dengan kueri bawaan sesi, autentikasi, dan notifikasi header tidak ditampilkan:
```
select count(*) as aggregate from `bukus`
select * from `bukus` order by `created_at` desc limit 10 offset 0
select * from `kategoris` where `kategoris`.`id` in (1, 3, 4, 5)
```
Hanya 3 kueri untuk daftar buku. Kategori untuk 10 buku diambil dalam satu kueri `in (...)` berkat `Buku::with('kategori')`. Tanpa eager loading, halaman yang sama akan menjalankan 1 kueri tambahan per buku (masalah N+1).

Halaman Bebas Pustaka untuk pustakawan juga bebas N+1: jumlah pinjaman aktif dan denda semua pemohon dihitung sekaligus di controller (`whereIn` + `groupBy`), sehingga View tidak menjalankan kueri.