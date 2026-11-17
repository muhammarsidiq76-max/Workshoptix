<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('slug', 60)->unique();
            $table->string('warna', 20)->default('oranye');
            $table->string('ikon', 20)->default('roda-gigi');
            $table->timestamps();
        });

        $awal = [
            ['Webinar', 'webinar', 'biru', 'layar'],
            ['Seminar', 'seminar', 'indigo', 'papan'],
            ['Workshop', 'workshop', 'oranye', 'roda-gigi'],
            ['Pelatihan', 'pelatihan', 'hijau', 'topi'],
            ['Bootcamp', 'bootcamp', 'ungu', 'petir'],
            ['Lomba', 'lomba', 'kuning', 'piala'],
        ];

        foreach ($awal as [$nama, $slug, $warna, $ikon]) {
            DB::table('categories')->insert([
                'name' => $nama,
                'slug' => $slug,
                'warna' => $warna,
                'ikon' => $ikon,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('workshops', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
        });

        $peta = DB::table('categories')->pluck('id', 'slug');

        if (Schema::hasColumn('workshops', 'category')) {
            foreach (DB::table('workshops')->get(['id', 'category']) as $w) {
                DB::table('workshops')->where('id', $w->id)
                    ->update(['category_id' => $peta->get($w->category, $peta->get('workshop'))]);
            }

            Schema::table('workshops', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }

        DB::table('workshops')->whereNull('category_id')->update(['category_id' => $peta->get('workshop')]);
    }

    public function down(): void
    {
        Schema::table('workshops', function (Blueprint $table) {
            $table->string('category', 30)->default('workshop');
        });

        $slug = DB::table('categories')->pluck('slug', 'id');

        foreach (DB::table('workshops')->get(['id', 'category_id']) as $w) {
            DB::table('workshops')->where('id', $w->id)
                ->update(['category' => $slug->get($w->category_id, 'workshop')]);
        }

        Schema::table('workshops', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        Schema::dropIfExists('categories');
    }
};