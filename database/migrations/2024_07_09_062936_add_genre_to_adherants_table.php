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
            $table->string('genre')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adherants', function (Blueprint $table) {
            $table->dropColumn('carte_membre') ;
            $table->dropColumn('autre') ;
            $table->dropColumn('date_expire_carte') ;
            $table->dropColumn('categorie') ;
        });
    }
};
