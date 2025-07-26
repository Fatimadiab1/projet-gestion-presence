<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnneeAcademique extends Model
{
    use HasFactory;

    protected $table = 'annees_academiques';
    protected $fillable = ['annee', 'date_debut', 'date_fin', 'est_active'];

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
        'annee_academique_id', 
        'classe_annee_id',     
        'id',               
        'id' 
    );
}

}
