<?php

namespace App\Http\Controllers\EtudiantSpace;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AnneeAcademique;
use App\Models\Trimestre;
use App\Models\Matiere;
use App\Models\Seance;
use App\Models\Presence;

class EtudiantAssiduiteController extends Controller
{
    public function index(Request $request)
    {
        // Étudiant connecté
        $etudiant = Auth::user()->etudiant;

        // Année académique active
        $annee = AnneeAcademique::where('est_active', true)->first();

        // Listes des trimestres de l’année active et de toutes les matières
        $trimestres = Trimestre::where('annee_academique_id', $annee->id)->get();
        $matieres = Matiere::all();

        // Valeurs des filtres
        $matiereId = $request->matiere_id;
        $trimestreId = $request->trimestre_id;

        $resultats = [];

        // Si l’étudiant a choisi une matière
        if ($matiereId) {
            // On récupère les séances liées à cette matière et à sa classe
            $seances = Seance::where('matiere_id', $matiereId)
                ->whereHas('classeAnnee.inscriptions', function ($query) use ($etudiant) {
                    $query->where('etudiant_id', $etudiant->id);
                });

            // Si un trimestre est sélectionné, on filtre aussi
            if ($trimestreId) {
                $seances->where('trimestre_id', $trimestreId);
            }

            $seanceIds = $seances->pluck('id');
            $nbSeances = $seanceIds->count();

            // Présences de l’étudiant sur ces séances
            $nbPresences = Presence::where('etudiant_id', $etudiant->id)
                ->whereIn('seance_id', $seanceIds)
                ->whereHas('statut', function ($q) {
                    $q->where('nom', 'présent');
                })
                ->count();

            // Calcul de la note
            $note = $nbSeances > 0 ? round(($nbPresences / $nbSeances) * 20, 2) : null;

            $resultats[] = [
                'prenom' => Auth::user()->prenom,
                'nom' => Auth::user()->nom,
                'nb_presences' => $nbPresences,
                'nb_seances' => $nbSeances,
                'note' => $note,
            ];
        }

        return view('etudiant.assiduite', compact(
            'trimestres',
            'matieres',
            'matiereId',
            'trimestreId',
            'resultats'
        ));
    }
}
