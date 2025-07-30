<?php

namespace App\Http\Controllers\EtudiantSpace;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Presence;
use App\Models\Matiere;
use App\Models\Seance;

class EtudiantController extends Controller
{
    public function index()
    {
        $etudiant = Auth::user()->etudiant;

        if (!$etudiant) {
            return back()->with('error', 'Profil étudiant introuvable.');
        }

        Carbon::setLocale('fr');
        $date = Carbon::now()->translatedFormat('d F Y');

        // Calcul des absences
        $presences = Presence::where('etudiant_id', $etudiant->id)
            ->with(['statut', 'seance.classeAnnee'])
            ->get();

        $absencesJustifiees = $presences->where('statut.nom', 'absent')
                                        ->whereNotNull('justification')
                                        ->count();

        $absencesNonJustifiees = $presences->where('statut.nom', 'absent')
                                           ->whereNull('justification')
                                           ->count();

        // Calcul des alertes (drop si taux < 30%)
        $alertes = [];

        $inscriptions = $etudiant->inscriptions()->with('classeAnnee')->get();

        foreach ($inscriptions as $inscription) {
            $classeId = $inscription->classe_annee_id;

            $matieres = Matiere::all(); 

            foreach ($matieres as $matiere) {
                $seanceIds = Seance::where('classe_annee_id', $classeId)
                    ->where('matiere_id', $matiere->id)
                    ->pluck('id');

                $total = count($seanceIds);
                if ($total === 0) continue;

                $nbPresences = Presence::where('etudiant_id', $etudiant->id)
                    ->whereIn('seance_id', $seanceIds)
                    ->whereHas('statut', function ($q) {
                        $q->whereIn('nom', ['présent', 'retard']);
                    })
                    ->count();

                $taux = round(($nbPresences / $total) * 100, 2);

                if ($taux < 30) {
                    $alertes[] = "Tu as été droppé(e) de la matière <strong>{$matiere->nom}</strong> (Taux : {$taux}%)";
                }
            }
        }

        return view('etudiant.dashboard', compact(
            'etudiant',
            'date',
            'absencesJustifiees',
            'absencesNonJustifiees',
            'alertes'
        ));
    }
}
