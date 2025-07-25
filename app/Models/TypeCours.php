<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeCours extends Model
{
    use HasFactory;
    
    protected $table = 'types_cours';

    protected $fillable = ['nom'];

    public function emploiTemps()
    {
        return $this->hasMany(EmploiTemps::class);
    }
}
