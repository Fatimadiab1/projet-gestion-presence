<?php

use Illuminate\Support\Facades\Route;

// 🌐 Redirection automatique vers le formulaire de connexion
Route::get('/', function () {
    return redirect()->route('login');
});

// 🔐 Redirection après connexion (déjà gérée dans le contrôleur via le rôle)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// 📂 Inclusion des routes d'authentification Breeze (login, logout, etc.)
require __DIR__.'/auth.php';
