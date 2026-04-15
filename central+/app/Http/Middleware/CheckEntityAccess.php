<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckEntityAccess
{
    /**
     * Vérifier que l'utilisateur accède uniquement aux données de son entité
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        // Le superadmin a accès à tout
        if ($user && $user->isSuperAdmin()) {
            return $next($request);
        }
        
        // Vérifier que l'utilisateur a une entité
        if ($user && !$user->entite_id && $user->type_utilisateur !== 'patient') {
            abort(403, 'Vous devez être associé à une entité pour accéder à cette ressource.');
        }
        
        return $next($request);
    }
}

