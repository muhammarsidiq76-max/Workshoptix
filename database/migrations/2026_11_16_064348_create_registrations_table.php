<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('workshop_id')->constrained()->cascadeOnDelete();
            $table->string('order_id')->unique();
            $table->unsignedInteger('amount');
            $table->string('status', 20)->default('pending');
            $table->string('snap_token')->nullable();
            $table->string('ticket_code')->nullable()->unique();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'workshop_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
