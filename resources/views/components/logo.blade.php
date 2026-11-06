@props([
    'label' => 'Logo SMK Bina Informatika',
    'teks' => 'BI',
])

@php
    // Cukup taruh file di public/images/logo-bi.(png|jpg|jpeg|webp|svg).
    // Komponen ini yang mengatur ukuran & posisi, jadi gambar tidak akan
    // meluber atau tampil raksasa seperti kalau <img> ditaruh mentah.
    $kandidat = glob(public_path('images/logo-bi.*')) ?: [];
    sort($kandidat);
    $berkas = $kandidat[0] ?? null;
@endphp

<span {{ $attributes->merge(['class' => 'logo-slot']) }}>
    @if ($berkas)
        <img src="{{ asset('images/'.basename($berkas)) }}?v={{ filemtime($berkas) }}"
             alt="{{ $label }}" loading="eager" decoding="async">
    @else
        {{ $teks }}
    @endif
</span>
