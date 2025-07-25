<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TypeCours;

class TypeCoursController extends Controller
{
    // Afficher la liste
    public function index()
    {
        $typesCours = TypeCours::orderBy('nom')->get();
        return view('admin.types-cours.index', compact('typesCours'));
    }

    // Creer un type de cours
    public function create()
    {
        return view('admin.types-cours.create');
    }

    // Enregistrer un type de cours
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        TypeCours::create(['nom' => $request->nom]);

        return redirect()->route('admin.types-cours.index')->with('success', 'Type de cours ajouté avec succès.');
    }

    // Modifier un type de cours
    public function edit(TypeCours $type)
    {
        return view('admin.types-cours.edit', compact('type'));
    }

    // Mise à jour d'un type de cours
    public function update(Request $request, TypeCours $type)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $type->update(['nom' => $request->nom]);

        return redirect()->route('admin.types-cours.index')->with('success', 'Type de cours mis à jour avec succès.');
    }

    // Supprimer un type de cours
    public function destroy(TypeCours $type)
    {
        $type->delete();

        return redirect()->route('admin.types-cours.index')->with('success', 'Type de cours supprimé.');
    }
}
