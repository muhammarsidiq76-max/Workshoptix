<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Registration extends Model
{
    protected $guarded = [];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workshop(): BelongsTo
    {
        return $this->belongsTo(Workshop::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
    public function terapkanStatus(object $hasil): void
    {
        $status = $hasil->transaction_status ?? null;
        $fraud = $hasil->fraud_status ?? 'accept';

        if ($status === 'settlement' || ($status === 'capture' && $fraud === 'accept')) {
            $this->update([
                'status' => 'paid',
                'paid_at' => now(),
                'ticket_code' => 'WTX-' . strtoupper(Str::random(8)),
            ]);
        } elseif (in_array($status, ['deny', 'cancel', 'expire', 'failure'])) {
            $this->update(['status' => 'failed']);
        }
    }
}