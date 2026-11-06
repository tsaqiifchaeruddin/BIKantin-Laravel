@extends('layouts.app')

@section('title', 'Menu & Pesanan')

@section('content')
    <div class="mx-auto max-w-6xl px-4 pt-8 sm:px-6">
        {{-- ============================================================ HEADER --}}
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-muted">Halo, {{ auth()->user()->username }}</p>
                <h1 class="mt-1 text-3xl font-bold tracking-tight">Mau makan apa hari ini?</h1>
                <p class="mt-1.5 text-sm text-muted">
                    {{ auth()->user()->kelas ? 'Kelas '.auth()->user()->kelas.' · ' : '' }}
                    Pesanan aktif: <strong class="font-semibold text-ink">{{ $pesananAktif }}</strong>
                </p>
            </div>

            <a href="#pesanan-saya" class="btn btn-ghost text-sm">
                Pesanan saya
                <span class="rounded-full bg-brand/10 px-2 py-0.5 text-xs font-bold text-brand">{{ $pesanans->count() }}</span>
            </a>
        </div>

        {{-- ============================================================== MENU --}}
        <section class="mt-8">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('siswa.dashboard') }}"
                   class="rounded-full border px-3.5 py-1.5 text-sm font-medium transition-colors {{ $kategoriAktif ? 'border-line bg-surface text-muted hover:bg-canvas' : 'border-brand bg-brand text-white' }}">
                    Semua
                </a>
                @foreach($kategoris as $kategori)
                    <a href="{{ route('siswa.dashboard', ['kategori' => $kategori]) }}#menu"
                       class="rounded-full border px-3.5 py-1.5 text-sm font-medium transition-colors {{ $kategoriAktif === $kategori ? 'border-brand bg-brand text-white' : 'border-line bg-surface text-muted hover:bg-canvas' }}">
                        {{ $kategori }}
                    </a>
                @endforeach
            </div>

            <div id="menu" class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($menus as $menu)
                    <article class="card anim-in flex flex-col p-5" style="--i: {{ $loop->iteration }}">
                        <div class="flex items-start justify-between gap-3">
                            <span class="rounded-full bg-canvas px-2.5 py-1 text-[11px] font-semibold text-muted">{{ $menu->kategori }}</span>
                            <span class="text-xs font-medium text-ok">Tersedia</span>
                        </div>

                        <h2 class="mt-3 text-[15px] font-bold leading-snug">{{ $menu->nama_menu }}</h2>
                        <p class="mt-1 line-clamp-2 text-sm text-muted">{{ $menu->deskripsi ?: 'Menu favorit kantin sekolah.' }}</p>

                        <div class="mt-4 flex items-center justify-between">
                            <p class="text-lg font-bold tracking-tight">Rp{{ number_format($menu->harga, 0, ',', '.') }}</p>
                            <p class="text-xs text-muted">per porsi</p>
                        </div>

                        <form method="POST" action="{{ route('siswa.pesanan.store') }}"
                              class="mt-4 flex items-center gap-2" data-order-form data-harga="{{ $menu->harga }}">
                            @csrf
                            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                            <label class="sr-only" for="jumlah-{{ $menu->id }}">Jumlah porsi {{ $menu->nama_menu }}</label>
                            <input class="input w-16 px-2 py-2 text-center" type="number"
                                   id="jumlah-{{ $menu->id }}" name="jumlah" value="1" min="1" max="20"
                                   inputmode="numeric" required>
                            <button type="submit" class="btn btn-primary flex-1 px-3 py-2 text-sm">
                                Pesan · <span data-total>Rp{{ number_format($menu->harga, 0, ',', '.') }}</span>
                            </button>
                        </form>
                    </article>
                @empty
                    <div class="card col-span-full p-8 text-center">
                        <p class="font-semibold">Belum ada menu di kategori ini.</p>
                        <p class="mt-1 text-sm text-muted">Coba kategori lain, atau cek lagi nanti.</p>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- ====================================================== PESANAN SAYA --}}
        <section id="pesanan-saya" class="mt-12 scroll-mt-24">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Pesanan saya</h2>
                    <p class="mt-1 text-sm text-muted">
                        Status diperbarui otomatis tiap 5 detik.
                        <span class="text-xs text-muted">Terakhir: <span data-live-time>—</span></span>
                    </p>
                </div>
            </div>

            @if($pesanans->isEmpty())
                <div class="card mt-5 p-8 text-center">
                    <p class="text-2xl">🍽️</p>
                    <p class="mt-2 font-semibold">Belum ada pesanan.</p>
                    <p class="mt-1 text-sm text-muted">Pilih menu di atas, pesananmu akan muncul di sini.</p>
                </div>
            @else
                <div class="mt-5 space-y-3" data-live-orders>
                    @foreach($pesanans as $pesanan)
                        <article class="card p-4 sm:p-5" data-order-row="{{ $pesanan->id }}">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="font-bold">#{{ str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) }}</p>
                                        <p class="truncate text-sm font-medium">{{ $pesanan->menu->nama_menu }}</p>
                                        <span class="text-xs text-muted">×{{ $pesanan->jumlah }}</span>
                                    </div>
                                    <p class="mt-1 text-xs text-muted">
                                        {{ $pesanan->created_at->format('d M Y, H:i') }}
                                        @if($pesanan->catatan)
                                            · Catatan: {{ $pesanan->catatan }}
                                        @endif
                                    </p>
                                </div>

                                <div class="text-right">
                                    <p class="font-bold tracking-tight">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                                    <p class="text-[11px] text-muted">{{ $pesanan->jumlah }} × Rp{{ number_format($pesanan->menu->harga, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <div class="mt-3 flex flex-wrap items-center gap-2 border-t border-line pt-3">
                                <span data-order-status><x-status :status="$pesanan->status" /></span>
                                <span data-order-bayar><x-status :status="$pesanan->status_bayar" /></span>

                                <p class="ml-auto text-xs text-muted" data-order-hint>
                                    @switch($pesanan->status)
                                        @case('Menunggu Konfirmasi')
                                            Bayar di kasir untuk memulai masak.
                                            @break
                                        @case('Sedang Dimasak')
                                            Sedang disiapkan, tunggu sebentar.
                                            @break
                                        @case('Siap Diambil')
                                            Sudah siap — ambil di kasir.
                                            @break
                                        @default
                                            Pesanan selesai. Terima kasih!
                                    @endswitch
                                </p>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        // Pratinjau total di kartu menu (harga × jumlah, format rupiah).
        document.querySelectorAll('[data-order-form]').forEach((form) => {
            const harga = Number(form.dataset.harga) || 0;
            const jumlah = form.querySelector('input[name="jumlah"]');
            const total = form.querySelector('[data-total]');
            const rupiah = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0,
            });

            const update = () => {
                const qty = Math.min(20, Math.max(1, Number(jumlah.value) || 1));
                total.textContent = rupiah.format(harga * qty);
            };

            jumlah.addEventListener('input', update);
            jumlah.addEventListener('change', () => {
                jumlah.value = Math.min(20, Math.max(1, Number(jumlah.value) || 1));
                update();
            });

            // Cegah pesanan dobel kalau tombol terklik dua kali.
            form.addEventListener('submit', () => {
                form.querySelector('button[type="submit"]').disabled = true;
            });
        });

        // Polling status pesanan siswa tiap 5 detik.
        const daftarPesanan = document.querySelector('[data-live-orders]');
        const waktu = document.querySelector('[data-live-time]');

        if (daftarPesanan) {
            const statusUrl = @json(route('siswa.pesanan.status'));

            const perbarui = async () => {
                try {
                    const res = await fetch(statusUrl, {
                        headers: { 'Accept': 'application/json' },
                        cache: 'no-store',
                    });
                    if (!res.ok) return;

                    const data = await res.json();
                    if (waktu) waktu.textContent = data.waktu;

                    data.pesanans.forEach((pesanan) => {
                        const row = daftarPesanan.querySelector(`[data-order-row="${pesanan.id}"]`);
                        if (!row) return;

                        const status = row.querySelector('[data-order-status] .badge');
                        const bayar = row.querySelector('[data-order-bayar] .badge');
                        const hint = row.querySelector('[data-order-hint]');

                        if (status && status.dataset.status !== pesanan.status) {
                            status.dataset.status = pesanan.status;
                            status.textContent = pesanan.status;
                            status.className = 'badge ' + kelasStatus(pesanan.status);
                        }

                        if (bayar && bayar.dataset.status !== pesanan.status_bayar) {
                            bayar.dataset.status = pesanan.status_bayar;
                            bayar.textContent = pesanan.status_bayar;
                            bayar.className = 'badge ' + kelasStatus(pesanan.status_bayar);
                        }

                        if (hint) {
                            hint.textContent = (pesanan.status_bayar === 'Lunas' && pesanan.status === 'Menunggu Konfirmasi')
                                ? 'Pembayaran diterima. Menunggu konfirmasi admin.'
                                : (petunjuk[pesanan.status] || '');
                        }
                    });
                } catch (e) {
                    // Offline sebentar? Coba lagi di putaran berikutnya.
                }
            };

            // Kelas warna disamakan dengan komponen status di server.
            const kelasStatus = (status) => ({
                'Menunggu Konfirmasi': 'border-warn/25 bg-warn/10 text-warn',
                'Sedang Dimasak': 'border-brand/25 bg-brand/10 text-brand',
                'Siap Diambil': 'border-accent/25 bg-accent/10 text-accent',
                'Selesai': 'border-ok/25 bg-ok/10 text-ok',
                'Lunas': 'border-ok/25 bg-ok/10 text-ok',
                'Belum Lunas': 'border-bad/25 bg-bad/10 text-bad',
            })[status] || 'border-line bg-canvas text-muted';

            const petunjuk = {
                'Menunggu Konfirmasi': 'Bayar di kasir untuk memulai masak.',
                'Sedang Dimasak': 'Sedang disiapkan, tunggu sebentar.',
                'Siap Diambil': 'Sudah siap — ambil di kasir.',
                'Selesai': 'Pesanan selesai. Terima kasih!',
            };

            perbarui();
            setInterval(perbarui, 5000);
        }
    </script>
@endpush
