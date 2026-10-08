@php
    $cover = [
        'linear-gradient(135deg,#4C1D95,#7C3AED)',
        'linear-gradient(135deg,#0369A1,#0EA5E9)',
        'linear-gradient(135deg,#047857,#10B981)',
        'linear-gradient(135deg,#B45309,#F59E0B)',
        'linear-gradient(135deg,#BE185D,#EC4899)',
        'linear-gradient(135deg,#B91C1C,#F97316)',
    ];
@endphp

<x-layout title="Daftar Buku">
    <div class="hero d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h2><i class="bi bi-journal-bookmark-fill me-2"></i>Koleksi Buku</h2>
            <p>Kelola seluruh koleksi Perpustakaan Kampus Institut Teknologi Del.</p>
            <span class="hero-stat"><i class="bi bi-collection-fill"></i>{{ $bukus->total() }} buku terdaftar</span>
        </div>
        <a href="{{ route('buku.create') }}" class="btn btn-hero"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Buku</a>
    </div>

    @if($bukus->count())
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
            @foreach($bukus as $buku)
                <div class="col">
                    <div class="book-card h-100">
                        <div class="cover" style="background: {{ $cover[$buku->kategori_id % count($cover)] }}">
                            <i class="bi bi-book-half cover-icon"></i>
                            <div class="cover-top">
                                <span class="chip"><i class="bi bi-tag-fill me-1"></i>{{ $buku->kategori->nama_kategori }}</span>
                                @if($buku->stok == 0)
                                    <span class="stok-chip stok-out"><i class="bi bi-x-circle-fill me-1"></i>Habis</span>
                                @elseif($buku->stok < 5)
                                    <span class="stok-chip stok-low"><i class="bi bi-exclamation-circle-fill me-1"></i>Stok {{ $buku->stok }}</span>
                                @else
                                    <span class="stok-chip stok-ok"><i class="bi bi-check-circle-fill me-1"></i>Stok {{ $buku->stok }}</span>
                                @endif
                            </div>
                            <h5 class="cover-title">{{ $buku->judul }}</h5>
                            <div class="cover-author"><i class="bi bi-person-circle me-1"></i>{{ $buku->penulis }}</div>
                        </div>

                        <div class="book-body">
                            <div class="meta">
                                <div>
                                    <span class="label">ISBN</span>
                                    <span class="val">{{ $buku->isbn }}</span>
                                </div>
                                <div>
                                    <span class="label">Tahun</span>
                                    <span class="val">{{ $buku->tahun_terbit }}</span>
                                </div>
                                <div class="full">
                                    <span class="label">Penerbit</span>
                                    <span class="val">{{ $buku->penerbit }}</span>
                                </div>
                            </div>

                            <div class="actions">
                                <a href="{{ route('buku.show', $buku) }}" class="btn-detail"><i class="bi bi-eye-fill me-1"></i>Detail</a>
                                <a href="{{ route('buku.edit', $buku) }}" class="btn-icon btn-edit" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                                <form action="{{ route('buku.destroy', $buku) }}" method="POST" onsubmit="return confirm('Hapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-icon btn-remove" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-journal-x display-3 d-block mb-3"></i>
            <h5>Belum ada data buku</h5>
            <p class="mb-0">Klik "Tambah Buku" untuk mulai mengisi koleksi.</p>
        </div>
    @endif

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-4">
        <small class="text-muted">Menampilkan {{ $bukus->firstItem() ?? 0 }}–{{ $bukus->lastItem() ?? 0 }} dari {{ $bukus->total() }} buku</small>
        {{ $bukus->onEachSide(1)->links() }}
    </div>
</x-layout>