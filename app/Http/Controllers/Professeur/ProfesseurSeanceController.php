<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Seance;
use App\Models\Matiere;

class ProfesseurSeanceController extends Controller
{
    public function index(Request $request)
    {
    
        $professeur = Auth::user()->professeur;

       
        $matieres = Matiere::whereHas('professeurs', function ($relation) use ($professeur) {
            $relation->where('professeur_id', $professeur->id);
        })->get();

    
        $seances = Seance::with(['classeAnnee.classe', 'matiere', 'typeCours'])
            ->where('professeur_id', $professeur->id);

    
        if (!empty($request->date)) {
            $seances = $seances->whereDate('date', $request->date);
        }

        
        if (!empty($request->matiere_id)) {
            $seances = $seances->where('matiere_id', $request->matiere_id);
        }

 
        $seances = $seances->orderByDesc('date')->get();

        return view('professeur.seances.index', [
            'seances' => $seances,
            'matieres' => $matieres,
        ]);
    }
}
