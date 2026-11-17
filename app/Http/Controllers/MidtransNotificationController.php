<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;

class MidtransNotificationController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->all();

        $orderId = $data['order_id'] ?? '';
        $gross = $data['gross_amount'] ?? '';

        $signature = hash('sha512',
            $orderId . ($data['status_code'] ?? '') . $gross . config('services.midtrans.server_key')
        );

        if (! hash_equals($signature, (string) ($data['signature_key'] ?? ''))) {
            abort(403, 'Signature tidak valid.');
        }

        $reg = Registration::where('order_id', $orderId)->first();

        if ($reg && $reg->status === 'pending' && (int) $gross === $reg->amount) {
            $reg->terapkanStatus((object) $data);
        }

        return response()->json(['ok' => true]);
    }
}