<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Registration;
use App\Models\Workshop;
use Illuminate\Http\Request;

class WorkshopController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $kategori = $request->query('kategori');

        if (! $kategori || ! Category::whereKey($kategori)->exists()) {
            $kategori = null;
        }

        $query = Workshop::withCount([
            'registrations as terisi' => fn ($r) => $r->whereIn('status', ['pending', 'paid']),
        ])->orderBy('starts_at');

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                    ->orWhere('speaker', 'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($kategori) {
            $query->where('category_id', $kategori);
        }

        $workshops = $query->get();
        $total = Workshop::count();
        $daftarKategori = Category::orderBy('name')->get();

        return view('workshops.index', compact('workshops', 'q', 'kategori', 'total', 'daftarKategori'));
    }

    public function show(Request $request, Workshop $workshop)
    {
        $sisa = $workshop->sisaKuota();

        $milikku = null;
        if ($request->user()) {
            $milikku = Registration::where('user_id', $request->user()->id)
                ->where('workshop_id', $workshop->id)
                ->first();
        }

        return view('workshops.show', compact('workshop', 'sisa', 'milikku'));
    }
}