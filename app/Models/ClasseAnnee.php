<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClasseAnnee extends Model
{
    use HasFactory;

    protected $table = 'classe_annee';

    protected $fillable = ['classe_id', 'annee_academique_id', 'coordinateur_id'];

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class);
    }

    public function coordinateur()
    {
        return $this->belongsTo(Coordinateur::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function seances()
    {
        return $this->hasMany(Seance::class);
    }
}
