<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Workshop;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $items = $request->user()
            ->registrations()
            ->with('workshop')
            ->latest()
            ->get();

        return view('registrations.index', compact('items'));
    }

    public function store(Request $request, Workshop $workshop)
    {
        $user = $request->user();

        if ($user->isPanitia()) {
            return back()->with('error', 'Akun panitia tidak bisa mendaftar workshop.');
        }

        $lama = Registration::where('user_id', $user->id)
            ->where('workshop_id', $workshop->id)
            ->first();

        if ($lama && $lama->status !== 'failed') {
            return redirect()->route('registrations.show', $lama)
                ->with('info', 'Kamu sudah terdaftar di workshop ini.');
        }

        if ($workshop->sisaKuota() < 1) {
            return back()->with('error', 'Maaf, kuota workshop ini sudah penuh.');
        }

        $reg = $lama ?? new Registration([
            'user_id' => $user->id,
            'workshop_id' => $workshop->id,
        ]);

        $reg->fill([
            'order_id' => 'WTX-' . $user->id . '-' . $workshop->id . '-' . time(),
            'amount' => $workshop->price,
            'status' => 'pending',
            'snap_token' => null,
            'ticket_code' => null,
            'paid_at' => null,
        ])->save();

        return redirect()->route('registrations.show', $reg)
            ->with('ok', 'Pendaftaran dibuat. Selesaikan pembayaran untuk mendapat tiket.');
    }

    public function show(Registration $registration, MidtransService $midtrans)
{
    Gate::authorize('view', $registration);

    $registration->load('workshop');

    if ($registration->status === 'pending' && ! $registration->snap_token) {
        $registration->update(['snap_token' => $midtrans->snapToken($registration)]);
    }

    if ($registration->status === 'pending') {
        $hasil = $midtrans->status($registration->order_id);

        if ($hasil && (int) $hasil->gross_amount === $registration->amount) {
            $this->terapkanStatus($registration, $hasil);
            $registration->refresh();
        }
    }

    return view('registrations.show', ['reg' => $registration]);
}

    public function cekStatus(Registration $registration, MidtransService $midtrans)
    {
        Gate::authorize('view', $registration);

        if ($registration->status === 'pending') {
            $hasil = $midtrans->status($registration->order_id);

            if ($hasil && (int) $hasil->gross_amount === $registration->amount) {
                $this->terapkanStatus($registration, $hasil);
            }
        }

        $registration->refresh();

        $pesan = match ($registration->status) {
            'paid' => ['ok', 'Pembayaran terverifikasi. Tiketmu sudah terbit!'],
            'failed' => ['error', 'Pembayaran gagal atau kedaluwarsa.'],
            default => ['info', 'Pembayaran belum kami terima. Coba cek lagi sebentar.'],
        };

        return redirect()->route('registrations.show', $registration)->with(...$pesan);
    }

    public function ticket(Registration $registration)
    {
        Gate::authorize('view', $registration);

        if (! $registration->isPaid()) {
            return redirect()->route('registrations.show', $registration)
                ->with('error', 'Tiket baru tersedia setelah pembayaran lunas.');
        }

        $registration->load(['workshop', 'user']);

        return view('registrations.ticket', ['reg' => $registration]);
    }

    private function terapkanStatus(Registration $reg, object $hasil): void
    {
        $status = $hasil->transaction_status;
        $fraud = $hasil->fraud_status ?? 'accept';

        if ($status === 'settlement' || ($status === 'capture' && $fraud === 'accept')) {
            $reg->update([
                'status' => 'paid',
                'paid_at' => now(),
                'ticket_code' => 'WTX-' . strtoupper(Str::random(8)),
            ]);
        } elseif (in_array($status, ['deny', 'cancel', 'expire', 'failure'])) {
            $reg->update(['status' => 'failed']);
        }
    }
}