@props(['buku', 'size' => 'md'])
@php
    $warna = [
        ['#4C1D95', '#7C3AED'], ['#0369A1', '#0EA5E9'], ['#047857', '#34D399'], ['#B45309', '#FBBF24'],
        ['#BE185D', '#F472B6'], ['#B91C1C', '#FB923C'], ['#0F766E', '#2DD4BF'], ['#1D4ED8', '#60A5FA'],
    ];
    $bidang = $buku->kategori?->bidang ?? $buku->kategori?->nama_kategori ?? 'Umum';
    [$c1, $c2] = $warna[crc32($bidang) % count($warna)];
@endphp
<div class="cover-box cover-{{ $size }}" style="background: linear-gradient(155deg, {{ $c1 }}, {{ $c2 }})">
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