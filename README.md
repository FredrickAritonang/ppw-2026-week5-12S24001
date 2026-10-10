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
Buat database MySQL kosong (contoh `week5`), lalu sesuaikan `DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` di `.env`. Setelah itu:
```bash
php artisan migrate:fresh --seed
php artisan serve
```
Buka http://127.0.0.1:8000

### Alamat lokal yang lebih rapi (opsional)
Agar aplikasi dapat dibuka di http://sipus-del.test:8000:

1. Tambahkan `127.0.0.1 sipus-del.test` ke `C:\Windows\System32\drivers\etc\hosts` (jalankan editor atau PowerShell sebagai Administrator).
2. Tambahkan tiga baris ini di `.env`, lalu jalankan `php artisan config:clear`:
```env
   APP_URL=http://sipus-del.test:8000
   SERVER_HOST=sipus-del.test
   SERVER_PORT=8000
```
3. Jalankan server seperti biasa:
```bash
   php artisan serve
```

File `.env.example` tetap memakai alamat `127.0.0.1:8000` agar `php artisan serve` biasa berjalan untuk siapa pun yang meng-clone repositori.

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