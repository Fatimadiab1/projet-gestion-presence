<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Seance;
use App\Models\Matiere;
use App\Models\ProfesseurMatiere;
use App\Models\AnneeAcademique;
use App\Models\Inscription;
use App\Models\Presence;
use Carbon\Carbon;

class ProfesseurController extends Controller
{
    public function index()
    {
        $utilisateur = Auth::user();
        $professeur = $utilisateur->professeur;

        if (!$professeur) {
            return back()->with('error', 'Professeur introuvable.');
        }

        $aujourdhui = Carbon::today()->toDateString();

      
        $coursDuJour = Seance::with(['classeAnnee.classe', 'matiere', 'typeCours'])
            ->where('professeur_id', $professeur->id)
            ->whereDate('date', $aujourdhui)
            ->orderBy('heure_debut')
            ->get();

     
        $presencesAEnregistrer = Seance::where('professeur_id', $professeur->id)
            ->whereDate('date', $aujourdhui)
            ->whereDoesntHave('presences')
            ->count();


        $matiereIds = ProfesseurMatiere::where('professeur_id', $professeur->id)->pluck('matiere_id');
$matieres = Matiere::whereIn('id', $matiereIds)
    ->orderBy('nom')
    ->paginate(5); 



        
$alertes = [];
$annee = AnneeAcademique::where('est_active', true)->first();

foreach ($matieres as $matiere) {
    $seanceIds = Seance::where('matiere_id', $matiere->id)
        ->where('professeur_id', $professeur->id)
        ->whereHas('classeAnnee', function ($query) use ($annee) {
            $query->where('annee_academique_id', $annee->id);
        })
        ->pluck('id');

    $totalSeances = count($seanceIds);
    if ($totalSeances === 0) continue;

    $classeIds = Seance::whereIn('id', $seanceIds)
        ->pluck('classe_annee_id')
        ->unique();

    foreach ($classeIds as $classeId) {
        $etudiants = Inscription::where('classe_annee_id', $classeId)
            ->with('etudiant.user')
            ->get();

        foreach ($etudiants as $inscription) {
            $etudiant = $inscription->etudiant;

            $nbPresences = Presence::where('etudiant_id', $etudiant->id)
                ->whereIn('seance_id', $seanceIds)
                ->whereHas('statut', function ($q) {
                    $q->whereIn('nom', ['présent', 'retard']);
                })
                ->count();

            $taux = round(($nbPresences / $totalSeances) * 100, 2);

            if ($taux < 30) {
                $alertes[] = "L'étudiant <strong>{$etudiant->user->prenom} {$etudiant->user->nom}</strong> est droppé de votre matière <strong>{$matiere->nom}</strong> (Taux : {$taux}%)";
            }
        }
    }
}
        return view('professeur.dashboard', [
            'coursDuJour' => $coursDuJour,
            'presencesAEnregistrer' => $presencesAEnregistrer,
            'matieres' => $matieres,
            'alertes' => $alertes,
        ]);
    }
}
