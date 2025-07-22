<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatutPresence extends Model
{
    use HasFactory;

    protected $fillable = ['nom'];

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }
}
