<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsEtudiant;
use App\Http\Middleware\IsProfesseur;
use App\Http\Middleware\IsParent;
use App\Http\Middleware\IsCoordinateur;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    // Dashboard admin
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware(IsAdmin::class)->name('admin.dashboard');

    // Dashboard étudiant
    Route::get('/etudiant/dashboard', function () {
        return view('etudiant.dashboard');
    })->middleware(IsEtudiant::class)->name('etudiant.dashboard');

    // Dashboard professeur
    Route::get('/professeur/dashboard', function () {
        return view('professeur.dashboard');
    })->middleware(IsProfesseur::class)->name('professeur.dashboard');

    // Dashboard parent
    Route::get('/parent/dashboard', function () {
        return view('parent.dashboard');
    })->middleware(IsParent::class)->name('parent.dashboard');

    // Dashboard coordinateur
    Route::get('/coordinateur/dashboard', function () {
        return view('coordinateur.dashboard');
    })->middleware(IsCoordinateur::class)->name('coordinateur.dashboard');
});

require __DIR__.'/auth.php';
