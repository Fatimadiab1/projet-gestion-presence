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
    // Affiche la liste des classes
   
    public function index(Request $request)
    {

        $user = Auth::user();
        $coordinateur = $user->coordinateur;

    
        $annee = AnneeAcademique::where('est_active', true)->first();

        
        $classes = ClasseAnnee::with(['classe', 'anneeAcademique'])
            ->where('coordinateur_id', $coordinateur->id)
            ->where('annee_academique_id', $annee->id)
            ->orderBy('classe_id', 'asc')
            ->get();

    
        $classeId = $request->input('classe_id');

  
        $etudiants = Inscription::with(['etudiant.user', 'classeAnnee.classe', 'classeAnnee.anneeAcademique'])
            ->whereHas('classeAnnee', function ($query) use ($coordinateur, $annee) {
                $query->where('coordinateur_id', $coordinateur->id)
                      ->where('annee_academique_id', $annee->id);
            });

        if (!empty($classeId)) {
            $etudiants = $etudiants->where('classe_annee_id', $classeId);
        }

   
        $etudiants = $etudiants->orderBy('id', 'desc')->get();

        return view('coordinateur.classes.index', [
            'classes' => $classes,
            'etudiants' => $etudiants,
            'classeId' => $classeId,
        ]);
    }
}
