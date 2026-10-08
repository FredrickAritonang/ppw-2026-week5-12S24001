<x-layout title="Notifikasi" heading="Notifikasi" :crumbs="['Notifikasi']">
    <div class="panel-box">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 border-0 pb-0">Semua Notifikasi</h6>
            <form method="POST" action="{{ route('notifikasi.baca-semua') }}">@csrf
                <button class="btn btn-sm btn-del"><i class="bi bi-check2-all me-1"></i>Tandai semua dibaca</button>
            </form>
        </div>
        @forelse($notifikasis as $n)
            <a href="{{ route('notifikasi.buka', $n) }}" class="notif-item {{ $n->dibaca ? '' : 'unread' }} rounded-3 mb-2">
                <span class="ni"><i class="bi {{ $n->ikon }}"></i></span>
                <div class="flex-grow-1">
                    <div class="t">{{ $n->judul }} @unless($n->dibaca)<span class="badge-st st-bad ms-1">Baru</span>@endunless</div>
                    <div class="m">{{ $n->pesan }}</div>
                    <small>{{ $n->created_at->diffForHumans() }}</small>
                </div>
            </a>
        @empty
            <div class="text-center text-muted py-5"><i class="bi bi-bell-slash fs-1 d-block mb-1"></i>Belum ada notifikasi.</div>
        @endforelse
        <div class="mt-3">{{ $notifikasis->links() }}</div>
    </div>
</x-layout>