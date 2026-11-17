<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Beranda') · WorkshopTix</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-slate-50 text-slate-800 font-sans antialiased">

<nav class="no-print sticky top-0 z-30 bg-navy text-white shadow-md">
    <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="text-xl font-extrabold italic tracking-tight">
            Workshop<span class="text-tix">TIX</span>
        </a>

        <div class="flex items-center gap-1 text-sm font-medium">
            <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg hover:bg-white/10">Workshop</a>

            @auth
                @if (auth()->user()->isPanitia())
                    <a href="{{ route('panitia.dashboard') }}" class="px-3 py-2 rounded-lg hover:bg-white/10">Panel Panitia</a>
                @else
                    <a href="{{ route('registrations.index') }}" class="px-3 py-2 rounded-lg hover:bg-white/10">Tiket Saya</a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                    @csrf
                    <button class="px-3 py-2 rounded-lg bg-white/10 hover:bg-white/20">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="px-3 py-2 rounded-lg hover:bg-white/10">Masuk</a>
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-tix hover:bg-orange-600 font-semibold">Daftar</a>
            @endauth
        </div>
    </div>
</nav>

@php
    $flash = [
        'ok' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
        'info' => 'bg-sky-50 text-sky-800 border-sky-200',
        'error' => 'bg-rose-50 text-rose-800 border-rose-200',
    ];
@endphp

<main class="flex-1 w-full max-w-6xl mx-auto px-4 py-8">
    <div class="space-y-3 mb-6 no-print">
        @foreach ($flash as $key => $kelas)
            @if (session($key))
                <div class="border rounded-xl px-4 py-3 text-sm {{ $kelas }}">{{ session($key) }}</div>
            @endif
        @endforeach
    </div>

    @yield('content')
</main>

<footer class="no-print py-6 text-center text-xs text-slate-400">
    WorkshopTix · Pendaftaran dan Tiket Digital Workshop IT
</footer>

@stack('scripts')
</body>
</html>