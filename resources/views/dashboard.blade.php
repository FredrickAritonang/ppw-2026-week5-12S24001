<x-layout title="Home" :heading="'Selamat datang, ' . auth()->user()->name" :crumbs="[]">
    <div class="row g-3 mb-4">
        @foreach($stat as [$label, $nilai, $ikon, $bg, $rute])
            <div class="col-6 col-xl-3">
                <a href="{{ route($rute) }}" class="text-decoration-none">
                    <div class="stat-card" style="background: {{ $bg }}">
                        <div><div class="n">{{ $nilai }}</div><div style="font-size:.85rem">{{ $label }}</div></div>
                        <i class="bi {{ $ikon }}"></i>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="panel-box">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 border-0 pb-0">Buku Terbaru</h6>
            <a href="{{ route('katalog.index') }}" class="btn btn-sm btn-del">Lihat Semua</a>
        </div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-xxl-4 g-4">
            @foreach($terbaru as $b)
                <div class="col">
                    <a href="{{ route('katalog.show', $b) }}" class="text-decoration-none">
                        <x-cover :buku="$b" size="lg" />
                        <div class="fw-semibold mt-2 small text-dark">{{ \Illuminate\Support\Str::limit($b->judul, 48) }}</div>
                        <div class="small text-muted">{{ $b->kategori->nama_kategori }}</div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <div class="panel-box">
        <h6>E-Book yang Bisa Dibaca Online</h6>
        @forelse($ebooks as $b)
            <div class="loan-row">
                <x-cover :buku="$b" size="md" />
                <div class="flex-grow-1">
                    <div class="fw-semibold">{{ $b->judul }}</div>
                    <div class="small text-muted">{{ $b->penulis }} · {{ $b->kategori->nama_kategori }}</div>
                </div>
                <a href="{{ route('katalog.baca', $b) }}" class="btn btn-del"><i class="bi bi-book-half me-1"></i>Baca</a>
            </div>
        @empty
            <div class="text-muted">Belum ada e-book.</div>
        @endforelse
    </div>

    <x-slot:panel>
        <div class="panel-box">
            <h6>Profil Saya</h6>
            <div class="info-row"><span>Nama</span><span>{{ auth()->user()->name }}</span></div>
            <div class="info-row"><span>Username</span><span>{{ auth()->user()->username }}</span></div>
            <div class="info-row"><span>Peran</span><span>{{ $peran['label'] }}</span></div>
            <div class="info-row"><span>Ketentuan</span><span>{{ $peran['aturan'] }}</span></div>
        </div>
        <div class="panel-box">
            <h6>Koleksi</h6>
            <div class="info-row"><span>Total Buku</span><span>{{ number_format($totalBuku, 0, ',', '.') }}</span></div>
            <div class="info-row"><span>Kategori</span><span>{{ $totalKategori }}</span></div>
        </div>
        <div class="panel-box">
            <h6>Jam Layanan</h6>
            <div class="info-row"><span>Senin–Jumat</span><span>08.00–17.00</span></div>
            <div class="info-row"><span>Denda</span><span>Rp1.000 / hari</span></div>
        </div>
    </x-slot:panel>
</x-layout>