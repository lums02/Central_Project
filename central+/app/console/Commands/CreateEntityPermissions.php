<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateEntityPermissions extends Command
{
    protected $signature = 'permissions:create-entity-permissions';
    protected $description = 'Crée toutes les permissions CRUD pour chaque type d\'entité';

    public function handle()
    {
        $this->info('Création des permissions CRUD pour chaque entité...');

        // Types d'entités
        $entityTypes = [
            'hopital' => 'Hôpital',
            'pharmacie' => 'Pharmacie', 
            'banque_sang' => 'Banque de Sang',
            'centre' => 'Centre',
            'patient' => 'Patient'
        ];

        // Actions CRUD
        $crudActions = [
            'view' => 'Voir',
            'create' => 'Créer',
            'edit' => 'Modifier',
            'delete' => 'Supprimer',
            'list' => 'Lister'
        ];

        $permissionsCreated = 0;

        foreach ($entityTypes as $entityKey => $entityName) {
            $this->info("\n📋 Création des permissions pour : {$entityName}");
            
            foreach ($crudActions as $actionKey => $actionName) {
                $permissionName = "{$actionKey}_{$entityKey}";
                $permissionDisplayName = "{$actionName} {$entityName}";
                
                // Créer la permission si elle n'existe pas
                $permission = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web'
                ]);
                
                if ($permission->wasRecentlyCreated) {
                    $this->line("  ✅ {$permissionDisplayName} créée");
                    $permissionsCreated++;
                } else {
                    $this->line("  ℹ️  {$permissionDisplayName} existe déjà");
                }
            }
        }

        // Créer des rôles par défaut avec permissions appropriées
        $this->createDefaultRoles($entityTypes);

        $this->info("\n🎉 {$permissionsCreated} nouvelles permissions créées !");
        $this->info('Utilise "php artisan permissions:assign-to-users" pour les attribuer aux utilisateurs.');
        
        return 0;
    }

    private function createDefaultRoles($entityTypes)
    {
        $this->info("\n👥 Création des rôles par défaut...");

        // Rôle Super Admin (toutes les permissions)
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());
        $this->line("  ✅ Super Admin créé avec toutes les permissions");

        // Rôle Admin (permissions limitées)
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminPermissions = Permission::whereIn('name', [
            'view_hopital', 'list_hopital',
            'view_pharmacie', 'list_pharmacie', 
            'view_banque_sang', 'list_banque_sang',
            'view_centre', 'list_centre',
            'view_patient', 'list_patient'
        ])->get();
        $admin->syncPermissions($adminPermissions);
        $this->line("  ✅ Admin créé avec permissions de consultation");

        // Rôles par entité
        foreach ($entityTypes as $entityKey => $entityName) {
            $roleName = "manager_{$entityKey}";
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            
            // Permissions spécifiques à cette entité
            $entityPermissions = Permission::where('name', 'like', "%_{$entityKey}")->get();
            $role->syncPermissions($entityPermissions);
            
            $this->line("  ✅ {$roleName} créé avec permissions {$entityName}");
        }
    }
}
