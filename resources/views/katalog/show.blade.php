@php
    $anggota = in_array(auth()->user()->role, ['mahasiswa', 'dosen']);
    $pengelola = in_array(auth()->user()->role, ['pustakawan', 'staf_it']);
@endphp
<x-layout :title="$buku->judul" :heading="$buku->judul" :crumbs="['Katalog Buku & E-Book' => route('katalog.index'), $buku->judul]">
    <div class="panel-box">
        <div class="row g-4">
            <div class="col-md-5 text-center">
                <a href="#" data-bs-toggle="modal" data-bs-target="#zoomSampul" title="Klik untuk memperbesar"><x-cover :buku="$buku" size="xl" /></a>
                <small class="text-muted d-block mt-2"><i class="bi bi-zoom-in me-1"></i>Klik sampul untuk memperbesar</small>
            </div>
            <div class="col-md-7">
                <div class="text-muted mb-2"><i class="bi bi-person-fill me-1"></i>{{ $buku->penulis }}</div>
                <div class="mb-3">
                    <span class="tag">{{ $buku->kategori->bidang }}</span>
                    <span class="tag">{{ $buku->kategori->nama_kategori }}</span>
                    @foreach($buku->prodis as $p)<span class="tag tag-info">{{ $p->jenjang }} {{ $p->nama }}</span>@endforeach
                </div>
                <div class="info-row"><span>ISBN</span><span>{{ $buku->isbn }}</span></div>
                <div class="info-row"><span>Penerbit</span><span>{{ $buku->penerbit }}</span></div>
                <div class="info-row"><span>Tahun Terbit</span><span>{{ $buku->tahun_terbit }}</span></div>
                <div class="info-row"><span>Jumlah Halaman</span><span>{{ $buku->jumlah_halaman ?? '-' }}</span></div>
                <div class="info-row"><span>Ketersediaan</span><span>{{ $buku->stok > 0 ? $buku->stok . ' eksemplar' : 'Habis' }}</span></div>
                <p class="mt-3">{{ $buku->sinopsis ?: 'Belum ada sinopsis.' }}</p>
                <div class="d-flex flex-wrap gap-2">
                    @if($anggota)
                        @if($sedangDipinjam)
                            <span class="btn btn-light border disabled">Sedang Anda pinjam</span>
                        @else
                            <form method="POST" action="{{ route('sirkulasi.pinjam', $buku) }}">@csrf
                                <button class="btn btn-del" @disabled($buku->stok == 0)><i class="bi bi-bag-plus-fill me-1"></i>Pinjam</button>
                            </form>
                        @endif
                    @endif
                    @if($buku->file_url)<a href="{{ route('katalog.baca', $buku) }}" class="btn btn-success"><i class="bi bi-book-half me-1"></i>Baca Online</a>@endif
                    @if($pengelola)<a href="{{ route('buku.edit', $buku) }}" class="btn btn-warning"><i class="bi bi-pencil-fill me-1"></i>Edit Data</a>@endif
                    <a href="{{ route('katalog.index') }}" class="btn btn-light border">Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="zoomSampul" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width:520px">
            <div class="modal-content border-0 bg-transparent">
                <div class="modal-body text-center"><x-cover :buku="$buku" size="xl" /></div>
            </div>
        </div>
    </div>

    <x-slot:panel>
        <div class="panel-box">
            <h6>Buku Terkait</h6>
            @forelse($terkait as $t)
                <a href="{{ route('katalog.show', $t) }}" class="d-flex gap-3 align-items-center text-decoration-none mb-3">
                    <x-cover :buku="$t" size="sm" /><span class="small text-dark fw-semibold">{{ \Illuminate\Support\Str::limit($t->judul, 45) }}</span>
                </a>
            @empty
                <small class="text-muted">Tidak ada buku terkait.</small>
            @endforelse
        </div>
    </x-slot:panel>
</x-layout>