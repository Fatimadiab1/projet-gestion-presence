<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Professeur;
use App\Models\Matiere;
use Illuminate\Http\Request;

class ProfesseurMatiereController extends Controller
{
    // Afficher la liste 
    public function index()
    {
        $professeurs = Professeur::with(['user', 'matieres'])->get();
        return view('admin.professeurs-matieres.index', compact('professeurs'));
    }

    // créer une association 
    public function create()
    {
        $professeurs = Professeur::with('user')
            ->join('users', 'professeurs.user_id', '=', 'users.id')
            ->orderBy('users.nom')
            ->select('professeurs.*')
            ->get();

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

        $professeur = Professeur::findOrFail($request->professeur_id);

      
        if ($professeur->matieres()->where('matiere_id', $request->matiere_id)->exists()) {
            return redirect()->back()->withErrors(['matiere_id' => 'Cette matière est déjà associée à ce professeur.']);
        }

        $professeur->matieres()->attach($request->matiere_id);

        return redirect()->route('admin.professeurs-matieres.index')->with('success', 'Matière associée au professeur.');
    }

    // modifier l'association
    public function edit($professeur_id, $matiere_id)
    {
        $professeur = Professeur::with('user')->findOrFail($professeur_id);
        $matiere = Matiere::findOrFail($matiere_id);
        $matieres = Matiere::orderBy('nom')->get();

        return view('admin.professeurs-matieres.edit', compact('professeur', 'matiere', 'matieres'));
    }

    // Mise à jour de l'association
    public function update(Request $request, $professeur_id, $matiere_id)
    {
        $request->validate([
            'nouvelle_matiere_id' => 'required|exists:matieres,id|different:' . $matiere_id,
        ]);

        $professeur = Professeur::findOrFail($professeur_id);

      
        $professeur->matieres()->detach($matiere_id);

        
        $professeur->matieres()->attach($request->nouvelle_matiere_id);

        return redirect()->route('admin.professeurs-matieres.index')->with('success', 'Matière mise à jour pour le professeur.');
    }
}
