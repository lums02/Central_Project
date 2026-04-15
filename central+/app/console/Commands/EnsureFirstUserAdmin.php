<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Utilisateur;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class EnsureFirstUserAdmin extends Command
{
    protected $signature = 'admin:ensure-first-user-admin';
    protected $description = 'S\'assure que le premier utilisateur de chaque type d\'entité est admin';

    public function handle()
    {
        $this->info('🔍 Vérification des premiers utilisateurs de chaque type d\'entité...');

        $entityTypes = ['hopital', 'pharmacie', 'banque_sang', 'centre', 'patient'];
        $changesMade = 0;

        foreach ($entityTypes as $entityType) {
            $this->info("\n📋 Vérification pour : " . ucfirst($entityType));
            
            // Trouver le premier utilisateur approuvé de ce type
            $firstUser = Utilisateur::where('type_utilisateur', $entityType)
                ->where('status', 'approved')
                ->orderBy('created_at', 'asc')
                ->first();

            if (!$firstUser) {
                $this->line("  ℹ️  Aucun utilisateur approuvé trouvé pour {$entityType}");
                continue;
            }

            $this->line("  👤 Premier utilisateur : {$firstUser->nom} ({$firstUser->email})");
            $this->line("  🎭 Rôle actuel : {$firstUser->role}");

            // Vérifier si le rôle admin existe
            $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
            
            if ($firstUser->role !== 'admin') {
                // Promouvoir en admin
                $firstUser->role = 'admin';
                $firstUser->save();
                
                // Attribuer le rôle admin
                $firstUser->assignRole($adminRole);
                
                // Attribuer les permissions appropriées
                $this->assignEntityPermissions($firstUser, $entityType);
                
                $this->line("  ✅ Promu en admin avec permissions {$entityType}");
                $changesMade++;
            } else {
                $this->line("  ✅ Déjà admin");
            }

            // S'assurer que les autres utilisateurs de ce type ne sont pas admin
            $otherUsers = Utilisateur::where('type_utilisateur', $entityType)
                ->where('status', 'approved')
                ->where('id', '!=', $firstUser->id)
                ->where('role', 'admin')
                ->get();

            foreach ($otherUsers as $user) {
                $user->role = 'user';
                $user->save();
                $user->removeRole($adminRole);
                
                $this->line("  🔄 {$user->nom} rétrogradé en utilisateur normal");
                $changesMade++;
            }
        }

        if ($changesMade > 0) {
            $this->info("\n🎉 {$changesMade} changements effectués !");
        } else {
            $this->info("\n✅ Aucun changement nécessaire, tout est déjà correct !");
        }

        return 0;
    }

    private function assignEntityPermissions($user, $entityType)
    {
        // Permissions de base pour tous les administrateurs
        $basePermissions = [
            'view_users', 'create_users', 'edit_users',
            'view_patients', 'create_patients', 'edit_patients',
            'view_appointments', 'create_appointments', 'edit_appointments',
            'view_medical_records', 'create_medical_records', 'edit_medical_records',
            'view_prescriptions', 'create_prescriptions', 'edit_prescriptions',
            'view_reports', 'create_reports'
        ];

        // Permissions spécifiques selon le type d'entité
        $entityPermissions = [];
        switch ($entityType) {
            case 'hopital':
                $entityPermissions = [
                    'view_consultations', 'create_consultations', 'edit_consultations',
                    'view_services', 'create_services', 'edit_services',
                    'view_hopital', 'edit_hopital'
                ];
                break;
            case 'pharmacie':
                $entityPermissions = [
                    'view_medicines', 'create_medicines', 'edit_medicines',
                    'view_stocks', 'create_stocks', 'edit_stocks',
                    'view_invoices', 'create_invoices', 'edit_invoices',
                    'view_pharmacie', 'edit_pharmacie'
                ];
                break;
            case 'banque_sang':
                $entityPermissions = [
                    'view_donors', 'create_donors', 'edit_donors',
                    'view_blood_reserves', 'create_blood_reserves', 'edit_blood_reserves',
                    'view_banque_sang', 'edit_banque_sang'
                ];
                break;
            case 'centre':
                $entityPermissions = [
                    'view_centre', 'edit_centre',
                    'view_patients', 'create_patients', 'edit_patients'
                ];
                break;
            case 'patient':
                $entityPermissions = [
                    'view_patient', 'edit_patient',
                    'view_appointments', 'create_appointments'
                ];
                break;
        }

        // Créer et attribuer toutes les permissions
        $allPermissions = array_merge($basePermissions, $entityPermissions);
        
        foreach ($allPermissions as $permissionName) {
            $permission = Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web'
            ]);
            $user->givePermissionTo($permission);
        }

        $this->line("  🔑 " . count($allPermissions) . " permissions attribuées");
    }
}
