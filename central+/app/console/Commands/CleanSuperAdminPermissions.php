<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Utilisateur;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CleanSuperAdminPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:clean-superadmin-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoyer et attribuer seulement les permissions spécifiques du superadmin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Nettoyage et attribution des permissions spécifiques du superadmin...');

        // Trouver le superadmin
        $superadmin = Utilisateur::where('role', 'superadmin')
            ->orWhere('email', 'admin@central.com')
            ->first();

        if (!$superadmin) {
            $this->error('Aucun superadmin trouvé !');
            return 1;
        }

        $this->info("Superadmin trouvé : {$superadmin->email}");

        // Permissions EXACTES du superadmin
        $superAdminPermissions = [
            // CRUD sur les entités
            'view_hopital', 'create_hopital', 'edit_hopital', 'delete_hopital',
            'view_pharmacie', 'create_pharmacie', 'edit_pharmacie', 'delete_pharmacie',
            'view_banque_sang', 'create_banque_sang', 'edit_banque_sang', 'delete_banque_sang',
            'view_centre', 'create_centre', 'edit_centre', 'delete_centre',
            
            // CRUD sur les utilisateurs
            'view_users', 'create_users', 'edit_users', 'delete_users',
            'manage_users', 'gérer_les_utilisateurs',
            
            // CRUD sur les rôles et permissions
            'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
            'manage_permissions',
            
            // Permissions de gestion générale
            'view_dashboard', 'view_reports', 'create_reports', 'edit_reports', 'delete_reports'
        ];

        $this->info("Permissions à attribuer : " . count($superAdminPermissions));

        // Créer les permissions si elles n'existent pas
        foreach ($superAdminPermissions as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web'
            ]);
        }

        // Récupérer seulement les permissions spécifiques
        $permissions = Permission::whereIn('name', $superAdminPermissions)->get();

        // Supprimer TOUTES les permissions actuelles
        $superadmin->syncPermissions([]);
        
        // Attribuer seulement les permissions spécifiques
        $superadmin->syncPermissions($permissions);

        // S'assurer que le rôle superadmin existe
        $superAdminRole = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web'
        ]);

        // Attribuer le rôle superadmin
        $superadmin->assignRole($superAdminRole);

        // Vérifier les permissions actuelles
        $currentPermissions = $superadmin->getAllPermissions();
        
        $this->info("Permissions attribuées au superadmin : {$currentPermissions->count()}");

        // Afficher les permissions
        $this->table(
            ['Permission'],
            $currentPermissions->pluck('name')->map(function($name) {
                return [$name];
            })
        );

        $this->info('✅ Superadmin a maintenant EXACTEMENT les permissions demandées !');
        
        return 0;
    }
}
