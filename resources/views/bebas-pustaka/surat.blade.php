<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Bebas Pustaka — {{ $bp->user->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family:'Times New Roman',serif; background:#eee; }
        .kertas { max-width:780px; margin:1.5rem auto; background:#fff; padding:2.5rem 3rem; box-shadow:0 8px 30px rgba(0,0,0,.15); }
        .kop { text-align:center; border-bottom:3px double #333; padding-bottom:.8rem; margin-bottom:1.5rem; }
        .kop img { height:70px; }
        @media print { body { background:#fff; } .kertas { box-shadow:none; margin:0; } .no-print { display:none; } }
    </style>
</head>
<body>
    <div class="text-center mt-3 no-print"><button class="btn btn-primary" onclick="window.print()">Cetak / Simpan PDF</button></div>
    <div class="kertas">
        <div class="kop">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <h5 class="mb-0 mt-2 fw-bold">PERPUSTAKAAN INSTITUT TEKNOLOGI DEL</h5>
            <small>Sitoluama, Laguboti, Toba, Sumatera Utara</small>
        </div>
        <h5 class="text-center fw-bold text-decoration-underline mb-1">SURAT KETERANGAN BEBAS PUSTAKA</h5>
        <p class="text-center">Nomor: BP/{{ str_pad($bp->id, 4, '0', STR_PAD_LEFT) }}/PERPUS/{{ $bp->tgl_proses?->format('Y') }}</p>

        <p>Kepala Perpustakaan Institut Teknologi Del menerangkan bahwa:</p>
        <table class="mb-3">
            <tr><td style="width:150px">Nama</td><td>: <b>{{ $bp->user->name }}</b></td></tr>
            <tr><td>NIM / Username</td><td>: {{ $bp->user->username }}</td></tr>
            <tr><td>Status</td><td>: {{ $bp->user->peran_data['label'] }}</td></tr>
        </table>
        <p style="text-align:justify">Berdasarkan data sistem sirkulasi per tanggal {{ $bp->tgl_proses?->translatedFormat('d F Y') }}, yang bersangkutan <b>telah mengembalikan seluruh buku pinjaman dan tidak memiliki tanggungan denda</b> di Perpustakaan Institut Teknologi Del.</p>
        <p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>

        <div class="d-flex justify-content-end mt-5">
            <div class="text-center">
                <div>Laguboti, {{ $bp->tgl_proses?->translatedFormat('d F Y') }}</div>
                <div>Pegawai Perpustakaan</div>
                <div style="height:80px"></div>
                <div class="fw-bold text-decoration-underline">Jesicha Ulina Hutabarat, S.Sos</div>
            </div>
        </div>
    </div>
</body>
</html>