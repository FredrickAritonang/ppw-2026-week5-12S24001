<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class PustakawanUjiSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'pustakawan@del.ac.id'],
            [
                'name' => 'Pustakawan Uji',
                'username' => 'pustakawan',
                'role' => 'pustakawan',
                'password' => 'password123',
                'nomor_induk' => 'PEG-UJI01',
                'prodi' => 'UPT Perpustakaan',
                'jabatan' => 'Staf Perpustakaan',
                'status_kerja' => 'Pegawai Tetap',
                'telepon' => '081200000000',
            ]
        );
    }
}