<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Inscription,
    StatutSuivi,
    SuiviEtudiant
};

class SuiviEtudiantController extends Controller
{
    // Affiche la liste des suivis
    public function index()
    {
        $suivis = SuiviEtudiant::with(['inscription.etudiant.user', 'statutSuivi'])
            ->latest()
            ->get();

        return view('admin.suivis.index', compact('suivis'));
    }

    // Affiche le formulaire d'ajout
    public function create()
    {
        $inscriptions = Inscription::with('etudiant.user')->get();
        $statuts = StatutSuivi::all();

        return view('admin.suivis.create', compact('inscriptions', 'statuts'));
    }

    // Enregistre un nouveau suivi
    public function store(Request $request)
    {
        $request->validate([
            'inscription_id' => 'required|exists:inscriptions,id',
            'statut_suivi_id' => 'required|exists:statuts_suivi,id',
            'date_decision' => 'required|date',
        ]);

        SuiviEtudiant::create($request->only(['inscription_id', 'statut_suivi_id', 'date_decision']));

        return redirect()->route('admin.suivi-etudiants.index')->with('success', 'Suivi enregistré avec succès.');
    }

    // Affiche le formulaire de modification
    public function edit(SuiviEtudiant $suivi)
    {
        $inscriptions = Inscription::with('etudiant.user')->get();
        $statuts = StatutSuivi::all();

        return view('admin.suivis.edit', compact('suivi', 'inscriptions', 'statuts'));
    }

    // Met à jour un suivi
    public function update(Request $request, SuiviEtudiant $suivi)
    {
        $request->validate([
            'inscription_id' => 'required|exists:inscriptions,id',
            'statut_suivi_id' => 'required|exists:statuts_suivi,id',
            'date_decision' => 'required|date',
        ]);

        $suivi->update($request->only(['inscription_id', 'statut_suivi_id', 'date_decision']));

        return redirect()->route('admin.suivi-etudiants.index')->with('success', 'Suivi mis à jour avec succès.');
    }

    // Supprime un suivi
    public function destroy(SuiviEtudiant $suivi)
    {
        $suivi->delete();

        return redirect()->route('admin.suivi-etudiants.index')->with('success', 'Suivi supprimé.');
    }
}
