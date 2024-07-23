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
        Schema::create('statcommunes', function (Blueprint $table) {
            $table->id();

            $table->string("commune")->nullable();
            $table->string("total_adherant")->nullable();
            $table->string("homme")->nullable();
            $table->string("femme")->nullable();
            $table->string("cep")->nullable();
            $table->string("bepc")->nullable();
            $table->string("bac")->nullable();
            $table->string("licence")->nullable();
            $table->string("master")->nullable();
            $table->string("doctorat")->nullable();
            $table->string("autre")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statcommunes');
    }
};
