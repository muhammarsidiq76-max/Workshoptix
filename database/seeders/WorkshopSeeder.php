<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Seeder;

class WorkshopSeeder extends Seeder
{
    public function run(): void
    {
        User::forceCreate([
            'name' => 'Panitia Workshop',
            'email' => 'panitia@workshop.test',
            'password' => 'password',
            'role' => 'panitia',
        ]);

        $kat = Category::pluck('id', 'slug');

        $data = [
            ['Karier di Dunia IT untuk Pelajar SMK', 'Anita Wulandari', 'Gambaran peluang kerja, skill yang dicari industri, dan cara menyiapkan portofolio sejak kelas XI.', 'Online via Google Meet', 25000, 'webinar'],
            ['Masa Depan AI dan Dampaknya bagi Kita', 'Dr. Hendra Kusuma', 'Seminar tentang perkembangan kecerdasan buatan dan peluang baru untuk pelajar.', 'Aula Sekolah', 30000, 'seminar'],
            ['Dasar Laravel untuk Pemula', 'Rizky Pratama', 'Kenalan sama routing, controller, dan Blade sambil bikin aplikasi sederhana dari nol.', 'Lab RPL 1', 75000, 'workshop'],
            ['Pelatihan Git & Kolaborasi Tim', 'Dimas Aryo', 'Branch, pull request, dan cara menyelesaikan konflik tanpa saling menyalahkan.', 'Ruang Teori 3', 35000, 'pelatihan'],
            ['Bootcamp Fullstack 3 Hari', 'Salsa Maharani', 'Tiga hari intensif membangun aplikasi lengkap dari database sampai tampilan.', 'Lab RPL 2', 150000, 'bootcamp'],
            ['Lomba Mini Hackathon Sekolah', 'Panitia IT', 'Bentuk tim, pecahkan studi kasus, dan presentasikan solusimu di depan juri.', 'Lab Multimedia', 20000, 'lomba'],
        ];

        $workshops = collect($data)->map(fn ($w, $i) => Workshop::create([
            'title' => $w[0],
            'speaker' => $w[1],
            'description' => $w[2],
            'location' => $w[3],
            'price' => $w[4],
            'category_id' => $kat[$w[5]] ?? null,
            'quota' => 30,
            'starts_at' => now()->addDays(7 + $i * 3)->setTime(9, 0),
        ]));

        foreach (range(1, 8) as $i) {
            $peserta = User::create([
                'name' => fake()->name(),
                'email' => "peserta{$i}@workshop.test",
                'password' => 'password',
            ]);

            $w = $workshops->random();
            $lunas = fake()->boolean(60);

            Registration::create([
                'user_id' => $peserta->id,
                'workshop_id' => $w->id,
                'order_id' => "WTX-SEED-{$i}-" . $w->id,
                'amount' => $w->price,
                'status' => $lunas ? 'paid' : 'pending',
                'ticket_code' => $lunas ? 'WTX-SEED' . str_pad($i, 4, '0', STR_PAD_LEFT) : null,
                'paid_at' => $lunas ? now() : null,
            ]);
        }
    }
}