<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Correction des permissions du superadmin...\n";

// Supprimer toutes les permissions du superadmin
DB::table('model_has_permissions')->where('model_type', 'App\Models\Utilisateur')->delete();

echo "Toutes les permissions supprimées.\n";

// Permissions spécifiques du superadmin
$superAdminPermissions = [
    'view_hopital', 'create_hopital', 'edit_hopital', 'delete_hopital',
    'view_pharmacie', 'create_pharmacie', 'edit_pharmacie', 'delete_pharmacie',
    'view_banque_sang', 'create_banque_sang', 'edit_banque_sang', 'delete_banque_sang',
    'view_centre', 'create_centre', 'edit_centre', 'delete_centre',
    'view_users', 'create_users', 'edit_users', 'delete_users',
    'manage_users', 'gérer_les_utilisateurs',
    'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
    'manage_permissions',
    'view_dashboard', 'view_reports', 'create_reports', 'edit_reports', 'delete_reports'
];

// Récupérer les IDs des permissions
$permissionIds = DB::table('permissions')->whereIn('name', $superAdminPermissions)->pluck('id');

echo "Permissions trouvées : " . $permissionIds->count() . "\n";

// Récupérer l'ID du superadmin
$superadminId = DB::table('utilisateurs')->where('email', 'admin@central.com')->value('id');

if (!$superadminId) {
    echo "Superadmin non trouvé !\n";
    exit(1);
}

echo "Superadmin ID : {$superadminId}\n";

// Attribuer chaque permission
foreach ($permissionIds as $permissionId) {
    DB::table('model_has_permissions')->insert([
        'permission_id' => $permissionId,
        'model_type' => 'App\Models\Utilisateur',
        'model_id' => $superadminId
    ]);
}

echo "✅ Permissions attribuées avec succès !\n";

// Vérifier
$count = DB::table('model_has_permissions')
    ->where('model_type', 'App\Models\Utilisateur')
    ->where('model_id', $superadminId)
    ->count();

echo "Nombre de permissions attribuées : {$count}\n";
