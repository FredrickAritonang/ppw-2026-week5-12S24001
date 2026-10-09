@php
    $dosen = $u->role === 'dosen';
    $baris = match ($u->role) {
        'dosen' => [
            ['Nama', $u->name], ['NUPTK', $u->nuptk], ['NIDN', $u->nidn], ['Prodi', $u->prodi],
            ['Jabatan Akademik', $u->jabatan], ['Golongan Kepangkatan', $u->golongan],
            ['Status Ikatan Kerja', $u->status_kerja], ['Username', $u->username], ['Email', $u->email], ['Telepon', $u->telepon],
        ],
        'mahasiswa' => [
            ['Nama', $u->name], ['NIM', $u->nomor_induk], ['Prodi', $u->prodi], ['Angkatan', '2024'],
            ['Status', $u->status_kerja], ['Username', $u->username], ['Email', $u->email], ['Telepon', $u->telepon],
        ],
        default => [
            ['Nama', $u->name], ['ID Pegawai', $u->nomor_induk], ['Unit', $u->prodi], ['Jabatan', $u->jabatan],
            ['Status Kepegawaian', $u->status_kerja], ['Username', $u->username], ['Email', $u->email], ['Telepon', $u->telepon],
        ],
    };
    $judulData = $dosen ? 'Data Dosen' : ($u->role === 'mahasiswa' ? 'Data Mahasiswa' : 'Data Pegawai');
@endphp
<x-layout title="Data Diri" :crumbs="[$peran['label'], $u->name]">
    <style>
        .prof-tabs .nav-link { color:#4b4565; border:0; border-bottom:3px solid transparent; padding:.9rem 1.4rem; }
        .prof-tabs .nav-link.active { color:var(--del); border-bottom-color:var(--del); font-weight:600; background:transparent; }
        .tbl-prof { width:100%; border-collapse:collapse; }
        .tbl-prof th { width:260px; background:#F3F0FA; font-weight:700; padding:.85rem 1rem; border-bottom:1px solid #fff; vertical-align:top; }
        .tbl-prof td { padding:.85rem 1rem; background:#FAF8FE; border-bottom:1px solid #fff; word-break:break-word; }
        @media (max-width:576px) { .tbl-prof th { width:130px; } }
    </style>

    <div class="panel-box p-0">
        <ul class="nav nav-tabs prof-tabs px-3 pt-1" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#t-data" type="button">{{ $judulData }}</button></li>
            @if($dosen)
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-pend" type="button">Riwayat Pendidikan</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-ajar" type="button">Riwayat Pengajaran</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-pub" type="button">Publikasi</button></li>
            @endif
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-pinjam" type="button">Riwayat Peminjaman</button></li>
        </ul>

        <div class="tab-content p-4">
            <div class="tab-pane fade show active" id="t-data">
                <h5 class="text-end mb-4"><i class="bi bi-person-fill me-2"></i>{{ $u->name }}</h5>
                <h4 class="mb-3">{{ $judulData }}</h4>
                <table class="tbl-prof">
                    @foreach($baris as [$k, $v])
                        <tr><th>{{ $k }}</th><td>{{ $v ?: '-' }}</td></tr>
                    @endforeach
                </table>
                <small class="text-muted d-block mt-3"><i class="bi bi-info-circle me-1"></i>Data kepegawaian pada halaman ini adalah data contoh untuk demo, bukan data resmi.</small>
            </div>

            @if($dosen)
                <div class="tab-pane fade" id="t-pend">
                    <h4 class="mb-3">Riwayat Pendidikan</h4>
                    <table class="tbl-prof">
                        @forelse($pendidikan as [$jenjang, $nama])
                            <tr><th>{{ $jenjang }}</th><td>{{ $nama }}</td></tr>
                        @empty
                            <tr><td class="text-muted">Belum ada data pendidikan.</td></tr>
                        @endforelse
                    </table>
                    <small class="text-muted d-block mt-3">Jenjang diperkirakan dari gelar pada nama.</small>
                </div>
                <div class="tab-pane fade" id="t-ajar">
                    <h4 class="mb-3">Riwayat Pengajaran</h4>
                    <div class="text-muted py-4 text-center"><i class="bi bi-inbox fs-1 d-block mb-1"></i>Belum ada data pengajaran.</div>
                </div>
                <div class="tab-pane fade" id="t-pub">
                    <h4 class="mb-3">Publikasi</h4>
                    <div class="text-muted py-4 text-center"><i class="bi bi-inbox fs-1 d-block mb-1"></i>Belum ada data publikasi.</div>
                </div>
            @endif

            <div class="tab-pane fade" id="t-pinjam">
                <h4 class="mb-3">Riwayat Peminjaman Terakhir</h4>
                @forelse($riwayat as $r)
                    <div class="loan-row">
                        <x-cover :buku="$r->buku" size="sm" />
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $r->buku->judul }}</div>
                            <div class="small text-muted">{{ $r->tgl_pinjam->translatedFormat('d M Y') }} · jatuh tempo {{ $r->tgl_jatuh_tempo->translatedFormat('d M Y') }}</div>
                        </div>
                        <span class="badge-st {{ $r->tgl_kembali ? 'st-ok' : 'st-info' }}">{{ $r->tgl_kembali ? 'Selesai' : 'Dipinjam' }}</span>
                    </div>
                @empty
                    <div class="text-muted py-4 text-center"><i class="bi bi-inbox fs-1 d-block mb-1"></i>Belum ada riwayat peminjaman.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-layout>