<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProfesseurMatiere extends Pivot
{
    protected $table = 'professeur_matiere';

    protected $fillable = ['professeur_id', 'matiere_id'];
}
