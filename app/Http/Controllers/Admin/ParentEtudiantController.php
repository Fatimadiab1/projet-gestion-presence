<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParentModel;
use App\Models\Etudiant;

class ParentEtudiantController extends Controller
{
    // Liste de toutes les associations
   public function index(Request $request)
{
    $recherche = $request->input('recherche');

    $parents = ParentModel::with(['user', 'enfants.user'])
        ->when($recherche, function ($filtre) use ($recherche) {
            $filtre->whereHas('enfants.user', function ($filtres) use ($recherche) {
                $filtres->where('prenom', 'like', '%' . $recherche . '%')
                         ->orWhere('nom', 'like', '%' . $recherche . '%');
            });
        })
        ->orderBy('id', 'desc')
        ->paginate(10);

    return view('admin.parents.index', [
        'parents' => $parents,
        'recherche' => $recherche
    ]);
}


    // Creer une association
    public function create()
    {
        // Charger les parents et étudiants avec leurs utilisateurs liés, triés par nom
        $parents = ParentModel::with('user')->get()->sortBy('user.nom');
        $etudiants = Etudiant::with('user')->get()->sortBy('user.nom');

        return view('admin.parents.create', compact('parents', 'etudiants'));
    }

    // Enregistrer une association
    public function store(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:parents,id',
            'etudiants' => 'required|array',
            'etudiants.*' => 'exists:etudiants,id',
        ]);

        $parent = ParentModel::findOrFail($request->parent_id);
        $parent->enfants()->sync($request->etudiants);

        return redirect()->route('admin.parents.index')
            ->with('success', 'Association enregistrée avec succès.');
    }

    // modifier une association
    public function edit($id)
    {
        $parent = ParentModel::with(['user', 'enfants'])->findOrFail($id);
        $etudiants = Etudiant::with('user')->get()->sortBy('user.nom');

        return view('admin.parents.edit', compact('parent', 'etudiants'));
    }

    // Mettre à jour l’association
    public function update(Request $request, $id)
    {
        $request->validate([
            'etudiants' => 'required|array',
            'etudiants.*' => 'exists:etudiants,id',
        ]);

        $parent = ParentModel::findOrFail($id);
        $parent->enfants()->sync($request->etudiants);

        return redirect()->route('admin.parents.index')
            ->with('success', 'Association mise à jour avec succès.');
    }
}
