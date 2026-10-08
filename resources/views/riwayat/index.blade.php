<x-layout title="Riwayat Peminjaman" heading="Riwayat Peminjaman" :crumbs="['Riwayat Peminjaman']">
    <div class="panel-box">
        <form method="GET" class="row g-2 mb-3">
            @if($staf)
                <div class="col-md-5"><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari anggota atau judul buku…"></div>
            @endif
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Semua status</option>
                    <option value="aktif" @selected(request('status') === 'aktif')>Sedang dipinjam</option>
                    <option value="selesai" @selected(request('status') === 'selesai')>Sudah dikembalikan</option>
                    <option value="terlambat" @selected(request('status') === 'terlambat')>Pernah / sedang terlambat</option>
                </select>
            </div>
            <div class="col-md-3"><button class="btn btn-del w-100"><i class="bi bi-funnel me-1"></i>Filter</button></div>
        </form>

        <div class="table-responsive">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Buku</th>@if($staf)<th>Anggota</th>@endif<th>Dipinjam</th><th>Jatuh Tempo</th><th>Dikembalikan</th><th>Status</th><th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($riwayat as $r)
                    <tr>
                        <td style="min-width:260px">
                            <div class="d-flex align-items-center gap-3">
                                <x-cover :buku="$r->buku" size="sm" />
                                <div><div class="fw-semibold">{{ $r->buku->judul }}</div><div class="small text-muted">{{ $r->buku->penulis }}</div></div>
                            </div>
                        </td>
                        @if($staf)<td>{{ $r->user->name }}<div class="small text-muted">{{ $r->user->peran_data['label'] }}</div></td>@endif
                        <td>{{ $r->tgl_pinjam->translatedFormat('d M Y') }}</td>
                        <td>{{ $r->tgl_jatuh_tempo->translatedFormat('d M Y') }}</td>
                        <td>{{ $r->tgl_kembali?->translatedFormat('d M Y') ?? '-' }}</td>
                        <td>
                            @if(! $r->tgl_kembali && $r->hari_terlambat > 0)<span class="badge-st st-bad">Terlambat</span>
                            @elseif(! $r->tgl_kembali)<span class="badge-st st-info">Dipinjam</span>
                            @elseif($r->hari_terlambat > 0)<span class="badge-st st-warn">Kembali terlambat</span>
                            @else<span class="badge-st st-ok">Selesai</span>@endif
                        </td>
                        <td>
                            @if($r->total_denda > 0)
                                Rp {{ number_format($r->total_denda, 0, ',', '.') }}
                                <div class="small {{ $r->denda_lunas ? 'text-success' : 'text-danger' }}">{{ $r->denda_lunas ? 'Lunas' : 'Belum lunas' }}</div>
                            @else - @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="{{ $staf ? 7 : 6 }}" class="text-center text-muted py-5"><i class="bi bi-inbox fs-1 d-block mb-1"></i>Belum ada riwayat peminjaman.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $riwayat->links() }}</div>
    </div>
</x-layout>