@php
    $cover = ['#0F766E', '#0369A1', '#4D7C0F', '#B45309', '#BE185D', '#B91C1C'];
@endphp

<x-layout title="Daftar Kategori">
    <div class="hero d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h2><i class="bi bi-tags-fill me-2"></i>Kategori Buku</h2>
            <p>Kelompokkan koleksi perpustakaan agar mudah ditemukan.</p>
            <span class="hero-stat"><i class="bi bi-grid-fill"></i>{{ $kategoris->total() }} kategori</span>
        </div>
        <a href="{{ route('kategori.create') }}" class="btn btn-hero"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Kategori</a>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
        @forelse($kategoris as $kategori)
            <div class="col">
                <div class="book-card h-100">
                    <div class="cover" style="background: {{ $cover[$kategori->id % count($cover)] }}; min-height: 150px;">
                        <i class="bi bi-collection-fill cover-icon"></i>
                        <div class="cover-top">
                            <span class="chip">{{ $kategori->kode_kategori }}</span>
                            <span class="stok-chip stok-ok"><i class="bi bi-book-fill me-1"></i>{{ $kategori->bukus_count }} buku</span>
                        </div>
                        <h5 class="cover-title" style="margin-top: 2.5rem;">{{ $kategori->nama_kategori }}</h5>
                    </div>
                    <div class="book-body">
                        <div class="actions">
                            <a href="{{ route('kategori.show', $kategori) }}" class="btn-detail"><i class="bi bi-eye-fill me-1"></i>Detail</a>
                            <a href="{{ route('kategori.edit', $kategori) }}" class="btn-icon btn-edit" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('kategori.destroy', $kategori) }}" method="POST" onsubmit="return confirm('Hapus kategori ini beserta seluruh bukunya?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn-icon btn-remove" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-inbox display-3 d-block mb-3"></i>
                    <h5>Belum ada data kategori</h5>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $kategoris->links() }}</div>
</x-layout>