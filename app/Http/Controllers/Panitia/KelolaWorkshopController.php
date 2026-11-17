<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KelolaWorkshopController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $kategori = $request->query('kategori');
        $waktu = $request->query('waktu');

        if (! $kategori || ! Category::whereKey($kategori)->exists()) {
            $kategori = null;
        }

        if (! in_array($waktu, ['akan', 'lewat'], true)) {
            $waktu = null;
        }

        $query = Workshop::withCount([
            'registrations as pendaftar' => fn ($r) => $r->whereIn('status', ['pending', 'paid']),
        ]);

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                    ->orWhere('speaker', 'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%");
            });
        }

        if ($kategori) {
            $query->where('category_id', $kategori);
        }

        if ($waktu === 'akan') {
            $query->where('starts_at', '>=', now());
        } elseif ($waktu === 'lewat') {
            $query->where('starts_at', '<', now());
        }

        $query->orderBy('starts_at', $waktu === 'lewat' ? 'desc' : 'asc');

        $workshops = $query->paginate(8)->withQueryString();
        $total = Workshop::count();
        $daftarKategori = Category::orderBy('name')->get();

        return view('panitia.workshops.index', compact('workshops', 'q', 'kategori', 'waktu', 'total', 'daftarKategori'));
    }

    public function create()
    {
        $workshop = new Workshop([
            'category_id' => Category::where('slug', 'workshop')->value('id'),
        ]);

        return view('panitia.workshops.form', [
            'workshop' => $workshop,
            'daftarKategori' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->aturan($request);
        $data['category_id'] = $this->tentukanKategori($request);
        unset($data['image'], $data['kategori_baru']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('workshops', 'public');
        }

        Workshop::create($data);

        return redirect()->route('panitia.workshops.index')->with('ok', 'Workshop berhasil ditambahkan.');
    }

    public function edit(Workshop $workshop)
    {
        return view('panitia.workshops.form', [
            'workshop' => $workshop,
            'daftarKategori' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Workshop $workshop)
    {
        $data = $this->aturan($request);
        $data['category_id'] = $this->tentukanKategori($request);
        unset($data['image'], $data['kategori_baru']);

        if ($request->hasFile('image')) {
            if ($workshop->image) {
                Storage::disk('public')->delete($workshop->image);
            }
            $data['image'] = $request->file('image')->store('workshops', 'public');
        }

        $workshop->update($data);

        return redirect()->route('panitia.workshops.index')->with('ok', 'Workshop diperbarui.');
    }

    public function destroy(Workshop $workshop)
    {
        if ($workshop->image) {
            Storage::disk('public')->delete($workshop->image);
        }

        $workshop->delete();

        return redirect()->route('panitia.workshops.index')->with('ok', 'Workshop dihapus.');
    }

    private function aturan(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'speaker' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:150'],
            'category_id' => [
                'required',
                function ($atribut, $nilai, $gagal) {
                    if ($nilai !== '__baru' && ! Category::whereKey($nilai)->exists()) {
                        $gagal('Jenis acara tidak valid.');
                    }
                },
            ],
            'kategori_baru' => ['required_if:category_id,__baru', 'nullable', 'string', 'max:50', 'regex:/[A-Za-z0-9]/'],
            'starts_at' => ['required', 'date'],
            'quota' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'integer', 'min:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }

    private function tentukanKategori(Request $request): int
    {
        if ($request->input('category_id') !== '__baru') {
            return (int) $request->input('category_id');
        }

        $nama = trim($request->input('kategori_baru'));

        $sudah = Category::whereRaw('lower(name) = ?', [mb_strtolower($nama)])->first();
        if ($sudah) {
            return $sudah->id;
        }

        $dasar = Str::slug($nama) ?: 'jenis';
        $slug = $dasar;
        $i = 2;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $dasar . '-' . $i++;
        }

        return Category::create([
            'name' => $nama,
            'slug' => $slug,
            'warna' => array_rand(Category::WARNA),
            'ikon' => 'roda-gigi',
        ])->id;
    }
}