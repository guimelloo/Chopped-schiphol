<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boekingen', function (Blueprint $table) {
            $table->id();
            $table->string('boekings_nummer')->unique();
            $table->foreignId('vlucht_id')->constrained('vluchten')->cascadeOnDelete();
            $table->foreignId('reiziger_id')->nullable()->constrained('reizigers')->nullOnDelete();
            $table->enum('stoelklasse', ['economy', 'business']);
            $table->enum('stoel_voorkeur', ['raam', 'midden', 'gangpad'])->nullable();
            $table->string('stoel_nummer')->nullable();
            $table->decimal('prijs', 10, 2);
            $table->enum('status', ['in_afwachting', 'bevestigd', 'geannuleerd'])->default('bevestigd');
            $table->string('naam_reiziger');
            $table->string('email_reiziger');
            $table->string('telefoon_reiziger')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boekingen');
    }
};
