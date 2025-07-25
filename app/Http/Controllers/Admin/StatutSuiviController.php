<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatutSuivi;
use Illuminate\Http\Request;

class StatutSuiviController extends Controller
{
    // Afficher la liste des statuts de suivi
    public function index()
    {
        $statuts = StatutSuivi::all();
        return view('admin.statuts-suivi.index', compact('statuts'));
    }
    // Créer un statut de suivi

    public function create()
    {
        return view('admin.statuts-suivi.create');
    }
    // Enregistrer un statut de suivi
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        StatutSuivi::create($request->only('nom'));

        return redirect()->route('admin.statuts-suivi.index')->with('success', 'Statut de suivi ajouté.');
    }
    // Modifier un statut de suivi
    public function edit(StatutSuivi $statut)
    {
        return view('admin.statuts-suivi.edit', compact('statut'));
    }
    // Mise à jour d'un statut de suivi
    public function update(Request $request, StatutSuivi $statut)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $statut->update($request->only('nom'));

        return redirect()->route('admin.statuts-suivi.index')->with('success', 'Statut de suivi modifié.');
    }
    // Supprimer un statut de suivi
    public function destroy(StatutSuivi $statut)
    {
        $statut->delete();

        return redirect()->route('admin.statuts-suivi.index')->with('success', 'Statut de suivi supprimé.');
    }
}
