<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParentModel;
use App\Models\Etudiant;

class ParentEtudiantController extends Controller
{
    // Afficher la liste 
    public function index(Request $requete)
    {
        $recherche = $requete->input('recherche');

        $liste_parents = ParentModel::with(['user', 'enfants.user'])->get();

        if ($recherche) {
            $liste_parents = $liste_parents->filter(function ($parent) use ($recherche) {
                return $parent->enfants->contains(function ($enfant) use ($recherche) {
                    return str_contains(strtolower($enfant->user->prenom), strtolower($recherche))
                        || str_contains(strtolower($enfant->user->nom), strtolower($recherche));
                });
            });
        }

        return view('admin.parents.index', [
            'parents' => $liste_parents,
            'recherche' => $recherche
        ]);
    }

    // Créer une association
    public function create()
    {
        // Récupère tous les parents avec leurs noms, triés par ordre alphabétique
        $liste_parents = ParentModel::with('user')->get()->sortBy('user.nom');

        // Récupère tous les étudiants avec leurs noms, triés aussi
        $liste_etudiants = Etudiant::with('user')->get()->sortBy('user.nom');

        return view('admin.parents.create', [
            'parents' => $liste_parents,
            'etudiants' => $liste_etudiants
        ]);
    }

    // Enregistrer une association
    public function store(Request $requete)
    {
        $requete->validate([
            'parent_id' => 'required|exists:parents,id',
            'etudiants' => 'required|array',
            'etudiants.*' => 'exists:etudiants,id',
        ]);


        $parent = ParentModel::find($requete->parent_id);

        $parent->enfants()->sync($requete->etudiants);

        return redirect()->route('admin.parents.index')->with('success', 'Association enregistrée.');
    }

    // Modifier une association
    public function edit($id)
    {

        $parent = ParentModel::with(['user', 'enfants'])->findOrFail($id);


        $liste_etudiants = Etudiant::with('user')->get()->sortBy('user.nom');

        return view('admin.parents.edit', [
            'parent' => $parent,
            'etudiants' => $liste_etudiants
        ]);
    }

    // Mise a jour de l'association
    public function update(Request $requete, $id)
    {

        $requete->validate([
            'etudiants' => 'required|array',
            'etudiants.*' => 'exists:etudiants,id',
        ]);


        $parent = ParentModel::findOrFail($id);


        $parent->enfants()->sync($requete->etudiants);

        return redirect()->route('admin.parents.index')->with('success', 'Association mise à jour.');
    }
}
