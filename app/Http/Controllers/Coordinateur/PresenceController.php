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
    // Afficher les presences des séances
   public function index(Request $request)
{
    $coordinateurConnecte = Auth::user()->coordinateur;
    $anneeActive = AnneeAcademique::where('est_active', true)->first();

    $classes = ClasseAnnee::with('classe')
        ->where('coordinateur_id', $coordinateurConnecte->id)
        ->where('annee_academique_id', $anneeActive->id)
        ->get();

    $idsClasses = $classes->pluck('id');

    $seances = Seance::with(['classeAnnee.classe', 'matiere', 'statutSeance'])
        ->withCount('presences') 
        ->whereIn('classe_annee_id', $idsClasses)
        ->whereHas('statutSeance', function ($query) {
            $query->whereNotIn('nom', ['Annulée', 'Reportée']);
        })
        ->when($request->classe_id, function ($query) use ($request) {
            $query->where('classe_annee_id', $request->classe_id);
        })
        ->when($request->matiere_id, function ($query) use ($request) {
            $query->where('matiere_id', $request->matiere_id);
        })
        ->when($request->date, function ($query) use ($request) {
            $query->whereDate('date', $request->date);
        })
        ->orderByDesc('date')
        ->paginate(10); 

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
            ->with('error', 'Impossible de gérer les présences pour cette séance.');
    }

    $matiereId = $seance->matiere_id;
    $classeId = $seance->classe_annee_id;

    $seanceIds = Seance::where('matiere_id', $matiereId)
        ->where('classe_annee_id', $classeId)
        ->pluck('id');

    $inscriptions = Inscription::with('etudiant.user')
        ->where('classe_annee_id', $classeId)
        ->get();

    $etudiants = [];

    foreach ($inscriptions as $inscription) {
        $etudiant = $inscription->etudiant;

        $nbPresences = Presence::where('etudiant_id', $etudiant->id)
            ->whereIn('seance_id', $seanceIds)
            ->whereHas('statut', function ($query) {
                $query->whereIn('nom', ['présent', 'retard']);
            })
            ->count();

        $totalSeances = count($seanceIds);
        $taux = $totalSeances > 0 ? ($nbPresences / $totalSeances) * 100 : 0;

        if ($taux >= 30) {
            $etudiants[] = $inscription;
        }
    }

    $statuts = StatutPresence::all();

    $presences = Presence::where('seance_id', $seance_id)
        ->get()
        ->keyBy('etudiant_id');

    return view('coordinateur.presences.edit', compact('seance', 'etudiants', 'statuts', 'presences'));
}


    // Voir les présences enregistrées
    public function show($seance_id)
{
    $seance = Seance::with('classeAnnee.classe', 'matiere', 'statutSeance')->findOrFail($seance_id);

    if (in_array($seance->statutSeance->nom, ['Annulée', 'Reportée'])) {
        return redirect()->route('coordinateur.presences.index')
            ->with('error', 'Cette séance est annulée ou reportée.');
    }

    $matiereId = $seance->matiere_id;
    $classeId = $seance->classe_annee_id;

    $seanceIds = Seance::where('matiere_id', $matiereId)
        ->where('classe_annee_id', $classeId)
        ->pluck('id');

    $inscriptions = Inscription::with('etudiant.user')
        ->where('classe_annee_id', $classeId)
        ->get();

    $etudiants = [];

    foreach ($inscriptions as $inscription) {
        $etudiant = $inscription->etudiant;

        $nbPresences = Presence::where('etudiant_id', $etudiant->id)
            ->whereIn('seance_id', $seanceIds)
            ->whereHas('statut', function ($query) {
                $query->whereIn('nom', ['présent', 'retard']);
            })
            ->count();

        $totalSeances = count($seanceIds);
        $taux = $totalSeances > 0 ? ($nbPresences / $totalSeances) * 100 : 0;

        if ($taux >= 30) {
            $etudiants[] = $inscription;
        }
    }

    $statuts = StatutPresence::all();

    $presencesExistantes = Presence::where('seance_id', $seance_id)
        ->get()
        ->keyBy('etudiant_id');

    return view('coordinateur.presences.show', compact('seance', 'etudiants', 'statuts', 'presencesExistantes'));
}


    // Mise à jour des présences
    public function update(Request $request, $seance_id)
    {
        $seance = Seance::with('statutSeance')->findOrFail($seance_id);

        if (in_array($seance->statutSeance->nom, ['Annulée', 'Reportée'])) {
            return redirect()->route('coordinateur.presences.index')
                ->with('error', 'Impossible d\'enregistrer des présences pour cette séance.');
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
