<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk') — BI-KANTIN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-dvh bg-canvas">
    <div class="flex min-h-dvh flex-col lg:flex-row">
        {{-- Logo otomatis dari public/images/logo-bi.* — tanpa file, tampil "BI". --}}
        <aside class="brand-gradient safe-top relative flex shrink-0 flex-col justify-between gap-8 p-6 sm:p-10 lg:w-[38%] lg:max-w-[440px]">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <x-logo class="h-11 w-11 text-base ring-1 ring-white/40" />
                <span class="text-lg font-bold tracking-tight text-white">BI-KANTIN</span>
            </a>

            <div class="hidden lg:block">
                <p class="text-[13px] font-semibold uppercase tracking-[0.18em] text-white/70">SMK Bina Informatika</p>
                <h1 class="mt-3 text-3xl font-bold leading-tight tracking-tight text-white">
                    Lapar?<br>Pesan dari kelas.
                </h1>
                <p class="mt-4 max-w-xs text-sm leading-relaxed text-white/75">
                    Pilih menu kantin, bayar di kasir, lalu ambil pesananmu begitu statusnya siap.
                </p>
            </div>

            <ul class="flex flex-wrap gap-2 text-xs font-medium text-white/85">
                <li class="rounded-full border border-white/25 bg-white/10 px-3 py-1.5">Makanan</li>
                <li class="rounded-full border border-white/25 bg-white/10 px-3 py-1.5">Minuman</li>
                <li class="rounded-full border border-white/25 bg-white/10 px-3 py-1.5">Snack</li>
            </ul>
        </aside>

        <main class="safe-bottom flex flex-1 items-center justify-center px-4 py-10 sm:px-8">
            <div class="w-full max-w-md anim-in">
                @if($errors->any())
                    <div class="card mb-5 border-bad/30 p-4">
                        <p class="mb-2 text-sm font-semibold text-bad">Periksa kembali inputanmu:</p>
                        <ul class="list-disc space-y-1 pl-5 text-sm text-muted">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="card mb-5 border-ok/30 p-4 text-sm font-medium text-ok">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
