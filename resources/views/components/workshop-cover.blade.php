@props(['workshop'])

@php $info = $workshop->kategori(); @endphp

<div {{ $attributes->merge(['class' => 'relative overflow-hidden bg-linear-to-br ' . $info['gradien']]) }}>
    @if ($workshop->image)
        <img src="{{ $workshop->imageUrl() }}" alt="{{ $workshop->title }}"
             class="absolute inset-0 w-full h-full object-cover" loading="lazy">
        <div class="absolute inset-0 bg-linear-to-t from-black/60 via-black/10 to-transparent"></div>
    @else
        <div class="absolute inset-0 opacity-60"
             style="background-image: radial-gradient(rgba(255,255,255,.18) 1px, transparent 1px); background-size: 18px 18px;"></div>
        <svg class="absolute -right-3 -bottom-3 w-28 h-28 text-white/25" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
            {!! $info['svg'] !!}
        </svg>
    @endif

    <div class="relative h-full">{{ $slot }}</div>
</div>