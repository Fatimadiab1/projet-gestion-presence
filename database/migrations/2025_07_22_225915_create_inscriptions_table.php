<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
            $table->foreignId('classe_annee_id')->constrained('classe_annee')->onDelete('cascade');
            $table->date('date_inscription');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};
