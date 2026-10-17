<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class BukuUjiSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = Kategori::firstOrCreate(
            ['kode_kategori' => 'UJI'],
            ['nama_kategori' => 'Buku Uji Transaksi', 'bidang' => 'Teknologi']
        );

        foreach ([
            ['9780000000001', 'Uji Transaksi ACID (Stok Nol)', 0],
            ['9780000000002', 'Uji Concurrency Locking (Stok Satu)', 1],
        ] as [$isbn, $judul, $stok]) {
            Buku::updateOrCreate(['isbn' => $isbn], [
                'judul' => $judul,
                'penulis' => 'Tim SIPUS-Del',
                'penerbit' => 'Institut Teknologi Del',
                'tahun_terbit' => 2026,
                'kategori_id' => $kategori->id,
                'stok' => $stok,
                'sinopsis' => 'Buku khusus untuk menguji penolakan stok 0 dan penguncian baris (lockForUpdate).',
            ]);
        }
    }
}