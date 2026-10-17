<x-layout title="Denda & Pembayaran" heading="Denda & Pembayaran" :crumbs="['Denda & Pembayaran']">
    <div class="row g-3 mb-3">
        <div class="col-md-6"><div class="stat-card" style="background:#B91C1C"><div><div class="n">Rp {{ number_format($totalBelum, 0, ',', '.') }}</div>Belum Lunas</div><i class="bi bi-cash-coin"></i></div></div>
        <div class="col-md-6"><div class="stat-card" style="background:#4D7C0F"><div><div class="n">Rp {{ number_format($totalLunas, 0, ',', '.') }}</div>Sudah Lunas</div><i class="bi bi-check-circle-fill"></i></div></div>
    </div>

    <div class="panel-box">
        @if($staf)
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-9"><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama atau username anggota…"></div>
                <div class="col-md-3"><button class="btn btn-del w-100"><i class="bi bi-search me-1"></i>Cari</button></div>
            </form>
        @endif

        @forelse($daftar as $p)
            <div class="loan-row">
                <x-cover :buku="$p->buku" size="sm" />
                <div class="flex-grow-1" style="min-width:220px">
                    <div class="fw-semibold">{{ $p->buku->judul }}</div>
                    @if($staf)<div class="small"><i class="bi bi-person-fill"></i> {{ $p->user->name }}</div>@endif
                    <div class="small text-muted">Jatuh tempo {{ $p->tanggal_jatuh_tempo->translatedFormat('d M Y') }} · {{ $p->tanggal_kembali ? 'Dikembalikan '.$p->tanggal_kembali->translatedFormat('d M Y') : 'Belum dikembalikan' }}</div>
                    <div class="small text-muted">Terlambat {{ $p->hari_terlambat }} hari × Rp1.000</div>
                </div>
                <div class="text-end">
                    <div class="fw-bold fs-5">Rp {{ number_format($p->total_denda, 0, ',', '.') }}</div>
                    @if($p->denda_lunas)
                        <span class="badge-st st-ok">Lunas {{ $p->tgl_bayar?->translatedFormat('d M Y') }}</span>
                    @elseif(! $p->tanggal_kembali)
                        <span class="badge-st st-warn">Kembalikan buku dulu</span>
                    @else
                        <span class="badge-st st-bad">Belum lunas</span>
                        <form method="POST" action="{{ route('denda.bayar', $p) }}" class="mt-2" onsubmit="return confirm('Konfirmasi pembayaran denda?')">@csrf
                            <button class="btn btn-sm btn-del"><i class="bi bi-credit-card me-1"></i>{{ $staf ? 'Konfirmasi Lunas' : 'Bayar Sekarang' }}</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5"><i class="bi bi-emoji-smile fs-1 d-block mb-1"></i>Tidak ada denda. Terima kasih sudah tertib!</div>
        @endforelse
        <div class="mt-3">{{ $daftar->links() }}</div>
    </div>

    <x-slot:panel>
        <div class="panel-box">
            <h6>Ketentuan Denda</h6>
            <div class="info-row"><span>Tarif</span><span>Rp1.000 / hari</span></div>
            <div class="info-row"><span>Dihitung</span><span>Setelah jatuh tempo</span></div>
            <small class="text-muted">Pembayaran pada demo ini bersifat simulasi.</small>
        </div>
    </x-slot:panel>
</x-layout>