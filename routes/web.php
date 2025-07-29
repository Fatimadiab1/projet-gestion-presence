<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ParentEtudiantController;
use App\Http\Controllers\Admin\AnneeAcademiqueController;
use App\Http\Controllers\Admin\TrimestreController;
use App\Http\Controllers\Admin\ClasseController;
use App\Http\Controllers\Admin\ClasseAnneeController;
use App\Http\Controllers\Admin\MatiereController;
use App\Http\Controllers\Admin\TypeCoursController;
use App\Http\Controllers\Admin\StatutSeanceController;
use App\Http\Controllers\Admin\StatutPresenceController;
use App\Http\Controllers\Admin\StatutSuiviController;
use App\Http\Controllers\Admin\InscriptionController;
use App\Http\Controllers\Admin\ProfesseurMatiereController;
use App\Http\Controllers\Admin\SuiviEtudiantController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Coordinateur\CoordinateurController;

use App\Http\Controllers\Professeur\ProfesseurPresenceController;
use App\Http\Controllers\Professeur\ProfesseurSeanceController;
use App\Http\Controllers\Professeur\ProfesseurController;

use App\Http\Controllers\Coordinateur\SeanceController;
use App\Http\Controllers\Coordinateur\PresenceController;
use App\Http\Controllers\Coordinateur\JustificationController;
use App\Http\Controllers\Coordinateur\StatistiqueController;
use App\Http\Controllers\Coordinateur\AfficherClasseController;
use App\Http\Controllers\Coordinateur\CalculController;


use App\Http\Controllers\ParentSpace\ParentDashboardController;
use App\Http\Controllers\ParentSpace\EmploiDuTempsController;
use App\Http\Controllers\ParentSpace\AbsenceController;
use App\Http\Controllers\ParentSpace\ParentCalculController;


use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsEtudiant;
use App\Http\Middleware\IsProfesseur;
use App\Http\Middleware\IsParent;
use App\Http\Middleware\IsCoordinateur;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->middleware(IsAdmin::class)->name('admin.dashboard');
    
    Route::get('/etudiant/dashboard', function () {
        return view('etudiant.dashboard');
    })->middleware(IsEtudiant::class)->name('etudiant.dashboard');
    
  Route::get('/professeur/dashboard', [ProfesseurController::class, 'index'])
    ->middleware(IsProfesseur::class)
    ->name('professeur.dashboard');

    Route::get('/parent/dashboard',[ParentDashboardController::class, 'index'
    ])->middleware(IsParent::class)->name('parent.dashboard');

    Route::get('/coordinateur/dashboard', [CoordinateurController::class, 'index'])
    ->middleware(IsCoordinateur::class)
    ->name('coordinateur.dashboard');

});

Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('parents', [ParentEtudiantController::class, 'index'])->name('parents.index');
    Route::get('parents/create', [ParentEtudiantController::class, 'create'])->name('parents.create');
    Route::post('parents', [ParentEtudiantController::class, 'store'])->name('parents.store');
    Route::get('parents/{parent}/edit', [ParentEtudiantController::class, 'edit'])->name('parents.edit');
    Route::put('parents/{parent}', [ParentEtudiantController::class, 'update'])->name('parents.update');
    Route::delete('parents/{parent}', [ParentEtudiantController::class, 'destroy'])->name('parents.destroy');

    Route::get('annees-academiques', [AnneeAcademiqueController::class, 'index'])->name('annees-academiques.index');
    Route::get('annees-academiques/create', [AnneeAcademiqueController::class, 'create'])->name('annees-academiques.create');
    Route::post('annees-academiques', [AnneeAcademiqueController::class, 'store'])->name('annees-academiques.store');
    Route::get('annees-academiques/{annee}/edit', [AnneeAcademiqueController::class, 'edit'])->name('annees-academiques.edit');
    Route::put('annees-academiques/{annee}', [AnneeAcademiqueController::class, 'update'])->name('annees-academiques.update');
    Route::delete('annees-academiques/{annee}', [AnneeAcademiqueController::class, 'destroy'])->name('annees-academiques.destroy');
    Route::patch('annees-academiques/{annee}/activer', [AnneeAcademiqueController::class, 'activer'])->name('annees-academiques.activer');

    Route::get('classes', [ClasseController::class, 'index'])->name('typeclasse.index');
    Route::get('classes/create', [ClasseController::class, 'create'])->name('typeclasse.create');
    Route::post('classes', [ClasseController::class, 'store'])->name('typeclasse.store');
    Route::get('classes/{classe}/edit', [ClasseController::class, 'edit'])->name('typeclasse.edit');
    Route::put('classes/{classe}', [ClasseController::class, 'update'])->name('typeclasse.update');
    Route::delete('classes/{classe}', [ClasseController::class, 'destroy'])->name('typeclasse.destroy');

    Route::get('matieres', [MatiereController::class, 'index'])->name('matieres.index');
    Route::get('matieres/create', [MatiereController::class, 'create'])->name('matieres.create');
    Route::post('matieres', [MatiereController::class, 'store'])->name('matieres.store');
    Route::get('matieres/{matiere}/edit', [MatiereController::class, 'edit'])->name('matieres.edit');
    Route::put('matieres/{matiere}', [MatiereController::class, 'update'])->name('matieres.update');
    Route::delete('matieres/{matiere}', [MatiereController::class, 'destroy'])->name('matieres.destroy');

    Route::get('trimestres', [TrimestreController::class, 'index'])->name('trimestres.index');
    Route::get('trimestres/create', [TrimestreController::class, 'create'])->name('trimestres.create');
    Route::post('trimestres', [TrimestreController::class, 'store'])->name('trimestres.store');
    Route::get('trimestres/{trimestre}/edit', [TrimestreController::class, 'edit'])->name('trimestres.edit');
    Route::put('trimestres/{trimestre}', [TrimestreController::class, 'update'])->name('trimestres.update');
    Route::delete('trimestres/{trimestre}', [TrimestreController::class, 'destroy'])->name('trimestres.destroy');

    Route::get('types-cours', [TypeCoursController::class, 'index'])->name('types-cours.index');
    Route::get('types-cours/create', [TypeCoursController::class, 'create'])->name('types-cours.create');
    Route::post('types-cours', [TypeCoursController::class, 'store'])->name('types-cours.store');
    Route::put('types-cours/{type}', [TypeCoursController::class, 'update'])->name('types-cours.update');
Route::get('types-cours/{type}/edit', [TypeCoursController::class, 'edit'])->name('types-cours.edit');
Route::delete('types-cours/{type}', [TypeCoursController::class, 'destroy'])->name('types-cours.destroy');

Route::get('statuts-seance', [StatutSeanceController::class, 'index'])
    ->name('statuts-seance.index');

 Route::get('statuts-presence', [StatutPresenceController::class, 'index'])->name('statuts-presence.index');

    Route::get('statuts-suivi', [StatutSuiviController::class, 'index'])->name('statuts-suivi.index');
    Route::get('statuts-suivi/create', [StatutSuiviController::class, 'create'])->name('statuts-suivi.create');
    Route::post('statuts-suivi', [StatutSuiviController::class, 'store'])->name('statuts-suivi.store');
    Route::get('statuts-suivi/{statutSuivi}/edit', [StatutSuiviController::class, 'edit'])->name('statuts-suivi.edit');
    Route::put('statuts-suivi/{statutSuivi}', [StatutSuiviController::class, 'update'])->name('statuts-suivi.update');
    Route::delete('statuts-suivi/{statutSuivi}', [StatutSuiviController::class, 'destroy'])->name('statuts-suivi.destroy');

   Route::get('professeurs-matieres', [ProfesseurMatiereController::class, 'index'])->name('professeurs-matieres.index');
Route::get('professeurs-matieres/create', [ProfesseurMatiereController::class, 'create'])->name('professeurs-matieres.create');
Route::post('professeurs-matieres', [ProfesseurMatiereController::class, 'store'])->name('professeurs-matieres.store');
Route::get('professeurs-matieres/edit/{professeur_id}/{matiere_id}', [ProfesseurMatiereController::class, 'edit'])->name('professeurs-matieres.edit');
Route::put('professeurs-matieres/update/{professeur_id}/{matiere_id}', [ProfesseurMatiereController::class, 'update'])->name('professeurs-matieres.update');

    Route::get('inscriptions', [InscriptionController::class, 'index'])->name('inscriptions.index');
    Route::get('inscriptions/create', [InscriptionController::class, 'create'])->name('inscriptions.create');
    Route::post('inscriptions', [InscriptionController::class, 'store'])->name('inscriptions.store');
    Route::get('inscriptions/{inscription}/edit', [InscriptionController::class, 'edit'])->name('inscriptions.edit');
    Route::put('inscriptions/{inscription}', [InscriptionController::class, 'update'])->name('inscriptions.update');
    Route::delete('inscriptions/{inscription}', [InscriptionController::class, 'destroy'])->name('inscriptions.destroy');
    Route::get('inscriptions/non-reinscrits', [InscriptionController::class, 'nonReinscrits'])->name('inscriptions.non_reinscrits');
Route::get('inscriptions/{etudiant}/reinscrire', [InscriptionController::class, 'reinscrire'])->name('inscriptions.reinscrire');
Route::post('inscriptions/{etudiant}/reinscrire', [InscriptionController::class, 'reinscrireStore'])->name('inscriptions.reinscrire.store');



   Route::get('annees-classes', [ClasseAnneeController::class, 'index'])->name('classes.index');
Route::get('annees-classes/create', [ClasseAnneeController::class, 'create'])->name('classes.create');
Route::post('annees-classes', [ClasseAnneeController::class, 'store'])->name('classes.store');
Route::get('annees-classes/{classeAnnee}/edit', [ClasseAnneeController::class, 'edit'])->name('classes.edit');
Route::put('annees-classes/{classeAnnee}', [ClasseAnneeController::class, 'update'])->name('classes.update');
Route::delete('annees-classes/{classeAnnee}', [ClasseAnneeController::class, 'destroy'])->name('classes.destroy');

    Route::get('suivi-etudiants', [SuiviEtudiantController::class, 'index'])->name('suivi-etudiants.index');
    Route::get('suivi-etudiants/create', [SuiviEtudiantController::class, 'create'])->name('suivi-etudiants.create');
    Route::post('suivi-etudiants', [SuiviEtudiantController::class, 'store'])->name('suivi-etudiants.store');
    Route::get('suivi-etudiants/{suiviEtudiant}/edit', [SuiviEtudiantController::class, 'edit'])->name('suivi-etudiants.edit');
    Route::put('suivi-etudiants/{suiviEtudiant}', [SuiviEtudiantController::class, 'update'])->name('suivi-etudiants.update');
    Route::delete('suivi-etudiants/{suiviEtudiant}', [SuiviEtudiantController::class, 'destroy'])->name('suivi-etudiants.destroy');

    Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');
});

// ...

Route::middleware(['auth', IsCoordinateur::class])->prefix('coordinateur')->name('coordinateur.')->group(function () {
  

    Route::get('seances', [SeanceController::class, 'index'])->name('seances.index');
    Route::get('seances/create', [SeanceController::class, 'create'])->name('seances.create');
    Route::post('seances', [SeanceController::class, 'store'])->name('seances.store');
    Route::get('seances/{seance}/edit', [SeanceController::class, 'edit'])->name('seances.edit');
    Route::put('seances/{seance}', [SeanceController::class, 'update'])->name('seances.update');
    Route::delete('seances/{seance}', [SeanceController::class, 'destroy'])->name('seances.destroy');
    Route::get('seances/{seance}/reporter', [SeanceController::class, 'formulaireReport'])->name('seances.formulaire-report');
    Route::post('seances/{seance}/reporter', [SeanceController::class, 'enregistrerReport'])->name('seances.enregistrer-report');
    Route::put('seances/{seance}/annuler', [SeanceController::class, 'annuler'])->name('seances.annuler');

    Route::get('presences', [PresenceController::class, 'index'])->name('presences.index');
    Route::get('presences/{seance}/edit', [PresenceController::class, 'edit'])->name('presences.edit');
    Route::put('presences/{seance}', [PresenceController::class, 'update'])->name('presences.update');
    Route::get('presences/{seance}/show', [PresenceController::class, 'show'])->name('presences.show');



        Route::get('/justifications', [JustificationController::class, 'index'])->name('justifications.index');
    Route::get('/justifications/create/{presence_id}', [JustificationController::class, 'create'])->name('justifications.create');
    Route::post('/justifications', [JustificationController::class, 'store'])->name('justifications.store');
    Route::get('/justifications/{id}/edit', [JustificationController::class, 'edit'])->name('justifications.edit');
    Route::put('/justifications/{id}', [JustificationController::class, 'update'])->name('justifications.update');



    Route::get('/statistiques/presence-etudiants', [StatistiqueController::class, 'tauxParEtudiant'])
    ->name('statistiques.presence-etudiants'); 
   Route::get('/statistiques/taux-par-classe', [StatistiqueController::class, 'tauxParClasse'])
    ->name('statistiques.classes'); 
Route::get('/statistiques/volume-cours', [StatistiqueController::class, 'volumeCoursParType'])
    ->name('statistiques.volume-cours');
Route::get('/coordinateur/statistiques/volume-total', [StatistiqueController::class, 'volumeCoursTotalParClasse'])
->name('statistiques.volume_total');


Route::get('/mes-classes', [AfficherClasseController::class, 'index'])->name('classes');

Route::prefix('calculs')->name('calculs.')->group(function () {
    Route::get('/assiduite', [CalculController::class, 'assiduiteParEtudiantEtMatiere'])->name('assiduite');
    Route::get('/presence-periode', [CalculController::class, 'presenceParPeriode'])->name('presence.periode');
    Route::get('/taux-classe-periode', [CalculController::class, 'tauxClasseParPeriode'])->name('taux.classe.periode');
});

});
Route::middleware(['auth', IsProfesseur::class])
    ->prefix('professeur')
    ->name('professeur.')
    ->group(function () {

    
    Route::get('seances', [ProfesseurSeanceController::class, 'index'])->name('seances.index');

   
    Route::get('presences', [ProfesseurPresenceController::class, 'index'])->name('presences.index');
    Route::get('presences/{seance}/edit', [ProfesseurPresenceController::class, 'edit'])->name('presences.edit');
    Route::put('presences/{seance}', [ProfesseurPresenceController::class, 'update'])->name('presences.update');
    Route::get('presences/{seance}/show', [ProfesseurPresenceController::class, 'show'])->name('presences.show');
});

Route::middleware(['auth', IsParent::class])
    ->prefix('parent')
    ->name('parent.')
    ->group(function () {
    Route::get('/emploi-du-temps', [EmploiDuTempsController::class, 'index'])->name('emploi');
     Route::get('/absences', [AbsenceController::class, 'index'])->name('absences');
     Route::get('/assiduite', [ParentCalculController::class, 'assiduite'])->name('assiduite');
    });




require __DIR__ . '/auth.php';





