@extends('layouts.app')
@section('title', 'Tiket Saya')

@section('content')
{{-- Latar Belakang Foto + Overlay Gelap --}}
<div class="fixed inset-0 z-0 pointer-events-none">
    <img src="{{ asset('images/bogor.jpg') }}"
         alt="Latar Belakang Tiket Saya"
         class="w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-slate-950/75 backdrop-blur-[2px]"></div>
</div>

{{-- Konten Utama --}}
<div class="relative z-10">
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-white drop-shadow-sm">Pendaftaran & Tiket Saya</h1>
        <p class="text-xs text-slate-300 mt-0.5">Riwayat pendaftaran dan akses cepat ke tiket digitalmu</p>
    </div>

    <div class="space-y-4">
        @forelse ($items as $r)
            <a href="{{ route('registrations.show', $r) }}"
               class="group flex items-center gap-4 bg-slate-900/70 backdrop-blur-md border border-white/10 rounded-2xl p-4 shadow-lg hover:bg-slate-900/85 hover:border-white/20 transition">
                <x-workshop-cover :workshop="$r->workshop" class="w-28 h-20 rounded-xl shrink-0 border border-white/10" />
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-slate-100 group-hover:text-white truncate transition">{{ $r->workshop->title }}</p>
                    <p class="text-sm text-slate-300 mt-0.5">{{ $r->workshop->starts_at->translatedFormat('d F Y, H:i') }} · {{ $r->workshop->location }}</p>
                </div>
                <x-status-badge :status="$r->status" />
            </a>
        @empty
            <div class="text-center bg-slate-900/70 backdrop-blur-md border border-dashed border-white/15 rounded-2xl py-16 shadow-xl">
                <p class="text-slate-300">Kamu belum mendaftar workshop apa pun.</p>
                <a href="{{ route('home') }}" class="inline-block mt-4 text-tix font-semibold hover:opacity-80 transition">Cari workshop →</a>
            </div>
        @endforelse
    </div>
</div>
@endsection