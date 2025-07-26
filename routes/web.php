<?php

use Illuminate\Support\Facades\Route;
// Controlleur
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ParentEtudiantController;
use App\Http\Controllers\Admin\AnneeAcademiqueController;
use App\Http\Controllers\Admin\TrimestreController;
use App\Http\Controllers\Admin\ClasseAnneeController;
use App\Http\Controllers\Admin\MatiereController;
use App\Http\Controllers\Admin\TypeCoursController;
use App\Http\Controllers\Admin\StatutSeanceController;
use App\Http\Controllers\Admin\StatutPresenceController;
use App\Http\Controllers\Admin\StatutSuiviController;
use App\Http\Controllers\Admin\InscriptionController;
use App\Http\Controllers\Admin\ClasseController;
use App\Http\Controllers\Admin\ProfesseurMatiereController;
use App\Http\Controllers\Admin\SuiviEtudiantController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Coordinateur\SeanceController;
// Middlewares
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsEtudiant;
use App\Http\Middleware\IsProfesseur;
use App\Http\Middleware\IsParent;
use App\Http\Middleware\IsCoordinateur;



// Accueil
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard pour chaque role
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware(IsAdmin::class)->name('admin.dashboard');

    Route::get('/etudiant/dashboard', function () {
        return view('etudiant.dashboard');
    })->middleware(IsEtudiant::class)->name('etudiant.dashboard');

    Route::get('/professeur/dashboard', function () {
        return view('professeur.dashboard');
    })->middleware(IsProfesseur::class)->name('professeur.dashboard');

    Route::get('/parent/dashboard', function () {
        return view('parent.dashboard');
    })->middleware(IsParent::class)->name('parent.dashboard');

    Route::get('/coordinateur/dashboard', function () {
        return view('coordinateur.dashboard');
    })->middleware(IsCoordinateur::class)->name('coordinateur.dashboard');
});


// Route pour dashboard admin
Route::middleware(['auth', IsAdmin::class])->group(function () {

    // role
    Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles.index');

    // users
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // parent-enfant
    Route::get('/admin/parents/index', [ParentEtudiantController::class, 'index'])->name('admin.parents.index');
    Route::get('/admin/parents/create', [ParentEtudiantController::class, 'create'])->name('admin.parents.create');
    Route::post('/admin/parents/create', [ParentEtudiantController::class, 'store'])->name('admin.parents.store');
    Route::get('/admin/parents/{id}/edit', [ParentEtudiantController::class, 'edit'])->name('admin.parents.edit');
    Route::put('/admin/parents/{id}', [ParentEtudiantController::class, 'update'])->name('admin.parents.update');

    // annee academiques
    Route::get('/admin/annees-academiques', [AnneeAcademiqueController::class, 'index'])->name('admin.annees-academiques.index');
    Route::get('/admin/annees-academiques/create', [AnneeAcademiqueController::class, 'create'])->name('admin.annees-academiques.create');
    Route::post('/admin/annees-academiques', [AnneeAcademiqueController::class, 'store'])->name('admin.annees-academiques.store');
    Route::get('/admin/annees-academiques/{annee}/edit', [AnneeAcademiqueController::class, 'edit'])->name('admin.annees-academiques.edit');
    Route::put('/admin/annees-academiques/{annee}', [AnneeAcademiqueController::class, 'update'])->name('admin.annees-academiques.update');
    Route::delete('/admin/annees-academiques/{annee}', [AnneeAcademiqueController::class, 'destroy'])->name('admin.annees-academiques.destroy');

    // trimestres
    Route::get('/admin/trimestres', [TrimestreController::class, 'index'])->name('admin.trimestres.index');
    Route::get('/admin/trimestres/create', [TrimestreController::class, 'create'])->name('admin.trimestres.create');
    Route::post('/admin/trimestres', [TrimestreController::class, 'store'])->name('admin.trimestres.store');
    Route::get('/admin/trimestres/{trimestre}/edit', [TrimestreController::class, 'edit'])->name('admin.trimestres.edit');
    Route::put('/admin/trimestres/{trimestre}', [TrimestreController::class, 'update'])->name('admin.trimestres.update');
    Route::delete('/admin/trimestres/{trimestre}', [TrimestreController::class, 'destroy'])->name('admin.trimestres.destroy');

    // classes
    Route::get('/admin/classes', [ClasseController::class, 'index'])->name('admin.typeclasse.index');
    Route::get('/admin/classes/create', [ClasseController::class, 'create'])->name('admin.typeclasse.create');
    Route::post('/admin/classes', [ClasseController::class, 'store'])->name('admin.typeclasse.store');
    Route::get('/admin/classes/{classe}/edit', [ClasseController::class, 'edit'])->name('admin.typeclasse.edit');
    Route::put('/admin/classes/{classe}', [ClasseController::class, 'update'])->name('admin.typeclasse.update');
    Route::delete('/admin/classes/{classe}', [ClasseController::class, 'destroy'])->name('admin.typeclasse.destroy');

    // annee-classe
    Route::get('/admin/annees-classes', [ClasseAnneeController::class, 'index'])->name('admin.classes.index');
    Route::get('/admin/annees-classes/create', [ClasseAnneeController::class, 'create'])->name('admin.classes.create');
    Route::post('/admin/annees-classes', [ClasseAnneeController::class, 'store'])->name('admin.classes.store');
    Route::get('/admin/annees-classes/{classe}/edit', [ClasseAnneeController::class, 'edit'])->name('admin.classes.edit');
    Route::put('/admin/annees-classes/{classe}', [ClasseAnneeController::class, 'update'])->name('admin.classes.update');
    Route::delete('/admin/annees-classes/{classe}', [ClasseAnneeController::class, 'destroy'])->name('admin.classes.destroy');

    // matieres
    Route::get('/admin/matieres', [MatiereController::class, 'index'])->name('admin.matieres.index');
    Route::get('/admin/matieres/create', [MatiereController::class, 'create'])->name('admin.matieres.create');
    Route::post('/admin/matieres', [MatiereController::class, 'store'])->name('admin.matieres.store');
    Route::get('/admin/matieres/{matiere}/edit', [MatiereController::class, 'edit'])->name('admin.matieres.edit');
    Route::put('/admin/matieres/{matiere}', [MatiereController::class, 'update'])->name('admin.matieres.update');
    Route::delete('/admin/matieres/{matiere}', [MatiereController::class, 'destroy'])->name('admin.matieres.destroy');

    // type de cours
    Route::get('/admin/types-cours', [TypeCoursController::class, 'index'])->name('admin.types-cours.index');
    Route::get('/admin/types-cours/create', [TypeCoursController::class, 'create'])->name('admin.types-cours.create');
    Route::post('/admin/types-cours', [TypeCoursController::class, 'store'])->name('admin.types-cours.store');
    Route::get('/admin/types-cours/{type}/edit', [TypeCoursController::class, 'edit'])->name('admin.types-cours.edit');
    Route::put('/admin/types-cours/{type}', [TypeCoursController::class, 'update'])->name('admin.types-cours.update');
    Route::delete('/admin/types-cours/{type}', [TypeCoursController::class, 'destroy'])->name('admin.types-cours.destroy');

    // statut seance presence suivi
    Route::get('/admin/statuts-seance', [StatutSeanceController::class, 'index'])->name('admin.statuts-seance.index');
    Route::get('/admin/statuts-seance/create', [StatutSeanceController::class, 'create'])->name('admin.statuts-seance.create');
    Route::post('/admin/statuts-seance', [StatutSeanceController::class, 'store'])->name('admin.statuts-seance.store');
    Route::get('/admin/statuts-seance/{statut}/edit', [StatutSeanceController::class, 'edit'])->name('admin.statuts-seance.edit');
    Route::put('/admin/statuts-seance/{statut}', [StatutSeanceController::class, 'update'])->name('admin.statuts-seance.update');
    Route::delete('/admin/statuts-seance/{statut}', [StatutSeanceController::class, 'destroy'])->name('admin.statuts-seance.destroy');

    Route::get('/admin/statuts-presence', [StatutPresenceController::class, 'index'])->name('admin.statuts-presence.index');
    Route::get('/admin/statuts-presence/create', [StatutPresenceController::class, 'create'])->name('admin.statuts-presence.create');
    Route::post('/admin/statuts-presence', [StatutPresenceController::class, 'store'])->name('admin.statuts-presence.store');
    Route::get('/admin/statuts-presence/{statut}/edit', [StatutPresenceController::class, 'edit'])->name('admin.statuts-presence.edit');
    Route::put('/admin/statuts-presence/{statut}', [StatutPresenceController::class, 'update'])->name('admin.statuts-presence.update');
    Route::delete('/admin/statuts-presence/{statut}', [StatutPresenceController::class, 'destroy'])->name('admin.statuts-presence.destroy');

    Route::get('/admin/statuts-suivi', [StatutSuiviController::class, 'index'])->name('admin.statuts-suivi.index');
    Route::get('/admin/statuts-suivi/create', [StatutSuiviController::class, 'create'])->name('admin.statuts-suivi.create');
    Route::post('/admin/statuts-suivi', [StatutSuiviController::class, 'store'])->name('admin.statuts-suivi.store');
    Route::get('/admin/statuts-suivi/{statut}/edit', [StatutSuiviController::class, 'edit'])->name('admin.statuts-suivi.edit');
    Route::put('/admin/statuts-suivi/{statut}', [StatutSuiviController::class, 'update'])->name('admin.statuts-suivi.update');
    Route::delete('/admin/statuts-suivi/{statut}', [StatutSuiviController::class, 'destroy'])->name('admin.statuts-suivi.destroy');

  // professeur-matiere
Route::get('/admin/professeurs-matieres', [ProfesseurMatiereController::class, 'index'])->name('admin.professeurs-matieres.index');
Route::get('/admin/professeurs-matieres/create', [ProfesseurMatiereController::class, 'create'])->name('admin.professeurs-matieres.create');
Route::post('/admin/professeurs-matieres', [ProfesseurMatiereController::class, 'store'])->name('admin.professeurs-matieres.store');
Route::get('/admin/professeurs-matieres/{professeur_id}/{matiere_id}/edit', [ProfesseurMatiereController::class, 'edit'])->name('admin.professeurs-matieres.edit');
Route::put('/admin/professeurs-matieres/{professeur_id}/{matiere_id}', [ProfesseurMatiereController::class, 'update'])->name('admin.professeurs-matieres.update'); // ✅ ICI

    // Inscription et reinscription
    Route::get('/admin/inscriptions', [InscriptionController::class, 'index'])->name('admin.inscriptions.index');
    Route::get('/admin/inscriptions/create', [InscriptionController::class, 'create'])->name('admin.inscriptions.create');
    Route::post('/admin/inscriptions', [InscriptionController::class, 'store'])->name('admin.inscriptions.store');
    Route::get('/admin/inscriptions/{inscription}/edit', [InscriptionController::class, 'edit'])->name('admin.inscriptions.edit');
    Route::put('/admin/inscriptions/{inscription}', [InscriptionController::class, 'update'])->name('admin.inscriptions.update');
    Route::delete('/admin/inscriptions/{inscription}', [InscriptionController::class, 'destroy'])->name('admin.inscriptions.destroy');

    Route::get('/admin/inscriptions/{etudiant}/reinscrire', [InscriptionController::class, 'reinscrire'])->name('admin.inscriptions.reinscrire');
    Route::post('/admin/inscriptions/{etudiant}/reinscrire', [InscriptionController::class, 'reinscrireStore'])->name('admin.inscriptions.reinscrire.store');
    Route::get('/admin/inscriptions/non-reinscrits', [InscriptionController::class, 'nonReinscrits'])->name('admin.inscriptions.non_reinscrits');

    // suivi etudiant
    Route::get('/admin/suivi-etudiants', [SuiviEtudiantController::class, 'index'])->name('admin.suivi-etudiants.index');
    Route::get('/admin/suivi-etudiants/create', [SuiviEtudiantController::class, 'create'])->name('admin.suivi-etudiants.create');
    Route::post('/admin/suivi-etudiants', [SuiviEtudiantController::class, 'store'])->name('admin.suivi-etudiants.store');
    Route::get('/admin/suivi-etudiants/{suivi}/edit', [SuiviEtudiantController::class, 'edit'])->name('admin.suivi-etudiants.edit');
    Route::put('/admin/suivi-etudiants/{suivi}', [SuiviEtudiantController::class, 'update'])->name('admin.suivi-etudiants.update');
    Route::delete('/admin/suivi-etudiants/{suivi}', [SuiviEtudiantController::class, 'destroy'])->name('admin.suivi-etudiants.destroy');

    // Dashboard admin
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});
Route::middleware(['auth', IsCoordinateur::class])->prefix('coordinateur')->name('coordinateur.')->group(function () {

    // 🏠 Dashboard coordinateur
    Route::get('/dashboard', function () {
        return view('coordinateur.dashboard');
    })->name('dashboard');

    // 📅 Liste des séances
    Route::get('/seances', [SeanceController::class, 'index'])->name('seances.index');

    // ➕ Formulaire de création
    Route::get('/seances/create', [SeanceController::class, 'create'])->name('seances.create');

    // 💾 Enregistrer une nouvelle séance
    Route::post('/seances', [SeanceController::class, 'store'])->name('seances.store');

    // ✏️ Formulaire d’édition
    Route::get('/seances/{seance}/edit', [SeanceController::class, 'edit'])->name('seances.edit');

    // ✅ Mettre à jour une séance
    Route::put('/seances/{seance}', [SeanceController::class, 'update'])->name('seances.update');

    // ❌ Supprimer une séance (optionnel)
    Route::delete('/seances/{seance}', [SeanceController::class, 'destroy'])->name('seances.destroy');
});


// auth de laravel breeze
require __DIR__.'/auth.php';
