<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coordinateur extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classeAnnees()
    {
        return $this->hasMany(ClasseAnnee::class);
    }
}
