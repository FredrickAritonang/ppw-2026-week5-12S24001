<x-layout :title="$judul" :heading="$judul" :crumbs="[$judul]">
    <div class="empty-state">
        <i class="bi bi-tools display-3 d-block mb-3"></i>
        <h5>Fitur sedang disiapkan</h5>
        <p class="mb-3">Halaman <b>{{ $judul }}</b> akan tersedia di tahap berikutnya.</p>
        <a href="{{ route('katalog.index') }}" class="btn btn-del">Kembali ke Katalog</a>
    </div>
</x-layout>