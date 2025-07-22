<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('professeur_matiere', function (Blueprint $table) {
            $table->foreignId('professeur_id')->constrained('professeurs')->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade');

            $table->primary(['professeur_id', 'matiere_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('professeur_matiere');
    }
};
