<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['nom', 'prenom', 'email', 'mot_de_passe', 'photo', 'role_id'];

    protected $hidden = ['mot_de_passe', 'remember_token'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function etudiant()
    {
        return $this->hasOne(Etudiant::class);
    }

    public function professeur()
    {
        return $this->hasOne(Professeur::class);
    }

    public function parent()
    {
        return $this->hasOne(ParentModel::class); // voir fichier ci-dessous
    }

    public function coordinateur()
    {
        return $this->hasOne(Coordinateur::class);
    }
}
