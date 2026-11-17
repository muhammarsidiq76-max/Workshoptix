@extends('layouts.app')
@section('title', 'Daftar Acara')

@section('content')
{{-- Latar Belakang Foto + Overlay Gelap --}}
<div class="fixed inset-0 z-0 pointer-events-none">
    <img src="{{ asset('images/bogor.jpg') }}"
         alt="Latar Belakang Daftar Acara"
         class="w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-slate-950/75 backdrop-blur-[2px]"></div>
</div>

{{-- Konten Utama --}}
<div class="relative z-10">
    <x-hero judul="Pilih acara, bayar, tiket langsung terbit."
            sub="Pembayaran diverifikasi otomatis lewat Midtrans. Tiket digital dengan kode QR muncul begitu pembayaranmu lunas."
            class="mb-8">
        <a href="#daftar" class="inline-block mt-7 bg-tix hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg shadow-tix/20 transition">
            Lihat acara
        </a>
    </x-hero>

    @php
        $langkah = [
            ['1', 'Pilih acara', 'Lihat jadwal, pemateri, dan kuota yang tersisa.'],
            ['2', 'Bayar lewat Midtrans', 'Kartu, virtual account, atau QRIS.'],
            ['3', 'Dapatkan tiket', 'Tiket dengan kode QR terbit setelah lunas.'],
        ];
        $ada = $q !== '' || $kategori;
    @endphp

    <div class="grid sm:grid-cols-3 gap-4 mb-12">
        @foreach ($langkah as [$no, $judul, $ket])
            <div class="flex gap-4 bg-slate-900/70 backdrop-blur-md border border-white/10 rounded-2xl p-5 shadow-lg">
                <span class="shrink-0 w-10 h-10 rounded-xl bg-tix/20 border border-tix/30 text-tix font-extrabold grid place-items-center">{{ $no }}</span>
                <div>
                    <p class="font-bold text-white">{{ $judul }}</p>
                    <p class="text-sm text-slate-300 mt-0.5">{{ $ket }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <h2 id="daftar" class="text-xl font-extrabold text-white mb-4 scroll-mt-24 drop-shadow-sm">Acara tersedia</h2>

    {{-- Form Pencarian & Filter Jenis Acara --}}
    <form method="GET" action="{{ route('home') }}#daftar"
          class="bg-slate-900/70 backdrop-blur-md border border-white/10 rounded-2xl p-4 mb-4 grid gap-3 md:grid-cols-[1fr_210px_auto] shadow-xl">
        <input type="search" name="q" value="{{ $q }}" placeholder="Cari judul, pemateri, atau lokasi..."
               class="rounded-xl border border-white/15 bg-slate-950/60 px-4 py-2.5 text-sm text-slate-100 placeholder-slate-400 focus:outline-none focus:border-tix focus:ring-2 focus:ring-tix/40 transition">

        <select name="kategori" onchange="this.form.submit()"
                class="rounded-xl border border-white/15 bg-slate-950/60 px-4 py-2.5 text-sm text-slate-100 focus:outline-none focus:border-tix focus:ring-2 focus:ring-tix/40 transition">
            <option value="" class="bg-slate-900 text-slate-100">Semua jenis</option>
            @foreach ($daftarKategori as $k)
                <option value="{{ $k->id }}" @selected((string) $kategori === (string) $k->id)>{{ $k->name }}</option>
            @endforeach
        </select>

        <div class="flex gap-2">
            <button class="px-5 py-2.5 rounded-xl bg-tix hover:bg-orange-600 text-white text-sm font-semibold shadow-lg shadow-tix/20 transition">
                Cari
            </button>
            @if ($ada)
                <a href="{{ route('home') }}#daftar"
                   class="px-4 py-2.5 rounded-xl border border-white/15 bg-slate-800/70 hover:bg-slate-800 text-slate-200 text-sm font-semibold transition">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <p class="text-sm text-slate-300 mb-4">
        @if ($ada)
            Ditemukan <span class="font-bold text-white">{{ $workshops->count() }}</span> dari {{ $total }} acara.
        @else
            Total <span class="font-bold text-white">{{ $total }}</span> acara.
        @endif
    </p>

    {{-- Grid Daftar Acara --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($workshops as $w)
            @php $sisa = max($w->quota - $w->terisi, 0); @endphp
            <a href="{{ route('workshops.show', $w) }}"
               class="group bg-slate-900/70 backdrop-blur-md border border-white/10 rounded-2xl overflow-hidden flex flex-col shadow-xl transition duration-200 hover:bg-slate-900/85 hover:border-white/20 hover:shadow-black/40 hover:-translate-y-1">
                <x-workshop-cover :workshop="$w" class="h-44">
                    <div class="absolute top-3 left-3 right-3 flex items-center justify-between text-xs font-bold text-white">
                        <span class="bg-black/45 backdrop-blur px-3 py-1 rounded-full">{{ $w->starts_at->translatedFormat('d M Y') }}</span>
                        <span class="px-3 py-1 rounded-full {{ $sisa ? 'bg-black/45 backdrop-blur' : 'bg-rose-600' }}">
                            {{ $sisa ? 'Sisa ' . $sisa . ' kursi' : 'Penuh' }}
                        </span>
                    </div>
                    <div class="absolute bottom-3 left-3">
                        <x-kategori-badge :workshop="$w" :cover="true" />
                    </div>
                </x-workshop-cover>

                <div class="p-5 flex flex-col flex-1">
                    <h3 class="font-bold text-slate-100 leading-snug group-hover:text-tix transition">{{ $w->title }}</h3>
                    <p class="text-sm text-slate-400 mt-1">{{ $w->speaker }} · {{ $w->location }}</p>
                    <p class="text-sm text-slate-300/85 mt-3 line-clamp-2 flex-1">{{ $w->description }}</p>
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-white/10">
                        <span class="font-extrabold text-white">{{ $w->rupiah() }}</span>
                        <span class="text-sm font-semibold text-tix">Lihat detail →</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center bg-slate-900/70 backdrop-blur-md border border-dashed border-white/15 rounded-2xl py-16 shadow-xl">
                <p class="text-slate-300">
                    {{ $ada ? 'Tidak ada acara yang cocok dengan pencarianmu.' : 'Belum ada acara yang dibuka.' }}
                </p>
                @if ($ada)
                    <a href="{{ route('home') }}#daftar" class="inline-block mt-3 text-tix font-semibold hover:opacity-80 transition">Reset pencarian →</a>
                @endif
            </div>
        @endforelse
    </div>
</div>
@endsection