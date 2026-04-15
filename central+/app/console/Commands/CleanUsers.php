<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class CleanUsers extends Command
{
    protected $signature = 'users:clean';
    protected $description = 'Supprime tous les utilisateurs sauf le superadmin';

    public function handle()
    {
        $this->info('🧹 Nettoyage de la base de données utilisateurs...');

        // Compter le nombre total d'utilisateurs
        $totalUsers = Utilisateur::count();
        $this->line("📊 Nombre total d'utilisateurs avant nettoyage : {$totalUsers}");

        // Identifier le superadmin (email: admin@central.com)
        $superadmin = Utilisateur::where('email', 'admin@central.com')->first();
        
        if (!$superadmin) {
            $this->warn('⚠️  Aucun superadmin trouvé avec l\'email admin@central.com');
            $this->line('🔧 Création du superadmin...');
            
            // Créer le superadmin s'il n'existe pas
            $superadmin = Utilisateur::create([
                'nom' => 'Super Administrateur',
                'email' => 'admin@central.com',
                'mot_de_passe' => Hash::make('admin123'),
                'role' => 'superadmin',
                'type_utilisateur' => 'admin',
                'entite_id' => null
            ]);
            
            $this->line('✅ Superadmin créé avec succès');
        } else {
            $this->line('✅ Superadmin trouvé : ' . $superadmin->nom);
        }

        // Supprimer tous les autres utilisateurs
        $usersToDelete = Utilisateur::where('id', '!=', $superadmin->id)->get();
        $countToDelete = $usersToDelete->count();

        if ($countToDelete > 0) {
            $this->line("🗑️  Suppression de {$countToDelete} utilisateur(s)...");
            
            foreach ($usersToDelete as $user) {
                $this->line("   - Suppression de : {$user->nom} ({$user->email})");
                $user->delete();
            }
            
            $this->info("✅ {$countToDelete} utilisateur(s) supprimé(s) avec succès");
        } else {
            $this->line('ℹ️  Aucun utilisateur à supprimer');
        }

        // Compter le nombre d'utilisateurs après nettoyage
        $remainingUsers = Utilisateur::count();
        $this->line("📊 Nombre d'utilisateurs après nettoyage : {$remainingUsers}");

        // Afficher les informations du superadmin
        $this->info('👑 Superadmin conservé :');
        $this->line("   - Nom : {$superadmin->nom}");
        $this->line("   - Email : {$superadmin->email}");
        $this->line("   - Rôle : {$superadmin->role}");
        $this->line("   - Type : {$superadmin->type_utilisateur}");

        $this->info('🎉 Nettoyage terminé avec succès !');
        return 0;
    }
}
