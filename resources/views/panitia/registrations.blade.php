@extends('layouts.app')
@section('title', 'Kelola Pendaftar')

@section('content')
{{-- Latar Belakang Foto + Overlay Gelap --}}
<div class="fixed inset-0 -z-10 pointer-events-none">
    <img src="{{ asset('images/bogor.jpg') }}"
         alt="Latar Belakang Kelola Pendaftar"
         class="w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-slate-950/75 backdrop-blur-[2px]"></div>
</div>

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-2xl font-extrabold text-white drop-shadow-sm">Kelola Pendaftar</h1>
        <p class="text-xs text-slate-300 mt-0.5">Pantau peserta terdaftar dan perbandingan performa query</p>
    </div>

    <div class="flex flex-wrap items-center gap-3 text-sm">
        <span class="px-3 py-1.5 rounded-full font-bold backdrop-blur-md border {{ $lazy ? 'bg-rose-500/20 text-rose-300 border-rose-500/30' : 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30' }}">
            {{ $jumlahQuery }} query · {{ $lazy ? 'Mode Lambat' : 'Mode Cepat' }}
        </span>
        <a href="{{ request()->fullUrlWithQuery(['lazy' => $lazy ? 0 : 1]) }}"
        class="font-semibold text-sky-400 hover:text-sky-300 underline transition">
            Ubah ke {{ $lazy ? 'Mode Cepat' : 'Mode Lambat' }}
        </a>
    </div>
</div>

<form method="GET" class="mb-4 flex flex-wrap gap-2">
    @if ($lazy) <input type="hidden" name="lazy" value="1"> @endif
    <select name="workshop"
            class="rounded-xl border border-white/15 bg-slate-900/80 backdrop-blur-md px-4 py-2 text-sm text-slate-200 focus:outline-none focus:border-sky-400">
        <option value="" class="bg-slate-900 text-slate-200">Semua workshop</option>
        @foreach ($daftarWorkshop as $w)
            <option value="{{ $w->id }}" class="bg-slate-900 text-slate-200" @selected(request('workshop') == $w->id)>
                {{ $w->title }}
            </option>
        @endforeach
    </select>
    <button class="px-4 py-2 rounded-xl bg-tix hover:opacity-90 text-white text-sm font-semibold shadow-lg shadow-tix/20 transition">
        Filter
    </button>
</form>

<div class="bg-slate-900/70 backdrop-blur-md border border-white/10 rounded-2xl overflow-x-auto shadow-xl">
    <table class="w-full text-sm">
        <thead class="bg-slate-950/60 text-left text-xs uppercase tracking-wider text-slate-300 border-b border-white/10">
            <tr>
                <th class="px-5 py-3.5">Peserta</th>
                <th class="px-5 py-3.5">Workshop</th>
                <th class="px-5 py-3.5">Order ID</th>
                <th class="px-5 py-3.5">Status</th>
                <th class="px-5 py-3.5">Daftar</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/10 text-slate-200">
            @forelse ($items as $r)
                <tr class="hover:bg-white/5 transition">
                    <td class="px-5 py-3.5 font-semibold text-white">{{ $r->user->name }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <x-workshop-cover :workshop="$r->workshop" class="w-12 h-9 rounded-lg shrink-0" />
                            <span>{{ $r->workshop->title }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 font-mono text-xs text-slate-300">{{ $r->order_id }}</td>
                    <td class="px-5 py-3.5"><x-status-badge :status="$r->status" /></td>
                    <td class="px-5 py-3.5 text-slate-400 whitespace-nowrap">{{ $r->created_at->diffForHumans() }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-slate-300">Belum ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $items->links() }}</div>
@endsection