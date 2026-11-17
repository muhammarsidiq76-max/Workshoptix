@extends('layouts.app')
@section('title', $workshop->exists ? 'Edit Workshop' : 'Tambah Workshop')

@section('content')
{{-- Latar Belakang Foto + Overlay Gelap --}}
<div class="fixed inset-0 z-0 pointer-events-none">
    <img src="{{ asset('images/bogor.jpg') }}"
         alt="Latar Belakang Form Workshop"
         class="w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-slate-950/75 backdrop-blur-[2px]"></div>
</div>

{{-- Konten Utama --}}
<div class="relative z-10">
    @php
        $input = 'mt-1.5 w-full rounded-xl border border-white/15 bg-slate-950/60 px-4 py-2.5 text-slate-100 placeholder-slate-400 focus:outline-none focus:border-tix focus:ring-2 focus:ring-tix/40 transition';
        $pilihan = (string) old('category_id', $workshop->category_id);
    @endphp

    <div class="max-w-2xl mx-auto bg-slate-900/70 backdrop-blur-md border border-white/10 rounded-2xl p-8 shadow-xl">
        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-white drop-shadow-sm">
                {{ $workshop->exists ? 'Edit workshop' : 'Tambah workshop' }}
            </h1>
            <p class="text-xs text-slate-300 mt-1">Unggah sampul dan lengkapi detail jadwal, kuota, serta harga workshop</p>
        </div>

        <form method="POST" enctype="multipart/form-data"
              action="{{ $workshop->exists ? route('panitia.workshops.update', $workshop) : route('panitia.workshops.store') }}"
              class="space-y-4">
            @csrf
            @if ($workshop->exists) @method('PUT') @endif

            <div>
                <label class="text-sm font-semibold text-slate-200">Foto workshop</label>
                <div class="mt-1.5 flex flex-wrap items-center gap-4 p-3 rounded-xl border border-white/10 bg-slate-950/40">
                    <img id="pratinjau" src="{{ $workshop->imageUrl() }}" alt="Pratinjau foto workshop"
                         class="w-32 h-20 object-cover rounded-xl border border-white/15 {{ $workshop->image ? '' : 'hidden' }}">
                    <input type="file" name="image" id="image" accept="image/png,image/jpeg,image/webp"
                           class="text-sm text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-tix/20 file:text-tix hover:file:bg-tix/30 file:cursor-pointer transition">
                </div>
                <p class="text-xs text-slate-400 mt-1.5">JPG, PNG, atau WebP, maksimal 2 MB. Foto lebar (16:9) paling bagus. Kosongkan untuk memakai sampul otomatis.</p>
                @error('image') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <label class="text-sm font-semibold text-slate-200">Jenis acara</label>
                    <a href="{{ route('panitia.kategori.index') }}" class="text-xs font-semibold text-tix hover:underline">Kelola jenis</a>
                </div>

                <select name="category_id" id="category_id" class="{{ $input }} [color-scheme:dark]">
                    @foreach ($daftarKategori as $k)
                        <option value="{{ $k->id }}" @selected($pilihan === (string) $k->id)>{{ $k->name }}</option>
                    @endforeach
                    <option value="__baru" @selected($pilihan === '__baru')>+ Tambah jenis baru...</option>
                </select>

                <input name="kategori_baru" id="kategori_baru" value="{{ old('kategori_baru') }}"
                       placeholder="Nama jenis baru, misalnya Talkshow"
                       class="{{ $input }} {{ $pilihan === '__baru' ? '' : 'hidden' }}">

                @error('category_id') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                @error('kategori_baru') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-200">Judul</label>
                <input name="title" value="{{ old('title', $workshop->title) }}" class="{{ $input }}">
                @error('title') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-200">Pemateri</label>
                <input name="speaker" value="{{ old('speaker', $workshop->speaker) }}" class="{{ $input }}">
                @error('speaker') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-200">Deskripsi</label>
                <textarea name="description" rows="4" class="{{ $input }}">{{ old('description', $workshop->description) }}</textarea>
                @error('description') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold text-slate-200">Lokasi</label>
                    <input name="location" value="{{ old('location', $workshop->location) }}" class="{{ $input }}">
                    @error('location') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-200">Waktu mulai</label>
                    <input type="datetime-local" name="starts_at"
                           value="{{ old('starts_at', $workshop->starts_at?->format('Y-m-d\TH:i')) }}"
                           class="{{ $input }} [color-scheme:dark]">
                    @error('starts_at') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-200">Kuota</label>
                    <input type="number" name="quota" value="{{ old('quota', $workshop->quota) }}" class="{{ $input }}">
                    @error('quota') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-200">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ old('price', $workshop->price) }}" class="{{ $input }}">
                    @error('price') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex flex-wrap gap-3 pt-4 border-t border-white/10">
                <button class="bg-tix hover:bg-orange-600 text-white font-semibold rounded-xl px-6 py-3 shadow-lg shadow-tix/20 transition">
                    Simpan
                </button>
                <a href="{{ route('panitia.workshops.index') }}"
                   class="px-6 py-3 rounded-xl border border-white/15 bg-slate-800/60 hover:bg-slate-800 text-slate-200 font-semibold transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('image').addEventListener('change', (e) => {
        const file = e.target.files[0];
        const img = document.getElementById('pratinjau');
        if (file) {
            img.src = URL.createObjectURL(file);
            img.classList.remove('hidden');
        }
    });

    const pilihJenis = document.getElementById('category_id');
    const jenisBaru = document.getElementById('kategori_baru');
    const aturJenisBaru = () => jenisBaru.classList.toggle('hidden', pilihJenis.value !== '__baru');
    pilihJenis.addEventListener('change', aturJenisBaru);
    aturJenisBaru();
</script>
@endsection                             