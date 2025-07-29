<?php

namespace App\Http\Controllers\ParentSpace;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Presence;
use App\Models\AnneeAcademique;

class ParentDashboardController extends Controller
{
    public function index()
    {
     
        $parent = Auth::user()->parentProfile;

        if (!$parent) {
            return back()->with('error', 'Aucun profil parent trouvé.');
        }

    
        $enfants = $parent->enfants()->with('user')->get();

    
        $enfant = $enfants->count() === 1 ? $enfants->first() : null;

    
        Carbon::setLocale('fr');
        $date = Carbon::now()->translatedFormat('d F Y');

       
        $absencesJustifiees = 0;
        $absencesNonJustifiees = 0;

        if ($enfant) {
   
            $annee = AnneeAcademique::where('est_active', true)->first();

            if ($annee) {
                $presences = Presence::where('etudiant_id', $enfant->id)
                    ->whereHas('seance', function ($query) use ($annee) {
                        $query->whereHas('classeAnnee', function ($q) use ($annee) {
                            $q->where('annee_academique_id', $annee->id);
                        });
                    })
                    ->with('statut')
                    ->get();

             
                $absencesJustifiees = $presences->where('statut.nom', 'absent')->whereNotNull('justification')->count();
                $absencesNonJustifiees = $presences->where('statut.nom', 'absent')->whereNull('justification')->count();
            }
        }

       
        return view('parent.dashboard', compact(
            'enfants',
            'enfant',
            'date',
            'absencesJustifiees',
            'absencesNonJustifiees'
        ));
    }
}
