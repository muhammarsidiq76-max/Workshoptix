@extends('layouts.app')
@section('title', 'Jenis Acara')

@section('content')
{{-- Latar Belakang Foto + Overlay Gelap --}}
<div class="fixed inset-0 z-0 pointer-events-none">
    <img src="{{ asset('images/bogor.jpg') }}"
         alt="Latar Belakang Jenis Acara"
         class="w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-slate-950/75 backdrop-blur-[2px]"></div>
</div>

{{-- Konten Utama --}}
<div class="relative z-10">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-white drop-shadow-sm">Jenis Acara</h1>
            <p class="text-xs text-slate-300 mt-0.5">Data master jenis acara. Tambahkan di sini, atau langsung dari form acara.</p>
        </div>
        <a href="{{ route('panitia.workshops.index') }}" class="text-sm font-semibold text-tix hover:opacity-80 transition">
            ← Kelola workshop
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 items-start">
        {{-- Form Tambah Jenis Baru --}}
        <form method="POST" action="{{ route('panitia.kategori.store') }}"
              class="bg-slate-900/70 backdrop-blur-md border border-white/10 rounded-2xl p-6 space-y-5 shadow-xl text-slate-200">
            @csrf
            <h2 class="font-bold text-white">Tambah jenis baru</h2>
            @include('panitia.kategori._fields', ['kategori' => null])
            <button class="w-full bg-tix hover:bg-orange-600 text-white font-semibold rounded-xl py-3 shadow-lg shadow-tix/20 transition">
                Tambah jenis
            </button>
        </form>

        {{-- Tabel Daftar Jenis Acara --}}
        <div class="lg:col-span-2 bg-slate-900/70 backdrop-blur-md border border-white/10 rounded-2xl overflow-x-auto shadow-xl">
            <table class="w-full text-sm">
                <thead class="bg-slate-950/60 text-left text-xs uppercase tracking-wider text-slate-300 border-b border-white/10">
                    <tr>
                        <th class="px-5 py-3.5">Jenis</th>
                        <th class="px-5 py-3.5">Dipakai</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 text-slate-200">
                    @forelse ($daftar as $k)
                        @php $t = $k->tampilan(); @endphp
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="grid place-items-center w-10 h-10 rounded-xl bg-linear-to-br {{ $t['gradien'] }} text-white shadow-md shrink-0">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">{!! $t['svg'] !!}</svg>
                                    </span>
                                    <span class="font-semibold text-white">{{ $k->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-slate-300 whitespace-nowrap">{{ $k->workshops_count }} acara</td>
                            <td class="px-5 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('panitia.kategori.edit', $k) }}" class="text-sky-400 hover:text-sky-300 font-semibold mr-3 transition">Edit</a>
                                @if ($k->workshops_count === 0)
                                    <form method="POST" action="{{ route('panitia.kategori.destroy', $k) }}" class="inline"
                                          onsubmit="return confirm('Hapus jenis ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-rose-400 hover:text-rose-300 font-semibold transition">Hapus</button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">Sedang dipakai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-12 text-center text-slate-300">Belum ada jenis acara.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection