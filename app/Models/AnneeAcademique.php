<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnneeAcademique extends Model
{
    use HasFactory;

    protected $table = 'annees_academiques';
    protected $fillable = ['annee', 'date_debut', 'date_fin'];

    public function trimestres()
    {
        return $this->hasMany(Trimestre::class);
    }

    public function classeAnnees()
    {
        return $this->hasMany(ClasseAnnee::class);
    }
    // AnneeAcademique.php

public function inscriptions()
{
    return $this->hasManyThrough(
        Inscription::class,
        ClasseAnnee::class,
        'annee_academique_id', // Foreign key sur classe_annees
        'classe_annee_id',     // Foreign key sur inscriptions
        'id',                  // Local key sur annees_academiques
        'id'                   // Local key sur classe_annees
    );
}

}
