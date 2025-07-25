<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    // Afficher la liste des classes
    public function index()
    {
        $classes = Classe::orderByDesc('created_at')->get();
        return view('admin.typeclasse.index', compact('classes'));
    }

    // Creer une classe
    public function create()
    {
        return view('admin.typeclasse.create');
    }

    // Enregistrer une classe
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100|unique:classes,nom',
        ]);

        Classe::create([
            'nom' => $request->nom,
        ]);

        return redirect()->route('admin.typeclasse.index')->with('success', 'Classe ajoutée avec succès.');
    }

    // Modifier une classe
    public function edit(Classe $classe)
    {
        return view('admin.typeclasse.edit', compact('classe'));
    }

    // Mise à jour d'une classe
    public function update(Request $request, Classe $classe)
    {
        $request->validate([
            'nom' => 'required|string|max:100|unique:classes,nom,' . $classe->id,
        ]);

        $classe->update([
            'nom' => $request->nom,
        ]);

        return redirect()->route('admin.typeclasse.index')->with('success', 'Classe modifiée avec succès.');
    }

    // Supprimer une classe
    public function destroy(Classe $classe)
    {
        $classe->delete();

        return redirect()->route('admin.typeclasse.index')->with('success', 'Classe supprimée.');
    }
}
