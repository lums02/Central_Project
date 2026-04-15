<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Role;

echo "Vérification des permissions du rôle superadmin...\n";

$role = Role::where('name', 'superadmin')->first();

if ($role) {
    echo "Rôle superadmin trouvé.\n";
    echo "Permissions attribuées :\n";
    
    $permissions = $role->permissions;
    foreach ($permissions as $permission) {
        echo "- {$permission->name}\n";
    }
    
    echo "Total : " . $permissions->count() . " permissions\n";
} else {
    echo "Rôle superadmin non trouvé !\n";
}
