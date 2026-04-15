<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Utilisateur;

class SuperAdminProtection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si la requête concerne la suppression d'un utilisateur
        if ($request->isMethod('DELETE') && $request->route('id')) {
            $userId = $request->route('id');
            $user = Utilisateur::find($userId);
            
            if ($user && !$user->canBeDeleted()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Impossible de supprimer le Super Administrateur'
                    ], 403);
                }
                
                return redirect()->back()->with('error', 'Impossible de supprimer le Super Administrateur');
            }
        }

        return $next($request);
    }
}
