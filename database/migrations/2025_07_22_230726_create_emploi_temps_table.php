<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emploi_temps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classe_annee_id')->constrained('classe_annee')->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade');
            $table->foreignId('professeur_id')->constrained('professeurs')->onDelete('cascade');
            $table->foreignId('type_cours_id')->constrained('types_cours')->onDelete('cascade');

            $table->string('jour_semaine');
            $table->time('heure_debut');
            $table->time('heure_fin');

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('emploi_temps');
    }
};
