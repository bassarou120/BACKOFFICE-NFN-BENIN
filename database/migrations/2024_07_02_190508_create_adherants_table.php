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
        Schema::create('adherants', function (Blueprint $table) {
            $table->id();
            $table->foreignId("departement_id")->constrained()->cascadeOnDelete();
            $table->foreignId("commune_id")->constrained()->cascadeOnDelete();
            $table->foreignId("arrondissement_id")->constrained()->cascadeOnDelete();
            $table->foreignId("quartier_id")->constrained()->cascadeOnDelete();
            $table->string("nom");
            $table->string("prenom");
            $table->date("date_naissance");
            $table->string("lieu_residence");
            $table->string("adresse");
            $table->string("telephone");
            $table->string("email");

            $table->string("photo_identite")->nullable();
            $table->string("piece_photo_identite")->nullable();
            $table->string("niveau_instruction");
            $table->string("activite_profession");
            $table->text("ambition_politique");
            $table->string("status")->default('EN ATTENTE');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adherants');
    }
};
