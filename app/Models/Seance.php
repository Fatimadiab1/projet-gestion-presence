<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'jour_semaine',
        'heure_debut',
        'heure_fin',
        'classe_annee_id',
        'matiere_id',
        'professeur_id',
        'type_cours_id',
        'statut_seance_id',
        'trimestre_id',
        'seance_reportee_de_id'
    ];

    // Relations

    public function classeAnnee()
    {
        return $this->belongsTo(ClasseAnnee::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function professeur()
    {
        return $this->belongsTo(Professeur::class);
    }

    public function typeCours()
    {
        return $this->belongsTo(TypeCours::class);
    }

   public function statutSeance()
    {
        return $this->belongsTo(StatutSeance::class, 'statut_seance_id');
    }

    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class);
    }

    public function seanceReportee()
    {
        return $this->belongsTo(Seance::class, 'seance_reportee_de_id');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }
    public function statut()
{
    return $this->belongsTo(StatutSeance::class, 'statut_seance_id');
}

}
