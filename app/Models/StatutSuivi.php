<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatutSuivi extends Model
{
    use HasFactory;

    protected $table = 'statuts_suivi';
    protected $fillable = ['nom'];

    public function suivis()
    {
        return $this->hasMany(SuiviEtudiant::class);
    }
}
