<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Utilisateur;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB; // Added this import for DB facade

echo "Nettoyage des permissions du superadmin...\n";

// Trouver le superadmin
$superadmin = Utilisateur::where('email', 'admin@central.com')->first();

if (!$superadmin) {
    echo "Aucun superadmin trouvé !\n";
    exit(1);
}

echo "Superadmin trouvé : {$superadmin->email}\n";

// Supprimer toutes les permissions actuelles
$superadmin->syncPermissions([]);

echo "Toutes les permissions supprimées.\n";

// Permissions spécifiques du superadmin
$superAdminPermissions = [
    // CRUD sur les entités
    'view_hopital', 'create_hopital', 'edit_hopital', 'delete_hopital',
    'view_pharmacie', 'create_pharmacie', 'edit_pharmacie', 'delete_pharmacie',
    'view_banque_sang', 'create_banque_sang', 'edit_banque_sang', 'delete_banque_sang',
    'view_centre', 'create_centre', 'edit_centre', 'delete_centre',
    
    // CRUD sur les utilisateurs
    'view_users', 'create_users', 'edit_users', 'delete_users',
    'manage_users', 'gérer_les_utilisateurs',
    
    // CRUD sur les rôles et permissions
    'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
    'manage_permissions',
    
    // Permissions de gestion générale
    'view_dashboard', 'view_reports', 'create_reports', 'edit_reports', 'delete_reports'
];

// Créer les permissions si elles n'existent pas
foreach ($superAdminPermissions as $permissionName) {
    Permission::firstOrCreate([
        'name' => $permissionName,
        'guard_name' => 'web'
    ]);
}

// Récupérer seulement les permissions spécifiques
$permissions = Permission::whereIn('name', $superAdminPermissions)->get();

// Attribuer seulement ces permissions
$superadmin->syncPermissions($permissions);

// Forcer la mise à jour en supprimant toutes les relations existantes
DB::table('model_has_permissions')->where('model_id', $superadmin->id)->delete();

// Attribuer manuellement chaque permission
foreach ($permissions as $permission) {
    DB::table('model_has_permissions')->insert([
        'permission_id' => $permission->id,
        'model_type' => 'App\Models\Utilisateur',
        'model_id' => $superadmin->id
    ]);
}

// S'assurer que le rôle superadmin existe
$superAdminRole = Role::firstOrCreate([
    'name' => 'superadmin',
    'guard_name' => 'web'
]);

// Attribuer le rôle superadmin
$superadmin->assignRole($superAdminRole);

// Vérifier les permissions actuelles
$currentPermissions = $superadmin->getAllPermissions();

echo "Permissions attribuées au superadmin : {$currentPermissions->count()}\n";

foreach ($currentPermissions as $permission) {
    echo "- {$permission->name}\n";
}

echo "✅ Superadmin a maintenant EXACTEMENT les permissions demandées !\n";
