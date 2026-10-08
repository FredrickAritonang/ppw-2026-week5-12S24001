@php
    $terlambat = $p->hari_terlambat > 0;
    $sisa = $p->sisa_hari;
@endphp
<div class="loan-row">
    <x-cover :buku="$p->buku" size="sm" />
    <div class="flex-grow-1" style="min-width:220px">
        <a href="{{ route('katalog.show', $p->buku) }}" class="fw-semibold text-dark text-decoration-none">{{ $p->buku->judul }}</a>
        <div class="small text-muted">{{ $p->buku->penulis }} · {{ $p->buku->kategori->nama_kategori }}</div>
        @if($staf)
            <div class="small mt-1"><i class="bi bi-person-fill"></i> {{ $p->user->name }} <span class="tag tag-info">{{ $p->user->peran_data['label'] }}</span></div>
        @endif
        <div class="small text-muted mt-1">Dipinjam {{ $p->tgl_pinjam->translatedFormat('d M Y') }} · Jatuh tempo <b>{{ $p->tgl_jatuh_tempo->translatedFormat('d M Y') }}</b> · Diperpanjang {{ $p->jumlah_perpanjang }}x</div>
    </div>
    <div class="text-end">
        @if($terlambat)
            <span class="badge-st st-bad">Terlambat {{ $p->hari_terlambat }} hari · Rp {{ number_format($p->total_denda, 0, ',', '.') }}</span>
        @elseif($sisa <= 2)
            <span class="badge-st st-warn">Sisa {{ $sisa }} hari</span>
        @else
            <span class="badge-st st-ok">Sisa {{ $sisa }} hari</span>
        @endif
        <div class="d-flex gap-2 justify-content-end mt-2">
            @unless($terlambat)
                <form method="POST" action="{{ route('sirkulasi.perpanjang', $p) }}">@csrf
                    <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-repeat me-1"></i>Perpanjang</button>
                </form>
            @endunless
            <form method="POST" action="{{ route('sirkulasi.kembali', $p) }}" onsubmit="return confirm('Proses pengembalian buku ini?')">@csrf
                <button class="btn btn-sm btn-del"><i class="bi bi-box-arrow-in-down me-1"></i>{{ $staf ? 'Terima Pengembalian' : 'Kembalikan' }}</button>
            </form>
        </div>
    </div>
</div>