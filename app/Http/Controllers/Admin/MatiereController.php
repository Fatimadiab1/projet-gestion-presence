<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Matiere;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    // Afficher la liste des matières
    public function index()
    {
        $matieres = Matiere::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.matieres.index', compact('matieres'));
    }

    // Creer une matière
    public function create()
    {
        return view('admin.matieres.create');
    }

    // Enregistrer une matière
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'volume_horaire_prevu' => 'required|integer|min:1',
        ]);

        Matiere::create($request->only('nom', 'volume_horaire_prevu'));

        return redirect()->route('admin.matieres.index')->with('success', 'Matière ajoutée avec succès.');
    }

    // Modifier une matière
    public function edit(Matiere $matiere)
    {
        return view('admin.matieres.edit', compact('matiere'));
    }

    // Mettre à jour une matière
    public function update(Request $request, Matiere $matiere)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'volume_horaire_prevu' => 'required|integer|min:1',
        ]);

        $matiere->update($request->only('nom', 'volume_horaire_prevu'));

        return redirect()->route('admin.matieres.index')->with('success', 'Matière modifiée avec succès.');
    }

    // Supprimer une matière
    public function destroy(Matiere $matiere)
    {
        $matiere->delete();
        return redirect()->route('admin.matieres.index')->with('success', 'Matière supprimée.');
    }
}
