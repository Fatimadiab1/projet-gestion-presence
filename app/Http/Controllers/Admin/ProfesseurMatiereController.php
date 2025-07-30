<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Professeur;
use App\Models\Matiere;

class ProfesseurMatiereController extends Controller
{
    // Afficher les associations 
    public function index()
    {
        $professeurs = Professeur::with(['user', 'matieres'])->paginate(10);
        return view('admin.professeurs-matieres.index', compact('professeurs'));
    }

    // Créer une association
    public function create()
    {
        $professeurs = Professeur::with('user')->get()->sortBy('user.nom');
        $matieres = Matiere::orderBy('nom')->get();

        return view('admin.professeurs-matieres.create', compact('professeurs', 'matieres'));
    }

    // Enregistrer l'association
    public function store(Request $request)
    {
        $request->validate([
            'professeur_id' => 'required|exists:professeurs,id',
            'matiere_id' => 'required|exists:matieres,id',
        ]);

        $professeur = Professeur::find($request->professeur_id);

        if ($professeur->matieres()->where('matiere_id', $request->matiere_id)->exists()) {
            return back()->withErrors(['matiere_id' => 'Cette matière est déjà associée à ce professeur.'])->withInput();
        }

        $professeur->matieres()->attach($request->matiere_id);

        return redirect()->route('admin.professeurs-matieres.index')->with('success', 'Matière associée avec succès.');
    }

    // Modifier l'association
    public function edit($professeur_id, $matiere_id)
    {
        $professeur = Professeur::with('user')->findOrFail($professeur_id);
        $matiere = Matiere::findOrFail($matiere_id);
        $matieres = Matiere::orderBy('nom')->get();

        return view('admin.professeurs-matieres.edit', compact('professeur', 'matiere', 'matieres'));
    }

    // Mettre à jour l'association
    public function update(Request $request, $professeur_id, $matiere_id)
    {
        $request->validate([
            'nouvelle_matiere_id' => 'required|exists:matieres,id|different:' . $matiere_id,
        ]);

        $professeur = Professeur::findOrFail($professeur_id);

        $professeur->matieres()->detach($matiere_id);
        $professeur->matieres()->attach($request->nouvelle_matiere_id);

        return redirect()->route('admin.professeurs-matieres.index')->with('success', 'Matière mise à jour.');
    }
}
