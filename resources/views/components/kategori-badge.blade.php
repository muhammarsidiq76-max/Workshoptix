@props(['workshop', 'cover' => false])

@php $info = $workshop->kategori(); @endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full ' . ($cover ? 'bg-black/45 backdrop-blur text-white' : 'border border-slate-200 text-slate-600')]) }}>
    <span class="w-2 h-2 rounded-full {{ $info['dot'] }}"></span>{{ $info['label'] }}
</span>