<?php

namespace App\Http\Controllers\Coordinateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seance;
use App\Models\ClasseAnnee;
use App\Models\Matiere;
use App\Models\Professeur;
use App\Models\TypeCours;
use App\Models\StatutSeance;
use App\Models\Trimestre;

class SeanceController extends Controller
{
    // Afficher toutes les séances avec filtres
    public function index(Request $request)
    {
        $classeChoisie = $request->input('classe_id');
        $matiereChoisie = $request->input('matiere_id');
        $trimestreChoisi = $request->input('trimestre_id');
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');

        $seancesQuery = Seance::with([
            'classeAnnee.classe',
            'matiere',
            'professeur.user',
            'typeCours',
            'statut',
            'trimestre'
        ])->orderByDesc('date');

        if ($classeChoisie) {
            $seancesQuery->where('classe_annee_id', $classeChoisie);
        }

        if ($matiereChoisie) {
            $seancesQuery->where('matiere_id', $matiereChoisie);
        }

        if ($trimestreChoisi) {
            $seancesQuery->where('trimestre_id', $trimestreChoisi);
        }

        if ($dateDebut) {
            $seancesQuery->whereDate('date', '>=', $dateDebut);
        }

        if ($dateFin) {
            $seancesQuery->whereDate('date', '<=', $dateFin);
        }

        $seances = $seancesQuery->get();

        $listeClasses = ClasseAnnee::with('classe', 'anneeAcademique')->get();
        $listeMatieres = Matiere::orderBy('nom')->get();
        $listeTrimestres = Trimestre::orderBy('date_debut')->get();

        return view('coordinateur.seances.index', [
            'seances' => $seances,
            'classes' => $listeClasses,
            'matieres' => $listeMatieres,
            'trimestres' => $listeTrimestres,
        ]);
    }

    // Afficher le formulaire de création
    public function create()
    {
        $listeClasses = ClasseAnnee::with('classe', 'anneeAcademique')->get();
        $listeMatieres = Matiere::orderBy('nom')->get();
        $listeProfesseurs = Professeur::with('user')->get();
        $listeTypes = TypeCours::all();
        $listeStatuts = StatutSeance::all();
        $listeTrimestres = Trimestre::orderBy('date_debut')->get();

        return view('coordinateur.seances.create', [
            'classes' => $listeClasses,
            'matieres' => $listeMatieres,
            'professeurs' => $listeProfesseurs,
            'types' => $listeTypes,
            'statuts' => $listeStatuts,
            'trimestres' => $listeTrimestres,
        ]);
    }

    // Enregistrer une nouvelle séance
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'jour_semaine' => 'required|string',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
            'classe_annee_id' => 'required|exists:classe_annee,id',
            'matiere_id' => 'required|exists:matieres,id',
            'professeur_id' => 'required|exists:professeurs,id',
            'type_cours_id' => 'required|exists:types_cours,id',
            'statut_seance_id' => 'required|exists:statuts_seance,id',
            'trimestre_id' => 'required|exists:trimestres,id',
        ]);

        Seance::create($request->all());

        return redirect()->route('coordinateur.seances.index')->with('success', 'Séance créée avec succès.');
    }

    // Formulaire d'édition
    public function edit(Seance $seance)
    {
        $listeClasses = ClasseAnnee::with('classe', 'anneeAcademique')->get();
        $listeMatieres = Matiere::orderBy('nom')->get();
        $listeProfesseurs = Professeur::with('user')->get();
        $listeTypes = TypeCours::all();
        $listeStatuts = StatutSeance::all();
        $listeTrimestres = Trimestre::orderBy('date_debut')->get();

        return view('coordinateur.seances.edit', [
            'seance' => $seance,
            'classes' => $listeClasses,
            'matieres' => $listeMatieres,
            'professeurs' => $listeProfesseurs,
            'types' => $listeTypes,
            'statuts' => $listeStatuts,
            'trimestres' => $listeTrimestres,
        ]);
    }

    // Enregistrer la mise à jour
    public function update(Request $request, Seance $seance)
    {
        $request->validate([
            'date' => 'required|date',
            'jour_semaine' => 'required|string',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
            'classe_annee_id' => 'required|exists:classe_annee,id',
            'matiere_id' => 'required|exists:matieres,id',
            'professeur_id' => 'required|exists:professeurs,id',
            'type_cours_id' => 'required|exists:types_cours,id',
            'statut_seance_id' => 'required|exists:statuts_seance,id',
            'trimestre_id' => 'required|exists:trimestres,id',
        ]);

        $seance->update($request->all());

        return redirect()->route('coordinateur.seances.index')->with('success', 'Séance mise à jour avec succès.');
    }

    // Supprimer une séance
    public function destroy(Seance $seance)
    {
        $seance->delete();

        return redirect()->route('coordinateur.seances.index')->with('success', 'Séance supprimée.');
    }
}
