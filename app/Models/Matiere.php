<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Matiere extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'volume_horaire_prevu'];

    public function professeurs()
    {
        return $this->belongsToMany(Professeur::class, 'professeur_matiere');
    }

    public function emploiTemps()
    {
        return $this->hasMany(EmploiTemps::class);
    }
}
