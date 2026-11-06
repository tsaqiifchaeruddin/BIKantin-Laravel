@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="mx-auto max-w-6xl px-4 pt-8 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-muted">Admin sekolah</p>
                <h1 class="mt-1 text-3xl font-bold tracking-tight">Dashboard kantin</h1>
                <p class="mt-1.5 text-sm text-muted">Ringkasan menu dan pesanan · {{ now()->format('d/m/Y') }}</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.menu.index') }}" class="btn btn-ghost text-sm">Kelola menu</a>
                <a href="{{ route('admin.pesanan.index') }}" class="btn btn-primary text-sm">Lihat pesanan</a>
            </div>
        </div>

        {{-- ============================================================ STATS --}}
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach([
                ['Menu tersedia', $menuTersedia.' / '.$totalMenu, '☰', 'Menu aktif dari total menu'],
                ['Pesanan hari ini', $pesananHariIni, '🧾', 'Semua pesanan masuk'],
                ['Pesanan aktif', $pesananAktif, '⏳', 'Belum berstatus selesai'],
                ['Pendapatan lunas', 'Rp'.number_format($pendapatanHariIni, 0, ',', '.'), '💳', 'Pembayaran hari ini'],
            ] as [$label, $nilai, $ikon, $ket])
                <div class="card anim-in p-5" style="--i: {{ $loop->iteration }}">
                    <div class="flex items-start justify-between gap-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-muted">{{ $label }}</p>
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand/10 text-base">{{ $ikon }}</span>
                    </div>
                    <p class="mt-3 text-2xl font-bold tracking-tight">{{ $nilai }}</p>
                    <p class="mt-1 text-xs text-muted">{{ $ket }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-3">
            {{-- ================================================= PESANAN TERBARU --}}
            <section class="lg:col-span-2">
                <div class="flex items-end justify-between gap-3">
                    <h2 class="text-xl font-bold tracking-tight">Pesanan terbaru</h2>
                    <a href="{{ route('admin.pesanan.index') }}" class="text-sm font-semibold text-brand link-brand">Semua pesanan →</a>
                </div>

                <div class="card mt-4 overflow-hidden">
                    @if($pesanans->isEmpty())
                        <div class="p-8 text-center">
                            <p class="font-semibold">Belum ada pesanan.</p>
                            <p class="mt-1 text-sm text-muted">Pesanan siswa akan tampil di sini.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[640px] text-sm">
                                <thead class="border-b border-line bg-canvas/60 text-left text-xs uppercase tracking-wide text-muted">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold">Pesanan</th>
                                        <th class="px-4 py-3 font-semibold">Siswa</th>
                                        <th class="px-4 py-3 font-semibold">Status</th>
                                        <th class="px-4 py-3 font-semibold">Bayar</th>
                                        <th class="px-4 py-3 text-right font-semibold">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-line">
                                    @foreach($pesanans as $pesanan)
                                        <tr class="transition-colors hover:bg-canvas/50">
                                            <td class="px-4 py-3">
                                                <p class="font-semibold">#{{ str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) }}</p>
                                                <p class="text-xs text-muted">{{ $pesanan->menu->nama_menu }} ×{{ $pesanan->jumlah }}</p>
                                            </td>
                                            <td class="px-4 py-3">
                                                <p class="font-medium">{{ $pesanan->user->username }}</p>
                                                <p class="text-xs text-muted">{{ $pesanan->user->kelas ?: '—' }}</p>
                                            </td>
                                            <td class="px-4 py-3"><x-status :status="$pesanan->status" /></td>
                                            <td class="px-4 py-3"><x-status :status="$pesanan->status_bayar" /></td>
                                            <td class="px-4 py-3 text-right font-semibold">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </section>

            {{-- ====================================================== RINGKASAN --}}
            <aside class="space-y-4">
                <div class="card p-5">
                    <h2 class="text-sm font-bold uppercase tracking-wide text-muted">Ringkasan</h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-muted">Total akun siswa</dt>
                            <dd class="font-bold">{{ $totalSiswa }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-muted">Menu tersedia</dt>
                            <dd class="font-bold">{{ $menuTersedia }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-muted">Pesanan aktif</dt>
                            <dd class="font-bold">{{ $pesananAktif }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3 border-t border-line pt-3">
                            <dt class="text-muted">Pendapatan hari ini</dt>
                            <dd class="font-bold text-ok">Rp{{ number_format($pendapatanHariIni, 0, ',', '.') }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="card p-5">
                    <h2 class="text-sm font-bold uppercase tracking-wide text-muted">Alur status</h2>
                    <ol class="mt-4 space-y-2.5 text-sm">
                        @foreach(\App\Models\Pesanan::STATUS as $i => $status)
                            <li class="flex items-center gap-3">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brand/10 text-[11px] font-bold text-brand">{{ $i + 1 }}</span>
                                <span class="font-medium">{{ $status }}</span>
                            </li>
                        @endforeach
                    </ol>
                    <p class="mt-4 text-xs leading-relaxed text-muted">
                        Admin memperbarui status dari halaman Pesanan. Kasir memvalidasi pembayaran jadi Lunas.
                    </p>
                </div>
            </aside>
        </div>
    </div>
@endsection
