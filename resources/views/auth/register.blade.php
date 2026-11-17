@extends('layouts.app')
@section('title', 'Buat Akun')

@section('content')
{{-- Latar Belakang Foto Penuh + Overlay Gelap --}}
<div class="fixed inset-0 z-0 pointer-events-none">
    <img src="{{ asset('images/bogor.jpg') }}"
         alt="Latar Belakang Buat Akun"
         class="w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-slate-950/75 backdrop-blur-[2px]"></div>
</div>

{{-- Konten Utama --}}
<div class="relative z-10">
    @php
        $input = 'mt-1.5 w-full rounded-xl border border-white/15 bg-slate-950/60 px-4 py-2.5 text-slate-100 placeholder-slate-400 focus:outline-none focus:border-tix focus:ring-2 focus:ring-tix/40 transition';
    @endphp

    <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto items-stretch">
        {{-- Panel Foto di Sebelah Form --}}
        <div class="hidden md:flex relative overflow-hidden rounded-3xl border border-white/15 shadow-2xl flex-col justify-end p-8">
            <img src="{{ asset('images/bogor.jpg') }}"
                 alt="Workshop IT"
                 class="absolute inset-0 w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/45 to-slate-950/20"></div>

            <div class="relative z-10 text-white">
                <span class="inline-block bg-tix/20 border border-tix/30 text-tix text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full mb-3">
                    WorkshopTIX
                </span>
                <h2 class="text-2xl font-extrabold leading-snug drop-shadow-sm">
                    Satu akun, semua workshop.
                </h2>
                <p class="text-sm text-slate-300 mt-2 leading-relaxed">
                    Daftar gratis, pilih workshop favoritmu, dan dapatkan tiket digital setelah pembayaran lunas.
                </p>
            </div>
        </div>

        {{-- Card Form Register (Dark Glassmorphism) --}}
        <div class="bg-slate-900/70 backdrop-blur-md border border-white/10 rounded-3xl p-8 shadow-2xl">
            <h1 class="text-2xl font-extrabold text-white">Buat akun peserta</h1>
            <p class="text-sm text-slate-300 mt-1 mb-6">Gratis, cukup beberapa detik.</p>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-semibold text-slate-200">Nama lengkap</label>
                    <input name="name" value="{{ old('name') }}" required autofocus class="{{ $input }}">
                    @error('name') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-200">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="{{ $input }}">
                    @error('email') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-200">Password</label>
                    <input type="password" name="password" required class="{{ $input }}">
                    @error('password') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-200">Ulangi password</label>
                    <input type="password" name="password_confirmation" required class="{{ $input }}">
                </div>
                <button class="w-full bg-tix hover:bg-orange-600 text-white font-semibold rounded-xl py-3 shadow-lg shadow-tix/20 transition">
                    Buat akun
                </button>
            </form>

            <p class="text-sm text-center text-slate-300 mt-6">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-tix hover:underline font-semibold">Masuk</a>
            </p>
        </div>
    </div>
</div>
@endsection