@php $warna = ['diajukan' => 'st-warn', 'disetujui' => 'st-ok', 'ditolak' => 'st-bad']; @endphp
<x-layout title="Layanan Bebas Pustaka" heading="Layanan Bebas Pustaka" :crumbs="['Layanan Bebas Pustaka']">
    @if($mode === 'mahasiswa')
        <div class="panel-box">
            <h6>Syarat Bebas Pustaka</h6>
            <div class="info-row"><span>Tidak ada pinjaman aktif</span><span>@if($aktif == 0)<span class="badge-st st-ok">Terpenuhi</span>@else<span class="badge-st st-bad">{{ $aktif }} buku belum kembali</span>@endif</span></div>
            <div class="info-row"><span>Tidak ada denda</span><span>@if($denda == 0)<span class="badge-st st-ok">Terpenuhi</span>@else<span class="badge-st st-bad">Rp {{ number_format($denda, 0, ',', '.') }}</span>@endif</span></div>
            <div class="mt-3">
                @if($menunggu)
                    <span class="badge-st st-warn">Pengajuan sedang diproses pegawai perpustakaan</span>
                @else
                    <form method="POST" action="{{ route('bebas-pustaka.ajukan') }}">@csrf
                        <button class="btn btn-del" @disabled($aktif > 0 || $denda > 0)><i class="bi bi-send-fill me-1"></i>Ajukan Surat Bebas Pustaka</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="panel-box">
            <h6>Riwayat Pengajuan</h6>
            @forelse($riwayat as $r)
                <div class="loan-row">
                    <div class="flex-grow-1">
                        <div class="fw-semibold">Pengajuan #{{ $r->id }}</div>
                        <div class="small text-muted">{{ $r->created_at->translatedFormat('d M Y') }}{{ $r->catatan ? ' · '.$r->catatan : '' }}</div>
                    </div>
                    <span class="badge-st {{ $warna[$r->status] }}">{{ ucfirst($r->status) }}</span>
                    @if($r->status === 'disetujui')
                        <a href="{{ route('bebas-pustaka.surat', $r) }}" target="_blank" class="btn btn-sm btn-del"><i class="bi bi-printer me-1"></i>Surat</a>
                    @endif
                </div>
            @empty
                <div class="text-muted text-center py-3">Belum ada pengajuan.</div>
            @endforelse
        </div>
    @else
        <div class="panel-box">
            <h6>Pengajuan Bebas Pustaka</h6>
            @forelse($pengajuan as $r)
                @php
                    $a = $r->user;
                    $aktifA = $aktifMap[$r->user_id] ?? 0;
                    $dendaA = $dendaMap[$r->user_id] ?? 0;
                @endphp
                <div class="loan-row">
                    <div class="flex-grow-1" style="min-width:220px">
                        <div class="fw-semibold">{{ $a->name }} <span class="text-muted small">({{ $a->username }})</span></div>
                        <div class="small text-muted">{{ $r->created_at->translatedFormat('d M Y') }}</div>
                        <div class="small mt-1">
                            <span class="badge-st {{ $aktifA == 0 ? 'st-ok' : 'st-bad' }}">Pinjaman aktif: {{ $aktifA }}</span>
                            <span class="badge-st {{ $dendaA == 0 ? 'st-ok' : 'st-bad' }}">Denda: Rp {{ number_format($dendaA, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge-st {{ $warna[$r->status] }}">{{ ucfirst($r->status) }}</span>
                        @if($r->status === 'diajukan')
                            <form method="POST" action="{{ route('bebas-pustaka.proses', $r) }}" class="mt-2 d-flex gap-2 justify-content-end flex-wrap">
                                @csrf
                                <input type="text" name="catatan" class="form-control form-control-sm" style="max-width:180px" placeholder="Catatan (opsional)">
                                <button name="status" value="disetujui" class="btn btn-sm btn-success">Setujui</button>
                                <button name="status" value="ditolak" class="btn btn-sm btn-danger">Tolak</button>
                            </form>
                        @elseif($r->status === 'disetujui')
                            <a href="{{ route('bebas-pustaka.surat', $r) }}" target="_blank" class="btn btn-sm btn-del mt-2"><i class="bi bi-printer me-1"></i>Surat</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">Belum ada pengajuan.</div>
            @endforelse
            <div class="mt-3">{{ $pengajuan->links() }}</div>
        </div>
    @endif
</x-layout>