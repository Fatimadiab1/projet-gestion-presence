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
    // Note d’assiduité sur 20
   public function assiduiteParEtudiantEtMatiere(Request $request)
{
    $coordinateurId = Auth::user()->coordinateur->id;
    $annee = AnneeAcademique::where('est_active', true)->first();

    $classes = ClasseAnnee::where('coordinateur_id', $coordinateurId)
        ->where('annee_academique_id', $annee->id)
        ->with('classe')
        ->get();

    $trimestres = Trimestre::where('annee_academique_id', $annee->id)->get();
    $matieres = Matiere::all();

    $classeId = $request->classe_id;
    $matiereId = $request->matiere_id;
    $trimestreId = $request->trimestre_id;
    $resultats = [];

    if ($classeId && $matiereId) {
        $classe = ClasseAnnee::find($classeId);
        $etudiants = $classe->inscriptions()->with('etudiant.user')->get();

        // Sélection des séances
        $listeSeances = Seance::where('classe_annee_id', $classeId)
            ->where('matiere_id', $matiereId);

        if ($trimestreId) {
            $listeSeances->where('trimestre_id', $trimestreId);
        }

        $seanceIds = $listeSeances->pluck('id');
        $totalSeances = count($seanceIds);

        foreach ($etudiants as $inscription) {
            $etudiant = $inscription->etudiant;

            $nbPresences = Presence::where('etudiant_id', $etudiant->id)
                ->whereIn('seance_id', $seanceIds)
                ->whereHas('statut', function ($filtreStatut) {
                    $filtreStatut->where('nom', 'présent');
                })
                ->count();

            $note = $totalSeances > 0 ? round(($nbPresences / $totalSeances) * 20, 2) : null;

            $resultats[] = [
                'nom' => $etudiant->user->nom,
                'prenom' => $etudiant->user->prenom,
                'nb_presences' => $nbPresences,
                'nb_seances' => $totalSeances,
                'note' => $note
            ];
        }
    }

    return view('coordinateur.calculs.assiduite', compact(
        'resultats',
        'classes',
        'matieres',
        'classeId',
        'matiereId',
        'trimestres',
        'trimestreId'
    ));
}


    // Taux de présence par étudiant sur une période
    public function presenceParPeriode(Request $request)
    {
        $coordinateurId = Auth::user()->coordinateur->id;
        $annee = AnneeAcademique::where('est_active', true)->first();

        $classes = ClasseAnnee::where('coordinateur_id', $coordinateurId)
            ->where('annee_academique_id', $annee->id)
            ->with('classe')
            ->get();

        $classeId = $request->classe_id;
        $debut = $request->date_debut;
        $fin = $request->date_fin;
        $resultats = [];

        if ($classeId && $debut && $fin) {
            $classe = ClasseAnnee::find($classeId);
            $etudiants = $classe->inscriptions()->with('etudiant.user')->get();

            $seanceIds = Seance::where('classe_annee_id', $classeId)
                ->whereBetween('date', [$debut, $fin])
                ->pluck('id');

            $total = count($seanceIds);

            foreach ($etudiants as $inscription) {
                $etudiant = $inscription->etudiant;

                $nbPresences = Presence::where('etudiant_id', $etudiant->id)
                    ->whereIn('seance_id', $seanceIds)
                    ->whereHas('statut', function ($filtreStatut) {
                        $filtreStatut->whereIn('nom', ['présent', 'retard']);
                    })
                    ->count();

                $taux = $total > 0 ? round(($nbPresences / $total) * 100, 2) : null;

                $resultats[] = [
                    'nom' => $etudiant->user->nom,
                    'prenom' => $etudiant->user->prenom,
                    'nb_presences' => $nbPresences,
                    'nb_seances' => $total,
                    'taux' => $taux
                ];
            }
        }

        return view('coordinateur.calculs.presence_periode', compact(
            'resultats',
            'classes',
            'classeId',
            'debut',
            'fin'
        ));
    }

    // Taux moyen d'une classe sur une période
    public function tauxClasseParPeriode(Request $request)
    {
        $coordinateurId = Auth::user()->coordinateur->id;
        $annee = AnneeAcademique::where('est_active', true)->first();

        $classes = ClasseAnnee::where('coordinateur_id', $coordinateurId)
            ->where('annee_academique_id', $annee->id)
            ->with('classe')
            ->get();

        $classeId = $request->classe_id;
        $dateDebut = $request->date_debut;
        $dateFin = $request->date_fin;
        $resultats = [];

        if ($classeId && $dateDebut && $dateFin) {
            $classe = ClasseAnnee::find($classeId);

            if ($classe) {
                $etudiants = $classe->inscriptions()->with('etudiant')->get();
                $etudiantIds = $etudiants->pluck('etudiant.id');

                $seanceIds = Seance::where('classe_annee_id', $classeId)
                    ->whereBetween('date', [$dateDebut, $dateFin])
                    ->pluck('id');

                $totalSeances = count($seanceIds);
                $totalPresences = 0;

                foreach ($etudiantIds as $id) {
                    $nbPresences = Presence::where('etudiant_id', $id)
                        ->whereIn('seance_id', $seanceIds)
                        ->whereHas('statut', function ($filtreStatut) {
                            $filtreStatut->where('nom', 'présent');
                        })
                        ->count();

                    $totalPresences += $nbPresences;
                }

                $totalAttendances = $etudiantIds->count() * $totalSeances;
                $taux = $totalAttendances > 0 ? round(($totalPresences / $totalAttendances) * 100, 2) : null;

                $resultats = [
                    'classe' => $classe->classe->nom,
                    'total_seances' => $totalSeances,
                    'total_presences' => $totalPresences,
                    'taux_moyen' => $taux
                ];
            }
        }

        return view('coordinateur.calculs.taux_classe_periode', compact(
            'classes',
            'resultats',
            'classeId',
            'dateDebut',
            'dateFin'
        ));
    }
}
