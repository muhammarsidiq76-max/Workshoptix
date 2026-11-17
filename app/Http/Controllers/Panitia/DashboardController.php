<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Workshop;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $stat = [
            'workshop' => Workshop::count(),
            'pendaftar' => Registration::count(),
            'lunas' => Registration::where('status', 'paid')->count(),
            'pending' => Registration::where('status', 'pending')->count(),
            'pemasukan' => Registration::where('status', 'paid')->sum('amount'),
        ];

        $terbaru = Registration::with(['user', 'workshop'])->latest()->take(5)->get();

        return view('panitia.dashboard', compact('stat', 'terbaru'));
    }
}