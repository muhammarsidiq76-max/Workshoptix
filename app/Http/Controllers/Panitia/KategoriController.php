<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    public function index()
    {
        $daftar = Category::withCount('workshops')->orderBy('name')->get();

        return view('panitia.kategori.index', compact('daftar'));
    }

    public function store(Request $request)
    {
        $data = $this->aturan($request);
        $data['slug'] = $this->buatSlug($data['name']);

        Category::create($data);

        return redirect()->route('panitia.kategori.index')->with('ok', 'Jenis acara ditambahkan.');
    }

    public function edit(Category $kategori)
    {
        return view('panitia.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Category $kategori)
    {
        $data = $this->aturan($request, $kategori);
        $data['slug'] = $this->buatSlug($data['name'], $kategori->id);

        $kategori->update($data);

        return redirect()->route('panitia.kategori.index')->with('ok', 'Jenis acara diperbarui.');
    }

    public function destroy(Category $kategori)
    {
        if ($kategori->workshops()->exists()) {
            return back()->with('error', 'Jenis ini masih dipakai oleh acara. Pindahkan acaranya ke jenis lain dulu.');
        }

        $kategori->delete();

        return redirect()->route('panitia.kategori.index')->with('ok', 'Jenis acara dihapus.');
    }

    private function aturan(Request $request, ?Category $kategori = null): array
    {
        return $request->validate([
            'name' => [
                'required', 'string', 'max:50', 'regex:/[A-Za-z0-9]/',
                Rule::unique('categories', 'name')->ignore($kategori?->id),
            ],
            'warna' => ['required', Rule::in(array_keys(Category::WARNA))],
            'ikon' => ['required', Rule::in(array_keys(Category::IKON))],
        ]);
    }

    private function buatSlug(string $nama, ?int $abaikan = null): string
    {
        $dasar = Str::slug($nama) ?: 'jenis';
        $slug = $dasar;
        $i = 2;

        while (
            Category::where('slug', $slug)
                ->when($abaikan, fn ($q) => $q->where('id', '!=', $abaikan))
                ->exists()
        ) {
            $slug = $dasar . '-' . $i++;
        }

        return $slug;
    }
}