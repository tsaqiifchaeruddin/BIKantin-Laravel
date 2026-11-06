<?php $__env->startSection('title', 'Masuk'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-7">
        <h1 class="text-2xl font-bold tracking-tight">Masuk ke BI-KANTIN</h1>
        <p class="mt-1.5 text-sm text-muted">Satu pintu untuk siswa, kasir, dan admin sekolah.</p>
    </div>

    <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-4" novalidate>
        <?php echo csrf_field(); ?>

        <div>
            <label class="label" for="username">Username</label>
            <input class="input" type="text" id="username" name="username"
                   value="<?php echo e(old('username')); ?>" placeholder="contoh: budi_siswa"
                   autocomplete="username" autofocus
                   aria-invalid="<?php echo e($errors->has('username') ? 'true' : 'false'); ?>">
            <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="label" for="password">Password</label>
            <input class="input" type="password" id="password" name="password"
                   placeholder="••••••••" autocomplete="current-password"
                   aria-invalid="<?php echo e($errors->has('password') ? 'true' : 'false'); ?>">
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <label class="flex cursor-pointer items-center gap-2.5 text-sm text-muted">
            <input type="checkbox" name="remember" value="1"
                   class="h-4 w-4 rounded border-line text-brand focus:ring-brand">
            Ingat saya di perangkat ini
        </label>

        <button type="submit" class="btn btn-primary w-full">Masuk</button>
    </form>

    <p class="mt-5 text-center text-sm text-muted">
        Belum punya akun siswa?
        <a href="<?php echo e(route('register')); ?>" class="font-semibold text-brand link-brand">Daftar di sini</a>
    </p>

    <div class="card mt-7 p-4">
        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-muted">Akun demo</p>
        <div class="mt-3 grid gap-2 text-sm sm:grid-cols-3">
            <div class="rounded-lg bg-canvas px-3 py-2">
                <p class="font-semibold">Siswa</p>
                <p class="text-xs text-muted">budi_siswa / 123</p>
            </div>
            <div class="rounded-lg bg-canvas px-3 py-2">
                <p class="font-semibold">Kasir</p>
                <p class="text-xs text-muted">kasir_kantin / kasir123</p>
            </div>
            <div class="rounded-lg bg-canvas px-3 py-2">
                <p class="font-semibold">Admin</p>
                <p class="text-xs text-muted">admin_kantin / admin123</p>
            </div>
        </div>
        <p class="mt-3 text-xs text-muted">Role otomatis menentukan halaman setelah masuk.</p>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /mnt/c/Sekolah/XI-GANJIL/Coding/PW2/BIKantin/resources/views/auth/login.blade.php ENDPATH**/ ?>