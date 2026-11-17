@php
    $k = $kategori ?? null;
    $warnaDipilih = old('warna', $k?->warna ?? 'oranye');
    $ikonDipilih = old('ikon', $k?->ikon ?? 'roda-gigi');
@endphp

<div>
    <label class="text-sm font-semibold">Nama jenis</label>
    <input name="name" value="{{ old('name', $k?->name) }}" placeholder="Misalnya Talkshow"
           class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-tix">
    @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <p class="text-sm font-semibold">Warna sampul</p>
    <div class="mt-2 flex flex-wrap gap-3">
        @foreach (\App\Models\Category::WARNA as $kunci => $w)
            <label class="cursor-pointer" title="{{ $w['label'] }}">
                <input type="radio" name="warna" value="{{ $kunci }}" class="peer sr-only" @checked($warnaDipilih === $kunci)>
                <span class="block w-9 h-9 rounded-full {{ $w['dot'] }} transition peer-checked:scale-110 peer-checked:ring-2 peer-checked:ring-white"></span>
            </label>
        @endforeach
    </div>
    @error('warna') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <p class="text-sm font-semibold">Ikon</p>
    <div class="mt-2 flex flex-wrap gap-2">
        @foreach (\App\Models\Category::IKON as $kunci => $ic)
            <label class="cursor-pointer" title="{{ $ic['label'] }}">
                <input type="radio" name="ikon" value="{{ $kunci }}" class="peer sr-only" @checked($ikonDipilih === $kunci)>
                <span class="grid place-items-center w-11 h-11 rounded-xl border border-slate-300 text-slate-500 transition peer-checked:border-tix peer-checked:text-tix peer-checked:bg-tix/10">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">{!! $ic['svg'] !!}</svg>
                </span>
            </label>
        @endforeach
    </div>
    @error('ikon') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
</div>