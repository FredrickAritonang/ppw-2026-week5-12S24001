<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\ProfilContoh;
use Illuminate\Console\Command;

class SinkronProdiDosen extends Command
{
    protected $signature = 'dosen:sinkron-prodi';
    protected $description = 'Perbarui prodi semua dosen dari database/data/prodi-dosen.csv';

    public function handle(): int
    {
        $cocok = 0;
        $kosong = [];

        foreach (User::where('role', 'dosen')->get() as $u) {
            $prodi = ProfilContoh::untuk($u->username, 'dosen', $u->name)['prodi'];
            $u->prodi = $prodi;
            $u->save();

            if ($prodi === '-') {
                $kosong[] = $u->username.'  ('.$u->name.')';
            } else {
                $cocok++;
            }
        }

        $this->info("Prodi terisi: {$cocok} dosen");
        $this->warn('Tanpa prodi: '.count($kosong).' dosen');
        foreach ($kosong as $k) {
            $this->line(' - '.$k);
        }

        return self::SUCCESS;
    }
}