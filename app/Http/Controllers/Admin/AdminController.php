<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Classe,
    Matiere,
    User,
    Role,
    Etudiant,
    Professeur,
    ParentModel,
    Coordinateur,
    AnneeAcademique
};

class AdminController extends Controller
{
    public function index(Request $requete)
    {
        $nombreClasses       = Classe::count();
        $nombreMatieres      = Matiere::count();
        $nombreEtudiants     = Etudiant::count();
        $nombreProfesseurs   = Professeur::count();
        $nombreParents       = ParentModel::count();
        $nombreCoordinateurs = Coordinateur::count();
        $nombreUtilisateurs  = User::count();

        $listeRoles = Role::all();


        $filtre = $requete->input('filtre_role');
        $utilisateurs = $filtre
            ? User::where('role_id', $filtre)->get()
            : User::all();

        $anneeActuelle = AnneeAcademique::where('est_active', true)->first();


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
