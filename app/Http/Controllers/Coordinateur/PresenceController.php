<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Seance;
use App\Models\Inscription;
use App\Models\Presence;
use App\Models\StatutPresence;
use App\Models\Matiere;
use App\Models\ClasseAnnee;
use App\Models\AnneeAcademique;

class PresenceController extends Controller
{
    // Liste des séances à gérer
    public function index(Request $request)
    {
        $utilisateur = Auth::user();
        $coordinateur = $utilisateur->coordinateur;
        $annee = AnneeAcademique::where('est_active', true)->first();

        $classes = ClasseAnnee::with('classe')
            ->where('coordinateur_id', $coordinateur->id)
            ->where('annee_academique_id', $annee->id)
            ->get();

        $idsClasses = $classes->pluck('id');

        $seances = Seance::with(['classeAnnee.classe', 'matiere', 'statutSeance'])
            ->whereIn('classe_annee_id', $idsClasses)
            ->whereHas('statutSeance', function ($filtre) {
                $filtre->whereNotIn('nom', ['Annulée', 'Reportée']);
            })
            ->when($request->classe_id, function ($filtre) use ($request) {
                $filtre->where('classe_annee_id', $request->classe_id);
            })
            ->when($request->matiere_id, function ($filtre) use ($request) {
                $filtre->where('matiere_id', $request->matiere_id);
            })
            ->when($request->date, function ($filtre) use ($request) {
                $filtre->whereDate('date', $request->date);
            })
            ->orderByDesc('date')
            ->get();

        $matieres = Matiere::orderBy('nom')->get();

        return view('coordinateur.presences.index', [
            'seances' => $seances,
            'classes' => $classes,
            'matieres' => $matieres,
        ]);
    }

    // Modifier les présences d'une séance
    public function edit($seance_id)
    {
        $seance = Seance::with('classeAnnee.classe', 'matiere', 'statutSeance')->findOrFail($seance_id);

        if (in_array($seance->statutSeance->nom, ['Annulée', 'Reportée'])) {
            return redirect()->route('coordinateur.presences.index')
                ->with('error', 'Impossible de gérer les présences d\'une séance annulée ou reportée.');
        }

        $etudiants = Inscription::with('etudiant.user')
            ->where('classe_annee_id', $seance->classe_annee_id)
            ->get();

        $statuts = StatutPresence::all();

        $presencesExistantes = Presence::where('seance_id', $seance_id)
            ->get()
            ->keyBy('etudiant_id');

        return view('coordinateur.presences.edit', [
            'seance' => $seance,
            'etudiants' => $etudiants,
            'statuts' => $statuts,
            'presencesExistantes' => $presencesExistantes,
        ]);
    }

    // Affichage des présences 
    public function show($seance_id)
    {
        $seance = Seance::with('classeAnnee.classe', 'matiere', 'statutSeance')->findOrFail($seance_id);

        if (in_array($seance->statutSeance->nom, ['Annulée', 'Reportée'])) {
            return redirect()->route('coordinateur.presences.index')
                ->with('error', 'Cette séance a été annulée ou reportée.');
        }

        $etudiants = Inscription::with('etudiant.user')
            ->where('classe_annee_id', $seance->classe_annee_id)
            ->get();

        $statuts = StatutPresence::all();

        $presencesExistantes = Presence::where('seance_id', $seance_id)
            ->get()
            ->keyBy('etudiant_id');

        return view('coordinateur.presences.show', [
            'seance' => $seance,
            'etudiants' => $etudiants,
            'statuts' => $statuts,
            'presencesExistantes' => $presencesExistantes,
        ]);
    }

    // Enregistrer des présences
    public function update(Request $request, $seance_id)
    {
        $seance = Seance::with('statutSeance')->findOrFail($seance_id);

        if (in_array($seance->statutSeance->nom, ['Annulée', 'Reportée'])) {
            return redirect()->route('coordinateur.presences.index')
                ->with('error', 'Impossible d\'enregistrer des présences pour une séance annulée ou reportée.');
        }

        $request->validate([
            'presences' => 'required|array',
        ]);

        foreach ($request->presences as $etudiantId => $statutId) {
            Presence::updateOrCreate(
                ['seance_id' => $seance_id, 'etudiant_id' => $etudiantId],
                ['statut_presence_id' => $statutId]
            );
        }

        return redirect()->route('coordinateur.presences.index')
            ->with('success', 'Présences enregistrées avec succès.');
    }
}
