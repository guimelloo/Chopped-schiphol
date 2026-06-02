<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vluchten', function (Blueprint $table) {
            $table->id();
            $table->string('vlucht_nummer')->unique();
            $table->foreignId('luchtvaartmaatschappij_id')->constrained('luchtvaartmaatschappijen')->cascadeOnDelete();
            $table->string('vertrek_luchthaven');
            $table->string('aankomst_luchthaven');
            $table->dateTime('vertrek_tijd');
            $table->dateTime('aankomst_tijd');
            $table->string('vliegtuig_type');
            $table->decimal('prijs_economy', 10, 2);
            $table->decimal('prijs_business', 10, 2);
            $table->integer('stoelen_economy');
            $table->integer('stoelen_business');
            $table->json('services')->nullable();
            $table->foreignId('gate_id')->nullable()->constrained('gates')->nullOnDelete();
            $table->enum('status', ['gepland', 'vertrokken', 'geland', 'geannuleerd'])->default('gepland');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vluchten');
    }
};
