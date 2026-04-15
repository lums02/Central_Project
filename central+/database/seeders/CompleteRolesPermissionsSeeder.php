<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CompleteRolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Réinitialiser le cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les permissions pour TOUTES les entités
        $permissions = [
            // Utilisateurs
            'view_users', 'create_users', 'edit_users', 'delete_users',
            
            // Rôles
            'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
            
            // Patients
            'view_patients', 'create_patients', 'edit_patients', 'delete_patients',
            
            // Dossiers médicaux
            'view_medical_records', 'create_medical_records', 'edit_medical_records', 'delete_medical_records',
            
            // Rendez-vous
            'view_appointments', 'create_appointments', 'edit_appointments', 'delete_appointments',
            
            // Prescriptions
            'view_prescriptions', 'create_prescriptions', 'edit_prescriptions', 'delete_prescriptions',
            
            // Médicaments (Pharmacies)
            'view_medicines', 'create_medicines', 'edit_medicines', 'delete_medicines',
            
            // Stocks (Pharmacies)
            'view_stocks', 'create_stocks', 'edit_stocks', 'delete_stocks',
            
            // Commandes (Pharmacies)
            'view_orders', 'create_orders', 'edit_orders', 'delete_orders',
            
            // Donneurs (Banques de sang)
            'view_donors', 'create_donors', 'edit_donors', 'delete_donors',
            
            // Réserves de sang
            'view_blood_reserves', 'create_blood_reserves', 'edit_blood_reserves', 'delete_blood_reserves',
            
            // Demandes de sang
            'view_blood_requests', 'create_blood_requests', 'edit_blood_requests', 'delete_blood_requests',
            
            // Factures
            'view_invoices', 'create_invoices', 'edit_invoices', 'delete_invoices',
            
            // Rapports
            'view_reports', 'create_reports', 'edit_reports', 'delete_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ========== RÔLES GÉNÉRAUX ==========
        $superadmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        
        // ========== RÔLES HÔPITAL ==========
        $hopitalAdmin = Role::firstOrCreate(['name' => 'hopital_admin', 'guard_name' => 'web']);
        $medecin = Role::firstOrCreate(['name' => 'medecin', 'guard_name' => 'web']);
        $infirmier = Role::firstOrCreate(['name' => 'infirmier', 'guard_name' => 'web']);
        $receptionniste = Role::firstOrCreate(['name' => 'receptionniste', 'guard_name' => 'web']);
        $caissier = Role::firstOrCreate(['name' => 'caissier', 'guard_name' => 'web']);
        $laborantin = Role::firstOrCreate(['name' => 'laborantin', 'guard_name' => 'web']);
        
        // ========== RÔLES PHARMACIE ==========
        $pharmacieAdmin = Role::firstOrCreate(['name' => 'pharmacie_admin', 'guard_name' => 'web']);
        $pharmacien = Role::firstOrCreate(['name' => 'pharmacien', 'guard_name' => 'web']);
        $assistant_pharmacie = Role::firstOrCreate(['name' => 'assistant_pharmacie', 'guard_name' => 'web']);
        
        // ========== RÔLES BANQUE DE SANG ==========
        $banqueSangAdmin = Role::firstOrCreate(['name' => 'banque_sang_admin', 'guard_name' => 'web']);
        $technicien_labo = Role::firstOrCreate(['name' => 'technicien_labo', 'guard_name' => 'web']);
        $gestionnaire_donneurs = Role::firstOrCreate(['name' => 'gestionnaire_donneurs', 'guard_name' => 'web']);
        
        // ========== RÔLE PATIENT ==========
        $patient = Role::firstOrCreate(['name' => 'patient', 'guard_name' => 'web']);

        // ========== PERMISSIONS SUPERADMIN ==========
        $superadmin->syncPermissions(Permission::all());

        // ========== PERMISSIONS HÔPITAL ==========
        $hopitalAdmin->syncPermissions([
            'view_users', 'create_users', 'edit_users',
            'view_patients', 'create_patients', 'edit_patients',
            'view_medical_records', 'create_medical_records', 'edit_medical_records',
            'view_appointments', 'create_appointments', 'edit_appointments',
            'view_prescriptions', 'create_prescriptions', 'edit_prescriptions',
            'view_invoices', 'create_invoices', 'edit_invoices',
            'view_reports', 'create_reports',
        ]);

        $medecin->syncPermissions([
            'view_patients',
            'view_medical_records', 'create_medical_records', 'edit_medical_records',
            'view_appointments', 'create_appointments', 'edit_appointments',
            'view_prescriptions', 'create_prescriptions', 'edit_prescriptions',
        ]);

        $infirmier->syncPermissions([
            'view_patients',
            'view_medical_records',
            'view_appointments',
            'view_prescriptions',
        ]);

        $receptionniste->syncPermissions([
            'view_patients', 'create_patients', 'edit_patients',
            'view_appointments', 'create_appointments', 'edit_appointments',
        ]);

        $caissier->syncPermissions([
            'view_patients',
            'view_invoices', 'create_invoices', 'edit_invoices',
            'view_appointments',
        ]);

        $laborantin->syncPermissions([
            'view_patients',
            'view_medical_records',
            'view_prescriptions',
        ]);

        // ========== PERMISSIONS PHARMACIE ==========
        $pharmacieAdmin->syncPermissions([
            'view_users', 'create_users', 'edit_users',
            'view_medicines', 'create_medicines', 'edit_medicines', 'delete_medicines',
            'view_stocks', 'create_stocks', 'edit_stocks',
            'view_orders', 'create_orders', 'edit_orders',
            'view_prescriptions',
            'view_invoices', 'create_invoices', 'edit_invoices',
            'view_reports', 'create_reports',
        ]);

        $pharmacien->syncPermissions([
            'view_medicines', 'create_medicines', 'edit_medicines',
            'view_stocks', 'edit_stocks',
            'view_orders', 'create_orders', 'edit_orders',
            'view_prescriptions',
        ]);

        $assistant_pharmacie->syncPermissions([
            'view_medicines',
            'view_stocks',
            'view_orders',
            'view_prescriptions',
        ]);

        // ========== PERMISSIONS BANQUE DE SANG ==========
        $banqueSangAdmin->syncPermissions([
            'view_users', 'create_users', 'edit_users',
            'view_donors', 'create_donors', 'edit_donors', 'delete_donors',
            'view_blood_reserves', 'create_blood_reserves', 'edit_blood_reserves', 'delete_blood_reserves',
            'view_blood_requests', 'create_blood_requests', 'edit_blood_requests',
            'view_invoices', 'create_invoices', 'edit_invoices',
            'view_reports', 'create_reports',
        ]);

        $technicien_labo->syncPermissions([
            'view_donors',
            'view_blood_reserves', 'create_blood_reserves', 'edit_blood_reserves',
            'view_blood_requests',
        ]);

        $gestionnaire_donneurs->syncPermissions([
            'view_donors', 'create_donors', 'edit_donors',
            'view_blood_reserves',
            'view_blood_requests', 'create_blood_requests',
        ]);

        // ========== PERMISSIONS PATIENT ==========
        $patient->syncPermissions([
            'view_appointments',
            'view_medical_records',
            'view_prescriptions',
        ]);

        echo "✅ TOUS les rôles et permissions créés avec succès !\n\n";
        echo "=== HÔPITAL ===\n";
        echo "   - hopital_admin (gestion complète hôpital)\n";
        echo "   - medecin (patients, dossiers, RDV)\n";
        echo "   - infirmier (consultation uniquement)\n\n";
        echo "=== PHARMACIE ===\n";
        echo "   - pharmacie_admin (gestion complète pharmacie)\n";
        echo "   - pharmacien (médicaments, stocks, commandes)\n";
        echo "   - assistant_pharmacie (consultation uniquement)\n\n";
        echo "=== BANQUE DE SANG ===\n";
        echo "   - banque_sang_admin (gestion complète banque)\n";
        echo "   - technicien_labo (réserves de sang)\n";
        echo "   - gestionnaire_donneurs (donneurs, demandes)\n\n";
        echo "=== GÉNÉRAL ===\n";
        echo "   - superadmin (TOUTES les permissions)\n";
        echo "   - patient (consultation uniquement)\n";
    }
}

