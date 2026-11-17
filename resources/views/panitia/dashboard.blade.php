@extends('layouts.app')
@section('title', 'Dashboard Panitia')

@section('content')
{{-- Latar Belakang Foto + Overlay Gelap --}}
<div class="fixed inset-0 z-0 pointer-events-none">
    <img src="{{ asset('images/bogor.jpg') }}"
         alt="Latar Belakang Dashboard"
         class="w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-slate-950/75 backdrop-blur-[2px]"></div>
</div>

{{-- Konten Utama --}}
<div class="relative z-10">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-white drop-shadow-sm">Dashboard Panitia</h1>
            <p class="text-xs text-slate-300 mt-0.5">Ringkasan aktivitas workshop dan status pendaftaran</p>
        </div>
        <div class="flex flex-wrap gap-2 text-sm font-semibold">
            <a href="{{ route('panitia.workshops.index') }}"
               class="px-4 py-2 rounded-xl bg-slate-900/80 hover:bg-slate-800 text-white border border-white/15 backdrop-blur-md transition">
                Kelola workshop
            </a>
            <a href="{{ route('panitia.kategori.index') }}"
               class="px-4 py-2 rounded-xl bg-slate-900/80 hover:bg-slate-800 text-white border border-white/15 hover:border-tix backdrop-blur-md transition">
                Kelola jenis
            </a>
            <a href="{{ route('panitia.registrations.index') }}"
               class="px-4 py-2 rounded-xl bg-tix hover:bg-orange-600 text-white shadow-lg shadow-tix/20 transition">
                Kelola pendaftar
            </a>
        </div>
    </div>

    @php
        $kartu = [
            ['Workshop', $stat['workshop'], 'border-t-blue-500'],
            ['Pendaftar', $stat['pendaftar'], 'border-t-fuchsia-500'],
            ['Lunas', $stat['lunas'], 'border-t-emerald-500'],
            ['Menunggu', $stat['pending'], 'border-t-amber-500'],
            ['Pemasukan', 'Rp ' . number_format($stat['pemasukan'], 0, ',', '.'), 'border-t-orange-500'],
        ];
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        @foreach ($kartu as [$label, $nilai, $garis])
            <div class="bg-slate-900/70 backdrop-blur-md border border-white/10 border-t-4 {{ $garis }} rounded-2xl p-5 shadow-lg transition hover:bg-slate-900/85">
                <p class="text-xs uppercase tracking-wider font-bold text-slate-300">{{ $label }}</p>
                <p class="text-xl font-extrabold text-white mt-1">{{ $nilai }}</p>
            </div>
        @endforeach
    </div>

    <h2 class="font-bold text-white mt-10 mb-3 drop-shadow-sm">Pendaftaran terbaru</h2>
    <div class="bg-slate-900/70 backdrop-blur-md border border-white/10 rounded-2xl divide-y divide-white/10 shadow-xl overflow-hidden">
        @forelse ($terbaru as $r)
            <div class="flex items-center gap-4 px-5 py-4 text-sm transition hover:bg-white/5">
                <x-workshop-cover :workshop="$r->workshop" class="w-16 h-12 rounded-lg shrink-0 border border-white/10" />
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-slate-100">{{ $r->user->name }}</p>
                    <p class="text-slate-300 text-xs mt-0.5 truncate">{{ $r->workshop->title }}</p>
                </div>
                <x-status-badge :status="$r->status" />
            </div>
        @empty
            <p class="p-6 text-center text-slate-300 text-sm">Belum ada pendaftaran.</p>
        @endforelse
    </div>
</div>
@endsection