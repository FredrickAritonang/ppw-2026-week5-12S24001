@php $staf = $mode === 'staf'; @endphp
<x-layout title="Sirkulasi" heading="Sirkulasi (Peminjaman & Pengembalian)" :crumbs="['Sirkulasi (Peminjaman & Pengembalian)']">
    @if($errors->any())
        <div class="alert alert-danger">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    @endif

    @if($staf)
        <div class="panel-box">
            <h6>Catat Peminjaman</h6>
            <form method="POST" action="{{ route('sirkulasi.catat') }}" class="row g-2 align-items-end">
                @csrf
                <div class="col-md-5">
                    <label class="form-label small mb-1">Anggota</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">-- Pilih anggota --</option>
                        @foreach($anggota as $a)<option value="{{ $a->id }}" @selected(old('user_id') == $a->id)>{{ $a->name }} ({{ $a->username }})</option>@endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small mb-1">ISBN buku</label>
                    <input type="text" name="isbn" value="{{ old('isbn') }}" class="form-control" placeholder="mis. 9780132350884" required>
                </div>
                <div class="col-md-3"><button class="btn btn-del w-100"><i class="bi bi-plus-circle me-1"></i>Catat</button></div>
            </form>
        </div>

        <div class="panel-box">
            <form method="GET" class="row g-2 mb-2">
                <div class="col-md-6"><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari anggota atau judul buku…"></div>
                <div class="col-md-3">
                    <select name="filter" class="form-select">
                        <option value="">Semua pinjaman aktif</option>
                        <option value="terlambat" @selected(request('filter') === 'terlambat')>Hanya terlambat</option>
                    </select>
                </div>
                <div class="col-md-3"><button class="btn btn-del w-100"><i class="bi bi-search me-1"></i>Filter</button></div>
            </form>
            <h6 class="mt-3">Pinjaman Aktif ({{ $aktif->total() }})</h6>
            @forelse($aktif as $p)
                @include('sirkulasi._baris', ['p' => $p, 'staf' => true])
            @empty
                <div class="text-center text-muted py-4">Tidak ada pinjaman aktif.</div>
            @endforelse
            <div class="mt-3">{{ $aktif->links() }}</div>
        </div>
    @else
        <div class="panel-box">
            <h6>Pinjam Buku</h6>
            <form method="GET" class="row g-2">
                <div class="col-md-9"><input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Ketik judul atau ISBN buku yang ingin dipinjam…"></div>
                <div class="col-md-3"><button class="btn btn-del w-100"><i class="bi bi-search me-1"></i>Cari</button></div>
            </form>
            @if(request()->filled('cari'))
                <div class="mt-3">
                    @forelse($hasil as $b)
                        <div class="loan-row">
                            <x-cover :buku="$b" size="sm" />
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $b->judul }}</div>
                                <div class="small text-muted">{{ $b->penulis }} · {{ $b->kategori->nama_kategori }} · Stok {{ $b->stok }}</div>
                            </div>
                            <form method="POST" action="{{ route('sirkulasi.pinjam', $b) }}">@csrf
                                <button class="btn btn-del" @disabled($b->stok == 0)>Pinjam</button>
                            </form>
                        </div>
                    @empty
                        <div class="text-muted py-3">Buku tidak ditemukan.</div>
                    @endforelse
                </div>
            @endif
        </div>

        <div class="panel-box">
            <h6>Buku yang Sedang Dipinjam ({{ $aktif->count() }})</h6>
            @forelse($aktif as $p)
                @include('sirkulasi._baris', ['p' => $p, 'staf' => false])
            @empty
                <div class="text-center text-muted py-4"><i class="bi bi-inbox fs-1 d-block mb-1"></i>Belum ada buku yang dipinjam. Cari di atas atau buka <a href="{{ route('katalog.index') }}">katalog</a>.</div>
            @endforelse
        </div>
    @endif

    <x-slot:panel>
        <div class="panel-box">
            <h6>Ketentuan</h6>
            <div class="info-row"><span>Mahasiswa</span><span>3 buku · 7 hari · 1x</span></div>
            <div class="info-row"><span>Dosen</span><span>10 buku · 30 hari · 2x</span></div>
            <div class="info-row"><span>Denda</span><span>Rp1.000 / hari</span></div>
            <small class="text-muted">Peminjaman diblokir jika masih ada denda belum lunas.</small>
        </div>
    </x-slot:panel>
</x-layout>