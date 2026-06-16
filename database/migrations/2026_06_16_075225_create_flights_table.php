<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('airline_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gate_id')->nullable()->constrained()->nullOnDelete();
            $table->string('flight_number');
            $table->string('origin');
            $table->string('destination');
            $table->dateTime('departure_at');
            $table->dateTime('arrival_at');
            $table->integer('duration_minutes');
            $table->string('aircraft_type')->nullable();
            $table->text('services')->nullable(); // e.g. "WiFi, Meal, Entertainment"
            $table->enum('status', ['scheduled', 'boarding', 'departed', 'arrived', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
