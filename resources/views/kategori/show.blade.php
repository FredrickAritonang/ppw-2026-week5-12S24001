<x-layout title="Detail Kategori">
    <div class="card card-body shadow-sm">
        <h4 class="text-del">{{ $kategori->nama_kategori }} <small class="text-muted">({{ $kategori->kode_kategori }})</small></h4>
        <h6 class="mt-3">Daftar Buku</h6>
        <ul>
            @forelse($kategori->bukus as $buku)
                <li><a href="{{ route('buku.show', $buku) }}">{{ $buku->judul }}</a> — {{ $buku->penulis }}</li>
            @empty
                <li class="text-muted">Belum ada buku di kategori ini.</li>
            @endforelse
        </ul>
        <div>
            <a href="{{ route('kategori.edit', $kategori) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</x-layout>