<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClasseAnnee;
use App\Models\Matiere;
use App\Models\Seance;
use App\Models\Presence;
use App\Models\AnneeAcademique;
use App\Models\Trimestre;

class CalculController extends Controller
{
    // Note d’assiduité par étudiant et par matière (note /20)
    public function assiduiteParEtudiantEtMatiere(Request $request)
    {
        $coordinateur = Auth::user()->coordinateur;
        $anneeActive = AnneeAcademique::where('est_active', true)->first();

        $classes = ClasseAnnee::where('coordinateur_id', $coordinateur->id)
            ->where('annee_academique_id', $anneeActive->id)
            ->with('classe')
            ->get();

        $trimestres = Trimestre::where('annee_academique_id', $anneeActive->id)->get();
        $matieres = Matiere::all();

        $classeId = $request->classe_id;
        $matiereId = $request->matiere_id;
        $trimestreId = $request->trimestre_id;

        $resultats = [];

        if ($classeId && $matiereId) {
            $classe = ClasseAnnee::find($classeId);
            $etudiants = $classe->inscriptions()->with('etudiant.user')->get();

            $seances = Seance::where('classe_annee_id', $classeId)
                ->where('matiere_id', $matiereId);

            if ($trimestreId) {
                $seances = $seances->where('trimestre_id', $trimestreId);
            }

            $seanceIds = $seances->pluck('id');
            $total = count($seanceIds);

            foreach ($etudiants as $inscription) {
                $etudiant = $inscription->etudiant;

                $presences = Presence::where('etudiant_id', $etudiant->id)
                    ->whereIn('seance_id', $seanceIds)
                    ->whereHas('statut', function ($statut) {
                        $statut->where('nom', 'présent');
                    })
                    ->count();

                $note = $total > 0 ? round(($presences / $total) * 20, 2) : null;

                $resultats[] = [
                    'nom' => $etudiant->user->nom,
                    'prenom' => $etudiant->user->prenom,
                    'nb_presences' => $presences,
                    'nb_seances' => $total,
                    'note' => $note
                ];
            }
        }

        return view('coordinateur.calculs.assiduite', compact(
            'resultats',
            'classes',
            'matieres',
            'trimestres',
            'classeId',
            'matiereId',
            'trimestreId'
        ));
    }

    // Taux de présence par étudiant sur une période donnée
    public function presenceParPeriode(Request $request)
    {
        $coordinateur = Auth::user()->coordinateur;
        $anneeActive = AnneeAcademique::where('est_active', true)->first();

        $classes = ClasseAnnee::where('coordinateur_id', $coordinateur->id)
            ->where('annee_academique_id', $anneeActive->id)
            ->with('classe')
            ->get();

        $classeId = $request->classe_id;
        $dateDebut = $request->date_debut;
        $dateFin = $request->date_fin;

        $resultats = [];

        if ($classeId && $dateDebut && $dateFin) {
            $classe = ClasseAnnee::find($classeId);
            $etudiants = $classe->inscriptions()->with('etudiant.user')->get();

            $seanceIds = Seance::where('classe_annee_id', $classeId)
                ->whereBetween('date', [$dateDebut, $dateFin])
                ->pluck('id');

            $total = count($seanceIds);

            foreach ($etudiants as $inscription) {
                $etudiant = $inscription->etudiant;

                $presences = Presence::where('etudiant_id', $etudiant->id)
                    ->whereIn('seance_id', $seanceIds)
                    ->whereHas('statut', function ($statut) {
                        $statut->whereIn('nom', ['présent', 'retard']);
                    })
                    ->count();

                $taux = $total > 0 ? round(($presences / $total) * 100, 2) : null;

                $resultats[] = [
                    'nom' => $etudiant->user->nom,
                    'prenom' => $etudiant->user->prenom,
                    'nb_presences' => $presences,
                    'nb_seances' => $total,
                    'taux' => $taux
                ];
            }
        }

        return view('coordinateur.calculs.presence_periode', compact(
            'classes',
            'classeId',
            'dateDebut',
            'dateFin',
            'resultats'
        ));
    }

    // Taux de présence moyen de la classe sur une période
    public function tauxClasseParPeriode(Request $request)
    {
        $coordinateur = Auth::user()->coordinateur;
        $anneeActive = AnneeAcademique::where('est_active', true)->first();

        $classes = ClasseAnnee::where('coordinateur_id', $coordinateur->id)
            ->where('annee_academique_id', $anneeActive->id)
            ->with('classe')
            ->get();

        $classeId = $request->classe_id;
        $dateDebut = $request->date_debut;
        $dateFin = $request->date_fin;

        $resultats = [];

        if ($classeId && $dateDebut && $dateFin) {
            $classe = ClasseAnnee::find($classeId);
            $etudiants = $classe->inscriptions()->with('etudiant')->get();
            $etudiantIds = $etudiants->pluck('etudiant.id');

            $seanceIds = Seance::where('classe_annee_id', $classeId)
                ->whereBetween('date', [$dateDebut, $dateFin])
                ->pluck('id');

            $totalSeances = count($seanceIds);
            $totalPresences = 0;

            foreach ($etudiantIds as $etudiantId) {
                $nbPresences = Presence::where('etudiant_id', $etudiantId)
                    ->whereIn('seance_id', $seanceIds)
                    ->whereHas('statut', function ($statut) {
                        $statut->where('nom', 'présent');
                    })
                    ->count();

                $totalPresences += $nbPresences;
            }

            $totalAttendus = $etudiantIds->count() * $totalSeances;

            $taux = $totalAttendus > 0 ? round(($totalPresences / $totalAttendus) * 100, 2) : null;

            $resultats = [
                'classe' => $classe->classe->nom,
                'total_seances' => $totalSeances,
                'total_presences' => $totalPresences,
                'taux_moyen' => $taux
            ];
        }

        return view('coordinateur.calculs.taux_classe_periode', compact(
            'classes',
            'classeId',
            'dateDebut',
            'dateFin',
            'resultats'
        ));
    }

    // Étudiants droppés par matière
    public function etudiantsDroppesParMatiere(Request $request)
{
    $coordinateur = Auth::user()->coordinateur;
    $annee = AnneeAcademique::where('est_active', true)->first();

    $classes = ClasseAnnee::where('coordinateur_id', $coordinateur->id)
        ->where('annee_academique_id', $annee->id)
        ->with('classe')
        ->get();

    $matieres = Matiere::all();
    $trimestres = Trimestre::where('annee_academique_id', $annee->id)->get();

    $classeId = $request->classe_id;
    $matiereId = $request->matiere_id;
    $trimestreId = $request->trimestre_id;

    $droppes = [];

    if ($classeId && $matiereId) {
        // Séances concernées
        $seances = Seance::where('classe_annee_id', $classeId)
                         ->where('matiere_id', $matiereId);

        if ($trimestreId) {
            $seances = $seances->where('trimestre_id', $trimestreId);
        }

        $seanceIds = $seances->pluck('id');
        $totalSeances = count($seanceIds);

        if ($totalSeances > 0) {
            $classe = ClasseAnnee::find($classeId);
            $etudiants = $classe->inscriptions()->with('etudiant.user')->get();

            foreach ($etudiants as $inscription) {
                $etudiant = $inscription->etudiant;

                $nbPresences = Presence::where('etudiant_id', $etudiant->id)
                    ->whereIn('seance_id', $seanceIds)
                    ->whereHas('statut', function ($statut) {
                        $statut->whereIn('nom', ['présent', 'retard']);
                    })
                    ->count();

                $taux = round(($nbPresences / $totalSeances) * 100, 2);

                if ($taux < 30) {
                    $droppes[] = [
                        'nom' => $etudiant->user->nom,
                        'prenom' => $etudiant->user->prenom,
                        'presences' => $nbPresences,
                        'total' => $totalSeances,
                        'taux' => $taux
                    ];
                }
            }
        }
    }

    return view('coordinateur.calculs.droppes', compact(
        'classes',
        'matieres',
        'trimestres',
        'classeId',
        'matiereId',
        'trimestreId',
        'droppes'
    ));
}

}
