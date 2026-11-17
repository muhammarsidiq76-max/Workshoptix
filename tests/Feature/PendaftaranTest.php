<?php

namespace Tests\Feature;

use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PendaftaranTest extends TestCase
{
    use RefreshDatabase;

    private function buatWorkshop(): Workshop
    {
        return Workshop::create([
            'title' => 'Workshop Uji',
            'speaker' => 'Pemateri',
            'description' => 'Deskripsi',
            'location' => 'Lab',
            'starts_at' => now()->addWeek(),
            'quota' => 10,
            'price' => 50000,
        ]);
    }

    private function buatPendaftaran(User $user, Workshop $w, string $status = 'pending'): Registration
    {
        return Registration::create([
            'user_id' => $user->id,
            'workshop_id' => $w->id,
            'order_id' => 'WTX-TEST-' . $user->id,
            'amount' => $w->price,
            'status' => $status,
            'ticket_code' => $status === 'paid' ? 'WTX-TEST0001' : null,
        ]);
    }

    public function test_pendaftaran_ganda_ditolak(): void
    {
        $user = User::factory()->create();
        $w = $this->buatWorkshop();

        $this->actingAs($user)->post(route('registrations.store', $w));
        $this->actingAs($user)->post(route('registrations.store', $w));

        $this->assertDatabaseCount('registrations', 1);
    }

    public function test_tiket_milik_akun_lain_tidak_bisa_dibuka(): void
    {
        $pemilik = User::factory()->create();
        $orangLain = User::factory()->create();
        $reg = $this->buatPendaftaran($pemilik, $this->buatWorkshop(), 'paid');

        $this->actingAs($orangLain)
            ->get(route('registrations.ticket', $reg))
            ->assertForbidden();
    }

    public function test_tiket_belum_lunas_tidak_tampil(): void
    {
        $user = User::factory()->create();
        $reg = $this->buatPendaftaran($user, $this->buatWorkshop(), 'pending');

        $this->actingAs($user)
            ->get(route('registrations.ticket', $reg))
            ->assertRedirect(route('registrations.show', $reg));
    }

    public function test_peserta_tidak_bisa_masuk_panel_panitia(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('panitia.dashboard'))
            ->assertForbidden();
    }
}