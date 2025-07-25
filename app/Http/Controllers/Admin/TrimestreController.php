<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trimestre;
use App\Models\AnneeAcademique;

class TrimestreController extends Controller
{
    // Afficher la liste des trimestres
    public function index()
    {
        $trimestres = Trimestre::with('anneeAcademique')->orderBy('created_at', 'desc')->get();
        return view('admin.trimestres.index', compact('trimestres'));
    }

    // Créer un trimestre
    public function create()
    {
        $annees = AnneeAcademique::orderBy('date_debut', 'desc')->get();

        return view('admin.trimestres.create', compact('annees'));
    }

    // Enregistrer un trimestre
    public function store(Request $request)
    {
      
        $request->validate([
            'nom' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'annee_academique_id' => 'required|exists:annees_academiques,id',
        ]);

        $annee = AnneeAcademique::find($request->annee_academique_id);

        if ($request->date_debut < $annee->date_debut || $request->date_fin > $annee->date_fin) {
            return back()->withErrors([
                'date_debut' => 'Les dates du trimestre doivent être comprises entre le ' .
                    $annee->date_debut . ' et le ' . $annee->date_fin . ' pour l’année ' . $annee->annee . '.'
            ])->withInput();
        }

       
        Trimestre::create([
            'nom' => $request->nom,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'annee_academique_id' => $request->annee_academique_id,
        ]);

        return redirect()->route('admin.trimestres.index')->with('success', 'Trimestre ajouté.');
    }

    // Modifier un trimestre
    public function edit(Trimestre $trimestre)
    {
        $annees = AnneeAcademique::orderBy('date_debut', 'desc')->get();

        return view('admin.trimestres.edit', compact('trimestre', 'annees'));
    }

    // Mise à jour d'un trimestre
    public function update(Request $request, Trimestre $trimestre)
    {
      
        $request->validate([
            'nom' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'annee_academique_id' => 'required|exists:annees_academiques,id',
        ]);

        $annee = AnneeAcademique::find($request->annee_academique_id);

        if ($request->date_debut < $annee->date_debut || $request->date_fin > $annee->date_fin) {
            return back()->withErrors([
                'date_debut' => 'Les dates du trimestre doivent être comprises entre le ' .
                    $annee->date_debut . ' et le ' . $annee->date_fin . ' pour l’année ' . $annee->annee . '.'
            ])->withInput();
        }
        $trimestre->update([
            'nom' => $request->nom,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'annee_academique_id' => $request->annee_academique_id,
        ]);

        return redirect()->route('admin.trimestres.index')->with('success', 'Trimestre modifié.');
    }

    // Supprime un trimestre
    public function destroy(Trimestre $trimestre)
    {
        $trimestre->delete();

        return redirect()->route('admin.trimestres.index')->with('success', 'Trimestre supprimé.');
    }
}
