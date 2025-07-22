<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seance extends Model
{
    use HasFactory;

    protected $fillable = [
        'emploi_temps_id',
        'date',
        'heure_debut',
        'heure_fin',
        'statut_seance_id',
        'trimestre_id',
        'seance_reportee_de_id'
    ];

    public function emploiTemps()
    {
        return $this->belongsTo(EmploiTemps::class);
    }

    public function statut()
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
}
