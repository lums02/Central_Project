<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Utilisateur;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class EnsureSuperAdmin extends Command
{
    protected $signature = 'admin:ensure-superadmin';
    protected $description = 'S\'assure que le superadmin existe et a toutes les permissions';

    public function handle()
    {
        $this->info('👑 Vérification et configuration du Super Administrateur...');

        // Vérifier si le superadmin existe
        $superadmin = Utilisateur::where('email', 'admin@central.com')->first();

        if (!$superadmin) {
            $this->warn('⚠️  Superadmin non trouvé. Création en cours...');
            
            $superadmin = Utilisateur::create([
                'nom' => 'Super Administrateur',
                'email' => 'admin@central.com',
                'mot_de_passe' => Hash::make('admin123'),
                'role' => 'superadmin',
                'type_utilisateur' => 'admin',
                'status' => 'approved'
            ]);
            
            $this->line('✅ Superadmin créé avec succès');
        } else {
            $this->line('✅ Superadmin trouvé : ' . $superadmin->nom);
            
            // S'assurer que le rôle est correct
            if ($superadmin->role !== 'superadmin') {
                $superadmin->update(['role' => 'superadmin']);
                $this->line('✅ Rôle mis à jour vers superadmin');
            }
        }

        // Créer le rôle superadmin s'il n'existe pas
        $superAdminRole = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web'
        ]);
        $this->line('✅ Rôle superadmin vérifié');

        // Créer toutes les permissions si elles n'existent pas
        $this->info('🔑 Création des permissions...');
        
        $permissions = [
            // Permissions pour les rôles et permissions
            'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
            
            // Permissions pour les utilisateurs
            'view_users', 'create_users', 'edit_users', 'delete_users',
            
            // Permissions pour les patients
            'view_patients', 'create_patients', 'edit_patients', 'delete_patients',
            
            // Permissions pour les rendez-vous
            'view_appointments', 'create_appointments', 'edit_appointments', 'delete_appointments',
            
            // Permissions pour les dossiers médicaux
            'view_medical_records', 'create_medical_records', 'edit_medical_records', 'delete_medical_records',
            
            // Permissions pour les prescriptions
            'view_prescriptions', 'create_prescriptions', 'edit_prescriptions', 'delete_prescriptions',
            
            // Permissions pour les factures
            'view_invoices', 'create_invoices', 'edit_invoices', 'delete_invoices',
            
            // Permissions pour les rapports
            'view_reports', 'create_reports', 'edit_reports', 'delete_reports',
            
            // Permissions pour les médicaments
            'view_medicines', 'create_medicines', 'edit_medicines', 'delete_medicines',
            
            // Permissions pour les stocks
            'view_stocks', 'create_stocks', 'edit_stocks', 'delete_stocks',
            
            // Permissions pour les donneurs de sang
            'view_donors', 'create_donors', 'edit_donors', 'delete_donors',
            
            // Permissions pour les réserves de sang
            'view_blood_reserves', 'create_blood_reserves', 'edit_blood_reserves', 'delete_blood_reserves',
            
            // Permissions pour les services
            'view_services', 'create_services', 'edit_services', 'delete_services',
            
            // Permissions pour les consultations
            'view_consultations', 'create_consultations', 'edit_consultations', 'delete_consultations',
        ];

        $permissionsCreated = 0;
        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web'
            ]);
            
            if ($permission->wasRecentlyCreated) {
                $permissionsCreated++;
            }
        }

        if ($permissionsCreated > 0) {
            $this->line("✅ {$permissionsCreated} nouvelles permissions créées");
        } else {
            $this->line('✅ Toutes les permissions existent déjà');
        }

        // Attribuer toutes les permissions au rôle superadmin
        $allPermissions = Permission::all();
        $superAdminRole->syncPermissions($allPermissions);
        $this->line('✅ Toutes les permissions attribuées au rôle superadmin');

        // Attribuer le rôle superadmin à l'utilisateur superadmin
        $superadmin->assignRole($superAdminRole);
        $superadmin->syncPermissions($allPermissions);
        $this->line('✅ Rôle et permissions attribués au superadmin');

        // Afficher les informations finales
        $this->info('🎉 Configuration du Super Administrateur terminée !');
        $this->line('');
        $this->line('📋 Informations du Super Administrateur :');
        $this->line("   - Nom : {$superadmin->nom}");
        $this->line("   - Email : {$superadmin->email}");
        $this->line("   - Rôle : {$superadmin->role}");
        $this->line("   - Permissions : " . $allPermissions->count() . " permissions");
        $this->line('');
        $this->warn('🔐 Mot de passe par défaut : admin123');
        $this->warn('⚠️  Changez ce mot de passe après la première connexion !');

        return 0;
    }
}
