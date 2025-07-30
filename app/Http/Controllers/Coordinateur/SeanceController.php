<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Seance;
use App\Models\Matiere;
use App\Models\TypeCours;
use App\Models\Trimestre;
use App\Models\ClasseAnnee;
use App\Models\StatutSeance;
use App\Models\AnneeAcademique;
use Carbon\Carbon;

class SeanceController extends Controller
{
    // Liste des séances
    public function index(Request $request)
    {
        $user = Auth::user();
        $coordinateur = $user->coordinateur;
        $annee = AnneeAcademique::where('est_active', true)->first();

        if (!$coordinateur || !$annee) {
            return redirect()->back()->with('error', 'Coordonnateur ou année introuvable.');
        }

        $classes = ClasseAnnee::where('coordinateur_id', $coordinateur->id)
            ->where('annee_academique_id', $annee->id)
            ->with('classe')
            ->get();

        $classeIds = $classes->pluck('id');

        $matieres = Matiere::whereHas('seances', function ($query) use ($classeIds) {
            $query->whereIn('classe_annee_id', $classeIds);
        })->get();

        $statuts = StatutSeance::orderBy('nom')->get();

        $seances = Seance::with(['classeAnnee.classe', 'matiere', 'professeur.user', 'typeCours', 'statutSeance'])
            ->whereHas('classeAnnee', function ($query) use ($coordinateur, $annee) {
                $query->where('coordinateur_id', $coordinateur->id)
                      ->where('annee_academique_id', $annee->id);
            })
            ->when($request->classe_id, function ($query) use ($request) {
                $query->where('classe_annee_id', $request->classe_id);
            })
            ->when($request->matiere_id, function ($query) use ($request) {
                $query->where('matiere_id', $request->matiere_id);
            })
            ->when($request->date, function ($query) use ($request) {
                $query->whereDate('date', $request->date);
            })
            ->when($request->statut_id, function ($query) use ($request) {
                $query->where('statut_seance_id', $request->statut_id);
            })
            ->orderByDesc('date')
            ->paginate(10); 

        return view('coordinateur.seances.index', compact('seances', 'classes', 'matieres', 'statuts'));
    }

    // Creer une séance
    public function create()
    {
        $coordinateurId = Auth::user()->coordinateur->id;
        $annee = AnneeAcademique::where('est_active', true)->first();

        $classes = ClasseAnnee::where('coordinateur_id', $coordinateurId)
            ->where('annee_academique_id', $annee->id)
            ->with('classe')
            ->get();

        $matieres = Matiere::with('professeurs.user')->orderBy('nom')->get();
        $types = TypeCours::orderBy('nom')->get();
        $trimestres = Trimestre::orderBy('date_debut')->get();

        return view('coordinateur.seances.create', compact('classes', 'matieres', 'types', 'trimestres'));
    }

    // Enregistrer une séance
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
            'classe_annee_id' => 'required|exists:classe_annee,id',
            'matiere_id' => 'required|exists:matieres,id',
            'type_cours_id' => 'required|exists:types_cours,id',
            'trimestre_id' => 'required|exists:trimestres,id',
            'professeur_id' => 'nullable|exists:professeurs,id',
        ]);

        $trimestre = Trimestre::find($request->trimestre_id);
        $date = Carbon::parse($request->date);

        if (!$trimestre || $date->lt($trimestre->date_debut) || $date->gt($trimestre->date_fin)) {
            return back()->with('error', 'La date ne correspond pas à la période du trimestre.');
        }

        $jour = ucfirst($date->locale('fr')->dayName);
        $profId = in_array($request->type_cours_id, [3, 4]) ? null : $request->professeur_id;
        $statut = StatutSeance::where('nom', 'Prévue')->first();

        if (!$statut) {
            return back()->with('error', 'Statut "Prévue" introuvable.');
        }

        Seance::create([
            'date' => $request->date,
            'jour_semaine' => $jour,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'classe_annee_id' => $request->classe_annee_id,
            'matiere_id' => $request->matiere_id,
            'professeur_id' => $profId,
            'type_cours_id' => $request->type_cours_id,
            'statut_seance_id' => $statut->id,
            'trimestre_id' => $request->trimestre_id,
        ]);

        return redirect()->route('coordinateur.seances.index')->with('success', 'Séance créée.');
    }

    // Modifier une séance
    public function edit(Seance $seance)
    {
        $classes = ClasseAnnee::with('classe')->get();
        $matieres = Matiere::with('professeurs.user')->orderBy('nom')->get();
        $types = TypeCours::orderBy('nom')->get();
        $trimestres = Trimestre::orderBy('date_debut')->get();
        $professeurs = $seance->matiere->professeurs()->with('user')->get();

        return view('coordinateur.seances.edit', compact('seance', 'classes', 'matieres', 'types', 'trimestres', 'professeurs'));
    }

    // Mettre à jour une séance
    public function update(Request $request, Seance $seance)
    {
        $request->validate([
            'date' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
            'classe_annee_id' => 'required|exists:classe_annee,id',
            'matiere_id' => 'required|exists:matieres,id',
            'type_cours_id' => 'required|exists:types_cours,id',
            'trimestre_id' => 'required|exists:trimestres,id',
            'professeur_id' => 'nullable|exists:professeurs,id',
        ]);

        $trimestre = Trimestre::find($request->trimestre_id);
        $date = Carbon::parse($request->date);

        if (!$trimestre || $date->lt($trimestre->date_debut) || $date->gt($trimestre->date_fin)) {
            return back()->with('error', 'La date ne correspond pas à la période du trimestre.');
        }

        $jour = ucfirst($date->locale('fr')->dayName);
        $profId = in_array($request->type_cours_id, [3, 4]) ? null : $request->professeur_id;

        $seance->update([
            'date' => $request->date,
            'jour_semaine' => $jour,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'classe_annee_id' => $request->classe_annee_id,
            'matiere_id' => $request->matiere_id,
            'professeur_id' => $profId,
            'type_cours_id' => $request->type_cours_id,
            'trimestre_id' => $request->trimestre_id,
        ]);

        return redirect()->route('coordinateur.seances.index')->with('success', 'Séance mise à jour.');
    }

    // Annuler une séance
    public function annuler(Seance $seance)
    {
        $statut = StatutSeance::where('nom', 'Annulée')->first();

        if (!$statut) {
            return back()->with('error', 'Statut "Annulée" introuvable.');
        }

        $seance->update(['statut_seance_id' => $statut->id]);

        return redirect()->route('coordinateur.seances.index')->with('success', 'Séance annulée.');
    }

    // Formulaire de report
    public function formulaireReport(Seance $seance)
    {
        return view('coordinateur.seances.report', compact('seance'));
    }

    // Enregistrer un report
    public function enregistrerReport(Request $request, Seance $seance)
    {
        $request->validate([
            'date' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
        ]);

        $statutReportee = StatutSeance::where('nom', 'Reportée')->first();
        $statutPrevue = StatutSeance::where('nom', 'Prévue')->first();

        if (!$statutReportee || !$statutPrevue) {
            return back()->with('error', 'Statuts introuvables.');
        }

        $seance->update(['statut_seance_id' => $statutReportee->id]);

        Seance::create([
            'date' => $request->date,
            'jour_semaine' => ucfirst(Carbon::parse($request->date)->locale('fr')->dayName),
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'classe_annee_id' => $seance->classe_annee_id,
            'matiere_id' => $seance->matiere_id,
            'professeur_id' => $seance->professeur_id,
            'type_cours_id' => $seance->type_cours_id,
            'statut_seance_id' => $statutPrevue->id,
            'trimestre_id' => $seance->trimestre_id,
            'seance_reportee_de_id' => $seance->id,
        ]);

        return redirect()->route('coordinateur.seances.index')->with('success', 'Séance reportée.');
    }

    // Supprimer une séance
    public function destroy(Seance $seance)
    {
        $statut = StatutSeance::where('nom', 'Annulée')->first();

        if ($seance->statut_seance_id !== $statut?->id) {
            return back()->with('error', 'Seules les séances annulées peuvent être supprimées.');
        }

        $seance->delete();

        return redirect()->route('coordinateur.seances.index')->with('success', 'Séance supprimée.');
    }
}
