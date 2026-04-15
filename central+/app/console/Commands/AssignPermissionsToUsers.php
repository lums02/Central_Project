<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Utilisateur;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignPermissionsToUsers extends Command
{
    protected $signature = 'permissions:assign-to-users {--user-id= : ID spécifique d\'un utilisateur} {--all : Attribuer à tous les utilisateurs}';
    protected $description = 'Attribue automatiquement les permissions appropriées aux utilisateurs selon leur type d\'entité';

    public function handle()
    {
        $this->info('🎯 Attribution des permissions aux utilisateurs...');

        if ($this->option('user-id')) {
            $this->assignToSpecificUser($this->option('user-id'));
        } elseif ($this->option('all')) {
            $this->assignToAllUsers();
        } else {
            $this->error('Utilise --user-id=X ou --all pour spécifier quels utilisateurs traiter');
            return 1;
        }

        return 0;
    }

    private function assignToSpecificUser($userId)
    {
        $utilisateur = Utilisateur::find($userId);
        
        if (!$utilisateur) {
            $this->error("Utilisateur avec l'ID {$userId} non trouvé");
            return;
        }

        $this->info("👤 Attribution des permissions à : {$utilisateur->nom} ({$utilisateur->type_utilisateur})");
        $this->assignPermissionsToUser($utilisateur);
    }

    private function assignToAllUsers()
    {
        $utilisateurs = Utilisateur::all();
        $this->info("👥 Attribution des permissions à {$utilisateurs->count()} utilisateurs...");

        $bar = $this->output->createProgressBar($utilisateurs->count());
        $bar->start();

        foreach ($utilisateurs as $utilisateur) {
            $this->assignPermissionsToUser($utilisateur, false);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('✅ Attribution terminée pour tous les utilisateurs !');
    }

    private function assignPermissionsToUser($utilisateur, $showDetails = true)
    {
        $typeUtilisateur = $utilisateur->type_utilisateur;
        $role = $utilisateur->role;

        // Déterminer le rôle approprié selon le type d'utilisateur
        $assignedRole = $this->determineRole($typeUtilisateur, $role);
        
        // Attribuer le rôle
        $utilisateur->assignRole($assignedRole);

        // Attribuer les permissions spécifiques à l'entité
        $entityPermissions = $this->getEntityPermissions($typeUtilisateur);
        $utilisateur->syncPermissions($entityPermissions);

        if ($showDetails) {
            $this->line("  🎭 Rôle attribué : {$assignedRole->name}");
            $this->line("  🔑 Permissions attribuées : " . $entityPermissions->count());
            
            foreach ($entityPermissions as $permission) {
                $displayName = $permission->display_name ? $permission->display_name : $permission->name;
                $this->line("    - {$displayName}");
            }
        }
    }

    private function determineRole($typeUtilisateur, $userRole)
    {
        // Si l'utilisateur est admin, garder le rôle admin
        if ($userRole === 'admin') {
            return Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        }

        // Sinon, attribuer le rôle manager de l'entité
        $roleName = "manager_{$typeUtilisateur}";
        return Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
    }

    private function getEntityPermissions($typeUtilisateur)
    {
        // Permissions de base pour tous les utilisateurs
        $basePermissions = Permission::whereIn('name', [
            'view_' . $typeUtilisateur,
            'list_' . $typeUtilisateur
        ])->get();

        // Permissions supplémentaires selon le type
        $additionalPermissions = collect();
        
        switch ($typeUtilisateur) {
            case 'hopital':
                $additionalPermissions = Permission::whereIn('name', [
                    'create_patient', 'edit_patient', 'view_patient', 'list_patient',
                    'create_centre', 'edit_centre', 'view_centre', 'list_centre'
                ])->get();
                break;
                
            case 'pharmacie':
                $additionalPermissions = Permission::whereIn('name', [
                    'create_patient', 'edit_patient', 'view_patient', 'list_patient'
                ])->get();
                break;
                
            case 'banque_sang':
                $additionalPermissions = Permission::whereIn('name', [
                    'create_patient', 'edit_patient', 'view_patient', 'list_patient'
                ])->get();
                break;
                
            case 'centre':
                $additionalPermissions = Permission::whereIn('name', [
                    'create_patient', 'edit_patient', 'view_patient', 'list_patient'
                ])->get();
                break;
                
            case 'patient':
                // Les patients ont des permissions limitées
                $additionalPermissions = collect();
                break;
        }

        return $basePermissions->merge($additionalPermissions);
    }
}
