<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('justifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('presence_id')->constrained('presences')->onDelete('cascade');
            $table->text('raison');
            $table->date('date_justif');

            $table->unsignedBigInteger('modifie_par')->nullable();
            $table->foreign('modifie_par')->references('id')->on('users')->nullOnDelete();

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('justifications');
    }
};
