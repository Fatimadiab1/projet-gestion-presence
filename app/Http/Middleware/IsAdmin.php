<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || $user->role->nom !== 'admin') {
            return redirect()->route('login')->with('error', 'Accès refusé.');
        }

        return $next($request);
    }
}
