<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsCoordinateur
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role && Auth::user()->role->nom === 'coordinateur') {
            return $next($request);
        }
        return redirect()->route('login');
    }
}
