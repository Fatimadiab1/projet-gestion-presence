<?php

namespace App\Http\Controllers\ParentSpace;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AnneeAcademique;
use App\Models\Trimestre;
use App\Models\Matiere;
use App\Models\Presence;
use App\Models\Seance;
use App\Models\Etudiant;

class ParentCalculController extends Controller
{
    public function assiduite(Request $request)
    {
        $parent = Auth::user()->parentProfile;

        
        if (!$parent) {
            return back()->with('error', 'Profil parent introuvable.');
        }

 
        $enfants = $parent->enfants;

        if (!$enfants || $enfants->isEmpty()) {
            return back()->with('error', 'Aucun enfant associé à ce compte parent.');
        }

      
        $annee = AnneeAcademique::where('est_active', true)->first();
        $trimestres = Trimestre::where('annee_academique_id', $annee->id)->get();
        $matieres = Matiere::all();

     
        $enfantId = $request->enfant_id ?? ($enfants->count() === 1 ? $enfants->first()->id : null);
        $matiereId = $request->matiere_id;
        $trimestreId = $request->trimestre_id;

        $resultats = [];

        if ($enfantId && $matiereId) {
       
            $seances = Seance::where('matiere_id', $matiereId)
                ->whereHas('classeAnnee.inscriptions', function ($query) use ($enfantId) {
                    $query->where('etudiant_id', $enfantId);
                });

            if ($trimestreId) {
                $seances->where('trimestre_id', $trimestreId);
            }

            $seanceIds = $seances->pluck('id');
            $nbSeances = $seanceIds->count();

       
            $nbPresences = Presence::where('etudiant_id', $enfantId)
                ->whereIn('seance_id', $seanceIds)
                ->whereHas('statut', function ($q) {
                    $q->where('nom', 'présent');
                })
                ->count();

      
            $note = $nbSeances > 0 ? round(($nbPresences / $nbSeances) * 20, 2) : null;

            $etudiant = Etudiant::with('user')->find($enfantId);

            $resultats[] = [
                'nom' => $etudiant->user->nom,
                'prenom' => $etudiant->user->prenom,
                'nb_presences' => $nbPresences,
                'nb_seances' => $nbSeances,
                'note' => $note,
            ];
        }

        return view('parent.assiduite', compact(
            'enfants',
            'enfantId',
            'matieres',
            'matiereId',
            'trimestres',
            'trimestreId',
            'resultats'
        ));
    }
}
