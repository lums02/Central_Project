<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class createrole extends Command
{
    protected $signature = 'create:role';
    protected $description = 'Crée des rôles avec des permissions spécifiques';

    public function handle()
    {
        $this->info('Création des rôles et permissions...');

        // Créer le rôle Super Admin
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $this->line("✅ Rôle 'super_admin' créé");

        // Créer le rôle Admin
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $this->line("✅ Rôle 'admin' créé");

        // Créer le rôle User
        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $this->line("✅ Rôle 'user' créé");

        // Créer des permissions de base
        $permissions = [
            'view_dashboard',
            'manage_users',
            'manage_permissions',
            'view_reports'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $this->line("✅ Permissions de base créées");

        // Attribuer toutes les permissions au super admin
        $superAdmin->givePermissionTo(Permission::all());
        $this->line("✅ Toutes les permissions attribuées au super_admin");

        // Attribuer quelques permissions à l'admin
        $admin->givePermissionTo(['view_dashboard', 'view_reports']);
        $this->line("✅ Permissions limitées attribuées à l'admin");

        // Attribuer permissions de base au user
        $user->givePermissionTo(['view_dashboard']);
        $this->line("✅ Permissions de base attribuées au user");

        $this->info('🎉 Rôles et permissions créés avec succès !');
        $this->line("Rôles disponibles: super_admin, admin, user");

        return 0;
    }
}
