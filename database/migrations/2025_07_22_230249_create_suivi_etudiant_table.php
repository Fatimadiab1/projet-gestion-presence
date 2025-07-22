<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suivi_etudiant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscription_id')->constrained('inscriptions')->onDelete('cascade');
            $table->foreignId('statut_suivi_id')->constrained('statuts_suivi')->onDelete('cascade');
            $table->date('date_decision');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('suivi_etudiant');
    }
};
