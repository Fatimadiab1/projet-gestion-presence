<?php

namespace App\Http\Controllers\ParentSpace;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Presence;
use App\Models\Trimestre;
use App\Models\Inscription;

class AbsenceController extends Controller
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

       
        $enfant = $enfants->count() === 1 ? $enfants->first() : null;
        $enfantId = $request->enfant_id ?? ($enfant ? $enfant->id : null);

        if (!$enfantId) {
            return view('parent.absences', [
                'enfants' => $enfants,
                'enfantId' => null,
                'trimestres' => [],
                'trimestreId' => null,
                'absencesNonJustifiees' => [],
                'absencesJustifiees' => [],
                'presencesNormales' => []
            ]);
        }

        $trimestres = Trimestre::with('anneeAcademique')
            ->orderBy('date_debut', 'desc')
            ->get();

        $trimestreId = $request->trimestre_id ?? $trimestres->first()->id ?? null;

        if (!$trimestreId) {
            return back()->with('error', 'Aucun trimestre sélectionné.');
        }

        $trimestre = $trimestres->where('id', $trimestreId)->first();
        $dateDebut = $trimestre->date_debut;
        $dateFin = $trimestre->date_fin;

     
        $inscription = Inscription::where('etudiant_id', $enfantId)->latest()->first();

        if (!$inscription) {
            return back()->with('error', 'Aucune inscription trouvée pour cet enfant.');
        }

     
        $presences = Presence::with(['seance.matiere', 'seance.typeCours', 'seance', 'justification'])
            ->where('etudiant_id', $enfantId)
            ->whereHas('seance', function ($query) use ($dateDebut, $dateFin, $inscription) {
                $query->whereBetween('date', [$dateDebut, $dateFin])
                      ->where('classe_annee_id', $inscription->classe_annee_id);
            })
            ->get();

 
        $absencesNonJustifiees = $presences->where('statut_presence_id', 3)->filter(function ($p) {
            return $p->justification === null;
        });

        $absencesJustifiees = $presences->where('statut_presence_id', 3)->filter(function ($p) {
            return $p->justification !== null;
        });

        $presencesNormales = $presences->where('statut_presence_id', 1);

        return view('parent.absences', [
            'enfants' => $enfants,
            'enfantId' => $enfantId,
            'trimestres' => $trimestres,
            'trimestreId' => $trimestreId,
            'absencesNonJustifiees' => $absencesNonJustifiees,
            'absencesJustifiees' => $absencesJustifiees,
            'presencesNormales' => $presencesNormales
        ]);
    }
}
