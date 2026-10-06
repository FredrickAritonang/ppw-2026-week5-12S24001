<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    private function rules(?Buku $buku = null): array
    {
        return [
            'isbn' => ['required', 'string', 'max:20', 'unique:bukus,isbn' . ($buku ? ',' . $buku->id : '')],
            'judul' => 'required|string|min:5|max:200',
            'penulis' => 'required|string|max:150',
            'penerbit' => 'required|string|max:150',
            'tahun_terbit' => 'required|integer|between:1900,' . date('Y'),
            'kategori_id' => 'required|exists:kategoris,id',
            'stok' => 'required|integer|min:0',
            'sinopsis' => 'nullable|string',
        ];
    }

    public function index()
    {
        $bukus = Buku::with('kategori')->latest()->paginate(10);
        return view('buku.index', compact('bukus'));
    }

    public function create()
    {
        return view('buku.create', ['kategoris' => Kategori::orderBy('nama_kategori')->get()]);
    }

    public function store(Request $request)
    {
        Buku::create($request->validate($this->rules()));
        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function show(Buku $buku)
    {
        return view('buku.show', ['buku' => $buku->load('kategori')]);
    }

    public function edit(Buku $buku)
    {
        return view('buku.edit', [
            'buku' => $buku,
            'kategoris' => Kategori::orderBy('nama_kategori')->get(),
        ]);
    }

    public function update(Request $request, Buku $buku)
    {
        $buku->update($request->validate($this->rules($buku)));
        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}