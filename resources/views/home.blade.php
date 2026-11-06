@extends('layouts.app')

@section('title', 'Kantin Sekolah Online')

@section('content')
    {{-- ================================================================ HERO --}}
    <section class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute -left-24 -top-28 h-72 w-72 rounded-full bg-brand/10 blur-3xl"></div>
            <div class="absolute -right-20 top-10 h-80 w-80 rounded-full bg-accent/10 blur-3xl"></div>
        </div>

        <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 pb-14 pt-12 sm:px-6 lg:grid-cols-2 lg:gap-14 lg:pb-20 lg:pt-20">
            <div class="anim-in">
                <span class="inline-flex items-center gap-2 rounded-full border border-brand/15 bg-brand/5 px-3 py-1.5 text-xs font-semibold text-brand">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand"></span>
                    Kantin SMK Bina Informatika
                </span>

                <h1 class="mt-5 text-4xl font-bold leading-[1.08] tracking-tight sm:text-5xl">
                    Pesan makanan kantin
                    <span class="brand-gradient bg-clip-text text-transparent">tanpa antre.</span>
                </h1>

                <p class="mt-4 max-w-lg text-[15px] leading-relaxed text-muted sm:text-base">
                    Buka menu dari HP, pilih porsi, lalu bayar di kasir. Kami yang siapkan —
                    kamu tinggal ambil saat status pesanan berubah jadi <strong class="font-semibold text-ink">Siap Diambil</strong>.
                </p>

                <div class="mt-7 flex flex-wrap items-center gap-3">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isKasir() ? route('kasir.dashboard') : route('siswa.dashboard')) }}"
                           class="btn btn-primary">
                            {{ auth()->user()->isSiswa() ? 'Pesan menu sekarang' : 'Buka dashboard' }}
                            <span aria-hidden="true">→</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            Pesan sekarang
                            <span aria-hidden="true">→</span>
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-ghost">Daftar akun siswa</a>
                    @endauth
                </div>

                <dl class="mt-9 grid max-w-md grid-cols-3 gap-4">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-muted">Menu</dt>
                        <dd class="mt-1 text-2xl font-bold tracking-tight">{{ $totalMenu }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-muted">Pesanan</dt>
                        <dd class="mt-1 text-2xl font-bold tracking-tight">{{ $totalPesanan }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-muted">Antre</dt>
                        <dd class="mt-1 text-2xl font-bold tracking-tight">Nol</dd>
                    </div>
                </dl>
            </div>

            {{-- Kartu ilustrasi pesanan --}}
            <div class="anim-in lg:justify-self-end" style="--i: 2">
                <div class="card w-full max-w-md p-5 shadow-xl shadow-brand/5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold">Pesanan #012</p>
                        <x-status status="Siap Diambil" />
                    </div>

                    <div class="mt-4 flex items-center gap-3 rounded-xl bg-canvas p-3">
                        <span class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-brand to-accent text-lg text-white">🍚</span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold">Nasi Goreng Spesial</p>
                            <p class="text-xs text-muted">2 porsi · sudah dibayar</p>
                        </div>
                        <p class="text-sm font-bold">Rp30.000</p>
                    </div>

                    <ol class="mt-5 space-y-3 text-sm">
                        @foreach([
                            ['Pesanan diterima', true],
                            ['Sedang dimasak', true],
                            ['Siap diambil', true],
                            ['Selesai', false],
                        ] as [$langkah, $selesai])
                            <li class="flex items-center gap-3">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-[11px] font-bold {{ $selesai ? 'bg-ok text-white' : 'border border-line bg-surface text-muted' }}">
                                    {{ $selesai ? '✓' : '' }}
                                </span>
                                <span class="{{ $selesai ? 'font-medium' : 'text-muted' }}">{{ $langkah }}</span>
                            </li>
                        @endforeach
                    </ol>

                    <p class="mt-5 rounded-lg bg-ok/10 px-3 py-2 text-xs font-medium text-ok">
                        Pesananmu sudah siap — ambil di meja kasir.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ================================================================ MENU --}}
    <section class="mx-auto max-w-6xl px-4 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Menu hari ini</h2>
                <p class="mt-1 text-sm text-muted">Harga sudah termasuk porsi kantin. Menu habis ditandai otomatis.</p>
            </div>
            @auth
                @if(auth()->user()->isSiswa())
                    <a href="{{ route('siswa.dashboard') }}" class="btn btn-soft text-sm">Lihat semua menu</a>
                @endif
            @endauth
        </div>

        @if($menus->isEmpty())
            <div class="card mt-6 p-8 text-center">
                <p class="font-semibold">Belum ada menu tersedia.</p>
                <p class="mt-1 text-sm text-muted">Cek lagi nanti, ya.</p>
            </div>
        @else
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($menus->take(6) as $menu)
                    <article class="card anim-in flex flex-col p-5 transition-transform duration-200 hover:-translate-y-0.5" style="--i: {{ $loop->iteration }}">
                        <div class="flex items-start justify-between gap-3">
                            <span class="rounded-full bg-canvas px-2.5 py-1 text-[11px] font-semibold text-muted">{{ $menu->kategori }}</span>
                            <span class="text-xs font-medium text-ok">Tersedia</span>
                        </div>
                        <h3 class="mt-3 text-[15px] font-bold leading-snug">{{ $menu->nama_menu }}</h3>
                        <p class="mt-1 line-clamp-2 text-sm text-muted">{{ $menu->deskripsi ?: 'Menu favorit kantin sekolah.' }}</p>
                        <div class="mt-auto flex items-center justify-between pt-4">
                            <p class="text-lg font-bold tracking-tight">Rp{{ number_format($menu->harga, 0, ',', '.') }}</p>
                            <a href="{{ auth()->check() && auth()->user()->isSiswa() ? route('siswa.dashboard') : route('login') }}"
                               class="btn btn-soft px-3 py-2 text-xs">Pesan</a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    {{-- ================================================================ CARA --}}
    <section class="mx-auto mt-16 max-w-6xl px-4 sm:px-6">
        <h2 class="text-2xl font-bold tracking-tight">Tiga langkah, makanan datang.</h2>
        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            @foreach([
                ['01', 'Pilih menu', 'Buka daftar menu, tentukan jumlah porsi, lalu kirim pesanan dari HP.'],
                ['02', 'Bayar di kasir', 'Tunjukkan nomor pesanan. Kasir memvalidasi pembayaran dan statusnya jadi Lunas.'],
                ['03', 'Ambil saat siap', 'Pantau status: Menunggu Konfirmasi → Sedang Dimasak → Siap Diambil → Selesai.'],
            ] as [$nomor, $judul, $isi])
                <div class="card anim-in p-5" style="--i: {{ $loop->iteration }}">
                    <span class="brand-gradient inline-flex h-9 w-9 items-center justify-center rounded-lg text-xs font-bold text-white">{{ $nomor }}</span>
                    <h3 class="mt-4 font-bold">{{ $judul }}</h3>
                    <p class="mt-1.5 text-sm leading-relaxed text-muted">{{ $isi }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ================================================================ CTA --}}
    <section class="mx-auto mt-16 max-w-6xl px-4 sm:px-6">
        <div class="brand-gradient relative overflow-hidden rounded-2xl px-6 py-10 text-white sm:px-10">
            <div class="pointer-events-none absolute -right-10 -top-16 h-56 w-56 rounded-full bg-white/10 blur-2xl"></div>
            <div class="relative flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Sudah lapar?</h2>
                    <p class="mt-1.5 max-w-xl text-sm leading-relaxed text-white/80">
                        Masuk dengan akun sekolahmu dan pesan sebelum bel istirahat berbunyi.
                    </p>
                </div>
                <a href="{{ auth()->check() ? route(auth()->user()->dashboardRoute()) : route('login') }}"
                   class="btn shrink-0 bg-white px-5 text-brand hover:bg-white/90">
                    {{ auth()->check() ? 'Buka dashboard' : 'Masuk ke BI-KANTIN' }}
                    <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </section>
@endsection
