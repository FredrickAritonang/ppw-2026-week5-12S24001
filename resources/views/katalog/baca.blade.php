<x-layout :title="'Baca: ' . $buku->judul" heading="Baca Online" :crumbs="['Katalog Buku & E-Book' => route('katalog.index'), $buku->judul => route('katalog.show', $buku), 'Baca Online']">
    <div class="panel-box">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div><div class="fw-bold text-del fs-5">{{ $buku->judul }}</div><small class="text-muted">{{ $buku->penulis }}</small></div>
            <div class="d-flex gap-2">
                <a href="{{ $buku->file_url }}" target="_blank" rel="noopener" class="btn btn-del"><i class="bi bi-box-arrow-up-right me-1"></i>Buka di tab baru</a>
                <a href="{{ route('katalog.show', $buku) }}" class="btn btn-light border">Kembali</a>
            </div>
        </div>
        <iframe src="{{ $buku->file_url }}" class="reader-frame" title="Pembaca {{ $buku->judul }}"></iframe>
        <small class="text-muted d-block mt-2">Jika halaman kosong, situs sumber melarang ditampilkan di sini. Gunakan tombol "Buka di tab baru".</small>
    </div>

    <x-slot:panel>
        <div class="panel-box text-center"><x-cover :buku="$buku" size="xl" /></div>
    </x-slot:panel>
</x-layout>