<x-layout title="Detail Buku">
    <div class="card card-body shadow-sm">
        <h4 class="text-del">{{ $buku->judul }}</h4>
        <p class="text-muted">{{ $buku->penulis }} · {{ $buku->penerbit }} ({{ $buku->tahun_terbit }})</p>
        <p>
            <strong>ISBN:</strong> {{ $buku->isbn }} |
            <strong>Kategori:</strong> {{ $buku->kategori->nama_kategori }} |
            <strong>Stok:</strong> {{ $buku->stok }}
        </p>
        <p>{{ $buku->sinopsis ?: 'Tidak ada sinopsis.' }}</p>
        <div>
            <a href="{{ route('buku.edit', $buku) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('buku.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</x-layout>