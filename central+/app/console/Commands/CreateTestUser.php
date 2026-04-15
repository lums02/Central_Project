<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Utilisateur;
use App\Models\Hopital;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    protected $signature = 'create:test-user';
    protected $description = 'Crée un utilisateur de test en attente d\'approbation';

    public function handle()
    {
        $this->info('🧪 Création d\'un utilisateur de test...');

        // Créer un hôpital de test
        $hopital = Hopital::create([
            'nom' => 'Hôpital de Test',
            'email' => 'test@hopital.com',
            'adresse' => '123 Rue de Test, Ville Test',
            'type_hopital' => 'general',
            'nombre_lits' => 100,
        ]);

        $this->line("✅ Hôpital créé : {$hopital->nom}");

        // Créer un utilisateur en attente
        $utilisateur = Utilisateur::create([
            'nom' => 'Dr. Test Médecin',
            'email' => 'medecin@test.com',
            'mot_de_passe' => Hash::make('password123'),
            'role' => 'medecin',
            'type_utilisateur' => 'hopital',
            'entite_id' => $hopital->id,
            'status' => 'pending', // En attente d'approbation
        ]);

        $this->line("✅ Utilisateur créé : {$utilisateur->nom}");
        $this->line("📧 Email : {$utilisateur->email}");
        $this->line("🔑 Mot de passe : password123");
        $this->line("📊 Statut : {$utilisateur->status}");
        $this->line("🏥 Entité : {$hopital->nom}");

        $this->info('🎉 Utilisateur de test créé avec succès !');
        $this->line('💡 Maintenant va sur la page "En Attente" pour l\'approuver ou le rejeter.');

        return 0;
    }
}
