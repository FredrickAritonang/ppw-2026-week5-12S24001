<?php

namespace App\Http\Controllers;

use App\Models\BebasPustaka;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BebasPustakaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'pustakawan') {
            $pengajuan = BebasPustaka::with('user')->latest()->latest('id')->paginate(10);
            $ids = $pengajuan->pluck('user_id')->unique();

            // Hitung semua syarat sekaligus (tanpa N+1) agar View tidak menjalankan kueri
            $aktifMap = Peminjaman::aktif()->whereIn('user_id', $ids)->get()
                ->groupBy('user_id')->map->count();

            $dendaMap = Peminjaman::berdenda()->whereIn('user_id', $ids)
                ->where('denda_lunas', false)->get()
                ->groupBy('user_id')->map(fn ($g) => (int) $g->sum('total_denda'));

            return view('bebas-pustaka.index', [
                'mode' => 'staf',
                'pengajuan' => $pengajuan,
                'aktifMap' => $aktifMap,
                'dendaMap' => $dendaMap,
            ]);
        }

        $riwayat = BebasPustaka::where('user_id', $user->id)->latest()->latest('id')->get();

        return view('bebas-pustaka.index', [
            'mode' => 'mahasiswa',
            'aktif' => $user->pinjamanAktif(),
            'denda' => $user->dendaBelumLunas(),
            'riwayat' => $riwayat,
            'menunggu' => $riwayat->firstWhere('status', 'diajukan'),
        ]);
    }

    public function ajukan()
    {
        $user = Auth::user();

        if ($user->pinjamanAktif() > 0 || $user->dendaBelumLunas() > 0) {
            return back()->with('error', 'Syarat belum terpenuhi: kembalikan semua buku dan lunasi denda.');
        }
        if (BebasPustaka::where('user_id', $user->id)->where('status', 'diajukan')->exists()) {
            return back()->with('error', 'Pengajuan sebelumnya masih diproses.');
        }

        BebasPustaka::create(['user_id' => $user->id]);

        Notifikasi::kirimKePeran('pustakawan', 'Pengajuan bebas pustaka', $user->name.' mengajukan surat bebas pustaka.', 'bi-patch-check-fill', route('bebas-pustaka.index', absolute: false));

        return back()->with('success', 'Pengajuan berhasil dikirim.');
    }

    public function proses(Request $request, BebasPustaka $bebasPustaka)
    {
        $data = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string|max:255',
        ]);

        $anggota = $bebasPustaka->user;

        if ($data['status'] === 'disetujui' && ($anggota->pinjamanAktif() > 0 || $anggota->dendaBelumLunas() > 0)) {
            return back()->with('error', 'Tidak dapat disetujui: masih ada pinjaman aktif atau denda belum lunas.');
        }

        $bebasPustaka->update($data + ['tgl_proses' => today()]);

        Notifikasi::kirim($anggota->id, 'Bebas pustaka '.$data['status'], 'Pengajuan bebas pustaka Anda '.$data['status'].'.'.($data['catatan'] ? ' Catatan: '.$data['catatan'] : ''), $data['status'] === 'disetujui' ? 'bi-patch-check-fill' : 'bi-x-circle-fill', route('bebas-pustaka.index', absolute: false));

        return back()->with('success', 'Pengajuan telah '.$data['status'].'.');
    }

    public function surat(BebasPustaka $bebasPustaka)
    {
        $user = Auth::user();
        abort_unless($user->role === 'pustakawan' || $bebasPustaka->user_id === $user->id, 403);
        abort_unless($bebasPustaka->status === 'disetujui', 404);

        return view('bebas-pustaka.surat', ['bp' => $bebasPustaka->load('user')]);
    }
}