@php
    $staf = auth()->user()->role === 'pustakawan';
    $warna = ['menunggu' => 'st-warn', 'disetujui' => 'st-ok', 'ditolak' => 'st-bad'];
@endphp
<x-layout title="Usulan Buku Baru" heading="Usulan Buku Baru" :crumbs="['Usulan Buku Baru']">
    @unless($staf)
        <div class="panel-box">
            <h6>Usulkan Buku</h6>
            <form method="POST" action="{{ route('usulan.store') }}" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label small mb-1">Judul buku</label>
                    <input type="text" name="judul" value="{{ old('judul') }}" class="form-control @error('judul') is-invalid @enderror" required>
                    @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label small mb-1">Penulis</label>
                    <input type="text" name="penulis" value="{{ old('penulis') }}" class="form-control @error('penulis') is-invalid @enderror" required>
                    @error('penulis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label small mb-1">Penerbit (opsional)</label>
                    <input type="text" name="penerbit" value="{{ old('penerbit') }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label small mb-1">Alasan / mata kuliah terkait</label>
                    <input type="text" name="alasan" value="{{ old('alasan') }}" class="form-control">
                </div>
                <div class="col-12"><button class="btn btn-del"><i class="bi bi-send-fill me-1"></i>Kirim Usulan</button></div>
            </form>
        </div>
    @endunless

    <div class="panel-box">
        <h6>{{ $staf ? 'Semua Usulan' : 'Usulan Saya' }}</h6>
        @forelse($usulans as $u)
            <div class="loan-row">
                <div class="flex-grow-1" style="min-width:240px">
                    <div class="fw-semibold">{{ $u->judul }}</div>
                    <div class="small text-muted">{{ $u->penulis }}{{ $u->penerbit ? ' · '.$u->penerbit : '' }}</div>
                    @if($staf)<div class="small"><i class="bi bi-person-fill"></i> {{ $u->user->name }}</div>@endif
                    @if($u->alasan)<div class="small text-muted mt-1">Alasan: {{ $u->alasan }}</div>@endif
                    @if($u->catatan)<div class="small mt-1" style="color:var(--del)">Catatan pustakawan: {{ $u->catatan }}</div>@endif
                    <div class="small text-muted">{{ $u->created_at->translatedFormat('d M Y') }}</div>
                </div>
                <div class="text-end">
                    <span class="badge-st {{ $warna[$u->status] }}">{{ ucfirst($u->status) }}</span>
                    @if($staf && $u->status === 'menunggu')
                        <form method="POST" action="{{ route('usulan.proses', $u) }}" class="mt-2 d-flex gap-2 justify-content-end flex-wrap">
                            @csrf
                            <input type="text" name="catatan" class="form-control form-control-sm" style="max-width:200px" placeholder="Catatan (opsional)">
                            <button name="status" value="disetujui" class="btn btn-sm btn-success">Setujui</button>
                            <button name="status" value="ditolak" class="btn btn-sm btn-danger">Tolak</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4"><i class="bi bi-lightbulb fs-1 d-block mb-1"></i>Belum ada usulan.</div>
        @endforelse
        <div class="mt-3">{{ $usulans->links() }}</div>
    </div>

    <x-slot:panel>
        <div class="panel-box">
            <h6>Alur Usulan</h6>
            <small class="text-muted">1. Anggota mengirim usulan.<br>2. Pegawai perpustakaan meninjau.<br>3. Hasil dikirim lewat notifikasi.</small>
        </div>
    </x-slot:panel>
</x-layout>