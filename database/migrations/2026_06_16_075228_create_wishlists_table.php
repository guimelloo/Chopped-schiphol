<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verlanglijsten', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coordinator_id')->constrained('coordinatoren')->cascadeOnDelete();
            $table->string('maatschappij_naam')->nullable();
            $table->string('bestemming');
            $table->date('gewenste_datum')->nullable();
            $table->enum('stoelklasse', ['economy', 'business'])->default('economy');
            $table->text('opmerkingen')->nullable();
            $table->enum('prioriteit', ['hoog', 'normaal', 'laag'])->default('normaal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verlanglijsten');
    }
};
