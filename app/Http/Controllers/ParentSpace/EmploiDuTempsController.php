<?php

namespace App\Http\Controllers\ParentSpace;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Seance;
use App\Models\Inscription;
use Carbon\Carbon;

class EmploiDuTempsController extends Controller
{
    public function index(Request $request)
    {
    $parent = Auth::user()->parentProfile;

        if (!$parent) {
            return back()->with('error', 'Profil parent introuvable.');
        }

        $enfants = $parent->enfants()->with('user')->get();

        if ($enfants->isEmpty()) {
            return back()->with('error', 'Aucun enfant associé à ce compte.');
        }

    
        $enfant = $enfants->first();

        $seances = [];

        $inscription = Inscription::where('etudiant_id', $enfant->id)->latest()->first();

        if ($inscription) {
            $debut = $request->date_debut
                ? Carbon::parse($request->date_debut)->startOfDay()
                : Carbon::now()->startOfWeek();

            $fin = $request->date_fin
                ? Carbon::parse($request->date_fin)->endOfDay()
                : Carbon::now()->endOfWeek();

            $seances = Seance::with(['classeAnnee.classe', 'matiere', 'typeCours'])
                ->where('classe_annee_id', $inscription->classe_annee_id)
                ->whereBetween('date', [$debut, $fin])
                ->orderByDesc('date')
                ->get();
        }

        return view('parent.emploi', compact('seances', 'enfant'));
    }
}
