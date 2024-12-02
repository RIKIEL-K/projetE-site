<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$role): Response
    {
         // Vérifier si l'utilisateur est authentifié
         if (!Auth::check()) {
            return redirect()->route('login')->withErrors('Vous devez être connecté pour accéder à cette page.');
        }

        // Récupérer le statut de l'utilisateur connecté
        $user = Auth::user();

        // Vérifier les rôles
        if ($role === 'admin' && $user->statut != 1) {
            return redirect()->route('admin.produit.index')->withErrors('Accès refusé.');
        }

        if ($role === 'user' && $user->statut != 0) {
            return redirect()->route('index')->withErrors('Accès refusé.');
        }

        return $next($request);

    }
}
