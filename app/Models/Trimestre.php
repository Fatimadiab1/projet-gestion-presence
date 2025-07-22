<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trimestre extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'date_debut', 'date_fin', 'annee_academique_id'];

    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class);
    }

    public function seances()
    {
        return $this->hasMany(Seance::class);
    }
}
