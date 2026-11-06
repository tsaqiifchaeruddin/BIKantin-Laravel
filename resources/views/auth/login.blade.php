@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
    <div class="mb-7">
        <h1 class="text-2xl font-bold tracking-tight">Masuk ke BI-KANTIN</h1>
        <p class="mt-1.5 text-sm text-muted">Satu pintu untuk siswa, kasir, dan admin sekolah.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4" novalidate>
        @csrf

        <div>
            <label class="label" for="username">Username</label>
            <input class="input" type="text" id="username" name="username"
                   value="{{ old('username') }}" placeholder="contoh: budi_siswa"
                   autocomplete="username" autofocus
                   aria-invalid="{{ $errors->has('username') ? 'true' : 'false' }}">
            @error('username')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <div>
            <label class="label" for="password">Password</label>
            <input class="input" type="password" id="password" name="password"
                   placeholder="••••••••" autocomplete="current-password"
                   aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}">
            @error('password')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <label class="flex cursor-pointer items-center gap-2.5 text-sm text-muted">
            <input type="checkbox" name="remember" value="1"
                   class="h-4 w-4 rounded border-line text-brand focus:ring-brand">
            Ingat saya di perangkat ini
        </label>

        <button type="submit" class="btn btn-primary w-full">Masuk</button>
    </form>

    <p class="mt-5 text-center text-sm text-muted">
        Belum punya akun siswa?
        <a href="{{ route('register') }}" class="font-semibold text-brand link-brand">Daftar di sini</a>
    </p>

    <div class="card mt-7 p-4">
        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-muted">Akun demo</p>
        <div class="mt-3 grid gap-2 text-sm sm:grid-cols-3">
            <div class="rounded-lg bg-canvas px-3 py-2">
                <p class="font-semibold">Siswa</p>
                <p class="text-xs text-muted">budi_siswa / 123</p>
            </div>
            <div class="rounded-lg bg-canvas px-3 py-2">
                <p class="font-semibold">Kasir</p>
                <p class="text-xs text-muted">kasir_kantin / kasir123</p>
            </div>
            <div class="rounded-lg bg-canvas px-3 py-2">
                <p class="font-semibold">Admin</p>
                <p class="text-xs text-muted">admin_kantin / admin123</p>
            </div>
        </div>
        <p class="mt-3 text-xs text-muted">Role otomatis menentukan halaman setelah masuk.</p>
    </div>
@endsection
