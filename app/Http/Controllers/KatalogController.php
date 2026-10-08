<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $bukus = Buku::with('kategori')
            ->when($request->q, fn ($q, $v) => $q->where(fn ($w) => $w
                ->where('judul', 'like', "%{$v}%")
                ->orWhere('penulis', 'like', "%{$v}%")
                ->orWhere('isbn', 'like', "%{$v}%")))
            ->when($request->bidang, fn ($q, $v) => $q->whereHas('kategori', fn ($k) => $k->where('bidang', $v)))
            ->when($request->kategori, fn ($q, $v) => $q->where('kategori_id', $v))
            ->when($request->prodi, fn ($q, $v) => $q->whereHas('prodis', fn ($p) => $p->where('prodis.id', $v)))
            ->when($request->ebook, fn ($q) => $q->whereNotNull('file_url'))
            ->when($request->tersedia, fn ($q) => $q->where('stok', '>', 0))
            ->orderBy('judul')
            ->paginate(12)
            ->withQueryString();

        return view('katalog.index', [
            'bukus' => $bukus,
            'bidangs' => Kategori::whereNotNull('bidang')->distinct()->orderBy('bidang')->pluck('bidang'),
            'kategoris' => Kategori::orderBy('bidang')->orderBy('nama_kategori')->get()->groupBy('bidang'),
            'prodis' => Prodi::orderBy('jenjang')->orderBy('nama')->get(),
            'dipinjam' => Peminjaman::aktif()->where('user_id', Auth::id())->pluck('buku_id')->all(),
        ]);
    }

    public function show(Buku $buku)
    {
        $buku->load(['kategori', 'prodis']);

        return view('katalog.show', [
            'buku' => $buku,
            'terkait' => Buku::with('kategori')->where('kategori_id', $buku->kategori_id)->where('id', '!=', $buku->id)->take(4)->get(),
            'sedangDipinjam' => Peminjaman::aktif()->where('user_id', Auth::id())->where('buku_id', $buku->id)->exists(),
        ]);
    }

    public function baca(Buku $buku)
    {
        abort_unless($buku->file_url, 404, 'E-book belum tersedia untuk judul ini.');

        return view('katalog.baca', compact('buku'));
    }
}