<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coordinatoren', function (Blueprint $table) {
            $table->string('email')->nullable()->after('gebruikersnaam');
            $table->boolean('actief')->default(true)->after('luchtvaartmaatschappij_id');
        });
    }

    public function down(): void
    {
        Schema::table('coordinatoren', function (Blueprint $table) {
            $table->dropColumn(['email', 'actief']);
        });
    }
};
