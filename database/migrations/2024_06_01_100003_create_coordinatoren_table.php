<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coordinatoren', function (Blueprint $table) {
            $table->id();
            $table->string('naam');
            $table->string('gebruikersnaam')->unique();
            $table->string('wachtwoord');
            $table->foreignId('luchtvaartmaatschappij_id')->nullable()->constrained('luchtvaartmaatschappijen')->nullOnDelete();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coordinatoren');
    }
};
