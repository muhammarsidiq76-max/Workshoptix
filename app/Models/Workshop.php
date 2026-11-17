<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workshop extends Model
{
    protected $guarded = [];

    protected $with = ['category'];

    protected $casts = [
        'starts_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function sisaKuota(): int
    {
        $terisi = $this->registrations()
            ->whereIn('status', ['pending', 'paid'])
            ->count();

        return max($this->quota - $terisi, 0);
    }

    public function rupiah(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function imageUrl(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function kategori(): array
    {
        return $this->category ? $this->category->tampilan() : Category::bawaan();
    }
}