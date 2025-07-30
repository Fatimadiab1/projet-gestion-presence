<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClasseAnnee;
use App\Models\Classe;
use App\Models\AnneeAcademique;
use App\Models\Coordinateur;

class ClasseAnneeController extends Controller
{
    // Afficher la liste des classes associées
    public function index(Request $request)
    {
        $annee_id = $request->input('annee');
        $classe_id = $request->input('classe');
        $coordinateur_id = $request->input('coordinateur');

        $query = ClasseAnnee::with(['classe', 'anneeAcademique', 'coordinateur.user'])
            ->orderBy('created_at', 'desc');

        if ($annee_id) {
            $query->where('annee_academique_id', $annee_id);
        }

        if ($classe_id) {
            $query->where('classe_id', $classe_id);
        }

        if ($coordinateur_id) {
            $query->where('coordinateur_id', $coordinateur_id);
        }

        $classesAssociees = $query->paginate(10);

        return view('admin.classes.index', [
            'classes' => $classesAssociees,
            'annees' => AnneeAcademique::orderByDesc('date_debut')->get(),
            'allClasses' => Classe::orderBy('nom')->get(),
            'coordinateurs' => Coordinateur::with('user')->get(),
            'annee_id' => $annee_id,
            'classe_id' => $classe_id,
            'coord_id' => $coordinateur_id,
        ]);
    }

    // Creer une association classe-année

    public function create()
    {
        return view('admin.classes.create', [
            'classes' => Classe::orderBy('nom')->get(),
            'annees' => AnneeAcademique::orderByDesc('date_debut')->get(),
            'coordinateurs' => Coordinateur::with('user')->get(),
        ]);
    }

    // Enregistrer une association classe-année

    public function store(Request $request)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'annee_academique_id' => 'required|exists:annees_academiques,id',
            'coordinateur_id' => 'required|exists:coordinateurs,id',
        ]);

        ClasseAnnee::create($request->only(['classe_id', 'annee_academique_id', 'coordinateur_id']));

        return redirect()->route('admin.classes.index')->with('success', 'Classe associée avec succès.');
    }
    // Modifier une association classe-année
    public function edit(ClasseAnnee $classeAnnee)
    {
        return view('admin.classes.edit', [
            'classeAnnee' => $classeAnnee,
            'classes' => Classe::orderBy('nom')->get(),
            'annees' => AnneeAcademique::orderByDesc('date_debut')->get(),
            'coordinateurs' => Coordinateur::with('user')->get(),
        ]);
    }
    // Mettre à jour une association classe-année
    public function update(Request $request, ClasseAnnee $classeAnnee)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'annee_academique_id' => 'required|exists:annees_academiques,id',
            'coordinateur_id' => 'required|exists:coordinateurs,id',
        ]);

        $classeAnnee->update($request->only(['classe_id', 'annee_academique_id', 'coordinateur_id']));

        return redirect()->route('admin.classes.index')->with('success', 'Classe mise à jour.');
    }
    // Supprimer une association classe-année
    public function destroy(ClasseAnnee $classeAnnee)
    {
        $classeAnnee->delete();
        return redirect()->route('admin.classes.index')->with('success', 'Classe supprimée.');
    }
}
