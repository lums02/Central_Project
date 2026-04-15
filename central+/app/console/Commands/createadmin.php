<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class createadmin extends Command
{
    protected $signature = 'create:admin';
    protected $description = 'Crée un super administrateur avec des coordonnées spécifiques';

    public function handle()
    {
        $this->info('Création du super administrateur...');

        // Coordonnées du super admin
        $adminData = [
            'nom' => 'Super Admin',
            'email' => 'admin@central.com',
            'mot_de_passe' => 'admin123',
            'role' => 'admin',
            'type_utilisateur' => 'admin',
            'entite_id' => null
        ];

        // Vérifier si l'admin existe déjà
        $existingAdmin = Utilisateur::where('email', $adminData['email'])->first();
        
        if ($existingAdmin) {
            $this->warn('Un administrateur avec cet email existe déjà !');
            $this->line("Email: {$existingAdmin->email}");
            $this->line("Nom: {$existingAdmin->nom}");
            $this->line("Rôle: {$existingAdmin->role}");
            return;
        }

        // Créer le super admin
        $admin = Utilisateur::create([
            'nom' => $adminData['nom'],
            'email' => $adminData['email'],
            'mot_de_passe' => Hash::make($adminData['mot_de_passe']),
            'role' => $adminData['role'],
            'type_utilisateur' => $adminData['type_utilisateur'],
            'entite_id' => $adminData['entite_id']
        ]);

        $this->info('✅ Super administrateur créé avec succès !');
        $this->line("Email: {$admin->email}");
        $this->line("Mot de passe: {$adminData['mot_de_passe']}");
        $this->line("Rôle: {$admin->role}");
        $this->line("Type: {$admin->type_utilisateur}");

        return 0;
    }
}
