<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClasseAnnee;
use App\Models\Inscription;
use App\Models\Presence;
use App\Models\TypeCours;
use App\Models\Trimestre;
use App\Models\AnneeAcademique;
use App\Models\Seance;
use Carbon\Carbon;

class StatistiqueController extends Controller
{
    // Taux de présence par étudiant
    public function tauxParEtudiant(Request $request)
    {
        $idCoordinateur = Auth::user()->coordinateur->id;

        $classes = ClasseAnnee::where('coordinateur_id', $idCoordinateur)
            ->with('classe')
            ->get();

        $idClasse = $request->classe_id;
        $dateDebut = $request->date_debut ? Carbon::parse($request->date_debut) : null;
        $dateFin = $request->date_fin ? Carbon::parse($request->date_fin) : null;

        $donnees = [];

        if ($idClasse) {
            $inscriptions = Inscription::with('etudiant.user')
                ->where('classe_annee_id', $idClasse)
                ->get();

            foreach ($inscriptions as $inscription) {
                if (!$inscription->etudiant || !$inscription->etudiant->user) continue;

                $presences = Presence::where('etudiant_id', $inscription->etudiant_id)
                    ->whereHas('seance', function ($s) use ($dateDebut, $dateFin) {
                        if ($dateDebut) $s->where('date', '>=', $dateDebut);
                        if ($dateFin) $s->where('date', '<=', $dateFin);
                    });

                $total = (clone $presences)->count();
                $presents = (clone $presences)->whereHas('statut', function ($s) {
                    $s->where('nom', 'Présent');
                })->count();

                $taux = $total > 0 ? round(($presents / $total) * 100, 1) : 0;

                $donnees[] = [
                    'nom' => $inscription->etudiant->user->prenom . ' ' . $inscription->etudiant->user->nom,
                    'taux' => $taux
                ];
            }

            usort($donnees, fn($a, $b) => $b['taux'] <=> $a['taux']);
        }

        return view('coordinateur.statistiques.presence_etudiants', [
            'donnees' => $donnees,
            'classes' => $classes,
            'classeChoisie' => $idClasse
        ]);
    }

    // Taux de présence moyen par classe
    public function tauxParClasse(Request $request)
    {
        $idCoordinateur = Auth::user()->coordinateur->id;
        $annee = AnneeAcademique::where('est_active', true)->first();
        if (!$annee) return back()->with('error', 'Année active manquante.');

        $dateDebut = $request->date_debut ? Carbon::parse($request->date_debut) : null;
        $dateFin = $request->date_fin ? Carbon::parse($request->date_fin) : null;

        $classes = ClasseAnnee::where('coordinateur_id', $idCoordinateur)
            ->where('annee_academique_id', $annee->id)
            ->with('classe')
            ->get();

        $resultats = [];

        foreach ($classes as $classe) {
            $inscriptions = Inscription::where('classe_annee_id', $classe->id)->get();
            $total = 0;
            $somme = 0;

            foreach ($inscriptions as $inscription) {
                if (!$inscription->etudiant) continue;

                $presences = Presence::where('etudiant_id', $inscription->etudiant_id)
                    ->whereHas('seance', function ($s) use ($dateDebut, $dateFin) {
                        if ($dateDebut) $s->where('date', '>=', $dateDebut);
                        if ($dateFin) $s->where('date', '<=', $dateFin);
                    });

                $nbTotal = (clone $presences)->count();
                $nbPresents = (clone $presences)->whereHas('statut', fn($s) => $s->where('nom', 'Présent'))->count();

                if ($nbTotal > 0) {
                    $somme += round(($nbPresents / $nbTotal) * 100, 1);
                    $total++;
                }
            }

            $moyenne = $total > 0 ? round($somme / $total, 1) : 0;

            $resultats[] = [
                'classe' => $classe->classe->nom,
                'taux' => $moyenne
            ];
        }

        usort($resultats, fn($a, $b) => $b['taux'] <=> $a['taux']);

        return view('coordinateur.statistiques.presence_classes', [
            'resultats' => $resultats,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin
        ]);
    }

    // Volume d'heures par type de cours
    public function volumeCoursParType()
    {
        $annee = AnneeAcademique::where('est_active', true)->first();
        if (!$annee) return back()->with('error', 'Année active manquante.');

        $idCoordinateur = Auth::user()->coordinateur->id;
        $classes = ClasseAnnee::with('classe')
            ->where('annee_academique_id', $annee->id)
            ->where('coordinateur_id', $idCoordinateur)
            ->get();

        $types = TypeCours::all();
        $trimestres = Trimestre::all();

        $donnees = [];

        foreach ($types as $type) {
            $dataType = [];
            foreach ($trimestres as $trim) {
                $dataTrim = [];
                foreach ($classes as $classe) {
                    $seances = Seance::where('classe_annee_id', $classe->id)
                        ->where('type_cours_id', $type->id)
                        ->where('trimestre_id', $trim->id)
                        ->get();

                    $heures = 0;
                    foreach ($seances as $s) {
                        if ($s->heure_debut && $s->heure_fin) {
                            $debut = Carbon::parse($s->heure_debut);
                            $fin = Carbon::parse($s->heure_fin);
                            $heures += $debut->diffInMinutes($fin) / 60;
                        }
                    }

                    if ($heures > 0) {
                        $dataTrim[] = [
                            'classe' => $classe->classe->nom,
                            'heures' => round($heures, 1)
                        ];
                    }
                }
                $dataType["trim_{$trim->id}"] = $dataTrim;
            }
            $donnees[$type->nom] = $dataType;
        }

        return view('coordinateur.statistiques.volume_cours', [
            'donnees' => $donnees,
            'typesCours' => $types,
            'trimestres' => $trimestres
        ]);
    }

    // Volume total par classe tous types confondus
    public function volumeCoursTotalParClasse()
    {
        $annee = AnneeAcademique::where('est_active', true)->first();
        if (!$annee) return back()->with('error', 'Année active manquante.');

        $idCoordinateur = Auth::user()->coordinateur->id;
        $classes = ClasseAnnee::where('annee_academique_id', $annee->id)
            ->where('coordinateur_id', $idCoordinateur)
            ->with('classe')
            ->get();

        $trimestres = Trimestre::all();
        $donnees = [];

        foreach ($classes as $classe) {
            $nomClasse = $classe->classe->nom;
            $donnees[$nomClasse] = [];

            foreach ($trimestres as $trim) {
                $seances = Seance::where('classe_annee_id', $classe->id)
                    ->where('trimestre_id', $trim->id)
                    ->get();

                $totalHeures = 0;
                foreach ($seances as $s) {
                    if ($s->heure_debut && $s->heure_fin) {
                        $debut = Carbon::parse($s->heure_debut);
                        $fin = Carbon::parse($s->heure_fin);
                        $totalHeures += $debut->diffInMinutes($fin) / 60;
                    }
                }

                $donnees[$nomClasse][] = round($totalHeures, 1);
            }
        }

        return view('coordinateur.statistiques.volume_total', [
            'donnees' => $donnees,
            'trimestres' => $trimestres
        ]);
    }
}