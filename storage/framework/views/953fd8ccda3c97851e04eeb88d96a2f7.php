<?php $__env->startSection('title', 'Kelola Menu'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mx-auto max-w-6xl px-4 pt-8 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-muted">Admin sekolah</p>
                <h1 class="mt-1 text-3xl font-bold tracking-tight">Kelola menu</h1>
                <p class="mt-1.5 text-sm text-muted">Tambah, ubah harga, atau tandai menu yang sedang habis.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-ghost text-sm">Dashboard</a>
                <button type="button" class="btn btn-primary text-sm" data-menu-add>
                    + Tambah menu
                </button>
            </div>
        </div>

        
        <form method="GET" action="<?php echo e(route('admin.menu.index')); ?>" class="mt-6 flex max-w-md gap-2">
            <label class="sr-only" for="cari">Cari menu</label>
            <input class="input" type="search" id="cari" name="cari" value="<?php echo e($cari); ?>"
                   placeholder="Cari nama atau kategori…">
            <button type="submit" class="btn btn-ghost shrink-0">Cari</button>
            <?php if($cari): ?>
                <a href="<?php echo e(route('admin.menu.index')); ?>" class="btn btn-ghost shrink-0">Reset</a>
            <?php endif; ?>
        </form>

        
        <div class="card mt-5 overflow-hidden">
            <?php if($menus->isEmpty()): ?>
                <div class="p-10 text-center">
                    <p class="font-semibold">Tidak ada menu yang cocok.</p>
                    <p class="mt-1 text-sm text-muted">Coba kata kunci lain atau tambahkan menu baru.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px] text-sm">
                        <thead class="border-b border-line bg-canvas/60 text-left text-xs uppercase tracking-wide text-muted">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Menu</th>
                                <th class="px-4 py-3 font-semibold">Kategori</th>
                                <th class="px-4 py-3 font-semibold">Harga</th>
                                <th class="px-4 py-3 font-semibold">Status</th>
                                <th class="px-4 py-3 text-right font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="transition-colors hover:bg-canvas/50">
                                    <td class="px-4 py-3">
                                        <p class="font-semibold"><?php echo e($menu->nama_menu); ?></p>
                                        <p class="max-w-[320px] truncate text-xs text-muted"><?php echo e($menu->deskripsi ?: '—'); ?></p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full bg-canvas px-2.5 py-1 text-[11px] font-semibold text-muted"><?php echo e($menu->kategori); ?></span>
                                    </td>
                                    <td class="px-4 py-3 font-semibold">Rp<?php echo e(number_format($menu->harga, 0, ',', '.')); ?></td>
                                    <td class="px-4 py-3">
                                        <?php if($menu->tersedia): ?>
                                            <span class="badge border-ok/25 bg-ok/10 text-ok" data-status="Tersedia">Tersedia</span>
                                        <?php else: ?>
                                            <span class="badge border-bad/25 bg-bad/10 text-bad" data-status="Habis">Habis</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap items-center justify-end gap-1.5">
                                            <button type="button"
                                                    class="btn btn-ghost px-2.5 py-1.5 text-xs"
                                                    data-menu-edit
                                                    data-id="<?php echo e($menu->id); ?>"
                                                    data-nama="<?php echo e($menu->nama_menu); ?>"
                                                    data-harga="<?php echo e($menu->harga); ?>"
                                                    data-kategori="<?php echo e($menu->kategori); ?>"
                                                    data-deskripsi="<?php echo e($menu->deskripsi); ?>"
                                                    data-tersedia="<?php echo e($menu->tersedia ? '1' : '0'); ?>">
                                                Edit
                                            </button>

                                            <form method="POST" action="<?php echo e(route('admin.menu.toggle', $menu)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="btn btn-ghost px-2.5 py-1.5 text-xs">
                                                    <?php echo e($menu->tersedia ? 'Tandai habis' : 'Tandai tersedia'); ?>

                                                </button>
                                            </form>

                                            <form method="POST" action="<?php echo e(route('admin.menu.destroy', $menu)); ?>"
                                                  data-confirm="Hapus menu &quot;<?php echo e($menu->nama_menu); ?>&quot;? Tindakan ini tidak bisa dibatalkan.">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger px-2.5 py-1.5 text-xs">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <?php if($menus->hasPages()): ?>
                    <div class="border-t border-line px-4 py-3">
                        <?php echo e($menus->links()); ?>

                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <p class="mt-3 text-xs text-muted">
            Menu yang sudah punya riwayat pesanan tidak bisa dihapus — tandai saja sebagai habis.
        </p>
    </div>

    
    <dialog id="dialog-menu" class="w-[min(94vw,34rem)] rounded-2xl border border-line bg-surface p-0 shadow-2xl">
        <form method="POST" action="<?php echo e(route('admin.menu.store')); ?>" id="form-menu" class="p-6">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" value="POST" data-menu-method>

            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold tracking-tight" data-menu-title>Tambah menu</h2>
                    <p class="mt-1 text-sm text-muted">Menu langsung tampil di halaman siswa.</p>
                </div>
                <button type="button" class="btn btn-ghost px-2.5 py-1.5 text-sm" data-menu-close aria-label="Tutup">×</button>
            </div>

            <div class="mt-5 space-y-4">
                <div>
                    <label class="label" for="nama_menu">Nama menu</label>
                    <input class="input" type="text" id="nama_menu" name="nama_menu"
                           value="<?php echo e(old('nama_menu')); ?>" placeholder="contoh: Nasi Goreng Spesial" required maxlength="100">
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label" for="harga">Harga (Rp)</label>
                        <input class="input" type="number" id="harga" name="harga"
                               value="<?php echo e(old('harga')); ?>" placeholder="15000" min="500" max="1000000" required>
                    </div>
                    <div>
                        <label class="label" for="kategori">Kategori</label>
                        <select class="input" id="kategori" name="kategori" required>
                            <?php $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kategori); ?>" <?php if(old('kategori') === $kategori): echo 'selected'; endif; ?>><?php echo e($kategori); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="label" for="deskripsi">Deskripsi <span class="font-normal text-muted">(opsional)</span></label>
                    <textarea class="input" id="deskripsi" name="deskripsi" rows="2"
                              placeholder="Isi singkat menu…" maxlength="255"><?php echo e(old('deskripsi')); ?></textarea>
                </div>

                <label class="flex cursor-pointer items-center gap-2.5 text-sm">
                    <input type="hidden" name="tersedia" value="0">
                    <input type="checkbox" name="tersedia" value="1" class="h-4 w-4 rounded border-line text-brand focus:ring-brand"
                           data-menu-tersedia <?php if(old('tersedia', true)): echo 'checked'; endif; ?>>
                    Menu tersedia (bisa dipesan siswa)
                </label>
            </div>

            <div class="mt-6 flex items-center justify-end gap-2">
                <button type="button" class="btn btn-ghost" data-menu-close>Batal</button>
                <button type="submit" class="btn btn-primary" data-menu-submit>Simpan menu</button>
            </div>
        </form>
    </dialog>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        const dialog = document.getElementById('dialog-menu');
        const form = document.getElementById('form-menu');
        const method = form.querySelector('[data-menu-method]');
        const title = form.querySelector('[data-menu-title]');
        const submit = form.querySelector('[data-menu-submit]');
        const tersedia = form.querySelector('[data-menu-tersedia]');

        const buka = (mode, data = null) => {
            if (mode === 'edit' && data) {
                form.action = <?php echo json_encode(url('/admin/menu'), 15, 512) ?> + '/' + data.id;
                method.value = 'PATCH';
                title.textContent = 'Edit menu';
                submit.textContent = 'Simpan perubahan';

                form.nama_menu.value = data.nama;
                form.harga.value = data.harga;
                form.kategori.value = data.kategori;
                form.deskripsi.value = data.deskripsi || '';
                tersedia.checked = data.tersedia === '1';
            } else {
                form.action = <?php echo json_encode(route('admin.menu.store'), 15, 512) ?>;
                method.value = 'POST';
                title.textContent = 'Tambah menu';
                submit.textContent = 'Simpan menu';
                form.reset();
                tersedia.checked = true;
            }

            dialog.showModal();
            form.nama_menu.focus();
        };

        document.querySelector('[data-menu-add]')?.addEventListener('click', () => buka('tambah'));

        document.querySelectorAll('[data-menu-edit]').forEach((tombol) => {
            tombol.addEventListener('click', () => buka('edit', tombol.dataset));
        });

        document.querySelectorAll('[data-menu-close]').forEach((tombol) => {
            tombol.addEventListener('click', () => dialog.close());
        });

        // Klik di luar panel menutup dialog.
        dialog.addEventListener('click', (e) => {
            if (e.target === dialog) dialog.close();
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /mnt/c/Sekolah/XI-GANJIL/Coding/PW2/BIKantin/resources/views/admin/menu.blade.php ENDPATH**/ ?>