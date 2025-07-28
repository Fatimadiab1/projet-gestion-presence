<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatutSeance extends Model
{
    use HasFactory;

    protected $table = 'statuts_seance';
    protected $fillable = ['nom'];

    public function seances()
    {
        return $this->hasMany(Seance::class);
    }
}
