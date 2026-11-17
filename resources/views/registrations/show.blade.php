@extends('layouts.app')
@section('title', 'Pendaftaran')

@section('content')
{{-- Latar Belakang Foto + Overlay Gelap --}}
<div class="fixed inset-0 z-0 pointer-events-none">
    <img src="{{ asset('images/bogor.jpg') }}"
         alt="Latar Belakang Pendaftaran"
         class="w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-slate-950/75 backdrop-blur-[2px]"></div>
</div>

{{-- Konten Utama --}}
<div class="relative z-10">
    <div class="max-w-2xl mx-auto bg-slate-900/70 backdrop-blur-md border border-white/10 rounded-2xl overflow-hidden shadow-xl">
        <x-workshop-cover :workshop="$reg->workshop" class="h-40 border-b border-white/10">
            <div class="absolute top-3 right-3"><x-status-badge :status="$reg->status" /></div>
            <div class="absolute top-3 left-3"><x-kategori-badge :workshop="$reg->workshop" :cover="true" /></div>
        </x-workshop-cover>

        <div class="p-8">
            <p class="text-xs uppercase tracking-wider font-bold text-slate-400">Pendaftaran</p>
            <h1 class="text-2xl font-extrabold text-white leading-snug mt-0.5 drop-shadow-sm">{{ $reg->workshop->title }}</h1>

            <dl class="grid grid-cols-2 gap-y-4 mt-6 text-sm">
                <dt class="text-slate-400">Jadwal</dt>
                <dd class="font-semibold text-right text-slate-100">{{ $reg->workshop->starts_at->translatedFormat('d F Y, H:i') }}</dd>

                <dt class="text-slate-400">Lokasi</dt>
                <dd class="font-semibold text-right text-slate-100">{{ $reg->workshop->location }}</dd>

                <dt class="text-slate-400">Order ID</dt>
                <dd class="font-mono text-xs text-right text-slate-300">{{ $reg->order_id }}</dd>

                <dt class="text-slate-400">Total bayar</dt>
                <dd class="font-extrabold text-right text-white text-base">Rp {{ number_format($reg->amount, 0, ',', '.') }}</dd>
            </dl>

            <div class="mt-8 pt-6 border-t border-white/10 space-y-3">
                @if ($reg->status === 'pending')
                    <button type="button" id="tombol-bayar"
                            class="w-full bg-tix hover:bg-orange-600 text-white font-semibold rounded-xl py-3 shadow-lg shadow-tix/20 transition">
                        Bayar sekarang
                    </button>

                    <form id="form-cek" method="POST" action="{{ route('registrations.check', $reg) }}">
                        @csrf
                        <button class="w-full border border-white/20 bg-slate-800/70 hover:bg-slate-800 text-slate-100 font-semibold rounded-xl py-3 transition">
                            Saya sudah bayar, cek status
                        </button>
                    </form>
                @elseif ($reg->isPaid())
                    <a href="{{ route('registrations.ticket', $reg) }}"
                       class="block text-center bg-sky-600 hover:bg-sky-500 text-white font-semibold rounded-xl py-3 shadow-lg shadow-sky-600/20 transition">
                        Buka tiket digital
                    </a>
                @else
                    <p class="text-sm text-rose-300 bg-rose-500/15 border border-rose-500/25 rounded-xl p-3">
                        Pembayaran tidak berhasil. Kamu bisa mendaftar ulang dari halaman workshop.
                    </p>
                    <a href="{{ route('workshops.show', $reg->workshop) }}"
                       class="block text-center bg-slate-800/90 hover:bg-slate-700 text-white border border-white/15 font-semibold rounded-xl py-3 transition">
                        Ke halaman workshop
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

@if ($reg->status === 'pending')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        const formCek = document.getElementById('form-cek');
        const kirimCek = () => formCek.submit();

        document.getElementById('tombol-bayar').addEventListener('click', () => {
            if (typeof window.snap === 'undefined') {
                alert('Script Midtrans belum termuat. Periksa koneksi atau adblock.');
                return;
            }
            window.snap.pay(@json($reg->snap_token), {
                onSuccess: kirimCek,
                onPending: kirimCek,
                onError: kirimCek,
                onClose: () => {},
            });
        });
    </script>
@endif
@endsection