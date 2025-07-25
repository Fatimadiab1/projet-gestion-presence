<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatutSeance;
use Illuminate\Http\Request;

class StatutSeanceController extends Controller
{
    // Afficher liste des statuts de séance
    public function index()
    {
        $statuts = StatutSeance::all();
        return view('admin.statuts-seance.index', compact('statuts'));
    }

    // cree un statut de séance
    public function create()
    {
        return view('admin.statuts-seance.create');
    }

    // Enregistrer un statut de séance
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        StatutSeance::create($request->all());

        return redirect()->route('admin.statuts-seance.index')->with('success', 'Statut ajouté.');
    }

    // Modifier un statut de séance
    public function edit(StatutSeance $statut)
    {
        return view('admin.statuts-seance.edit', compact('statut'));
    }

    // Mise à jour d'un statut de séance
    public function update(Request $request, StatutSeance $statut)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $statut->update($request->all());

        return redirect()->route('admin.statuts-seance.index')->with('success', 'Statut modifié.');
    }

    // Supprimer un statut de séance
    public function destroy(StatutSeance $statut)
    {
        $statut->delete();

        return redirect()->route('admin.statuts-seance.index')->with('success', 'Statut supprimé.');
    }
}
