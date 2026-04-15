<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateDefaultRoles extends Command
{
    protected $signature = 'create:default-roles';
    protected $description = 'Crée des rôles prédéfinis avec des noms spécifiques';

    public function handle()
    {
        $this->info('Création des rôles prédéfinis...');

        // Rôles à créer
        $roles = [
            'superadmin' => 'Super Administrateur',
            'admin_hopital' => 'Administrateur Hôpital',
            'admin_pharmacie' => 'Administrateur Pharmacie',
            'admin_banque_sang' => 'Administrateur Banque de Sang',
            'admin_centre' => 'Administrateur Centre',
            'manager_hopital' => 'Manager Hôpital',
            'manager_pharmacie' => 'Manager Pharmacie',
            'manager_banque_sang' => 'Manager Banque de Sang',
            'manager_centre' => 'Manager Centre',
            'medecin' => 'Médecin',
            'infirmier' => 'Infirmier',
            'pharmacien' => 'Pharmacien',
            'technicien' => 'Technicien',
            'secretaire' => 'Secrétaire',
            'patient' => 'Patient'
        ];

        foreach ($roles as $roleName => $roleDisplayName) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);
            
            if ($role->wasRecentlyCreated) {
                $this->line("✅ Rôle '{$roleDisplayName}' ({$roleName}) créé");
            } else {
                $this->line("ℹ️  Rôle '{$roleDisplayName}' ({$roleName}) existe déjà");
            }
        }

        $this->info('🎉 Rôles prédéfinis créés avec succès !');
        $this->line('Rôles disponibles:');
        foreach ($roles as $roleName => $roleDisplayName) {
            $this->line("  - {$roleDisplayName} ({$roleName})");
        }

        return 0;
    }
}
