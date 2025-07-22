<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsParent
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || $user->role->nom !== 'parent') {
            return redirect()->route('login')->with('error', 'Accès refusé.');
        }

        return $next($request);
    }
}
