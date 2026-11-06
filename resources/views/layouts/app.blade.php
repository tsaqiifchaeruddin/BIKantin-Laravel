<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BI-KANTIN') — SMK Bina Informatika</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-dvh">
    {{-- Logo otomatis dari public/images/logo-bi.* — tanpa file, tampil "BI". --}}
    <header class="safe-top sticky top-0 z-40 border-b border-line bg-surface/85 backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-6xl items-center gap-3 px-4 sm:px-6">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <x-logo class="h-9 w-9 text-[11px] ring-1 ring-line" />
                <span class="hidden text-[15px] font-bold tracking-tight sm:block">
                    BI-KANTIN
                </span>
            </a>

            <nav class="ml-auto hidden items-center gap-1 md:flex">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost py-2 text-sm {{ request()->routeIs('admin.dashboard') ? 'font-bold text-brand' : '' }}">Dashboard</a>
                        <a href="{{ route('admin.menu.index') }}" class="btn btn-ghost py-2 text-sm {{ request()->routeIs('admin.menu.*') ? 'font-bold text-brand' : '' }}">Kelola Menu</a>
                        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-ghost py-2 text-sm {{ request()->routeIs('admin.pesanan.*') ? 'font-bold text-brand' : '' }}">Pesanan</a>
                        <a href="{{ route('kasir.dashboard') }}" class="btn btn-ghost py-2 text-sm {{ request()->routeIs('kasir.*') ? 'font-bold text-brand' : '' }}">Kasir</a>
                    @elseif(auth()->user()->isKasir())
                        <a href="{{ route('kasir.dashboard') }}" class="btn btn-ghost py-2 text-sm {{ request()->routeIs('kasir.*') ? 'font-bold text-brand' : '' }}">Kasir</a>
                    @else
                        <a href="{{ route('siswa.dashboard') }}" class="btn btn-ghost py-2 text-sm {{ request()->routeIs('siswa.dashboard') ? 'font-bold text-brand' : '' }}">Menu</a>
                        <a href="{{ route('siswa.dashboard') }}#pesanan-saya" class="btn btn-ghost py-2 text-sm">Pesanan Saya</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="ml-1">
                        @csrf
                        <button type="submit" class="btn btn-soft py-2 text-sm">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost py-2 text-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary py-2 text-sm">Daftar</a>
                @endauth
            </nav>

            {{-- Menu mobile: disclosure native, tanpa JS --}}
            <details class="relative ml-auto md:hidden">
                <summary class="btn btn-ghost flex-col gap-1 px-3 py-2 list-none [&::-webkit-details-marker]:hidden" aria-label="Buka menu">
                    <span class="block h-0.5 w-5 rounded bg-current"></span>
                    <span class="block h-0.5 w-5 rounded bg-current"></span>
                    <span class="block h-0.5 w-5 rounded bg-current"></span>
                </summary>
                <div class="card absolute right-0 top-12 w-56 p-2 anim-in">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-canvas">Dashboard</a>
                            <a href="{{ route('admin.menu.index') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-canvas">Kelola Menu</a>
                            <a href="{{ route('admin.pesanan.index') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-canvas">Pesanan</a>
                            <a href="{{ route('kasir.dashboard') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-canvas">Kasir</a>
                        @elseif(auth()->user()->isKasir())
                            <a href="{{ route('kasir.dashboard') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-canvas">Kasir</a>
                        @else
                            <a href="{{ route('siswa.dashboard') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-canvas">Menu</a>
                            <a href="{{ route('siswa.dashboard') }}#pesanan-saya" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-canvas">Pesanan Saya</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="mt-1 block w-full rounded-lg px-3 py-2.5 text-left text-sm font-medium text-bad hover:bg-canvas">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-canvas">Masuk</a>
                        <a href="{{ route('register') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium hover:bg-canvas">Daftar</a>
                    @endauth
                </div>
            </details>
        </div>
    </header>

    @if(session('success'))
        <div class="pointer-events-none fixed inset-x-0 top-20 z-50 flex justify-center px-4"
             data-toast data-autohide="4000">
            <div class="toast pointer-events-auto flex items-start gap-3 rounded-xl border border-ok/30 bg-surface px-4 py-3 shadow-lg shadow-ok/10">
                <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-ok text-[11px] font-bold text-white">✓</span>
                <p class="text-sm font-medium">{{ session('success') }}</p>
                <button type="button" class="ml-2 text-muted hover:text-ink" data-toast-close aria-label="Tutup">×</button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="pointer-events-none fixed inset-x-0 top-20 z-50 flex justify-center px-4"
             data-toast data-autohide="5000">
            <div class="toast pointer-events-auto flex items-start gap-3 rounded-xl border border-bad/30 bg-surface px-4 py-3 shadow-lg shadow-bad/10">
                <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-bad text-[11px] font-bold text-white">!</span>
                <p class="text-sm font-medium">{{ session('error') }}</p>
                <button type="button" class="ml-2 text-muted hover:text-ink" data-toast-close aria-label="Tutup">×</button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mx-auto mt-4 max-w-6xl px-4 sm:px-6">
            <div class="card border-bad/30 p-4">
                <p class="mb-2 text-sm font-semibold text-bad">Periksa kembali inputanmu:</p>
                <ul class="list-disc space-y-1 pl-5 text-sm text-muted">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="safe-bottom mt-16 border-t border-line bg-surface">
        <div class="mx-auto flex max-w-6xl flex-col gap-2 px-4 py-6 text-sm text-muted sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <p>© {{ date('Y') }} BI-KANTIN — SMK Bina Informatika</p>
            <p class="text-xs">Kantin sekolah: pesan dari kelas, bayar di kasir, ambil saat siap.</p>
        </div>
    </footer>

    <script>
        document.querySelectorAll('[data-toast]').forEach((box) => {
            const hide = () => {
                box.firstElementChild.classList.add('is-leaving');
                setTimeout(() => box.remove(), 240);
            };
            box.querySelector('[data-toast-close]')?.addEventListener('click', hide);
            setTimeout(hide, Number(box.dataset.autohide) || 4000);
        });

        // Menu mobile menutup diri setelah tautan diklik
        document.querySelectorAll('details > div a, details > div button').forEach((el) => {
            el.addEventListener('click', () => el.closest('details')?.removeAttribute('open'));
        });
    </script>
    @stack('scripts')
</body>
</html>
