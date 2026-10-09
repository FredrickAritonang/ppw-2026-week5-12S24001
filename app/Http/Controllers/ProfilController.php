<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Support\Peran;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function index()
    {
        $u = Auth::user();

        return view('profil.index', [
            'u' => $u,
            'peran' => Peran::DATA[$u->role],
            'pendidikan' => $this->pendidikan($u->name),
            'riwayat' => Peminjaman::with('buku.kategori')->where('user_id', $u->id)->latest('tgl_pinjam')->take(10)->get(),
        ]);
    }

    /** Perkiraan jenjang pendidikan dari gelar yang tertulis pada nama. */
    private function pendidikan(string $nama): array
    {
        $hasil = [];
        if (stripos($nama, 'Ph.D') !== false || preg_match('/^(Prof\. )?Dr\.? /', $nama) || str_contains($nama, 'Dr. Ir.')) {
            $hasil[] = ['S3', 'Doktor'];
        }
        if (preg_match('/\b(M\.[A-Za-z]|MT\b|MSc\b|MH\b|MS\b|MM\b|MAB\b|MBA\b|MIM\b|M\.ISD)/', $nama)) {
            $hasil[] = ['S2', 'Magister'];
        }
        if (preg_match('/\b(S\.[A-Za-z]|ST\b|SST\b|SH\b|SP\b|SS\b|SPd\b|A\.Md)/', $nama)) {
            $hasil[] = ['S1', 'Sarjana'];
        }

        return $hasil;
    }
}