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
        Schema::table('adherants', function (Blueprint $table) {
            //
            $table->string('categorie_socio')->nullable();
            $table->string('situation_matrimoniale')->nullable();
            $table->string('ethnie')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adherants', function (Blueprint $table) {
            //
        });
    }
};
