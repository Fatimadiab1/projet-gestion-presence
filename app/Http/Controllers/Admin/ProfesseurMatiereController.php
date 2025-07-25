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

    // cree une association
    public function create()
    {
        $professeurs = Professeur::with('user')->orderByRelation('user.nom')->get();
        $matieres = Matiere::orderBy('nom')->get();

        return view('admin.professeurs-matieres.create', compact('professeurs', 'matieres'));
    }

    // enregistrer une association
    public function store(Request $request)
    {
        $request->validate([
            'professeur_id' => 'required|exists:professeurs,id',
            'matiere_id' => 'required|exists:matieres,id',
            'volume_horaire' => 'nullable|integer|min:1',
        ]);

        $professeur = Professeur::findOrFail($request->professeur_id);
        if ($professeur->matieres()->where('matiere_id', $request->matiere_id)->exists()) {
            return redirect()->back()->withErrors(['matiere_id' => 'Cette matière est déjà associée à ce professeur.']);
        }
        $professeur->matieres()->attach($request->matiere_id, [
            'volume_horaire' => $request->volume_horaire
        ]);

        return redirect()->route('admin.professeurs-matieres.index')->with('success', 'Matière associée au professeur.');
    }
}
