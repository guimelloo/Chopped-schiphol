<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('luchtvaartmaatschappijen', function (Blueprint $table) {
            $table->id();
            $table->string('naam');
            $table->string('iata_code', 3)->unique();
            $table->string('land');
            $table->string('logo_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('luchtvaartmaatschappijen');
    }
};
