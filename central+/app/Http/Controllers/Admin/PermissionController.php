<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    // Affiche la liste des rôles
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            // Super admin voit tous les rôles
            $roles = Role::all();
        } else {
            // Admin d'entité voit seulement les rôles pertinents pour son entité
            $roles = $this->getEntityRelevantRoles($user->type_utilisateur);
        }

        // Récupérer toutes les permissions disponibles pour la vue
        if ($user->isSuperAdmin()) {
            // Filtrer uniquement les permissions qui existent vraiment dans la base
            $existingPermissions = [
                'view_hopital', 'create_hopital', 'edit_hopital', 'delete_hopital', 'list_hopital',
                'view_pharmacie', 'create_pharmacie', 'edit_pharmacie', 'delete_pharmacie', 'list_pharmacie',
                'view_banque_sang', 'create_banque_sang', 'edit_banque_sang', 'delete_banque_sang', 'list_banque_sang',
                'view_centre', 'create_centre', 'edit_centre', 'delete_centre', 'list_centre',
                'view_patient', 'create_patient', 'edit_patient', 'delete_patient', 'list_patient',
                'view_dashboard', 'manage_users', 'manage_permissions', 'view_reports',
                'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'view_permissions', 'create_permissions', 'edit_permissions', 'delete_permissions',
                'gérer_les_utilisateurs',
                // Nouvelles permissions ajoutées
                'view_stocks', 'create_stocks', 'edit_stocks', 'delete_stocks', 'list_stocks',
                'view_sang', 'create_sang', 'edit_sang', 'delete_sang', 'list_sang',
                'view_prescriptions', 'create_prescriptions', 'edit_prescriptions', 'delete_prescriptions', 'list_prescriptions',
                'view_rendezvous', 'create_rendezvous', 'edit_rendezvous', 'delete_rendezvous', 'list_rendezvous'
            ];
            $permissions = Permission::whereIn('name', $existingPermissions)->get();
        } else {
            $permissions = $this->getEntityRelevantPermissions($user->type_utilisateur);
        }
        
        return view('admin.permissions.index', compact('roles', 'permissions'));
    }

    private function getEntityRelevantRoles($entityType)
    {
        // Rôles de base pour tous les admins d'entité
        $baseRoles = ['admin'];
        
        // Rôles spécifiques selon le type d'entité
        $entityRoles = [
            'hopital' => ['hopital_admin', 'hopital_staff', 'hopital_doctor', 'hopital_nurse'],
            'pharmacie' => ['pharmacie_admin', 'pharmacie_staff', 'pharmacie_pharmacist'],
            'banque_sang' => ['banque_admin', 'banque_staff', 'banque_technician'],
            'centre' => ['centre_admin', 'centre_staff', 'centre_doctor'],
        ];
        
        // Combiner les rôles de base avec les rôles spécifiques à l'entité
        $relevantRoleNames = array_merge($baseRoles, $entityRoles[$entityType] ?? []);
        
        // Récupérer les rôles existants qui correspondent
        return Role::whereIn('name', $relevantRoleNames)->get();
    }

    // Formulaire de création
    public function create()
    {
        return view('admin.permissions.create');
    }

    // Sauvegarde un rôle
    public function store(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|unique:roles',
        ]);

        // Vérifier que l'admin d'entité ne peut créer que des rôles pour son entité
        if (!$user->isSuperAdmin()) {
            $entityType = $user->type_utilisateur;
            $allowedPrefixes = [
                'hopital' => 'hopital_',
                'pharmacie' => 'pharmacie_',
                'banque_sang' => 'banque_',
                'centre' => 'centre_',
            ];
            
            $allowedPrefix = $allowedPrefixes[$entityType] ?? '';
            if (!empty($allowedPrefix) && !str_starts_with($request->name, $allowedPrefix)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Vous ne pouvez créer que des rôles pour votre entité (' . $allowedPrefix . '*)'
                ], 403);
            }
        }

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

        return response()->json(['success' => true, 'message' => 'Rôle créé avec succès']);
    }

    // Formulaire d’édition
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin.permissions.edit', compact('permission'));
    }

    // Mise à jour
    public function update(Request $request, $id)
    {
        try {
            $user = auth()->user();
            $role = Role::findOrFail($id);

            // Vérifier que l'admin d'entité ne peut modifier que les rôles de son entité
            if (!$user->isSuperAdmin()) {
                $entityType = $user->type_utilisateur;
                $allowedPrefixes = [
                    'hopital' => 'hopital_',
                    'pharmacie' => 'pharmacie_',
                    'banque_sang' => 'banque_',
                    'centre' => 'centre_',
                ];
                
                $allowedPrefix = $allowedPrefixes[$entityType] ?? '';
                if (!empty($allowedPrefix) && !str_starts_with($role->name, $allowedPrefix) && $role->name !== 'admin') {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Vous ne pouvez modifier que les rôles de votre entité'
                    ], 403);
                }
            }

            // Validation du nom du rôle (seulement si le nom a changé)
            if ($request->has('name') && $request->name !== $role->name) {
                $request->validate([
                    'name' => 'required|unique:roles,name,' . $role->id,
                ]);
                $role->update(['name' => $request->name]);
            }

            // Traiter les permissions - C'est la partie la plus importante !
            if ($request->has('permissions')) {
                \Log::info('=== MISE À JOUR DES PERMISSIONS ===');
                \Log::info('Permissions reçues (raw):', $request->permissions);
                
                // Gérer les deux cas : formulaire HTML (array) et AJAX (JSON string)
                if (is_array($request->permissions)) {
                    $permissions = $request->permissions;
                } else {
                    $permissions = json_decode($request->permissions, true);
                }
                
                \Log::info('Permissions traitées:', $permissions);
                
                if (is_array($permissions)) {
                    // Valider que toutes les permissions existent dans la base
                    $validPermissions = [];
                    $invalidPermissions = [];
                    
                    foreach ($permissions as $permissionName) {
                        $permission = Permission::where('name', $permissionName)->first();
                        if ($permission) {
                            $validPermissions[] = $permissionName;
                        } else {
                            $invalidPermissions[] = $permissionName;
                        }
                    }
                    
                    // Si des permissions invalides sont trouvées, les créer
                    if (!empty($invalidPermissions)) {
                        foreach ($invalidPermissions as $permissionName) {
                            Permission::create([
                                'name' => $permissionName,
                                'guard_name' => 'web'
                            ]);
                            $validPermissions[] = $permissionName;
                        }
                        \Log::info('Permissions créées automatiquement:', $invalidPermissions);
                    }
                    
                    // Synchroniser les permissions avec le rôle
                    $role->syncPermissions($validPermissions);
                    
                    // Vérifier les permissions après synchronisation
                    $roleAfterSync = Role::find($role->id);
                    $actualPermissions = $roleAfterSync->permissions->pluck('name')->toArray();
                    
                    // Log pour debug
                    \Log::info('Permissions synchronisées pour le rôle ' . $role->name . ':', [
                        'permissions_count' => count($validPermissions),
                        'permissions_sent' => $validPermissions,
                        'permissions_actual' => $actualPermissions,
                        'sync_success' => count($validPermissions) === count($actualPermissions)
                    ]);
                } else {
                    \Log::warning('Format de permissions invalide reçu:', $request->permissions);
                    return response()->json([
                        'success' => false, 
                        'message' => 'Format de permissions invalide'
                    ], 400);
                }
            } else {
                // Si aucune permission n'est envoyée, supprimer toutes les permissions
                $role->syncPermissions([]);
                \Log::info('Toutes les permissions supprimées pour le rôle ' . $role->name);
            }

            // Retourner une réponse selon le type de requête
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Rôle et permissions mis à jour avec succès'
                ]);
            } else {
                // Redirection pour les formulaires classiques
                return redirect()->route('admin.permissions.index')
                    ->with('success', 'Rôle et permissions mis à jour avec succès');
            }

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour du rôle: ' . $e->getMessage());
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()
                ], 500);
            } else {
                return redirect()->route('admin.permissions.index')
                    ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
            }
        }
    }

    // Suppression
    public function destroy($id)
    {
        $user = auth()->user();
        $role = Role::findOrFail($id);
        
        // Vérifier que l'admin d'entité ne peut supprimer que les rôles de son entité
        if (!$user->isSuperAdmin()) {
            $entityType = $user->type_utilisateur;
            $allowedPrefixes = [
                'hopital' => 'hopital_',
                'pharmacie' => 'pharmacie_',
                'banque_sang' => 'banque_',
                'centre' => 'centre_',
            ];
            
            $allowedPrefix = $allowedPrefixes[$entityType] ?? '';
            if (!empty($allowedPrefix) && !str_starts_with($role->name, $allowedPrefix) && $role->name !== 'admin') {
                return response()->json([
                    'success' => false, 
                    'message' => 'Vous ne pouvez supprimer que les rôles de votre entité'
                ], 403);
            }
        }
        
        // Vérifier si le rôle est utilisé par des utilisateurs
        if ($role->users()->count() > 0) {
            return response()->json([
                'success' => false, 
                'message' => 'Impossible de supprimer ce rôle car il est attribué à des utilisateurs'
            ]);
        }
        
        // Supprimer le rôle et ses permissions
        $role->delete();

        return response()->json([
            'success' => true, 
            'message' => 'Rôle supprimé avec succès'
        ]);
    }
    public function show(Permission $permission)
    {
        return view('admin.permissions.show', compact('permission'));
    }

    // Récupérer les permissions d'un rôle
    public function getRolePermissions($id)
    {
        $user = auth()->user();
        $role = Role::findOrFail($id);

        // Vérifier que l'admin d'entité ne peut voir que les rôles de son entité
        if (!$user->isSuperAdmin()) {
            $entityType = $user->type_utilisateur;
            $allowedPrefixes = [
                'hopital' => 'hopital_',
                'pharmacie' => 'pharmacie_',
                'banque_sang' => 'banque_',
                'centre' => 'centre_',
            ];
            
            $allowedPrefix = $allowedPrefixes[$entityType] ?? '';
            if (!empty($allowedPrefix) && !str_starts_with($role->name, $allowedPrefix) && $role->name !== 'admin') {
                return response()->json([
                    'success' => false, 
                    'message' => 'Vous ne pouvez voir que les rôles de votre entité'
                ], 403);
            }
        }

        // Récupérer toutes les permissions disponibles (filtrées selon l'entité)
        if ($user->isSuperAdmin()) {
            $allPermissions = Permission::all();
        } else {
            $allPermissions = $this->getEntityRelevantPermissions($user->type_utilisateur);
        }

        // Récupérer les permissions actuelles du rôle
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        // Grouper les permissions par module côté serveur
        $groupedPermissions = $this->groupPermissionsByModule($allPermissions);

        return response()->json([
            'success' => true,
            'permissions' => $allPermissions,
            'role_permissions' => $rolePermissions,
            'grouped_permissions' => $groupedPermissions
        ]);
    }

    // Vérifier les permissions d'un rôle après mise à jour
    public function verifyRolePermissions($id)
    {
        $role = Role::findOrFail($id);
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        
        return response()->json([
            'success' => true,
            'role_name' => $role->name,
            'permissions_count' => count($rolePermissions),
            'permissions' => $rolePermissions,
            'message' => "Le rôle '{$role->name}' a {$rolePermissions} permissions"
        ]);
    }

    // Méthode pour filtrer les permissions selon l'entité
    private function getEntityRelevantPermissions($entityType)
    {
        // Utiliser les vraies permissions de la base de données selon l'entité
        $permissionGroups = [
            'hopital' => [
                'view_hopital', 'create_hopital', 'edit_hopital', 'delete_hopital', 'list_hopital',
                'view_patient', 'create_patient', 'edit_patient', 'delete_patient',
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
                'view_permissions', 'create_permissions', 'edit_permissions', 'delete_permissions',
                'view_dashboard', 'create_dashboard', 'edit_dashboard', 'delete_dashboard',
                'view_reports', 'create_reports', 'edit_reports', 'delete_reports'
            ],
            'pharmacie' => [
                'view_pharmacie', 'create_pharmacie', 'edit_pharmacie', 'delete_pharmacie', 'list_pharmacie',
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
                'view_permissions', 'create_permissions', 'edit_permissions', 'delete_permissions',
                'view_dashboard', 'create_dashboard', 'edit_dashboard', 'delete_dashboard',
                'view_reports', 'create_reports', 'edit_reports', 'delete_reports'
            ],
            'banque_sang' => [
                'view_banque_sang', 'create_banque_sang', 'edit_banque_sang', 'delete_banque_sang', 'list_banque_sang',
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
                'view_permissions', 'create_permissions', 'edit_permissions', 'delete_permissions',
                'view_dashboard', 'create_dashboard', 'edit_dashboard', 'delete_dashboard',
                'view_reports', 'create_reports', 'edit_reports', 'delete_reports'
            ],
            'centre' => [
                'view_centre', 'create_centre', 'edit_centre', 'delete_centre', 'list_centre',
                'view_patient', 'create_patient', 'edit_patient', 'delete_patient',
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
                'view_permissions', 'create_permissions', 'edit_permissions', 'delete_permissions',
                'view_dashboard', 'create_dashboard', 'edit_dashboard', 'delete_dashboard',
                'view_reports', 'create_reports', 'edit_reports', 'delete_reports'
            ]
        ];

        $relevantPermissions = $permissionGroups[$entityType] ?? [];
        
        return Permission::whereIn('name', $relevantPermissions)->get();
    }

    // Méthode pour grouper les permissions par module côté serveur
    private function groupPermissionsByModule($permissions)
    {
        $grouped = [];
        
        foreach ($permissions as $permission) {
            $parts = explode('_', $permission->name);
            if (count($parts) >= 2) {
                $action = $parts[0]; // view, create, edit, delete
                $module = implode('_', array_slice($parts, 1)); // le reste forme le nom du module
                
                // Nettoyer et normaliser le nom du module
                $cleanModule = strtolower($module);
                
                // Mapper les modules vers des noms cohérents
                $moduleMapping = [
                    'les_utilisateurs' => 'users',
                    'utilisateurs' => 'users',
                    'utilisateurs_view' => 'users',
                    'utilisateurs_create' => 'users',
                    'utilisateurs_edit' => 'users',
                    'utilisateurs_delete' => 'users',
                    'hopital' => 'hopitals',
                    'hopitals' => 'hopitals',
                    'pharmacie' => 'pharmacies',
                    'pharmacies' => 'pharmacies',
                    'banque_sang' => 'banque_sang',
                    'banque_sangs' => 'banque_sang',
                    'centre' => 'centres',
                    'centres' => 'centres',
                    'patient' => 'patients',
                    'patients' => 'patients',
                    'dashboard' => 'dashboard',
                    'permissions' => 'permissions',
                    'permission' => 'permissions',
                    'roles' => 'roles',
                    'role' => 'roles',
                    'appointments' => 'appointments',
                    'appointment' => 'appointments',
                    'medical_records' => 'medical_records',
                    'medical_record' => 'medical_records',
                    'prescriptions' => 'prescriptions',
                    'prescription' => 'prescriptions',
                    'invoices' => 'invoices',
                    'invoice' => 'invoices',
                    'reports' => 'reports',
                    'report' => 'reports',
                    'medicines' => 'medicines',
                    'medicine' => 'medicines',
                    'stocks' => 'stocks',
                    'stock' => 'stocks',
                    'donors' => 'donors',
                    'donor' => 'donors',
                    'blood_reserves' => 'blood_reserves',
                    'services' => 'services',
                    'consultations' => 'consultations',
                    'consultation' => 'consultations',
                    'view' => 'general',
                    'create' => 'general',
                    'edit' => 'general',
                    'delete' => 'general',
                    'sang' => 'blood_reserves'
                ];
                
                // Appliquer le mapping
                if (isset($moduleMapping[$cleanModule])) {
                    $cleanModule = $moduleMapping[$cleanModule];
                }
                
                // Ignorer les permissions trop génériques
                if ($cleanModule === 'general') {
                    continue;
                }
                
                if (!isset($grouped[$cleanModule])) {
                    $grouped[$cleanModule] = [];
                }
                $grouped[$cleanModule][] = $permission;
            }
        }

        return $grouped;
    }

    // Méthode pour obtenir le nom d'affichage d'un module
    private function getModuleDisplayName($moduleName)
    {
        $displayNames = [
            'roles' => 'Gérer les Rôles et Permissions',
            'users' => 'Gérer les Utilisateurs',
            'patients' => 'Gérer les Patients',
            'appointments' => 'Gérer les Rendez-vous',
            'medical_records' => 'Gérer les Dossiers Médicaux',
            'prescriptions' => 'Gérer les Prescriptions',
            'invoices' => 'Gérer les Factures',
            'reports' => 'Gérer les Rapports',
            'medicines' => 'Gérer les Médicaments',
            'stocks' => 'Gérer les Stocks',
            'donors' => 'Gérer les Donneurs',
            'consultations' => 'Gérer les Consultations',
            'hopitals' => 'Gérer les Hôpitaux',
            'pharmacies' => 'Gérer les Pharmacies',
            'banque_sang' => 'Gérer les Banques de Sang',
            'centres' => 'Gérer les Centres',
            'permissions' => 'Gérer les Permissions',
            'dashboard' => 'Gérer le Tableau de Bord',
            'blood_reserves' => 'Gérer les Réserves de Sang',
            'services' => 'Gérer les Services'
        ];

        return $displayNames[$moduleName] ?? 'Gérer les ' . ucfirst($moduleName);
    }
}
