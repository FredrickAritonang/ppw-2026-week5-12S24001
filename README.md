# SIPUS-Del: Sistem Informasi Perpustakaan Kampus Del

Tugas Mandiri Minggu 5, Pemrograman & Pengujian Web (12S3101), Institut Teknologi Del.
Dibuat dengan Laravel 11 (arsitektur MVC), Eloquent ORM, Blade, dan Bootstrap 5.

**Mahasiswa:** Fredrick Laurensius Aritonang
**NIM:** 12S24001
**Prodi:** S1 Sistem Informasi

## Fitur
- CRUD Buku dan Kategori (`Route::resource`, 7 aksi, Implicit Route Model Binding)
- Relasi Kategori hasMany Buku, Buku belongsTo Kategori
- Validasi server-side dan proteksi CSRF
- Layout Blade component `<x-layout>` tema Del Purple (#4C1D95), pagination, flash message, `@forelse`
- Seeder dan Factory dengan Faker:
  - `FactoryBukuSeeder` membuat 5 kategori dan 20 buku lewat `Kategori::factory()->has(Buku::factory()->count(4))`
  - `BukuSeeder` menambah koleksi realistis (30 kategori, sekitar 100 buku, dan data tugas akhir)
- Audit kueri SQL untuk membuktikan Eager Loading

## Instalasi
```bash
git clone https://github.com/<username>/ppw-2026-week5-12S24001.git
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

## Bukti Audit SQL (Eager Loading, tanpa N+1)
Setiap kueri pada request web dicatat oleh `DB::listen` di `AppServiceProvider` dengan awalan `[SQL AUDIT]`.
Log dari `storage/logs/laravel.log` saat membuka `/buku` (10 buku per halaman), dengan kueri bawaan sesi, autentikasi, dan notifikasi header tidak ditampilkan:
```
select count(*) as aggregate from `bukus`
select * from `bukus` order by `created_at` desc limit 10 offset 0
select * from `kategoris` where `kategoris`.`id` in (1, 3, 4, 5)
```
Hanya 3 kueri untuk daftar buku. Kategori untuk 10 buku diambil dalam satu kueri `in (...)` berkat `Buku::with('kategori')`. Tanpa eager loading, halaman yang sama akan menjalankan 1 kueri tambahan per buku (masalah N+1).