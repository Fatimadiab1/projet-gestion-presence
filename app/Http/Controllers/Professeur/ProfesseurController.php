<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Seance;
use App\Models\Matiere;
use App\Models\ProfesseurMatiere;
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

        $matieres = Matiere::whereIn('id', $matiereIds)->get();

        return view('professeur.dashboard', [
            'coursDuJour' => $coursDuJour,
            'presencesAEnregistrer' => $presencesAEnregistrer,
            'matieres' => $matieres,
        ]);
    }
}
