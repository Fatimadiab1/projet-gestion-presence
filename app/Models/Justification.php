<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Justification extends Model
{
    use HasFactory;

    protected $fillable = ['presence_id', 'raison', 'date_justif', 'modifie_par'];

    public function presence()
    {
        return $this->belongsTo(Presence::class);
    }

    public function modificateur()
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }
}
