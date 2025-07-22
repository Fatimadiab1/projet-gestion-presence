<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inscription extends Model
{
    use HasFactory;

    protected $fillable = ['etudiant_id', 'classe_annee_id', 'date_inscription'];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function classeAnnee()
    {
        return $this->belongsTo(ClasseAnnee::class);
    }

    public function suivis()
    {
        return $this->hasMany(SuiviEtudiant::class);
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }
}
