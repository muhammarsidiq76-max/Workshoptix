<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PendaftarController extends Controller
{
    public function index(Request $request)
    {
        $daftarWorkshop = Workshop::orderBy('title')->get(['id', 'title']);
        $lazy = $request->boolean('lazy');

        DB::flushQueryLog();
        DB::enableQueryLog();

        $query = Registration::latest();

        if ($request->filled('workshop')) {
            $query->where('workshop_id', $request->workshop);
        }

        if (! $lazy) {
            $query->with(['user', 'workshop']);
        }

        $items = $query->paginate(15)->withQueryString();

        if ($lazy) {
            // sengaja tanpa eager loading, buat bukti N+1
            $items->each(fn ($r) => [$r->user->name, $r->workshop->title]);
        }

        $jumlahQuery = count(DB::getQueryLog());

        return view('panitia.registrations', compact('items', 'daftarWorkshop', 'lazy', 'jumlahQuery'));
    }
}