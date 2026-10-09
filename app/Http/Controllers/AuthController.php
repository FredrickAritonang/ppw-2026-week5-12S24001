<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        $akunSaran = User::whereIn('role', ['dosen', 'pustakawan'])
            ->orderBy('name')
            ->get(['username', 'name', 'role']);

        return view('auth.login', compact('akunSaran'));
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $input = trim($data['username']);
        $slug = Str::slug($input, '');

        $user = User::where('username', strtolower($input))
            ->orWhere('username', $slug)
            ->orWhere('name', $input)
            ->first();

        if ($user && Hash::check($data['password'], $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
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

        return redirect()->route('login');
    }
}