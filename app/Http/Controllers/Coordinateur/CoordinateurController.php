<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ClasseAnnee;
use App\Models\Etudiant;
use App\Models\Presence;
use App\Models\Seance;
use App\Models\AnneeAcademique;
use Carbon\Carbon;

class CoordinateurController extends Controller
{
// Afficher tableau de bord du coordinateur
    public function index(Request $request)
    {
        $utilisateur = Auth::user();
        $coordinateur = $utilisateur->coordinateur;
        $annee = AnneeAcademique::where('est_active', true)->first();

        if (!$coordinateur || !$annee) {
            return back()->with('error', 'Coordonnateur ou année académique introuvable.');
        }

        // Récupérer les classes du coordinateur 
        $classes = ClasseAnnee::where('coordinateur_id', $coordinateur->id)
            ->where('annee_academique_id', $annee->id)
            ->with('classe')
            ->get();

        $classeIds = $classes->pluck('id');

        // Compter les étudiants
        $nombreEtudiants = Etudiant::whereHas('inscriptions', function ($filtre) use ($classeIds) {
            $filtre->whereIn('classe_annee_id', $classeIds);
        })->count();

        // Date du jour
        $aujourdhui = Carbon::today()->toDateString();

        // Nombre d'absents aujourd'hui
        $absentsAujourdhui = Presence::whereDate('created_at', $aujourdhui)
            ->whereHas('etudiant.inscriptions', function ($filtre) use ($classeIds) {
                $filtre->whereIn('classe_annee_id', $classeIds);
            })
            ->whereHas('statut', function ($filtre) {
                $filtre->where('nom', 'absent');
            })
            ->count();

        // Présences à enregistrer
        $presencesAEnregistrer = Seance::whereIn('classe_annee_id', $classeIds)
            ->whereDate('date', $aujourdhui)
            ->whereDoesntHave('presences')
            ->count();

        // Absences non justifiées
        $absencesNonJustifiees = Presence::whereIn('etudiant_id', Etudiant::pluck('id'))
            ->whereHas('statut', function ($filtre) {
                $filtre->where('nom', 'absent');
            })
            ->whereDoesntHave('justification')
            ->count();

        // Taux de présence par classe
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');
        $tauxClasses = [];

        if ($dateDebut && $dateFin) {
            foreach ($classes as $classe) {
                $seanceIds = Seance::where('classe_annee_id', $classe->id)
                    ->whereBetween('date', [$dateDebut, $dateFin])
                    ->pluck('id');

                $totalPresences = Presence::whereIn('seance_id', $seanceIds)->count();

                $presencesEffectives = Presence::whereIn('seance_id', $seanceIds)
                    ->whereHas('statut', function ($filtre) {
                        $filtre->where('nom', 'présent');
                    })
                    ->count();

                $taux = $totalPresences > 0 ? round(($presencesEffectives / $totalPresences) * 100, 1) : 0;

                $tauxClasses[$classe->classe->nom] = $taux;
            }
        }

        // Étudiants filtrés par classe 
        $classeFiltre = $request->input('classe_id');

        $etudiants = Etudiant::whereHas('inscriptions', function ($filtre) use ($classeFiltre, $classeIds) {
            $filtre->whereIn('classe_annee_id', $classeIds);
            if ($classeFiltre) {
                $filtre->where('classe_annee_id', $classeFiltre);
            }
        })
        ->with('user')
        ->get();

        // Cours du jour
        $coursDuJour = Seance::with(['classeAnnee.classe', 'matiere', 'typeCours', 'professeur.user'])
            ->whereIn('classe_annee_id', $classeIds)
            ->whereDate('date', $aujourdhui)
            ->orderBy('heure_debut')
            ->get();

        return view('coordinateur.dashboard', [
            'nombreEtudiants' => $nombreEtudiants,
            'absentsAujourdhui' => $absentsAujourdhui,
            'presencesAEnregistrer' => $presencesAEnregistrer,
            'absencesNonJustifiees' => $absencesNonJustifiees,
            'classes' => $classes,
            'etudiants' => $etudiants,
            'tauxClasses' => $tauxClasses,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
            'coursDuJour' => $coursDuJour,
        ]);
    }
}
