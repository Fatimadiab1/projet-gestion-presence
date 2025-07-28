<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Seance;
use App\Models\Inscription;
use App\Models\Presence;
use App\Models\StatutPresence;
use App\Models\Matiere;

class ProfesseurPresenceController extends Controller
{
    public function index(Request $request)
    {
        $professeurConnecte = Auth::user()->professeur;

        $matieresEnseignees = Matiere::whereHas('professeurs', function ($relation) use ($professeurConnecte) {
            $relation->where('professeur_id', $professeurConnecte->id);
        })->get();

        $seancesDuProfesseur = Seance::with(['classeAnnee.classe', 'matiere', 'statutSeance'])
            ->where('professeur_id', $professeurConnecte->id)
            ->when($request->date, function ($filtreParDate) use ($request) {
                $filtreParDate->whereDate('date', $request->date);
            })
            ->when($request->matiere_id, function ($filtreParMatiere) use ($request) {
                $filtreParMatiere->where('matiere_id', $request->matiere_id);
            })
            ->orderByDesc('date')
            ->get();

        return view('professeur.presences.index', [
            'seances' => $seancesDuProfesseur,
            'matieres' => $matieresEnseignees,
        ]);
    }

    public function show($seanceId)
    {
        $seance = Seance::with(['classeAnnee.classe', 'matiere'])->findOrFail($seanceId);

        if ($seance->professeur_id !== Auth::user()->professeur->id) {
            return redirect()->route('professeur.presences.index')->with('error', 'Accès non autorisé.');
        }

        $etudiantsInscrits = Inscription::with('etudiant.user')
            ->where('classe_annee_id', $seance->classe_annee_id)
            ->get();

        $statutsPresence = StatutPresence::all();

        $presencesExistantes = Presence::where('seance_id', $seanceId)
            ->get()
            ->keyBy('etudiant_id');

        return view('professeur.presences.show', [
            'seance' => $seance,
            'etudiants' => $etudiantsInscrits,
            'statuts' => $statutsPresence,
            'presencesExistantes' => $presencesExistantes,
        ]);
    }

    public function edit($seanceId)
    {
        $seance = Seance::with(['classeAnnee.classe', 'matiere'])->findOrFail($seanceId);

        if ($seance->professeur_id !== Auth::user()->professeur->id) {
            return redirect()->route('professeur.presences.index')->with('error', 'Accès non autorisé.');
        }

        if (now()->diffInDays($seance->date, false) < -14) {
            return redirect()->route('professeur.presences.index')->with('error', 'Délai dépassé pour modifier cette fiche.');
        }

        $etudiantsInscrits = Inscription::with('etudiant.user')
            ->where('classe_annee_id', $seance->classe_annee_id)
            ->get();

        $statutsPresence = StatutPresence::all();

        $presencesExistantes = Presence::where('seance_id', $seanceId)
            ->get()
            ->keyBy('etudiant_id');

        return view('professeur.presences.edit', [
            'seance' => $seance,
            'etudiants' => $etudiantsInscrits,
            'statuts' => $statutsPresence,
            'presencesExistantes' => $presencesExistantes,
        ]);
    }

    public function update(Request $request, $seanceId)
    {
        $seance = Seance::findOrFail($seanceId);

        if ($seance->professeur_id !== Auth::user()->professeur->id) {
            return redirect()->route('professeur.presences.index')->with('error', 'Accès non autorisé.');
        }

        if (now()->diffInDays($seance->date, false) < -14) {
            return redirect()->route('professeur.presences.index')->with('error', 'Délai dépassé pour modifier cette fiche.');
        }

        $request->validate([
            'presences' => 'required|array',
        ]);

        foreach ($request->presences as $etudiantId => $statutPresenceId) {
            Presence::updateOrCreate(
                ['seance_id' => $seanceId, 'etudiant_id' => $etudiantId],
                ['statut_presence_id' => $statutPresenceId]
            );
        }

        return redirect()->route('professeur.presences.index')->with('success', 'Présences mises à jour.');
    }
}
