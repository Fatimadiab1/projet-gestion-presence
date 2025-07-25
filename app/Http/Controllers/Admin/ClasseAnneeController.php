<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\ClasseAnnee;
use App\Models\AnneeAcademique;
use App\Models\Coordinateur;
use Illuminate\Http\Request;

class ClasseAnneeController extends Controller
{
// Afficher la liste des classes
public function index(Request $request)
{
    $annee_id = $request->input('annee');
    $classe_id = $request->input('classe');
    $coord_id = $request->input('coordinateur');

    $resultats = ClasseAnnee::with(['classe', 'anneeAcademique', 'coordinateur.user']);

    if ($annee_id) {
        $resultats->where('annee_academique_id', $annee_id);
    }

    if ($classe_id) {
        $resultats->where('classe_id', $classe_id);
    }

    if ($coord_id) {
        $resultats->where('coordinateur_id', $coord_id);
    }

    $classes = $resultats->orderByDesc('created_at')->get();

    $annees = AnneeAcademique::orderByDesc('date_debut')->get();
    $allClasses = Classe::orderBy('nom')->get();
    $coordinateurs = Coordinateur::with('user')->get();

    return view('admin.classes.index', compact(
        'classes',
        'annees',
        'allClasses',
        'coordinateurs',
        'annee_id',
        'classe_id',
        'coord_id'
    ));
}


    // Créer une association 
    public function create()
    {
        $classes = Classe::orderBy('nom')->get();
        $annees = AnneeAcademique::orderByDesc('date_debut')->get();
        $coordinateurs = Coordinateur::with('user')->get();

        return view('admin.classes.create', compact('classes', 'annees', 'coordinateurs'));
    }

    // Enregistrer une association
    public function store(Request $request)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'annee_academique_id' => 'required|exists:annees_academiques,id',
            'coordinateur_id' => 'required|exists:coordinateurs,id',
        ]);

        ClasseAnnee::create([
            'classe_id' => $request->classe_id,
            'annee_academique_id' => $request->annee_academique_id,
            'coordinateur_id' => $request->coordinateur_id,
        ]);

        return redirect()->route('admin.classes.index')->with('success', 'Classe associée à une année avec succès.');
    }

    // Modifier une association
    public function edit(ClasseAnnee $classe)
    {
        $classes = Classe::orderBy('nom')->get();
        $annees = AnneeAcademique::orderByDesc('date_debut')->get();
        $coordinateurs = Coordinateur::with('user')->get();

        return view('admin.classes.edit', compact('classe', 'classes', 'annees', 'coordinateurs'));
    }

    // Mise à jour d'une association
    public function update(Request $request, ClasseAnnee $classe)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'annee_academique_id' => 'required|exists:annees_academiques,id',
            'coordinateur_id' => 'required|exists:coordinateurs,id',
        ]);

        $classe->update([
            'classe_id' => $request->classe_id,
            'annee_academique_id' => $request->annee_academique_id,
            'coordinateur_id' => $request->coordinateur_id,
        ]);

        return redirect()->route('admin.classes.index')->with('success', 'Classe modifiée avec succès.');
    }

    // Supprimer une association
    public function destroy(ClasseAnnee $classe)
    {
        $classe->delete();
        return redirect()->route('admin.classes.index')->with('success', 'Classe supprimée.');
    }
}
