<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }
        if (Auth::user()->hasRole('Admin')) {
            return $next($request);
        }
        if ($role === 'Compétiteur' && Auth::user()->hasRole('Organisateur')) {
            return $next($request);
        }

        if (!Auth::user()->hasRole($role)) {
            
            abort(403, 'Accès non autorisé : Vous n\'avez pas les droits nécessaires.');
        }

        return $next($request);
    }
}
