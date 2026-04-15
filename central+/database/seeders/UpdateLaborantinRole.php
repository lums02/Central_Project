<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UpdateLaborantinRole extends Seeder
{
    public function run(): void
    {
        // Créer les permissions pour les examens
        Permission::firstOrCreate(['name' => 'view_exams', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create_exams', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit_exams', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'upload_exam_results', 'guard_name' => 'web']);

        // Mettre à jour les permissions du laborantin
        $laborantin = Role::where('name', 'laborantin')->first();
        
        if ($laborantin) {
            $laborantin->syncPermissions([
                'view_exams',
                'create_exams',
                'edit_exams',
                'upload_exam_results'
            ]);
            
            echo "✅ Permissions du laborantin mises à jour !\n";
            echo "   - Voir les examens\n";
            echo "   - Créer des examens\n";
            echo "   - Modifier des examens\n";
            echo "   - Uploader les résultats d'examens\n";
        } else {
            echo "❌ Rôle laborantin introuvable\n";
        }
    }
}

