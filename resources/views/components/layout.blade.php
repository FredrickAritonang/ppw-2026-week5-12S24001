@php
    $dataPeran = \App\Support\Peran::DATA[$pengguna->role];
    $seksi = \App\Support\Peran::menu($pengguna->role);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'SIPUS-Del' }} — Perpustakaan IT Del</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script>try{if(window.innerWidth>=992&&localStorage.getItem('sidebarCollapsed')==='1')document.documentElement.classList.add('collapsed')}catch(e){}</script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --del:#4C1D95; --del-dark:#3B0764; --del-mid:#6D28D9; --del-soft:#F3E8FF; --side:#1E1535; --sidew:268px; }
        body { font-family:'Poppins',sans-serif; background:#F4F2FA; color:#1f2937; margin:0; }

        /* ===== Sidebar ===== */
        .sidebar { position:fixed; inset:0 auto 0 0; width:var(--sidew); background:var(--side); color:#cfc7e6; overflow-y:auto; overflow-x:hidden; z-index:100; transition:width .25s, transform .25s; }
        .side-brand { background:linear-gradient(135deg,var(--del-dark),var(--del)); padding:1rem; display:flex; align-items:center; gap:.7rem; min-height:78px; }
        .side-brand .pill { background:#fff; border-radius:.6rem; padding:.2rem .5rem; display:flex; }
        .side-brand img { height:36px; }
        .side-brand b { color:#fff; font-weight:800; line-height:1.1; display:block; }
        .side-brand small { display:block; color:#d9ccf5; font-size:.62rem; letter-spacing:.08em; text-transform:uppercase; }
        .brand-mini { display:none; color:#fff; font-size:1.7rem; }
        .side-role { display:flex; align-items:center; gap:.7rem; margin:.9rem 1rem .2rem; padding:.6rem .8rem; border-radius:.8rem; background:rgba(255,255,255,.07); color:#fff; font-size:.8rem; }
        .side-role i { font-size:1.2rem; color:#C4B5FD; }
        .side-label { padding:1.1rem 1.2rem .4rem; font-size:.68rem; letter-spacing:.12em; color:#8d82b3; text-transform:uppercase; white-space:nowrap; }
        .side-link { display:flex; align-items:center; gap:.8rem; padding:.78rem 1.2rem; color:#cfc7e6; text-decoration:none; font-size:.88rem; border-left:4px solid transparent; transition:.15s; }
        .side-link i { font-size:1.05rem; width:1.3rem; text-align:center; flex-shrink:0; }
        .side-link:hover { background:rgba(255,255,255,.06); color:#fff; }
        .side-link.active { background:rgba(124,58,237,.28); color:#fff; border-left-color:#A78BFA; font-weight:600; }
        .side-sub { background:rgba(0,0,0,.2); }
        .side-sub .side-link { padding-left:2.9rem; font-size:.85rem; }
        .chev { font-size:.8rem !important; transition:.2s; }
        [aria-expanded="true"] > .chev { transform:rotate(180deg); }

        @media (min-width:992px) {
            html.collapsed { --sidew:76px; }
            html.collapsed .side-text, html.collapsed .brand-full, html.collapsed .side-role .txt { display:none; }
            html.collapsed .brand-mini { display:block; }
            html.collapsed .side-brand { justify-content:center; padding:1rem .5rem; }
            html.collapsed .side-role { justify-content:center; margin:.9rem .6rem .2rem; padding:.6rem 0; }
            html.collapsed .side-link { justify-content:center; padding:.95rem 0; gap:0; }
            html.collapsed .side-link i { font-size:1.3rem; }
            html.collapsed .side-sub .side-link { padding-left:0; }
            html.collapsed .side-label { font-size:0; padding:.7rem 1rem .3rem; }
            html.collapsed .side-label::after { content:''; display:block; border-top:1px solid rgba(255,255,255,.14); }
        }

        /* ===== Area utama ===== */
        .app-main { margin-left:var(--sidew); min-height:100vh; display:flex; flex-direction:column; transition:margin-left .25s; }
        .topbar { background:linear-gradient(135deg,var(--del-dark),var(--del) 55%,var(--del-mid)); color:#fff; padding:.65rem 1.4rem; display:flex; align-items:center; gap:1rem; position:sticky; top:0; z-index:50; box-shadow:0 2px 14px rgba(0,0,0,.18); }
        .btn-burger { background:none; border:0; color:#fff; font-size:1.6rem; line-height:1; padding:.1rem .3rem; }
        .topsearch { position:relative; flex:1; max-width:420px; }
        .topsearch i { position:absolute; left:.9rem; top:50%; transform:translateY(-50%); color:#8b84a3; }
        .topsearch input { width:100%; border:0; border-radius:50rem; padding:.5rem 1rem .5rem 2.4rem; font-size:.85rem; outline:none; }
        .top-actions { margin-left:auto; display:flex; align-items:center; gap:.9rem; }
        .icon-btn { position:relative; color:#fff; font-size:1.35rem; text-decoration:none; width:40px; height:40px; display:flex; align-items:center; justify-content:center; border-radius:50%; transition:.2s; }
        .icon-btn:hover { background:rgba(255,255,255,.15); color:#fff; }
        .notif-badge { position:absolute; top:2px; right:0; background:#EF4444; color:#fff; font-size:.62rem; font-weight:700; min-width:18px; height:18px; border-radius:50rem; display:flex; align-items:center; justify-content:center; padding:0 4px; border:2px solid var(--del); }
        .user-chip { display:flex; align-items:center; gap:.6rem; color:#fff; text-decoration:none; }
        .user-chip .av { width:38px; height:38px; border-radius:50%; background:rgba(255,255,255,.2); display:flex; align-items:center; justify-content:center; font-size:1.1rem; }
        .crumb { background:#fff; padding:.7rem 1.6rem; font-size:.85rem; border-bottom:1px solid #E9E4F5; }
        .crumb a { color:var(--del-mid); text-decoration:none; }
        .crumb .sep { color:#b3abcd; margin:0 .5rem; }
        .page-title { color:var(--del); font-weight:700; font-size:1.55rem; padding:1.2rem 1.6rem .2rem; }
        .content { padding:1.2rem 1.6rem 2rem; flex:1; }

        /* Notifikasi */
        .notif-menu { width:360px; max-width:92vw; border-radius:1rem !important; overflow:hidden; }
        .notif-head { padding:.8rem 1rem; font-weight:700; color:var(--del); display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #EEE9F8; }
        .notif-item { display:flex; gap:.8rem; padding:.8rem 1rem; text-decoration:none; color:#1f2937; border-bottom:1px solid #F3EFFA; transition:.15s; }
        .notif-item:hover { background:#FAF7FF; color:#1f2937; }
        .notif-item.unread { background:#F5EEFF; }
        .notif-item .ni { width:38px; height:38px; border-radius:50%; background:var(--del-soft); color:var(--del); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .notif-item .t { font-weight:600; font-size:.86rem; }
        .notif-item .m { font-size:.8rem; color:#6b6585; }
        .notif-item small { color:#9a93b3; font-size:.7rem; }

        /* Panel & info */
        .panel-box { background:#fff; border-radius:1rem; box-shadow:0 4px 16px rgba(76,29,149,.09); padding:1.2rem; margin-bottom:1.2rem; }
        .panel-box h6 { color:var(--del); font-weight:700; border-bottom:2px solid var(--del-soft); padding-bottom:.6rem; margin-bottom:.9rem; }
        .info-row { display:flex; justify-content:space-between; gap:1rem; padding:.5rem 0; border-bottom:1px dashed #E9E4F5; font-size:.85rem; }
        .info-row:last-child { border-bottom:0; }
        .info-row span:first-child { color:#8b84a3; flex-shrink:0; }
        .info-row span:last-child { font-weight:600; text-align:right; min-width:0; overflow-wrap:anywhere; }

        /* ===== Sampul buku ===== */
        .cover-box { position:relative; aspect-ratio:2/3; border-radius:.3rem .65rem .65rem .3rem; overflow:hidden; box-shadow:0 12px 26px rgba(30,21,53,.35), 0 2px 4px rgba(0,0,0,.2); container-type:inline-size; background:#4C1D95; flex-shrink:0; }
        .cover-box::before { content:''; position:absolute; inset:0 auto 0 0; width:6%; background:linear-gradient(90deg,rgba(0,0,0,.35),rgba(255,255,255,.12) 60%,rgba(0,0,0,0)); z-index:3; }
        .cover-box::after { content:''; position:absolute; inset:0; background:linear-gradient(115deg,rgba(255,255,255,.2),rgba(255,255,255,0) 40%); z-index:3; pointer-events:none; }
        .cover-box img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; background:#fff; z-index:2; }
        .cf { position:absolute; inset:0; padding:12cqw 9cqw 9cqw 14cqw; display:flex; flex-direction:column; color:#fff; }
        .cf-top { font-size:5.2cqw; letter-spacing:.14em; text-transform:uppercase; opacity:.85; font-weight:600; }
        .cf-title { font-size:9cqw; overflow-wrap:anywhere; font-weight:800; line-height:1.15; margin-top:10cqw; display:-webkit-box; -webkit-line-clamp:5; -webkit-box-orient:vertical; overflow:hidden; text-shadow:0 2px 6px rgba(0,0,0,.3); }
        .cf-line { width:18cqw; height:1cqw; background:rgba(255,255,255,.85); margin:7cqw 0; }
        .cf-author { font-size:6.2cqw; font-weight:600; }
        .cf-pub { margin-top:auto; font-size:4.8cqw; opacity:.8; }
        .cover-sm { width:72px; } .cover-md { width:112px; } .cover-lg { width:100%; } .cover-xl { width:100%; max-width:360px; margin:0 auto; }

        /* Kartu katalog */
        .kat-card { background:#fff; border-radius:1.1rem; padding:1rem; height:100%; display:flex; flex-direction:column; gap:.8rem; box-shadow:0 4px 16px rgba(76,29,149,.08); transition:.25s; }
        .kat-card:hover { transform:translateY(-5px); box-shadow:0 16px 32px rgba(76,29,149,.20); }
        .kat-card .judul { font-weight:600; font-size:.95rem; line-height:1.3; color:#231942; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
        .kat-card .penulis { font-size:.8rem; color:#7b7494; }
        .tag { display:inline-block; background:var(--del-soft); color:var(--del); padding:.2rem .65rem; border-radius:50rem; font-size:.72rem; font-weight:500; margin:0 .25rem .25rem 0; }
        .tag-ebook { background:#DCFCE7; color:#166534; } .tag-low { background:#FEF3C7; color:#92400E; } .tag-out { background:#FEE2E2; color:#991B1B; } .tag-info { background:#E0F2FE; color:#0369A1; }
        .stat-card { border-radius:1rem; padding:1.2rem; color:#fff; display:flex; justify-content:space-between; align-items:center; box-shadow:0 8px 20px rgba(0,0,0,.14); transition:.2s; }
        .stat-card:hover { transform:translateY(-3px); }
        .stat-card .n { font-size:1.7rem; font-weight:800; line-height:1.1; } .stat-card i { font-size:2.4rem; opacity:.35; }
        .reader-frame { width:100%; height:78vh; border:0; border-radius:.8rem; background:#fff; box-shadow:0 6px 22px rgba(76,29,149,.15); }

        /* Sirkulasi & tabel ringan */
        .loan-row { display:flex; gap:1rem; align-items:center; padding:1rem 0; border-bottom:1px solid #EFEAF8; flex-wrap:wrap; }
        .loan-row:last-child { border-bottom:0; }
        .tbl { width:100%; border-collapse:collapse; font-size:.86rem; }
        .tbl th { text-align:left; font-size:.7rem; letter-spacing:.08em; text-transform:uppercase; color:#8b84a3; padding:.6rem .6rem; border-bottom:2px solid var(--del-soft); white-space:nowrap; }
        .tbl td { padding:.8rem .6rem; border-bottom:1px solid #F0ECF8; vertical-align:middle; }
        .tbl tr:hover td { background:#FBF9FF; }
        .badge-st { display:inline-block; padding:.28rem .75rem; border-radius:50rem; font-size:.74rem; font-weight:600; }
        .st-ok { background:#DCFCE7; color:#166534; } .st-warn { background:#FEF3C7; color:#92400E; } .st-bad { background:#FEE2E2; color:#991B1B; } .st-info { background:#E0F2FE; color:#0369A1; } .st-grey { background:#EEE; color:#555; }

        /* Gaya halaman Kelola */
        .hero { position:relative; overflow:hidden; border-radius:1.5rem; padding:2.2rem 2.4rem; margin-bottom:2rem; color:#fff; background:linear-gradient(120deg,var(--del-dark),var(--del) 45%,#7C3AED); box-shadow:0 14px 34px rgba(76,29,149,.30); }
        .hero::before { content:''; position:absolute; width:320px; height:320px; border-radius:50%; background:rgba(255,255,255,.08); right:-70px; top:-120px; }
        .hero > * { position:relative; z-index:1; } .hero h2 { font-weight:800; margin:0; } .hero p { margin:.3rem 0 0; opacity:.85; }
        .hero-stat { display:inline-flex; align-items:center; gap:.5rem; background:rgba(255,255,255,.16); border:1px solid rgba(255,255,255,.25); padding:.35rem .9rem; border-radius:50rem; font-size:.85rem; margin-top:.9rem; }
        .btn-hero { background:#fff; color:var(--del); border:0; border-radius:.9rem; padding:.75rem 1.5rem; font-weight:700; box-shadow:0 8px 20px rgba(0,0,0,.2); }
        .btn-hero:hover { background:#FAF5FF; color:var(--del-dark); }
        .btn-del { background:linear-gradient(135deg,var(--del),var(--del-mid)); color:#fff; border:0; border-radius:.7rem; padding:.55rem 1.2rem; font-weight:500; box-shadow:0 4px 12px rgba(76,29,149,.3); }
        .btn-del:hover { color:#fff; transform:translateY(-1px); }
        .btn-del:disabled, .btn-del.disabled { opacity:.5; }
        .text-del { color:var(--del); }
        .book-card { background:#fff; border-radius:1.2rem; overflow:hidden; box-shadow:0 4px 16px rgba(76,29,149,.10); transition:.25s; display:flex; flex-direction:column; }
        .book-card:hover { transform:translateY(-6px); box-shadow:0 18px 36px rgba(76,29,149,.22); }
        .cover { position:relative; padding:1.1rem 1.2rem 1.3rem; min-height:190px; color:#fff; display:flex; flex-direction:column; justify-content:flex-end; overflow:hidden; }
        .cover::after { content:''; position:absolute; inset:0; background:linear-gradient(180deg,rgba(0,0,0,0) 30%,rgba(0,0,0,.35) 100%); }
        .cover > * { position:relative; z-index:1; }
        .cover-icon { position:absolute !important; right:-14px; top:10px; font-size:8rem; opacity:.16; transform:rotate(12deg); z-index:0 !important; }
        .cover-top { position:absolute !important; top:1rem; left:1.2rem; right:1.2rem; display:flex; justify-content:space-between; align-items:center; }
        .chip { background:rgba(255,255,255,.22); border:1px solid rgba(255,255,255,.35); padding:.25rem .8rem; border-radius:50rem; font-size:.75rem; font-weight:600; }
        .stok-chip { padding:.25rem .75rem; border-radius:50rem; font-size:.75rem; font-weight:700; background:#fff; }
        .stok-ok { color:#15803D; } .stok-low { color:#B45309; } .stok-out { color:#B91C1C; }
        .cover-title { font-weight:700; font-size:1.15rem; line-height:1.3; margin:3rem 0 .4rem; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
        .cover-author { font-size:.85rem; opacity:.92; }
        .book-body { padding:1rem 1.2rem 1.2rem; display:flex; flex-direction:column; gap:.9rem; flex:1; }
        .meta { display:grid; grid-template-columns:1fr 1fr; gap:.6rem .8rem; font-size:.8rem; }
        .meta .label { color:#9a93b3; font-size:.68rem; text-transform:uppercase; letter-spacing:.06em; display:block; }
        .meta .val { font-weight:600; color:#2d2650; word-break:break-word; } .meta .full { grid-column:1/-1; }
        .actions { display:flex; gap:.5rem; margin-top:auto; align-items:stretch; }
        .actions form { display:flex; }
        .btn-detail { flex:1; background:var(--del-soft); color:var(--del); border:0; border-radius:.7rem; padding:.55rem; font-weight:600; font-size:.85rem; text-decoration:none; text-align:center; }
        .btn-detail:hover { background:var(--del); color:#fff; }
        .btn-icon { width:40px; height:40px; padding:0; display:inline-flex; align-items:center; justify-content:center; border-radius:.7rem; border:0; text-decoration:none; }
        .btn-edit { background:#FEF3C7; color:#B45309; } .btn-remove { background:#FEE2E2; color:#B91C1C; }
        .empty-state { background:#fff; border-radius:1.2rem; padding:4rem 1rem; text-align:center; color:#8b84a3; box-shadow:0 4px 16px rgba(76,29,149,.08); }
        .page-header { background:#fff; border-radius:1rem; padding:1.2rem 1.5rem; box-shadow:0 4px 18px rgba(76,29,149,.08); }
        .card-del { border:0; border-radius:1.2rem; box-shadow:0 6px 22px rgba(76,29,149,.12); background:#fff; }
        .form-control,.form-select { border-radius:.7rem; padding:.65rem .95rem; }
        .form-control:focus,.form-select:focus { border-color:var(--del); box-shadow:0 0 0 .2rem rgba(76,29,149,.15); }
        .alert { border:0; border-radius:.9rem; box-shadow:0 4px 14px rgba(0,0,0,.07); }
        .pagination .page-link { color:var(--del); border:0; border-radius:.6rem; margin:0 2px; }
        .pagination .active .page-link { background:var(--del); color:#fff; }
        footer { color:#6b7280; font-size:.82rem; background:#fff; border-top:1px solid #E9E4F5; }

        @media (max-width:991px) {
            .sidebar { transform:translateX(-100%); width:268px; } .sidebar.open { transform:none; box-shadow:0 0 40px rgba(0,0,0,.4); }
            .app-main { margin-left:0; } .content,.crumb,.page-title { padding-left:1rem; padding-right:1rem; }
        }
    </style>
</head>
<body>
    <aside class="sidebar" id="sidebar">
        <div class="side-brand">
            <span class="pill brand-full"><img src="{{ asset('images/logo.png') }}" alt="Logo IT Del Library"></span>
            <div class="brand-full"><b>SIPUS-Del</b><small>Perpustakaan IT Del</small></div>
            <i class="bi bi-book-half brand-mini"></i>
        </div>

        <div class="side-role" title="{{ $dataPeran['label'] }}">
            <i class="bi {{ $dataPeran['icon'] }}"></i>
            <div class="txt"><div class="fw-semibold">{{ $dataPeran['label'] }}</div></div>
        </div>

        @foreach($seksi as $s)
            <div class="side-label">{{ $s['label'] }}</div>
            @foreach($s['items'] as $it)
                @php
                    [$label, $icon, $rute, $pola] = $it;
                    $anak = $it[4] ?? null;
                @endphp
                @if($anak)
                    @php
                        $buka = collect($anak)->contains(fn ($c) => request()->is(...$c[3]));
                        $idSub = 'sub-'.$loop->parent->index.'-'.$loop->index;
                    @endphp
                    <a href="#{{ $idSub }}" class="side-link {{ $buka ? 'active' : '' }}" data-bs-toggle="collapse" aria-expanded="{{ $buka ? 'true' : 'false' }}" title="{{ $label }}">
                        <i class="bi {{ $icon }}"></i><span class="side-text">{{ $label }}</span>
                        <i class="bi bi-chevron-down ms-auto side-text chev"></i>
                    </a>
                    <div class="collapse side-sub {{ $buka ? 'show' : '' }}" id="{{ $idSub }}">
                        @foreach($anak as [$l2, $i2, $r2, $p2])
                            <a href="{{ route($r2) }}" class="side-link {{ request()->is(...$p2) ? 'active' : '' }}" title="{{ $l2 }}">
                                <i class="bi {{ $i2 }}"></i><span class="side-text">{{ $l2 }}</span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <a href="{{ route($rute) }}" class="side-link {{ request()->is(...$pola) ? 'active' : '' }}" title="{{ $label }}">
                        <i class="bi {{ $icon }}"></i><span class="side-text">{{ $label }}</span>
                    </a>
                @endif
            @endforeach
        @endforeach
    </aside>

    <div class="app-main">
        <header class="topbar">
            <button class="btn-burger" type="button" id="btnToggle" title="Tutup / buka menu"><i class="bi bi-list"></i></button>

            <form class="topsearch d-none d-md-block" action="{{ route('katalog.index') }}" method="GET">
                <i class="bi bi-search"></i>
                <input type="text" name="q" placeholder="Cari judul, penulis, atau ISBN…">
            </form>

            <div class="top-actions">
                <div class="dropdown">
                    <a href="#" class="icon-btn" data-bs-toggle="dropdown" data-bs-auto-close="outside" title="Notifikasi">
                        <i class="bi bi-bell-fill"></i>
                        @if($jumlahNotif > 0)<span class="notif-badge">{{ $jumlahNotif > 9 ? '9+' : $jumlahNotif }}</span>@endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end notif-menu p-0 shadow border-0">
                        <div class="notif-head">
                            <span>Notifikasi</span>
                            @if($jumlahNotif > 0)
                                <form method="POST" action="{{ route('notifikasi.baca-semua') }}">@csrf
                                    <button class="btn btn-link btn-sm p-0 text-decoration-none">Tandai dibaca</button>
                                </form>
                            @endif
                        </div>
                        @forelse($notifs as $n)
                            <a href="{{ route('notifikasi.buka', $n) }}" class="notif-item {{ $n->dibaca ? '' : 'unread' }}">
                                <span class="ni"><i class="bi {{ $n->ikon }}"></i></span>
                                <div>
                                    <div class="t">{{ $n->judul }}</div>
                                    <div class="m">{{ \Illuminate\Support\Str::limit($n->pesan, 80) }}</div>
                                    <small>{{ $n->created_at->diffForHumans() }}</small>
                                </div>
                            </a>
                        @empty
                            <div class="text-center text-muted py-4 small"><i class="bi bi-bell-slash fs-3 d-block mb-1"></i>Belum ada notifikasi</div>
                        @endforelse
                        <a href="{{ route('notifikasi.index') }}" class="d-block text-center py-2 small fw-semibold text-decoration-none" style="color:var(--del)">Lihat semua notifikasi</a>
                    </div>
                </div>

                <div class="dropdown">
                    <a href="#" class="user-chip dropdown-toggle" data-bs-toggle="dropdown">
                        <span class="av"><i class="bi {{ $dataPeran['icon'] }}"></i></span>
                        <span class="d-none d-sm-block text-end" style="line-height:1.2">
                            <span class="fw-semibold">{{ \Illuminate\Support\Str::limit($pengguna->name, 26) }}</span><br>
                            <small style="opacity:.8">{{ $dataPeran['label'] }}</small>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><h6 class="dropdown-header">{{ $pengguna->username }}</h6></li>
                        <li><a class="dropdown-item" href="{{ route('profil.index') }}"><i class="bi bi-person-fill me-2"></i>Data Diri</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">@csrf
                                <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <nav class="crumb">
            <a href="{{ route('dashboard') }}"><i class="bi bi-house-fill me-1"></i>Home</a>
            @foreach(($crumbs ?? []) as $k => $v)
                <span class="sep">/</span>
                @if(is_string($k)) <a href="{{ $v }}">{{ $k }}</a> @else <span class="text-muted">{{ $v }}</span> @endif
            @endforeach
        </nav>

        @isset($heading)<div class="page-title">{{ $heading }}</div>@endisset

        <div class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @isset($panel)
                <div class="row g-4">
                    <div class="col-xl-9">{{ $slot }}</div>
                    <div class="col-xl-3">{{ $panel }}</div>
                </div>
            @else
                {{ $slot }}
            @endisset
        </div>

        <footer class="text-center py-3">&copy; {{ date('Y') }} SIPUS-Del · Perpustakaan Kampus Institut Teknologi Del</footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('btnToggle').addEventListener('click', function () {
            if (window.innerWidth < 992) {
                document.getElementById('sidebar').classList.toggle('open');
            } else {
                var tutup = document.documentElement.classList.toggle('collapsed');
                try { localStorage.setItem('sidebarCollapsed', tutup ? '1' : '0'); } catch (e) {}
            }
        });
    </script>
</body>
</html>