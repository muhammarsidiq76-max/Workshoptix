<?php

namespace App\Services;

use App\Models\Registration;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        // Diperbaiki: baca dari config/env agar tidak hardcode false
        Config::$isProduction = (bool) config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function snapToken(Registration $reg): string
    {
        $reg->loadMissing(['user', 'workshop']);

        return Snap::getSnapToken([
            'transaction_details' => [
                'order_id' => $reg->order_id,
                'gross_amount' => (int) $reg->amount, // Pastikan integer
            ],
            'item_details' => [[
                'id' => 'WS-' . $reg->workshop_id,
                'price' => (int) $reg->amount, // Pastikan integer
                'quantity' => 1,
                'name' => Str::limit($reg->workshop->title, 45, ''),
            ]],
            'customer_details' => [
                'first_name' => $reg->user->name,
                'email' => $reg->user->email,
            ],
        ]);
    }

    public function status(string $orderId): ?object
    {
        try {
            $res = (object) Transaction::status($orderId);
        } catch (\Throwable $e) {
            return null;
        }

        return isset($res->transaction_status) ? $res : null;
    }
}