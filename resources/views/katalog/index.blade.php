@php $anggota = in_array(auth()->user()->role, ['mahasiswa', 'dosen']); @endphp
<x-layout title="Katalog Buku" heading="Katalog Buku & E-Book" :crumbs="['Katalog Buku & E-Book']">
    <div class="row g-3 mb-3">
        @foreach([
            ['Total Judul Buku', $statistik['total_judul'], 'bi-journal-bookmark-fill', '#4C1D95'],
            ['Total Stok Tersedia', $statistik['total_stok'], 'bi-stack', '#0F766E'],
            ['Sedang Dipinjam', $statistik['sedang_dipinjam'], 'bi-arrow-left-right', '#B45309'],
            ['Total Dikembalikan', $statistik['dikembalikan'], 'bi-check2-circle', '#1D4ED8'],
        ] as [$label, $nilai, $ikon, $warna])
            <div class="col-6 col-xl-3">
                <div class="stat-card" style="background:{{ $warna }}">
                    <div><div class="n">{{ number_format($nilai, 0, ',', '.') }}</div><small>{{ $label }}</small></div>
                    <i class="bi {{ $ikon }}"></i>
                </div>
            </div>
        @endforeach
    </div>

    <form method="GET" class="panel-box">
        <div class="row g-2 align-items-end">
            <div class="col-lg-4">
                <label class="form-label small mb-1">Cari judul, penulis, atau ISBN</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="mis. Clean Code">
            </div>
            <div class="col-lg-4">
                <label class="form-label small mb-1">Bidang</label>
                <select name="bidang" class="form-select">
                    <option value="">Semua bidang</option>
                    @foreach($bidangs as $bd)<option value="{{ $bd }}" @selected(request('bidang') == $bd)>{{ $bd }}</option>@endforeach
                </select>
            </div>
            <div class="col-lg-4">
                <label class="form-label small mb-1">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua kategori</option>
                    @foreach($kategoris as $bd => $daftar)
                        <optgroup label="{{ $bd }}">
                            @foreach($daftar as $k)<option value="{{ $k->id }}" @selected(request('kategori') == $k->id)>{{ $k->nama_kategori }}</option>@endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-5">
                <label class="form-label small mb-1">Program Studi</label>
                <select name="prodi" class="form-select">
                    <option value="">Semua prodi</option>
                    @foreach($prodis as $p)<option value="{{ $p->id }}" @selected(request('prodi') == $p->id)>{{ $p->jenjang }} {{ $p->nama }}</option>@endforeach
                </select>
            </div>
            <div class="col-lg-4 d-flex gap-3 pb-2">
                <div class="form-check"><input class="form-check-input" type="checkbox" name="ebook" value="1" id="ebook" @checked(request('ebook'))><label class="form-check-label small" for="ebook">Hanya E-Book</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" name="tersedia" value="1" id="tersedia" @checked(request('tersedia'))><label class="form-check-label small" for="tersedia">Stok tersedia</label></div>
            </div>
            <div class="col-lg-3 d-flex gap-2">
                <button class="btn btn-del flex-grow-1"><i class="bi bi-search"></i> Cari</button>
                <a href="{{ route('katalog.index') }}" class="btn btn-light border" title="Reset"><i class="bi bi-x-lg"></i></a>
            </div>
        </div>
    </form>

    <div class="row row-cols-2 row-cols-md-3 row-cols-xxl-4 g-4">
        @forelse($bukus as $b)
            <div class="col">
                <div class="kat-card">
                    <a href="{{ route('katalog.show', $b) }}"><x-cover :buku="$b" size="lg" /></a>
                    <div>
                        <div class="judul">{{ $b->judul }}</div>
                        <div class="penulis">{{ $b->penulis }} · {{ $b->tahun_terbit }}</div>
                    </div>
                    <div>
                        <span class="tag">{{ $b->kategori->nama_kategori }}</span>
                        @if($b->file_url)<span class="tag tag-ebook"><i class="bi bi-laptop me-1"></i>E-Book</span>@endif
                        @if($b->stok == 0)<span class="tag tag-out">Stok habis</span>
                        @elseif($b->stok < 5)<span class="tag tag-low">Sisa {{ $b->stok }}</span>
                        @else<span class="tag tag-info">Tersedia {{ $b->stok }}</span>@endif
                        <span class="tag"><i class="bi bi-arrow-left-right me-1"></i>Dipinjam {{ $b->peminjamans_count }}×</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('katalog.show', $b) }}" class="btn-detail">Detail</a>
                        @if($anggota)
                            @if(in_array($b->id, $dipinjam))
                                <span class="btn btn-sm btn-light border disabled">Dipinjam</span>
                            @elseif($b->stok > 0)
                                <form method="POST" action="{{ route('sirkulasi.pinjam', $b->id) }}">@csrf
                                    <button class="btn btn-sm btn-del">Pinjam</button>
                                </form>
                            @endif
                        @endif
                        @if($b->file_url)
                            <a href="{{ route('katalog.baca', $b) }}" class="btn btn-sm btn-success" title="Baca online"><i class="bi bi-book-half"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><div class="empty-state"><i class="bi bi-search display-4 d-block mb-2"></i>Buku tidak ditemukan.</div></div>
        @endforelse
    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-4">
        <small class="text-muted">Menampilkan {{ $bukus->firstItem() ?? 0 }}–{{ $bukus->lastItem() ?? 0 }} dari {{ number_format($bukus->total(), 0, ',', '.') }} buku</small>
        {{ $bukus->onEachSide(1)->links() }}
    </div>

    <x-slot:panel>
        <div class="panel-box">
            <h6>Petunjuk</h6>
            <small class="text-muted">Gunakan filter bidang, kategori, dan program studi. Judul berlabel <b>E-Book</b> dapat dibaca online. Tombol <b>Pinjam</b> tersedia untuk mahasiswa dan dosen.</small>
        </div>
        <div class="panel-box">
            <h6>Status Sesi</h6>
            <div class="info-row"><span>Pencarian terakhir</span><span>{{ session('pencarian_terakhir', '-') }}</span></div>
            <div class="info-row"><span>Login sejak</span><span>{{ session('login_timestamp', '-') }}</span></div>
            <div class="info-row"><span>Peminjam terakhir</span><span>{{ session('peminjam_terakhir', '-') }}</span></div>
        </div>
        <div class="panel-box">
            <h6>Program Studi</h6>
            @foreach($prodis as $p)
                <a href="{{ route('katalog.index', ['prodi' => $p->id]) }}" class="d-block small text-decoration-none py-1">{{ $p->jenjang }} · {{ $p->nama }}</a>
            @endforeach
        </div>
    </x-slot:panel>
</x-layout>