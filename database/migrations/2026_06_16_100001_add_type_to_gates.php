<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gates', function (Blueprint $table) {
            $table->string('type')->default('standaard')->after('terminal');
        });
    }

    public function down(): void
    {
        Schema::table('gates', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
