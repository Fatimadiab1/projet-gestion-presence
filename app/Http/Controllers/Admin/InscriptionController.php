<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inscription;
use App\Models\ClasseAnnee;
use App\Models\Etudiant;
use App\Models\AnneeAcademique;
use App\Models\Classe;

class InscriptionController extends Controller
{
    public function index(Request $request)
    {
        $classes = Classe::orderBy('nom')->get();
        $annees = AnneeAcademique::orderByDesc('date_debut')->get();

        $classe_id = $request->classe_id;
        $annee_id = $request->annee_id;

        $classeAnnees = ClasseAnnee::query();

        if ($classe_id) {
            $classeAnnees->where('classe_id', $classe_id);
        }

        if ($annee_id) {
            $classeAnnees->where('annee_academique_id', $annee_id);
        }

        $classeAnneesIds = $classeAnnees->pluck('id');

        $inscriptions = Inscription::with([
                'etudiant.user',
                'classeAnnee.classe',
                'classeAnnee.anneeAcademique',
                'suivis.statutSuivi'
            ])
            ->whereIn('classe_annee_id', $classeAnneesIds)
            ->orderByDesc('created_at')
            ->paginate(10); 

        return view('admin.inscriptions.index', compact(
            'inscriptions',
            'classes',
            'annees',
            'classe_id',
            'annee_id'
        ));
    }

    public function create()
    {
        $etudiants = Etudiant::with('user')->get();
        $classesAnnees = ClasseAnnee::with(['classe', 'anneeAcademique'])->get();

        return view('admin.inscriptions.create', compact('etudiants', 'classesAnnees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'classe_annee_id' => 'required|exists:classe_annee,id',
            'date_inscription' => 'required|date',
        ]);

        $classeAnnee = ClasseAnnee::with('anneeAcademique')->findOrFail($request->classe_annee_id);
        $date = $request->date_inscription;

        if ($date < $classeAnnee->anneeAcademique->date_debut || $date > $classeAnnee->anneeAcademique->date_fin) {
            return back()->withInput()->withErrors([
                'date_inscription' => "La date doit être comprise entre le {$classeAnnee->anneeAcademique->date_debut} et le {$classeAnnee->anneeAcademique->date_fin}.",
            ]);
        }

        Inscription::create($request->only('etudiant_id', 'classe_annee_id', 'date_inscription'));

        return redirect()->route('admin.inscriptions.index')->with('success', 'Inscription créée avec succès.');
    }

    public function edit(Inscription $inscription)
    {
        $etudiants = Etudiant::with('user')->get();
        $classesAnnees = ClasseAnnee::with(['classe', 'anneeAcademique'])->get();

        return view('admin.inscriptions.edit', compact('inscription', 'etudiants', 'classesAnnees'));
    }

    public function update(Request $request, Inscription $inscription)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'classe_annee_id' => 'required|exists:classe_annee,id',
            'date_inscription' => 'required|date',
        ]);

        $inscription->update($request->only('etudiant_id', 'classe_annee_id', 'date_inscription'));

        return redirect()->route('admin.inscriptions.index')->with('success', 'Inscription mise à jour.');
    }

    public function destroy(Inscription $inscription)
    {
        $inscription->delete();

        return redirect()->route('admin.inscriptions.index')->with('success', 'Inscription supprimée.');
    }

    public function reinscrire($etudiant_id)
    {
        $etudiant = Etudiant::with('user')->findOrFail($etudiant_id);
        $classesAnnees = ClasseAnnee::with(['classe', 'anneeAcademique'])->get();

        return view('admin.inscriptions.reinscrire', compact('etudiant', 'classesAnnees'));
    }

    public function reinscrireStore(Request $request, $etudiant_id)
    {
        $request->validate([
            'classe_annee_id' => 'required|exists:classe_annee,id',
            'date_inscription' => 'required|date',
        ]);

        $classeAnnee = ClasseAnnee::with('anneeAcademique')->findOrFail($request->classe_annee_id);
        $date = $request->date_inscription;

        if ($date < $classeAnnee->anneeAcademique->date_debut || $date > $classeAnnee->anneeAcademique->date_fin) {
            return back()->withInput()->withErrors([
                'date_inscription' => "La date doit être comprise entre le {$classeAnnee->anneeAcademique->date_debut} et le {$classeAnnee->anneeAcademique->date_fin}.",
            ]);
        }

        Inscription::create([
            'etudiant_id' => $etudiant_id,
            'classe_annee_id' => $request->classe_annee_id,
            'date_inscription' => $date,
        ]);

        return redirect()->route('admin.inscriptions.index')->with('success', 'Étudiant réinscrit avec succès.');
    }

    public function nonReinscrits(Request $request)
    {
        $annees = AnneeAcademique::orderByDesc('date_debut')->get();
        $classes = Classe::orderBy('nom')->get();

        $anneeActuelle = $request->filled('annee_id')
            ? AnneeAcademique::find($request->annee_id)
            : $annees->first();

        $classe_id = $request->classe_id;

        $classeAnnees = ClasseAnnee::where('annee_academique_id', $anneeActuelle->id);

        if ($classe_id) {
            $classeAnnees->where('classe_id', $classe_id);
        }

        $classeAnneesIds = $classeAnnees->pluck('id');
        $etudiantsInscrits = Inscription::whereIn('classe_annee_id', $classeAnneesIds)->pluck('etudiant_id');

        $nonReinscrits = Etudiant::with('user')
            ->whereNotIn('id', $etudiantsInscrits)
            ->get();

        return view('admin.inscriptions.non_reinscrits', compact(
            'nonReinscrits',
            'annees',
            'anneeActuelle',
            'classes',
            'classe_id'
        ));
    }
}
