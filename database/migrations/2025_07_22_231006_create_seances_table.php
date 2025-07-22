<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('emploi_temps_id')->constrained('emploi_temps')->onDelete('cascade');
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');

            $table->foreignId('statut_seance_id')->constrained('statuts_seance')->onDelete('cascade');
            $table->foreignId('trimestre_id')->constrained('trimestres')->onDelete('cascade');

            $table->foreignId('seance_reportee_de_id')->nullable()->constrained('seances')->onDelete('set null');

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('seances');
    }
};
