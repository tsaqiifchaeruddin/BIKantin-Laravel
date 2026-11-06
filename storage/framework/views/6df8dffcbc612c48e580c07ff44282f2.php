<?php $__env->startSection('title', 'Menu & Pesanan'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mx-auto max-w-6xl px-4 pt-8 sm:px-6">
        
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-muted">Halo, <?php echo e(auth()->user()->username); ?></p>
                <h1 class="mt-1 text-3xl font-bold tracking-tight">Mau makan apa hari ini?</h1>
                <p class="mt-1.5 text-sm text-muted">
                    <?php echo e(auth()->user()->kelas ? 'Kelas '.auth()->user()->kelas.' · ' : ''); ?>

                    Pesanan aktif: <strong class="font-semibold text-ink"><?php echo e($pesananAktif); ?></strong>
                </p>
            </div>

            <a href="#pesanan-saya" class="btn btn-ghost text-sm">
                Pesanan saya
                <span class="rounded-full bg-brand/10 px-2 py-0.5 text-xs font-bold text-brand"><?php echo e($pesanans->count()); ?></span>
            </a>
        </div>

        
        <section class="mt-8">
            <div class="flex flex-wrap items-center gap-2">
                <a href="<?php echo e(route('siswa.dashboard')); ?>"
                   class="rounded-full border px-3.5 py-1.5 text-sm font-medium transition-colors <?php echo e($kategoriAktif ? 'border-line bg-surface text-muted hover:bg-canvas' : 'border-brand bg-brand text-white'); ?>">
                    Semua
                </a>
                <?php $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('siswa.dashboard', ['kategori' => $kategori])); ?>#menu"
                       class="rounded-full border px-3.5 py-1.5 text-sm font-medium transition-colors <?php echo e($kategoriAktif === $kategori ? 'border-brand bg-brand text-white' : 'border-line bg-surface text-muted hover:bg-canvas'); ?>">
                        <?php echo e($kategori); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div id="menu" class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <?php $__empty_1 = true; $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <article class="card anim-in flex flex-col p-5" style="--i: <?php echo e($loop->iteration); ?>">
                        <div class="flex items-start justify-between gap-3">
                            <span class="rounded-full bg-canvas px-2.5 py-1 text-[11px] font-semibold text-muted"><?php echo e($menu->kategori); ?></span>
                            <span class="text-xs font-medium text-ok">Tersedia</span>
                        </div>

                        <h2 class="mt-3 text-[15px] font-bold leading-snug"><?php echo e($menu->nama_menu); ?></h2>
                        <p class="mt-1 line-clamp-2 text-sm text-muted"><?php echo e($menu->deskripsi ?: 'Menu favorit kantin sekolah.'); ?></p>

                        <div class="mt-4 flex items-center justify-between">
                            <p class="text-lg font-bold tracking-tight">Rp<?php echo e(number_format($menu->harga, 0, ',', '.')); ?></p>
                            <p class="text-xs text-muted">per porsi</p>
                        </div>

                        <form method="POST" action="<?php echo e(route('siswa.pesanan.store')); ?>"
                              class="mt-4 flex items-center gap-2" data-order-form data-harga="<?php echo e($menu->harga); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="menu_id" value="<?php echo e($menu->id); ?>">
                            <label class="sr-only" for="jumlah-<?php echo e($menu->id); ?>">Jumlah porsi <?php echo e($menu->nama_menu); ?></label>
                            <input class="input w-16 px-2 py-2 text-center" type="number"
                                   id="jumlah-<?php echo e($menu->id); ?>" name="jumlah" value="1" min="1" max="20"
                                   inputmode="numeric">
                            <button type="submit" class="btn btn-primary flex-1 px-3 py-2 text-sm">
                                Pesan · <span data-total>Rp<?php echo e(number_format($menu->harga, 0, ',', '.')); ?></span>
                            </button>
                        </form>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="card col-span-full p-8 text-center">
                        <p class="font-semibold">Belum ada menu di kategori ini.</p>
                        <p class="mt-1 text-sm text-muted">Coba kategori lain, atau cek lagi nanti.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        
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

            <?php if($pesanans->isEmpty()): ?>
                <div class="card mt-5 p-8 text-center">
                    <p class="text-2xl">🍽️</p>
                    <p class="mt-2 font-semibold">Belum ada pesanan.</p>
                    <p class="mt-1 text-sm text-muted">Pilih menu di atas, pesananmu akan muncul di sini.</p>
                </div>
            <?php else: ?>
                <div class="mt-5 space-y-3" data-live-orders>
                    <?php $__currentLoopData = $pesanans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pesanan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <article class="card p-4 sm:p-5" data-order-row="<?php echo e($pesanan->id); ?>">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="font-bold">#<?php echo e(str_pad($pesanan->id, 3, '0', STR_PAD_LEFT)); ?></p>
                                        <p class="truncate text-sm font-medium"><?php echo e($pesanan->menu->nama_menu); ?></p>
                                        <span class="text-xs text-muted">×<?php echo e($pesanan->jumlah); ?></span>
                                    </div>
                                    <p class="mt-1 text-xs text-muted">
                                        <?php echo e($pesanan->created_at->format('d M Y, H:i')); ?>

                                        <?php if($pesanan->catatan): ?>
                                            · Catatan: <?php echo e($pesanan->catatan); ?>

                                        <?php endif; ?>
                                    </p>
                                </div>

                                <div class="text-right">
                                    <p class="font-bold tracking-tight">Rp<?php echo e(number_format($pesanan->total_harga, 0, ',', '.')); ?></p>
                                    <p class="text-[11px] text-muted"><?php echo e($pesanan->jumlah); ?> × Rp<?php echo e(number_format($pesanan->menu->harga, 0, ',', '.')); ?></p>
                                </div>
                            </div>

                            <div class="mt-3 flex flex-wrap items-center gap-2 border-t border-line pt-3">
                                <span data-order-status><?php if (isset($component)) { $__componentOriginal51ed764111e345fc11534f121cfeb451 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal51ed764111e345fc11534f121cfeb451 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status','data' => ['status' => $pesanan->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pesanan->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal51ed764111e345fc11534f121cfeb451)): ?>
<?php $attributes = $__attributesOriginal51ed764111e345fc11534f121cfeb451; ?>
<?php unset($__attributesOriginal51ed764111e345fc11534f121cfeb451); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal51ed764111e345fc11534f121cfeb451)): ?>
<?php $component = $__componentOriginal51ed764111e345fc11534f121cfeb451; ?>
<?php unset($__componentOriginal51ed764111e345fc11534f121cfeb451); ?>
<?php endif; ?></span>
                                <span data-order-bayar><?php if (isset($component)) { $__componentOriginal51ed764111e345fc11534f121cfeb451 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal51ed764111e345fc11534f121cfeb451 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status','data' => ['status' => $pesanan->status_bayar]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pesanan->status_bayar)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal51ed764111e345fc11534f121cfeb451)): ?>
<?php $attributes = $__attributesOriginal51ed764111e345fc11534f121cfeb451; ?>
<?php unset($__attributesOriginal51ed764111e345fc11534f121cfeb451); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal51ed764111e345fc11534f121cfeb451)): ?>
<?php $component = $__componentOriginal51ed764111e345fc11534f121cfeb451; ?>
<?php unset($__componentOriginal51ed764111e345fc11534f121cfeb451); ?>
<?php endif; ?></span>

                                <?php if($pesanan->status !== 'Selesai'): ?>
                                    <p class="ml-auto text-xs text-muted">
                                        <?php if($pesanan->status === 'Menunggu Konfirmasi'): ?>
                                            Bayar di kasir untuk memulai masak.
                                        <?php elseif($pesanan->status === 'Sedang Dimasak'): ?>
                                            Sedang disiapkan, tunggu sebentar.
                                        <?php else: ?>
                                            Sudah siap — ambil di kasir.
                                        <?php endif; ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
        });

        // Polling status pesanan siswa tiap 5 detik.
        const daftarPesanan = document.querySelector('[data-live-orders]');
        const waktu = document.querySelector('[data-live-time]');

        if (daftarPesanan) {
            const statusUrl = <?php echo json_encode(route('siswa.pesanan.status'), 15, 512) ?>;

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

            perbarui();
            setInterval(perbarui, 5000);
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /mnt/c/Sekolah/XI-GANJIL/Coding/PW2/BIKantin/resources/views/siswa/dashboard.blade.php ENDPATH**/ ?>