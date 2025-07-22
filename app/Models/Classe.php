<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = ['nom'];

    public function classeAnnees()
    {
        return $this->hasMany(ClasseAnnee::class);
    }
}
