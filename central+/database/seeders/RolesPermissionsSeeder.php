<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Réinitialiser le cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les permissions
        $permissions = [
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'view_patients',
            'create_patients',
            'edit_patients',
            'delete_patients',
            'view_medical_records',
            'create_medical_records',
            'edit_medical_records',
            'delete_medical_records',
            'view_appointments',
            'create_appointments',
            'edit_appointments',
            'delete_appointments',
            'view_prescriptions',
            'create_prescriptions',
            'edit_prescriptions',
            'delete_prescriptions',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Créer les rôles
        $superadmin = Role::create(['name' => 'superadmin', 'guard_name' => 'web']);
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $medecin = Role::create(['name' => 'medecin', 'guard_name' => 'web']);
        $user = Role::create(['name' => 'user', 'guard_name' => 'web']);

        // Assigner toutes les permissions au superadmin
        $superadmin->givePermissionTo(Permission::all());

        // Assigner des permissions à l'admin
        $admin->givePermissionTo([
            'view_users',
            'create_users',
            'edit_users',
            'view_patients',
            'create_patients',
            'edit_patients',
            'view_medical_records',
            'create_medical_records',
            'edit_medical_records',
            'view_appointments',
            'create_appointments',
            'edit_appointments',
            'view_prescriptions',
            'create_prescriptions',
            'edit_prescriptions',
        ]);

        // Assigner des permissions au médecin
        $medecin->givePermissionTo([
            'view_patients',
            'view_medical_records',
            'create_medical_records',
            'edit_medical_records',
            'view_appointments',
            'create_appointments',
            'edit_appointments',
            'view_prescriptions',
            'create_prescriptions',
            'edit_prescriptions',
        ]);

        // Assigner des permissions basiques au user
        $user->givePermissionTo([
            'view_appointments',
        ]);

        echo "✅ Rôles et permissions créés avec succès !\n";
        echo "   - superadmin (toutes les permissions)\n";
        echo "   - admin (gestion complète)\n";
        echo "   - medecin (patients, dossiers, RDV)\n";
        echo "   - user (consultation uniquement)\n";
    }
}

