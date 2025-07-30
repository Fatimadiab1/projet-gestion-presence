<?php

namespace App\Http\Controllers\EtudiantSpace;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Seance;
use App\Models\Inscription;
use Carbon\Carbon;

class EtudiantEmploiController extends Controller
{
    public function index(Request $request)
    {
        $etudiant = Auth::user()->etudiant;

        if (!$etudiant) {
            return back()->with('error', 'Profil étudiant introuvable.');
        }

        $inscription = $etudiant->inscriptions()->latest()->first();

        $seances = collect(); 


        if ($inscription) {
            $debut = $request->date_debut
                ? Carbon::parse($request->date_debut)->startOfWeek()
                : Carbon::now()->startOfWeek();

            $fin = $request->date_fin
                ? Carbon::parse($request->date_fin)->endOfWeek()
                : Carbon::now()->endOfWeek();

            $seances = Seance::with(['matiere', 'typeCours', 'classeAnnee.classe', 'professeur.user'])
                ->where('classe_annee_id', $inscription->classe_annee_id)
                ->whereBetween('date', [$debut, $fin])
                ->orderBy('date')
                ->get();
        }

        return view('etudiant.emploi', compact('seances'));
    }
}
