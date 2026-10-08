<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Usulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsulanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $usulans = Usulan::with('user')
            ->when($user->role !== 'pustakawan', fn ($q) => $q->where('user_id', $user->id))
            ->latest()->latest('id')
            ->paginate(10);

        return view('usulan.index', compact('usulans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required|string|min:3|max:200',
            'penulis' => 'required|string|max:150',
            'penerbit' => 'nullable|string|max:150',
            'alasan' => 'nullable|string|max:500',
        ]);

        Usulan::create($data + ['user_id' => Auth::id()]);

        Notifikasi::kirimKePeran('pustakawan', 'Usulan buku baru', Auth::user()->name.' mengusulkan "'.$data['judul'].'".', 'bi-lightbulb-fill', route('usulan.index', absolute: false));

        return back()->with('success', 'Usulan berhasil dikirim. Terima kasih!');
    }

    public function proses(Request $request, Usulan $usulan)
    {
        $data = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string|max:255',
        ]);

        $usulan->update($data);

        Notifikasi::kirim(
            $usulan->user_id,
            'Usulan '.$data['status'],
            "Usulan \"{$usulan->judul}\" {$data['status']}.".($data['catatan'] ? ' Catatan: '.$data['catatan'] : ''),
            $data['status'] === 'disetujui' ? 'bi-check-circle-fill' : 'bi-x-circle-fill',
            route('usulan.index', absolute: false)
        );

        return back()->with('success', 'Usulan telah '.$data['status'].'.');
    }
}