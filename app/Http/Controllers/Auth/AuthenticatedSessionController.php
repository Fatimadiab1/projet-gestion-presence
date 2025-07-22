<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{

    // Le form de connexion
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

        switch ($user->role->nom) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'etudiant':
                return redirect()->route('etudiant.dashboard');
            case 'professeur':
                return redirect()->route('professeur.dashboard');
            case 'parent':
                return redirect()->route('parent.dashboard');
            case 'coordinateur':
                return redirect()->route('coordinateur.dashboard');
            default:
                return redirect('/');
        }
    }


    // Deconnexion
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
