@extends('layouts.app')
@section('title', $workshop->title)

@section('content')
{{-- Latar Belakang Foto + Overlay Gelap --}}
<div class="fixed inset-0 z-0 pointer-events-none">
    <img src="{{ asset('images/bogor.jpg') }}"
         alt="Latar Belakang Detail Workshop"
         class="w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-slate-950/75 backdrop-blur-[2px]"></div>
</div>

{{-- Konten Utama --}}
<div class="relative z-10">
    <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-sm text-slate-300 hover:text-tix transition">
        ← Semua workshop
    </a>

    <x-workshop-cover :workshop="$workshop" class="h-64 md:h-80 rounded-3xl mt-4 border border-white/10 shadow-2xl overflow-hidden">
        <div class="absolute inset-x-0 bottom-0 p-6 md:p-10 bg-linear-to-t from-black/80 via-black/40 to-transparent text-white">
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-block bg-tix text-xs font-bold px-3 py-1 rounded-full">{{ $workshop->starts_at->translatedFormat('d F Y') }}</span>
                <x-kategori-badge :workshop="$workshop" :cover="true" />
            </div>
            <h1 class="text-2xl md:text-4xl font-extrabold mt-3 leading-tight max-w-3xl drop-shadow-sm">{{ $workshop->title }}</h1>
            <p class="text-white/85 mt-1 text-sm">oleh {{ $workshop->speaker }}</p>
        </div>
    </x-workshop-cover>

    <div class="grid lg:grid-cols-3 gap-8 mt-8">
        <div class="lg:col-span-2 bg-slate-900/70 backdrop-blur-md border border-white/10 rounded-2xl p-8 shadow-xl">
            <h2 class="text-lg font-extrabold text-white">Tentang workshop</h2>
            <p class="mt-3 leading-relaxed text-slate-300/90 whitespace-pre-line">{{ $workshop->description }}</p>
        </div>

        <aside class="bg-slate-900/70 backdrop-blur-md border border-white/10 rounded-2xl p-6 h-fit space-y-4 lg:sticky lg:top-24 shadow-xl">
            <div>
                <p class="text-xs uppercase tracking-wider font-bold text-slate-400">Jadwal</p>
                <p class="font-semibold text-slate-100 mt-0.5">{{ $workshop->starts_at->translatedFormat('l, d F Y') }}</p>
                <p class="text-sm text-slate-300">Pukul {{ $workshop->starts_at->format('H:i') }} WIB</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wider font-bold text-slate-400">Lokasi</p>
                <p class="font-semibold text-slate-100 mt-0.5">{{ $workshop->location }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wider font-bold text-slate-400">Kuota</p>
                <p class="font-semibold text-slate-100 mt-0.5">{{ $sisa }} dari {{ $workshop->quota }} kursi tersisa</p>
            </div>
            <div class="pt-4 border-t border-white/10">
                <p class="text-2xl font-extrabold text-white">{{ $workshop->rupiah() }}</p>
            </div>

            @guest
                <a href="{{ route('login') }}"
                   class="block text-center bg-slate-800/90 hover:bg-slate-700 text-white border border-white/15 font-semibold rounded-xl py-3 transition">
                    Masuk untuk mendaftar
                </a>
            @else
                @if (auth()->user()->isPanitia())
                    <p class="text-sm text-slate-300 bg-slate-950/60 border border-white/10 rounded-xl p-3">
                        Kamu masuk sebagai panitia, jadi tidak bisa mendaftar.
                    </p>
                @elseif ($milikku && $milikku->status !== 'failed')
                    <a href="{{ route('registrations.show', $milikku) }}"
                       class="block text-center bg-sky-600 hover:bg-sky-500 text-white font-semibold rounded-xl py-3 shadow-lg shadow-sky-600/20 transition">
                        Lihat pendaftaranku
                    </a>
                @elseif ($sisa < 1)
                    <button disabled class="w-full bg-slate-800/60 text-slate-400 border border-white/10 font-semibold rounded-xl py-3 cursor-not-allowed">
                        Kuota penuh
                    </button>
                @else
                    <form method="POST" action="{{ route('registrations.store', $workshop) }}">
                        @csrf
                        <button class="w-full bg-tix hover:bg-orange-600 text-white font-semibold rounded-xl py-3 shadow-lg shadow-tix/20 transition">
                            Daftar sekarang
                        </button>
                    </form>
                @endif
            @endguest
        </aside>
    </div>
</div>
@endsection