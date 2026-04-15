<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Utilisateur;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EnsureSuperAdminPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:ensure-superadmin-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'S\'assurer que le superadmin a toutes les permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Vérification et attribution des permissions au superadmin...');

        // Trouver le superadmin
        $superadmin = Utilisateur::where('role', 'superadmin')
            ->orWhere('email', 'admin@central.com')
            ->first();

        if (!$superadmin) {
            $this->error('Aucun superadmin trouvé !');
            return 1;
        }

        $this->info("Superadmin trouvé : {$superadmin->email}");

        // Récupérer toutes les permissions existantes
        $allPermissions = Permission::all();
        
        if ($allPermissions->isEmpty()) {
            $this->warn('Aucune permission trouvée dans la base de données.');
            return 1;
        }

        $this->info("Nombre total de permissions : {$allPermissions->count()}");

        // Attribuer toutes les permissions au superadmin
        $superadmin->syncPermissions($allPermissions);

        // S'assurer que le rôle superadmin existe
        $superAdminRole = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web'
        ]);

        // Attribuer le rôle superadmin
        $superadmin->assignRole($superAdminRole);

        // Vérifier les permissions actuelles
        $currentPermissions = $superadmin->getAllPermissions();
        
        $this->info("Permissions attribuées au superadmin : {$currentPermissions->count()}");

        // Afficher les permissions
        $this->table(
            ['Permission'],
            $currentPermissions->pluck('name')->map(function($name) {
                return [$name];
            })
        );

        $this->info('✅ Superadmin a maintenant toutes les permissions !');
        
        return 0;
    }
}
