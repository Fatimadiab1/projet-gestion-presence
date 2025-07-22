<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuiviEtudiant extends Model
{
    use HasFactory;

    protected $fillable = [
        'inscription_id',
        'statut_suivi_id',
        'date_decision'
    ];

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    public function statut()
    {
        return $this->belongsTo(StatutSuivi::class, 'statut_suivi_id');
    }
}
