@props(['status'])

@php
    $kelas = match ($status) {
        'Menunggu Konfirmasi' => 'border-warn/25 bg-warn/10 text-warn',
        'Sedang Dimasak' => 'border-brand/25 bg-brand/10 text-brand',
        'Siap Diambil' => 'border-accent/25 bg-accent/10 text-accent',
        'Selesai' => 'border-ok/25 bg-ok/10 text-ok',
        'Lunas' => 'border-ok/25 bg-ok/10 text-ok',
        'Belum Lunas' => 'border-bad/25 bg-bad/10 text-bad',
        default => 'border-line bg-canvas text-muted',
    };
@endphp

<span {{ $attributes->merge(['class' => "badge $kelas"]) }} data-status="{{ $status }}">{{ $status }}</span>
