@props(['buku', 'size' => 'md'])
@php
    $warna = ['#0F766E', '#0369A1', '#4D7C0F', '#B45309', '#BE185D', '#B91C1C', '#334155', '#1D4ED8'];
    $bidang = $buku->kategori?->bidang ?? $buku->kategori?->nama_kategori ?? 'Umum';
    $latar = $warna[crc32($bidang) % count($warna)];
@endphp
<div class="cover-box cover-{{ $size }}" style="background: {{ $latar }}">
    <div class="cf">
        <div class="cf-top">{{ $bidang }}</div>
        <div class="cf-title">{{ $buku->judul }}</div>
        <div class="cf-line"></div>
        <div class="cf-author">{{ $buku->penulis }}</div>
        <div class="cf-pub">{{ $buku->penerbit }}</div>
    </div>
    @if($buku->sampul)
        <img src="{{ $buku->sampul }}" alt="Sampul {{ $buku->judul }}" loading="lazy" referrerpolicy="no-referrer" onerror="this.remove()">
    @endif
</div>