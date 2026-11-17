@extends('layouts.app')
@section('title', 'Kelola Workshop')

@section('content')
{{-- Latar Belakang Foto + Overlay Gelap --}}
<div class="fixed inset-0 z-0 pointer-events-none">
    <img src="{{ asset('images/bogor.jpg') }}"
         alt="Latar Belakang Kelola Workshop"
         class="w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-slate-950/75 backdrop-blur-[2px]"></div>
</div>

{{-- Konten Utama --}}
<div class="relative z-10">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-white drop-shadow-sm">Kelola Workshop</h1>
            <p class="text-xs text-slate-300 mt-0.5">Atur jadwal, harga, dan kuota peserta workshop</p>
        </div>

        {{-- Kelompok Tombol Aksi di Sisi Kanan --}}
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('panitia.kategori.index') }}"
               class="px-4 py-2 rounded-xl bg-slate-900/80 hover:bg-slate-800 text-slate-100 border border-white/15 backdrop-blur-md text-sm font-semibold transition">
                Kelola jenis
            </a>
            <a href="{{ route('panitia.workshops.create') }}"
               class="px-4 py-2 rounded-xl bg-tix hover:bg-orange-600 text-white text-sm font-semibold shadow-lg shadow-tix/20 transition">
                 Tambah
            </a>
        </div>
    </div>

    <div class="bg-slate-900/70 backdrop-blur-md border border-white/10 rounded-2xl overflow-x-auto shadow-xl">
        <table class="w-full text-sm">
            <thead class="bg-slate-950/60 text-left text-xs uppercase tracking-wider text-slate-300 border-b border-white/10">
                <tr>
                    <th class="px-5 py-3.5">Workshop</th>
                    <th class="px-5 py-3.5">Jadwal</th>
                    <th class="px-5 py-3.5">Harga</th>
                    <th class="px-5 py-3.5">Pendaftar</th>
                    <th class="px-5 py-3.5">Jenis</th>
                    <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10 text-slate-200">
                @forelse ($workshops as $w)
                    <tr class="hover:bg-white/5 transition">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <x-workshop-cover :workshop="$w" class="w-20 h-14 rounded-lg shrink-0 border border-white/10" />
                                <span class="font-semibold text-white">{{ $w->title }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-slate-300 whitespace-nowrap">{{ $w->starts_at->translatedFormat('d M Y, H:i') }}</td>
                        <td class="px-5 py-3 font-medium text-slate-200 whitespace-nowrap">{{ $w->rupiah() }}</td>
                        <td class="px-5 py-3 text-slate-300 whitespace-nowrap">{{ $w->registrations_count }} / {{ $w->quota }}</td>
                        <td class="px-5 py-3 whitespace-nowrap"><x-kategori-badge :workshop="$w" /></td>
                        <td class="px-5 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('panitia.workshops.edit', $w) }}" class="text-sky-400 hover:text-sky-300 font-semibold mr-3 transition">Edit</a>
                            <form method="POST" action="{{ route('panitia.workshops.destroy', $w) }}" class="inline"
                                  onsubmit="return confirm('Hapus workshop ini beserta pendaftarnya?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-400 hover:text-rose-300 font-semibold transition">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-slate-300">Belum ada workshop.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection