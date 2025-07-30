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
        $message = null;

        if (!$parent) {
            $message = 'Profil parent introuvable.';
            return view('parent.assiduite', compact('message'));
        }

        $enfants = $parent->enfants()->with('user')->get();
        if ($enfants->isEmpty()) {
            $message = 'Aucun enfant associé à ce compte parent.';
            return view('parent.assiduite', compact('message'));
        }

        $annee      = AnneeAcademique::where('est_active', true)->first();
        $trimestres = $annee ? Trimestre::where('annee_academique_id', $annee->id)->get() : collect();
        $matieres   = Matiere::all();

        /* Filtres */
        $enfantId    = $request->input('enfant_id') ?: ($enfants->count() === 1 ? $enfants->first()->id : null);
        $matiereId   = $request->input('matiere_id');
        $trimestreId = $request->input('trimestre_id');

        $resultats = [];

        if ($enfantId && $matiereId) {
            /* a) Séances concernées */
            $seances = Seance::where('matiere_id', $matiereId)
                ->whereHas('classeAnnee.inscriptions', function ($q) use ($enfantId) {
                    $q->where('etudiant_id', $enfantId);
                });

            if ($trimestreId) {
                $seances->where('trimestre_id', $trimestreId);
            }

            $seanceIds  = $seances->pluck('id');
            $nbSeances  = $seanceIds->count();

            /* b) Présences de l’élève */
            $nbPresences = Presence::where('etudiant_id', $enfantId)
                ->whereIn('seance_id', $seanceIds)
                ->whereHas('statut', fn ($q) => $q->where('nom', 'présent'))
                ->count();

            $note     = $nbSeances ? round($nbPresences / $nbSeances * 20, 2) : null;
            $etudiant = Etudiant::with('user')->find($enfantId);

            $resultats[] = [
                'nom'          => $etudiant?->user?->nom,
                'prenom'       => $etudiant?->user?->prenom,
                'nb_presences' => $nbPresences,
                'nb_seances'   => $nbSeances,
                'note'         => $note,
            ];
        }

        return view('parent.assiduite', compact(
            'enfants',
            'enfantId',
            'matieres',
            'matiereId',
            'trimestres',
            'trimestreId',
            'resultats',
            'message'
        ));
    }
}
