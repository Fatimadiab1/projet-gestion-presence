<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClasseAnnee;
use App\Models\Inscription;
use App\Models\AnneeAcademique;

class AfficherClasseController extends Controller
{
    
    public function index(Request $request)
    {
        $utilisateur = Auth::user();
        $coordinateur = $utilisateur->coordinateur;
        $annee = AnneeAcademique::where('est_active', true)->first();

        $classes = ClasseAnnee::with(['classe', 'anneeAcademique'])
            ->where('coordinateur_id', $coordinateur->id)
            ->where('annee_academique_id', $annee->id)
            ->get();

        $classeId = $request->input('classe_id');

        $etudiants = Inscription::with(['etudiant.user', 'classeAnnee.classe', 'classeAnnee.anneeAcademique'])
            ->whereHas('classeAnnee', function ($filtre) use ($coordinateur, $annee) {
                $filtre->where('coordinateur_id', $coordinateur->id)
                       ->where('annee_academique_id', $annee->id);
            })
            ->when($classeId, function ($filtre) use ($classeId) {
                $filtre->where('classe_annee_id', $classeId);
            })
            ->get();

        return view('coordinateur.classes.index', [
            'classes' => $classes,
            'etudiants' => $etudiants,
        ]);
    }
}
