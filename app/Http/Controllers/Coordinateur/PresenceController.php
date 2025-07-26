<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seance;
use App\Models\Inscription;
use App\Models\Presence;
use App\Models\StatutPresence;

class PresenceController extends Controller
{
    // Affiche la liste des séances avec bouton "Présences"
    public function index()
    {
        $seances = Seance::with(['classeAnnee.classe', 'matiere'])->orderBy('date', 'desc')->get();
        return view('coordinateur.presences.index', compact('seances'));
    }

    // Formulaire de gestion des présences pour une séance
    public function edit($seance_id)
    {
        $seance = Seance::with('classeAnnee.classe')->findOrFail($seance_id);

        // Étudiants inscrits dans cette classe_annee
        $etudiants = Inscription::with('etudiant.user')
            ->where('classe_annee_id', $seance->classe_annee_id)
            ->get();

        // Statuts possibles (Présent, Absent, Justifié)
        $statuts = StatutPresence::all();

        // Récupère les présences existantes
        $presencesExistantes = Presence::where('seance_id', $seance_id)->get()->keyBy('inscription_id');

        return view('coordinateur.presences.edit', compact('seance', 'etudiants', 'statuts', 'presencesExistantes'));
    }

    // Enregistre les présences (ajout ou mise à jour)
    public function update(Request $request, $seance_id)
    {
        $request->validate([
            'presences' => 'required|array',
        ]);

        foreach ($request->presences as $inscription_id => $statut_presence_id) {
            Presence::updateOrCreate(
                [
                    'seance_id' => $seance_id,
                    'inscription_id' => $inscription_id,
                ],
                [
                    'statut_presence_id' => $statut_presence_id,
                ]
            );
        }

        return redirect()->route('coordinateur.presences.index')->with('success', 'Présences enregistrées avec succès.');
    }
}
