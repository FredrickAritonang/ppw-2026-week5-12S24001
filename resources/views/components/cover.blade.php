@props(['buku', 'size' => 'md'])
@php
    $warna = ['#4C1D95', '#0369A1', '#047857', '#B45309', '#BE185D', '#B91C1C', '#0F766E', '#1D4ED8'];
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