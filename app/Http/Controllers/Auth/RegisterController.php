<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:30', 'regex:/^[a-zA-Z0-9_.]+$/', 'unique:users,username'],
            'kelas' => ['required', 'string', 'max:30'],
            'no_wa' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'username.regex' => 'Username hanya boleh huruf, angka, titik, dan garis bawah.',
            'username.unique' => 'Username sudah dipakai, coba yang lain.',
        ]);

        $user = User::create([
            'username' => $data['username'],
            'password' => $data['password'],
            'role' => 'siswa',
            'kelas' => $data['kelas'],
            'no_wa' => $data['no_wa'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('siswa.dashboard')
            ->with('success', 'Akun berhasil dibuat. Selamat datang di BI-KANTIN!');
    }
}
