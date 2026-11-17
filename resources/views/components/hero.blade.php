@props(['judul', 'sub' => null])

@php $foto = file_exists(public_path('images/hero.jpg')) ? asset('images/hero.jpg') : null; @endphp

<section {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-3xl text-white bg-navy-dark']) }}>
    @if ($foto)
        <img src="{{ $foto }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-40">
    @endif
    <div class="absolute inset-0 bg-linear-to-br from-navy/90 via-navy-dark/80 to-black/70"></div>
    <div class="absolute -right-16 -top-16 w-72 h-72 rounded-full bg-tix/30 blur-3xl"></div>

    <div class="relative px-8 py-12 md:px-14 md:py-16">
        <p class="text-tix font-bold text-xs uppercase tracking-widest">Workshop IT Sekolah</p>
        <h1 class="text-3xl md:text-4xl font-extrabold mt-3 max-w-2xl leading-tight">{{ $judul }}</h1>
        @if ($sub)
            <p class="mt-4 text-white/70 max-w-xl text-sm md:text-base">{{ $sub }}</p>
        @endif
        {{ $slot }}
    </div>
</section>