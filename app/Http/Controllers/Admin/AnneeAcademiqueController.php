<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnneeAcademique;

class AnneeAcademiqueController extends Controller
{
    // Afficher la liste des années académiques
    public function index()
    {
        $annees = AnneeAcademique::orderBy('created_at', 'desc')->get();

        return view('admin.annees-academiques.index', compact('annees'));
    }

    // Creer une année académique
    public function create()
    {
        return view('admin.annees-academiques.create');
    }

    // Enregistrer une année académique
    public function store(Request $request)
    {

        $request->validate([
            'annee' => 'required|unique:annees_academiques,annee', 
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut', 

        ]);
        AnneeAcademique::create([
            'annee' => $request->annee,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
        ]);

        return redirect()->route('admin.annees-academiques.index')->with('success', 'Année ajoutée.');
    }

    // Modifer une année académique
    public function edit(AnneeAcademique $annee)
    {
        return view('admin.annees-academiques.edit', compact('annee'));
    }

    // Mise a jour d'une année académique
    public function update(Request $request, AnneeAcademique $annee)
    {

        $request->validate([
            'annee' => 'required|unique:annees_academiques,annee,' . $annee->id,
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $annee->update([
            'annee' => $request->annee,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
        ]);

        return redirect()->route('admin.annees-academiques.index')->with('success', 'Année mise à jour.');
    }

    // Supprimer une année académique
    public function destroy(AnneeAcademique $annee)
    {
        $annee->delete();

        return redirect()->route('admin.annees-academiques.index')->with('success', 'Année supprimée.');
    }
    
    public function activer(AnneeAcademique $annee)
{
    AnneeAcademique::where('est_active', true)->update(['est_active' => false]);

    $annee->update(['est_active' => true]);
    return redirect()->route('admin.annees-academiques.index')
        ->with('success', "L'année {$annee->annee} est maintenant l'année en cours.");
}

}
