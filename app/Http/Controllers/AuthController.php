<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        $akunSaran = User::whereIn('role', ['dosen', 'pustakawan'])
            ->orderBy('name')
            ->get(['username', 'name', 'role']);

        $terakhir = $request->cookie('del_last_user');

        return view('auth.login', compact('akunSaran', 'terakhir'));
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $input = trim($data['username']);

        $user = User::where('username', strtolower($input))
            ->orWhere('email', strtolower($input))
            ->orWhere('username', Str::slug($input, ''))
            ->orWhere('name', $input)
            ->first();

        if ($user && Auth::attempt(
            ['username' => $user->username, 'password' => $data['password']],
            $request->boolean('remember')
        )) {
            $request->session()->regenerate();

            session(['login_timestamp' => now()->format('d M Y, H:i:s').' WIB']);
            Cookie::queue('del_last_user', $user->username, 60 * 24 * 7);

            return redirect()->intended(route('dashboard'))->with('success', 'Sesi Anda telah aktif.');
        }

        return back()
            ->withErrors(['username' => 'Username atau password salah.'])
            ->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesi server telah dihapus dengan aman.');
    }
}