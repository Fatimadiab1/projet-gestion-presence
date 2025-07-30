<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\User;
use App\Models\Role;
use App\Models\Etudiant;
use App\Models\Professeur;
use App\Models\ParentModel;
use App\Models\Coordinateur;
use App\Models\AnneeAcademique;

class AdminController extends Controller
{
    
    public function index(Request $request)
    {
        $nombreClasses       = Classe::count();
        $nombreMatieres      = Matiere::count();
        $nombreEtudiants     = Etudiant::count();
        $nombreProfesseurs   = Professeur::count();
        $nombreParents       = ParentModel::count();
        $nombreCoordinateurs = Coordinateur::count();
        $nombreUtilisateurs  = User::count();

        $listeRoles    = Role::withCount('users')->get();
        $anneeActuelle = AnneeAcademique::where('est_active', true)->first();
        $utilisateurs  = User::with('role')->orderBy('created_at', 'desc')->take(3)->get();

        return view('admin.dashboard', compact(
            'nombreClasses',
            'nombreMatieres',
            'nombreEtudiants',
            'nombreProfesseurs',
            'nombreParents',
            'nombreCoordinateurs',
            'nombreUtilisateurs',
            'listeRoles',
            'utilisateurs',
            'anneeActuelle'
        ));
    }
}
