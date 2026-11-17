@props(['status'])

@php
    $peta = [
        'paid' => ['Lunas', 'bg-emerald-100 text-emerald-700'],
        'pending' => ['Menunggu pembayaran', 'bg-amber-100 text-amber-700'],
        'failed' => ['Gagal / kedaluwarsa', 'bg-rose-100 text-rose-700'],
    ];
    [$label, $kelas] = $peta[$status] ?? [$status, 'bg-slate-100 text-slate-600'];
@endphp

<span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $kelas }}">{{ $label }}</span>