<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $penggunas = User::withCount(['peminjamans as pinjaman_aktif' => fn ($q) => $q->whereNull('tanggal_kembali')])
            ->when($request->role, fn ($q, $v) => $q->where('role', $v))
            ->when($request->q, fn ($q, $v) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$v}%")
                ->orWhere('username', 'like', "%{$v}%")))
            ->orderBy('role')->orderBy('name')
            ->paginate(20)->withQueryString();

        return view('pengguna.index', compact('penggunas'));
    }
}