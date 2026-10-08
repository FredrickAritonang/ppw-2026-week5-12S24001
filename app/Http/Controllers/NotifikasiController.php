<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::where('user_id', Auth::id())->latest()->latest('id')->paginate(15);

        return view('notifikasi.index', compact('notifikasis'));
    }

    public function bacaSemua()
    {
        Notifikasi::where('user_id', Auth::id())->where('dibaca', false)->update(['dibaca' => true]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function buka(Notifikasi $notifikasi)
    {
        abort_unless($notifikasi->user_id === Auth::id(), 403);

        $notifikasi->update(['dibaca' => true]);

        return redirect($notifikasi->url ?: route('notifikasi.index'));
    }
}