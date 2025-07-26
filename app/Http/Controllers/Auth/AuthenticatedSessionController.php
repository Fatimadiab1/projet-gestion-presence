<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    // formulaire de connexion
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // Redirection 
        if ($user->role->nom === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role->nom === 'coordinateur') {
            return redirect()->route('coordinateur.dashboard');
        } elseif ($user->role->nom === 'professeur') {
            return redirect()->route('professeur.dashboard');
        } elseif ($user->role->nom === 'etudiant') {
            return redirect()->route('etudiant.dashboard');
        } elseif ($user->role->nom === 'parent') {
            return redirect()->route('parent.dashboard');
        } else {
            return redirect()->route('login');
        }
    }

    // déconnexion
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
