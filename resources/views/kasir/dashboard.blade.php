@extends('layouts.app')

@section('title', 'Kasir & Pembayaran')

@section('content')
    <div class="mx-auto max-w-6xl px-4 pt-8 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-muted">Kasir kantin</p>
                <h1 class="mt-1 text-3xl font-bold tracking-tight">Validasi pembayaran</h1>
                <p class="mt-1.5 text-sm text-muted">Cek pesanan siswa, terima uang, lalu tandai Lunas.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost text-sm">Dashboard admin</a>
                @endif
                <a href="{{ route('kasir.dashboard', ['filter' => 'semua']) }}" class="btn btn-ghost text-sm">Semua transaksi</a>
            </div>
        </div>

        {{-- ============================================================ STATS --}}
        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <div class="card p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-muted">Belum lunas</p>
                <p class="mt-2 text-3xl font-bold tracking-tight text-warn">{{ $jumlahBelumLunas }}</p>
                <p class="mt-1 text-xs text-muted">Pesanan menunggu pembayaran</p>
            </div>
            <div class="card p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-muted">Pendapatan hari ini</p>
                <p class="mt-2 text-3xl font-bold tracking-tight text-ok">Rp{{ number_format($pendapatanHariIni, 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-muted">Dari pesanan berstatus Lunas</p>
            </div>
            <div class="card p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-muted">Transaksi hari ini</p>
                <p class="mt-2 text-3xl font-bold tracking-tight">{{ $transaksiHariIni }}</p>
                <p class="mt-1 text-xs text-muted">Pembayaran yang sudah divalidasi</p>
            </div>
        </div>

        {{-- ============================================================ FILTER --}}
        <div class="mt-8 flex flex-wrap items-center gap-2">
            @foreach([
                'belum' => 'Belum lunas',
                'semua' => 'Semua',
                'lunas' => 'Lunas',
            ] as $nilai => $label)
                <a href="{{ route('kasir.dashboard', array_filter(['filter' => $nilai, 'cari' => $cari])) }}"
                   class="rounded-full border px-3.5 py-1.5 text-sm font-medium transition-colors {{ $filter === $nilai ? 'border-brand bg-brand text-white' : 'border-line bg-surface text-muted hover:bg-canvas' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('kasir.dashboard') }}" class="mt-3 flex max-w-md gap-2">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <label class="sr-only" for="cari">Cari pesanan</label>
            <input class="input" type="search" id="cari" name="cari" value="{{ $cari }}"
                   placeholder="Cari nomor pesanan atau username…">
            <button type="submit" class="btn btn-ghost shrink-0">Cari</button>
            @if($cari)
                <a href="{{ route('kasir.dashboard', ['filter' => $filter]) }}" class="btn btn-ghost shrink-0">Reset</a>
            @endif
        </form>

        {{-- ============================================================= TABEL --}}
        <div class="card mt-5 overflow-hidden">
            @if($pesanans->isEmpty())
                <div class="p-10 text-center">
                    <p class="font-semibold">Tidak ada pesanan di filter ini.</p>
                    <p class="mt-1 text-sm text-muted">Kalau siswa baru memesan, halaman ini akan menampilkannya.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[820px] text-sm">
                        <thead class="border-b border-line bg-canvas/60 text-left text-xs uppercase tracking-wide text-muted">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Pesanan</th>
                                <th class="px-4 py-3 font-semibold">Siswa</th>
                                <th class="px-4 py-3 font-semibold">Menu</th>
                                <th class="px-4 py-3 text-right font-semibold">Total</th>
                                <th class="px-4 py-3 font-semibold">Status</th>
                                <th class="px-4 py-3 text-right font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach($pesanans as $pesanan)
                                <tr class="transition-colors hover:bg-canvas/50">
                                    <td class="px-4 py-3">
                                        <p class="font-semibold">#{{ str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) }}</p>
                                        <p class="text-xs text-muted">{{ $pesanan->created_at->format('d/m H:i') }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-medium">{{ $pesanan->user->username }}</p>
                                        <p class="text-xs text-muted">{{ $pesanan->user->kelas ?: '—' }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-medium">{{ $pesanan->menu->nama_menu }}</p>
                                        <p class="text-xs text-muted">{{ $pesanan->jumlah }} × Rp{{ number_format($pesanan->menu->harga, 0, ',', '.') }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-col items-start gap-1.5">
                                            <x-status :status="$pesanan->status_bayar" />
                                            <span class="text-[11px] text-muted">{{ $pesanan->status }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap items-center justify-end gap-1.5">
                                            @if($pesanan->sudahLunas())
                                                <span class="badge border-ok/25 bg-ok/10 text-ok">Sudah lunas</span>
                                            @else
                                                <form method="POST" action="{{ route('kasir.pesanan.lunas', $pesanan) }}"
                                                      data-confirm="Validasi pembayaran pesanan #{{ $pesanan->id }} sebesar Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }} sebagai LUNAS?">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-primary px-3 py-1.5 text-xs">Validasi lunas</button>
                                                </form>
                                            @endif

                                            <a href="{{ route('kasir.pesanan.struk', $pesanan) }}" class="btn btn-ghost px-2.5 py-1.5 text-xs">
                                                Struk
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($pesanans->hasPages())
                    <div class="border-t border-line px-4 py-3">
                        {{ $pesanans->links() }}
                    </div>
                @endif
            @endif
        </div>

        <p class="mt-3 text-xs text-muted">
            Validasi pembayaran hanya mengubah status bayar. Status masak tetap diatur admin sekolah.
        </p>
    </div>
@endsection
