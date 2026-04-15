<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un superadmin
        DB::table('utilisateurs')->insert([
            'nom' => 'Super Admin',
            'prenom' => 'CENTRAL+',
            'email' => 'admin@central.com',
            'type_utilisateur' => 'hopital',
            'entite_id' => null,
            'mot_de_passe' => Hash::make('password'),
            'role' => 'superadmin',
            'status' => 'approved',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        echo "✅ Superadmin créé : admin@central.com / password\n";
    }
}

