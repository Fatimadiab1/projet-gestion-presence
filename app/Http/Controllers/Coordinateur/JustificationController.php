<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Justification;
use App\Models\Presence;
use App\Models\ClasseAnnee;
use App\Models\Seance;

class JustificationController extends Controller
{
    // Liste des absences 
    public function index(Request $request)
    {
        $coordinateurId = Auth::user()->coordinateur->id;
        $classes = ClasseAnnee::where('coordinateur_id', $coordinateurId)->pluck('id');
        $seances = Seance::whereIn('classe_annee_id', $classes)->pluck('id');

        $absences = Presence::with(['etudiant.user', 'seance.matiere', 'justification', 'statut'])
            ->whereIn('seance_id', $seances)
            ->whereHas('statut', function ($statut) {
                $statut->where('nom', 'Absent');
            });

        if ($request->justifie === 'oui') {
            $absences->has('justification');
        } elseif ($request->justifie === 'non') {
            $absences->doesntHave('justification');
        }

        $absences = $absences->get();

        return view('coordinateur.justifications.index', compact('absences'));
    }

    // Créer une justification
    public function create($presenceId)
    {
        $presence = Presence::with(['etudiant.user', 'seance.matiere'])->findOrFail($presenceId);

        if ($presence->statut?->nom !== 'Absent') {
            return redirect()->back()->with('error', 'Seules les absences peuvent être justifiées.');
        }

        if ($presence->justification) {
            return redirect()->back()->with('error', 'Cette absence est déjà justifiée.');
        }

        return view('coordinateur.justifications.create', compact('presence'));
    }

    // Enregistrer la justification
    public function store(Request $request)
    {
        $request->validate([
            'presence_id' => 'required|exists:presences,id',
            'raison' => 'required|string|max:1000',
        ]);

        $presence = Presence::findOrFail($request->presence_id);

        if ($presence->justification) {
            return redirect()->back()->with('error', 'Cette absence est déjà justifiée.');
        }

        Justification::create([
            'presence_id' => $presence->id,
            'raison' => $request->raison,
            'date_justif' => now(),
            'modifie_par' => Auth::id(),
        ]);

        return redirect()->route('coordinateur.justifications.index')->with('success', 'Justification enregistrée avec succès.');
    }

    // Modifier une justification
    public function edit($id)
    {
        $justification = Justification::with(['presence.etudiant.user', 'presence.seance.matiere'])->findOrFail($id);
        return view('coordinateur.justifications.edit', compact('justification'));
    }

    // Mise à jour
    public function update(Request $request, $id)
    {
        $request->validate([
            'raison' => 'required|string|max:1000',
        ]);

        $justification = Justification::findOrFail($id);
        $justification->update([
            'raison' => $request->raison,
            'modifie_par' => Auth::id(),
        ]);

        return redirect()->route('coordinateur.justifications.index')->with('success', 'Justification mise à jour avec succès.');
    }
}
