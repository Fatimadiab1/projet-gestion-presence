<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatutPresence;
use Illuminate\Http\Request;

class StatutPresenceController extends Controller
{
    // Afficher la liste des statuts de présence
    public function index()
    {
        $statuts = StatutPresence::all();
        return view('admin.statuts-presence.index', compact('statuts'));
    }
    // Créer un statut de présence
    public function create()
    {
        return view('admin.statuts-presence.create');
    }
    // Enregistrer un statut de présence
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        StatutPresence::create($request->all());

        return redirect()->route('admin.statuts-presence.index')->with('success', 'Statut ajouté.');
    }
    // Modifier un statut de présence
public function edit(StatutPresence $statutPresence)
{
    return view('admin.statuts-presence.edit', compact('statutPresence'));
}

// Mise à jour d'un statut de présence
public function update(Request $request, StatutPresence $statutPresence)
{
    $request->validate([
        'nom' => 'required|string|max:255',
    ]);

    $statutPresence->update($request->only('nom'));

    return redirect()->route('admin.statuts-presence.index')->with('success', 'Statut modifié.');
}

// Supprimer un statut de présence
public function destroy(StatutPresence $statutPresence)
{
    $statutPresence->delete();

    return redirect()->route('admin.statuts-presence.index')->with('success', 'Statut supprimé.');
}

}
