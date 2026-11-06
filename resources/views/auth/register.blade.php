@extends('layouts.auth')

@section('title', 'Daftar Akun Siswa')

@section('content')
    <div class="mb-7">
        <h1 class="text-2xl font-bold tracking-tight">Daftar akun siswa</h1>
        <p class="mt-1.5 text-sm text-muted">Cukup sekali, lalu kamu bisa langsung pesan menu kantin.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4" novalidate>
        @csrf

        <div>
            <label class="label" for="username">Username</label>
            <input class="input" type="text" id="username" name="username"
                   value="{{ old('username') }}" placeholder="contoh: budi_siswa"
                   autocomplete="username" autofocus
                   aria-invalid="{{ $errors->has('username') ? 'true' : 'false' }}">
            @error('username')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="kelas">Kelas</label>
                <select class="input" id="kelas" name="kelas"
                        aria-invalid="{{ $errors->has('kelas') ? 'true' : 'false' }}">
                    <option value="" disabled {{ old('kelas') ? '' : 'selected' }}>Pilih kelas</option>
                    @foreach(['X RPL 1', 'X RPL 2', 'XI RPL 1', 'XI RPL 2', 'XII RPL 1', 'XII RPL 2', 'X TKJ 1', 'XI TKJ 1', 'XII TKJ 1'] as $kelas)
                        <option value="{{ $kelas }}" @selected(old('kelas') === $kelas)>{{ $kelas }}</option>
                    @endforeach
                </select>
                @error('kelas')<span class="error-text">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="label" for="no_wa">Nomor WhatsApp</label>
                <input class="input" type="tel" id="no_wa" name="no_wa"
                       value="{{ old('no_wa') }}" placeholder="081234567890"
                       autocomplete="tel"
                       aria-invalid="{{ $errors->has('no_wa') ? 'true' : 'false' }}">
                @error('no_wa')<span class="error-text">{{ $message }}</span>@enderror
            </div>
        </div>

        <div>
            <label class="label" for="password">Password</label>
            <input class="input" type="password" id="password" name="password"
                   placeholder="minimal 6 karakter" autocomplete="new-password"
                   aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}">
            @error('password')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <div>
            <label class="label" for="password_confirmation">Ulangi password</label>
            <input class="input" type="password" id="password_confirmation" name="password_confirmation"
                   placeholder="ketik ulang password" autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-primary w-full">Buat akun</button>
    </form>

    <p class="mt-5 text-center text-sm text-muted">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-brand link-brand">Masuk di sini</a>
    </p>
@endsection
