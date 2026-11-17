<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    public const WARNA = [
        'biru' => ['label' => 'Biru', 'dot' => 'bg-sky-400', 'gradien' => 'from-sky-500 to-blue-900'],
        'indigo' => ['label' => 'Indigo', 'dot' => 'bg-indigo-400', 'gradien' => 'from-indigo-600 to-slate-900'],
        'oranye' => ['label' => 'Oranye', 'dot' => 'bg-orange-400', 'gradien' => 'from-orange-500 to-rose-800'],
        'hijau' => ['label' => 'Hijau', 'dot' => 'bg-emerald-400', 'gradien' => 'from-emerald-500 to-teal-900'],
        'ungu' => ['label' => 'Ungu', 'dot' => 'bg-fuchsia-400', 'gradien' => 'from-fuchsia-600 to-purple-900'],
        'kuning' => ['label' => 'Kuning', 'dot' => 'bg-amber-400', 'gradien' => 'from-amber-500 to-red-800'],
        'merah' => ['label' => 'Merah', 'dot' => 'bg-rose-400', 'gradien' => 'from-red-600 to-slate-900'],
        'pink' => ['label' => 'Pink', 'dot' => 'bg-pink-400', 'gradien' => 'from-pink-500 to-rose-800'],
    ];

    public const IKON = [
        'layar' => ['label' => 'Layar', 'svg' => '<rect x="2" y="4" width="20" height="13" rx="2"/><path d="M8 21h8M12 17v4"/>'],
        'papan' => ['label' => 'Papan presentasi', 'svg' => '<path d="M3 4h18M5 4v10h14V4M12 14v6M8 20h8"/>'],
        'roda-gigi' => ['label' => 'Roda gigi', 'svg' => '<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.9 4.9l2.1 2.1M17 17l2.1 2.1M4.9 19.1L7 17M17 7l2.1-2.1"/>'],
        'topi' => ['label' => 'Topi wisuda', 'svg' => '<path d="M22 10L12 5 2 10l10 5 10-5zM6 12v5c3 2 9 2 12 0v-5"/>'],
        'petir' => ['label' => 'Petir', 'svg' => '<path d="M13 2L4 14h7l-1 8 9-12h-7l1-8z"/>'],
        'piala' => ['label' => 'Piala', 'svg' => '<path d="M8 21h8M12 17v4M7 4h10v5a5 5 0 01-10 0V4zM17 5h3v2a3 3 0 01-3 3M7 5H4v2a3 3 0 003 3"/>'],
        'buku' => ['label' => 'Buku', 'svg' => '<path d="M2 4h7a3 3 0 013 3v13a2 2 0 00-2-2H2V4zM22 4h-7a3 3 0 00-3 3v13a2 2 0 012-2h8V4z"/>'],
        'mikrofon' => ['label' => 'Mikrofon', 'svg' => '<rect x="9" y="2" width="6" height="12" rx="3"/><path d="M5 11a7 7 0 0014 0M12 18v4M8 22h8"/>'],
        'bintang' => ['label' => 'Bintang', 'svg' => '<path d="M12 2l3.1 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.8 21l1.2-6.8-5-4.9 6.9-1L12 2z"/>'],
    ];

    protected $guarded = [];

    public function workshops(): HasMany
    {
        return $this->hasMany(Workshop::class);
    }

    public function tampilan(): array
    {
        $warna = self::WARNA[$this->warna] ?? self::WARNA['oranye'];
        $ikon = self::IKON[$this->ikon] ?? self::IKON['roda-gigi'];

        return [
            'label' => $this->name,
            'dot' => $warna['dot'],
            'gradien' => $warna['gradien'],
            'svg' => $ikon['svg'],
        ];
    }

    public static function bawaan(): array
    {
        return [
            'label' => 'Tanpa jenis',
            'dot' => 'bg-slate-400',
            'gradien' => 'from-slate-600 to-slate-900',
            'svg' => self::IKON['roda-gigi']['svg'],
        ];
    }
}