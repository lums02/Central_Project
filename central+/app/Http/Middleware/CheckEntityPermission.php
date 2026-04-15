<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEntityPermission
{
    public function handle(Request $request, Closure $next, string $permission, string $entity = null): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Si pas d'entité spécifiée, utiliser le type de l'utilisateur
        if (!$entity) {
            $entity = $user->type_utilisateur;
        }

        // Construire le nom de la permission
        $permissionName = "{$permission}_{$entity}";
        
        // Vérifier si l'utilisateur a la permission
        if (!$user->hasPermissionTo($permissionName)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Permission refusée',
                    'message' => "Vous n'avez pas la permission '{$permissionName}'"
                ], 403);
            }
            
            abort(403, "Vous n'avez pas la permission d'accéder à cette ressource.");
        }

        return $next($request);
    }
}
