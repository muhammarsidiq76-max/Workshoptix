@extends('layouts.app')
@section('title', 'Tiket ' . $reg->ticket_code)

@section('content')
{{-- Latar Belakang Foto + Overlay Gelap (disembunyikan saat dicetak) --}}
<div class="fixed inset-0 z-0 pointer-events-none no-print">
    <img src="{{ asset('images/bogor.jpg') }}"
         alt="Latar Belakang Tiket"
         class="w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-slate-950/75 backdrop-blur-[2px]"></div>
</div>

{{-- Konten Utama --}}
<div class="relative z-10 max-w-xl mx-auto">
    <div class="rounded-3xl overflow-hidden shadow-2xl bg-slate-900/75 backdrop-blur-md border border-white/15">
        <x-workshop-cover :workshop="$reg->workshop" class="h-44 border-b border-white/10">
            <div class="absolute inset-0 p-6 flex flex-col justify-between bg-linear-to-t from-black/75 via-black/30 to-black/40 text-white">
                <div class="flex items-center justify-between">
                    <span class="text-xl font-extrabold italic drop-shadow">Workshop<span class="text-tix">TIX</span></span>
                    <span class="text-xs font-bold bg-emerald-500/90 text-white px-3 py-1 rounded-full shadow">LUNAS</span>
                </div>
                <p class="text-xs uppercase tracking-widest font-bold text-white/85">Tiket masuk</p>
            </div>
        </x-workshop-cover>

        <div class="px-8 py-7">
            <p class="text-xs uppercase tracking-wider font-bold text-slate-400">Workshop</p>
            <h1 class="text-2xl font-extrabold text-white leading-snug mt-0.5 drop-shadow-sm">{{ $reg->workshop->title }}</h1>

            <div class="grid grid-cols-2 gap-6 mt-6 text-sm">
                <div>
                    <p class="text-xs uppercase tracking-wider font-bold text-slate-400">Nama peserta</p>
                    <p class="font-semibold text-slate-100 mt-0.5">{{ $reg->user->name }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider font-bold text-slate-400">Lokasi</p>
                    <p class="font-semibold text-slate-100 mt-0.5">{{ $reg->workshop->location }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider font-bold text-slate-400">Tanggal</p>
                    <p class="font-semibold text-slate-100 mt-0.5">{{ $reg->workshop->starts_at->translatedFormat('l, d F Y') }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider font-bold text-slate-400">Pukul</p>
                    <p class="font-semibold text-slate-100 mt-0.5">{{ $reg->workshop->starts_at->format('H:i') }} WIB</p>
                </div>
            </div>
        </div>

        {{-- Garis Sobekan Tiket --}}
        <div class="relative border-t-2 border-dashed border-white/15">
            <span class="absolute -left-4 -top-4 w-8 h-8 rounded-full bg-slate-950 border border-white/15"></span>
            <span class="absolute -right-4 -top-4 w-8 h-8 rounded-full bg-slate-950 border border-white/15"></span>
        </div>

        {{-- Bagian Kode & QR --}}
        <div class="px-8 py-6 text-center bg-slate-950/50">
            <p class="text-xs uppercase tracking-wider font-bold text-slate-400">Kode tiket</p>
            <p class="font-mono text-3xl font-extrabold tracking-widest text-white mt-1">{{ $reg->ticket_code }}</p>

            <div class="flex justify-center mt-5">
                {{-- Kotak QR tetap putih agar mudah dipindai kamera --}}
                <div id="qr" class="qr-box bg-white p-3 rounded-xl shadow-md"></div>
            </div>

            <a id="unduh-qr" href="#" download="tiket-{{ $reg->ticket_code }}.png"
               class="no-print inline-block mt-3 text-sm font-semibold text-sky-400 hover:text-sky-300 underline transition">
                Unduh QR
            </a>

            <p class="text-xs text-slate-300 mt-2">Tunjukkan kode atau QR ini ke panitia saat registrasi ulang.</p>
        </div>
    </div>

    <div class="no-print flex gap-3 mt-6">
        <button onclick="window.print()"
                class="flex-1 bg-tix hover:bg-orange-600 text-white font-semibold rounded-xl py-3 shadow-lg shadow-tix/20 transition">
            Cetak tiket
        </button>
        <a href="{{ route('registrations.index') }}"
           class="flex-1 text-center border border-white/15 bg-slate-900/70 backdrop-blur-md hover:bg-slate-800 text-slate-200 font-semibold rounded-xl py-3 transition">
            Kembali
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    new QRCode(document.getElementById('qr'), {
        text: @json($reg->ticket_code),
        width: 160,
        height: 160,
    });

    setTimeout(() => {
        const kanvas = document.querySelector('#qr canvas');
        if (kanvas) {
            document.getElementById('unduh-qr').href = kanvas.toDataURL('image/png');
        }
    }, 300);
</script>
@endsection