@extends('layouts.app')

@section('title', 'Pesanan Masuk')

@section('content')
    <div class="mx-auto max-w-6xl px-4 pt-8 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-muted">Admin sekolah</p>
                <h1 class="mt-1 text-3xl font-bold tracking-tight">Pesanan masuk</h1>
                <p class="mt-1.5 text-sm text-muted">Perbarui status pesanan mengikuti alur kantin.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost text-sm">Dashboard</a>
                <a href="{{ route('admin.menu.index') }}" class="btn btn-ghost text-sm">Kelola menu</a>
            </div>
        </div>

        {{-- ============================================================ FILTER --}}
        <div class="mt-6 flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.pesanan.index', array_filter(['cari' => $cari])) }}"
               class="rounded-full border px-3.5 py-1.5 text-sm font-medium transition-colors {{ $status ? 'border-line bg-surface text-muted hover:bg-canvas' : 'border-brand bg-brand text-white' }}">
                Semua
            </a>
            @foreach($daftarStatus as $s)
                <a href="{{ route('admin.pesanan.index', array_filter(['status' => $s, 'cari' => $cari])) }}"
                   class="rounded-full border px-3.5 py-1.5 text-sm font-medium transition-colors {{ $status === $s ? 'border-brand bg-brand text-white' : 'border-line bg-surface text-muted hover:bg-canvas' }}">
                    {{ $s }}
                </a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('admin.pesanan.index') }}" class="mt-3 flex max-w-md gap-2">
            @if($status)<input type="hidden" name="status" value="{{ $status }}">@endif
            <label class="sr-only" for="cari">Cari pesanan</label>
            <input class="input" type="search" id="cari" name="cari" value="{{ $cari }}"
                   placeholder="Cari username atau nama menu…">
            <button type="submit" class="btn btn-ghost shrink-0">Cari</button>
            @if($cari)
                <a href="{{ route('admin.pesanan.index', array_filter(['status' => $status])) }}" class="btn btn-ghost shrink-0">Reset</a>
            @endif
        </form>

        {{-- ============================================================= TABEL --}}
        <div class="card mt-5 overflow-hidden">
            @if($pesanans->isEmpty())
                <div class="p-10 text-center">
                    <p class="font-semibold">Tidak ada pesanan di filter ini.</p>
                    <p class="mt-1 text-sm text-muted">Coba filter status lain atau hapus kata kunci.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-sm">
                        <thead class="border-b border-line bg-canvas/60 text-left text-xs uppercase tracking-wide text-muted">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Pesanan</th>
                                <th class="px-4 py-3 font-semibold">Siswa</th>
                                <th class="px-4 py-3 font-semibold">Menu</th>
                                <th class="px-4 py-3 text-right font-semibold">Total</th>
                                <th class="px-4 py-3 font-semibold">Bayar</th>
                                <th class="px-4 py-3 font-semibold">Status</th>
                                <th class="px-4 py-3 text-right font-semibold">Ubah status</th>
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
                                        <p class="text-xs text-muted">{{ $pesanan->jumlah }} porsi</p>
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3"><x-status :status="$pesanan->status_bayar" /></td>
                                    <td class="px-4 py-3"><x-status :status="$pesanan->status" /></td>
                                    <td class="px-4 py-3">
                                        <form method="POST" action="{{ route('admin.pesanan.status', $pesanan) }}"
                                              class="flex items-center justify-end gap-1.5">
                                            @csrf
                                            @method('PATCH')
                                            <label class="sr-only" for="status-{{ $pesanan->id }}">Status pesanan #{{ $pesanan->id }}</label>
                                            <select class="input w-auto px-2 py-1.5 text-xs" id="status-{{ $pesanan->id }}" name="status">
                                                @foreach($daftarStatus as $s)
                                                    <option value="{{ $s }}" @selected($pesanan->status === $s)>{{ $s }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-soft px-2.5 py-1.5 text-xs">Simpan</button>
                                        </form>
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
            Alur: Menunggu Konfirmasi → Sedang Dimasak → Siap Diambil → Selesai.
        </p>
    </div>
@endsection
