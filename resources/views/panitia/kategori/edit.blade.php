@extends('layouts.app')
@section('title', 'Edit Jenis Acara')

@section('content')
{{-- Latar Belakang Foto + Overlay Gelap --}}
<div class="fixed inset-0 z-0 pointer-events-none">
    <img src="{{ asset('images/bogor.jpg') }}"
         alt="Latar Belakang Edit Jenis Acara"
         class="w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-slate-950/75 backdrop-blur-[2px]"></div>
</div>

{{-- Konten Utama --}}
<div class="relative z-10">
    <div class="max-w-lg mx-auto bg-slate-900/70 backdrop-blur-md border border-white/10 rounded-2xl p-8 shadow-xl text-slate-200">
        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-white drop-shadow-sm">Edit jenis acara</h1>
            <p class="text-xs text-slate-300 mt-1">Perbarui nama, warna, atau ikon untuk kategori acara</p>
        </div>

        <form method="POST" action="{{ route('panitia.kategori.update', $kategori) }}" class="space-y-5">
            @csrf @method('PUT')
            @include('panitia.kategori._fields', ['kategori' => $kategori])

            <div class="flex flex-wrap gap-3 pt-4 border-t border-white/10">
                <button class="bg-tix hover:bg-orange-600 text-white font-semibold rounded-xl px-6 py-3 shadow-lg shadow-tix/20 transition">
                    Simpan
                </button>
                <a href="{{ route('panitia.kategori.index') }}"
                   class="px-6 py-3 rounded-xl border border-white/15 bg-slate-800/60 hover:bg-slate-800 text-slate-200 font-semibold transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection