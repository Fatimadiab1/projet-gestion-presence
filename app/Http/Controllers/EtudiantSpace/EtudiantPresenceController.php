<?php

namespace App\Http\Controllers\EtudiantSpace;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Presence;
use App\Models\AnneeAcademique;
use App\Models\Trimestre;
use App\Models\Seance;

class EtudiantPresenceController extends Controller
{
public function index()
{
    $etudiant = Auth::user()->etudiant;
    $annee = AnneeAcademique::where('est_active', true)->first();

    $trimestres = Trimestre::where('annee_academique_id', $annee->id)->get();
    $trimestreId = request('trimestre_id') ?? $trimestres->first()?->id;

    $seancesIds = Seance::whereHas('classeAnnee.inscriptions', function ($query) use ($etudiant) {
        $query->where('etudiant_id', $etudiant->id);
    })
    ->whereHas('classeAnnee', function ($q) use ($annee) {
        $q->where('annee_academique_id', $annee->id);
    })
    ->where('trimestre_id', $trimestreId) // on filtre les séances du trimestre sélectionné
    ->pluck('id');

    $totalSeances = $seancesIds->count();

    $presences = Presence::where('etudiant_id', $etudiant->id)
        ->whereIn('seance_id', $seancesIds)
        ->with('statut', 'seance.matiere', 'seance.typeCours', 'justification')
        ->get();

    $nbPresences = $presences->where('statut.nom', 'présent')->count();
    $nbJustifiees = $presences->where('statut.nom', 'absent')->where('justifie', true)->count();
    $nbNonJustifiees = $presences->where('statut.nom', 'absent')->where('justifie', false)->count();

    $presencesNormales = $presences->where('statut.nom', 'présent');
    $absencesJustifiees = $presences->where('statut.nom', 'absent')->where('justifie', true);
    $absencesNonJustifiees = $presences->where('statut.nom', 'absent')->where('justifie', false);

    return view('etudiant.presences', compact(
        'nbPresences',
        'nbJustifiees',
        'nbNonJustifiees',
        'totalSeances',
        'presencesNormales',
        'absencesJustifiees',
        'absencesNonJustifiees',
        'trimestres',
        'trimestreId'
    ));
}

}
