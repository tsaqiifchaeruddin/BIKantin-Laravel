<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['status']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['status']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $kelas = match ($status) {
        'Menunggu Konfirmasi' => 'border-warn/25 bg-warn/10 text-warn',
        'Sedang Dimasak' => 'border-brand/25 bg-brand/10 text-brand',
        'Siap Diambil' => 'border-accent/25 bg-accent/10 text-accent',
        'Selesai' => 'border-ok/25 bg-ok/10 text-ok',
        'Lunas' => 'border-ok/25 bg-ok/10 text-ok',
        'Belum Lunas' => 'border-bad/25 bg-bad/10 text-bad',
        default => 'border-line bg-canvas text-muted',
    };
?>

<span <?php echo e($attributes->merge(['class' => "badge $kelas"])); ?> data-status="<?php echo e($status); ?>"><?php echo e($status); ?></span>
<?php /**PATH /mnt/c/Sekolah/XI-GANJIL/Coding/PW2/BIKantin/resources/views/components/status.blade.php ENDPATH**/ ?>