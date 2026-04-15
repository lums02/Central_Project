<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Vérifie si l'utilisateur connecté peut effectuer une action sur une entité
     */
    public static function can($action, $entity = null)
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        
        if (!$entity) {
            $entity = $user->type_utilisateur;
        }

        $permissionName = "{$action}_{$entity}";
        
        return $user->hasPermissionTo($permissionName);
    }

    /**
     * Vérifie si l'utilisateur peut voir une entité
     */
    public static function canView($entity = null)
    {
        return self::can('view', $entity);
    }

    /**
     * Vérifie si l'utilisateur peut créer une entité
     */
    public static function canCreate($entity = null)
    {
        return self::can('create', $entity);
    }

    /**
     * Vérifie si l'utilisateur peut modifier une entité
     */
    public static function canEdit($entity = null)
    {
        return self::can('edit', $entity);
    }

    /**
     * Vérifie si l'utilisateur peut supprimer une entité
     */
    public static function canDelete($entity = null)
    {
        return self::can('delete', $entity);
    }

    /**
     * Vérifie si l'utilisateur peut lister une entité
     */
    public static function canList($entity = null)
    {
        return self::can('list', $entity);
    }

    /**
     * Retourne les permissions de l'utilisateur pour une entité
     */
    public static function getUserPermissions($entity = null)
    {
        if (!Auth::check()) {
            return collect();
        }

        $user = Auth::user();
        
        if (!$entity) {
            $entity = $user->type_utilisateur;
        }

        return $user->permissions->filter(function ($permission) use ($entity) {
            return str_contains($permission->name, "_{$entity}");
        });
    }

    /**
     * Retourne le type d'entité de l'utilisateur connecté
     */
    public static function getUserEntityType()
    {
        if (!Auth::check()) {
            return null;
        }

        return Auth::user()->type_utilisateur;
    }

    /**
     * Retourne le rôle de l'utilisateur connecté
     */
    public static function getUserRole()
    {
        if (!Auth::check()) {
            return null;
        }

        return Auth::user()->role;
    }
}
