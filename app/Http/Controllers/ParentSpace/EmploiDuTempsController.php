<?php

namespace App\Http\Controllers\ParentSpace;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Seance;

class EmploiDuTempsController extends Controller
{
    public function index(Request $request)
    {

        $parent = Auth::user()->parentProfile;

     
        $message  = null;
        $seances  = [];
        $enfant   = null;
        $enfants  = collect();

        if (!$parent) {
            $message = 'Profil parent introuvable.';
            return view('parent.emploi', compact('seances', 'enfant', 'enfants', 'message'));
        }

     
        $enfants = $parent->enfants()->with('user')->get();

        if ($enfants->isEmpty()) {
            $message = 'Aucun enfant associé à ce compte.';
            return view('parent.emploi', compact('seances', 'enfant', 'enfants', 'message'));
        }

      
        $enfantId = $request->input('enfant_id');
        $enfant   = $enfants->where('id', $enfantId)->first() ?: $enfants->first();

               $debut = $request->input('date_debut')
            ? Carbon::parse($request->input('date_debut'))->startOfDay()
            : Carbon::now()->startOfWeek();

        $fin = $request->input('date_fin')
            ? Carbon::parse($request->input('date_fin'))->endOfDay()
            : Carbon::now()->endOfWeek();

        $inscription = $enfant->inscriptions()->latest()->first();

        if (!$inscription) {
            $message = 'Aucune inscription trouvée pour cet enfant.';
            return view('parent.emploi', compact('seances', 'enfant', 'enfants', 'message', 'debut', 'fin'));
        }

      
        $seances = Seance::with(['classeAnnee.classe', 'matiere', 'typeCours'])
            ->where('classe_annee_id', $inscription->classe_annee_id)
            ->whereBetween('date', [$debut, $fin])
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->get();

        return view('parent.emploi', compact('seances', 'enfant', 'enfants', 'message', 'debut', 'fin'));
    }
}
