@extends('layouts.admin')

@section('title', 'Gestion des Rôles - CENTRAL+')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Messages de succès/erreur -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Entête simple -->
            <div class="page-header mb-4" style="background: #003366; padding: 1.5rem; border-radius: 8px;">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 style="color: white; margin: 0; font-size: 1.8rem; font-weight: 500;">Gestion des Rôles</h1>
                    <button type="button" class="btn" style="background: white; color: #003366; border: none; padding: 0.75rem 1.5rem; border-radius: 4px; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#createRoleModal" onclick="resetPermissions()">
                        + Nouveau Rôle
                    </button>
                </div>
            </div>

            <!-- Tableau des rôles -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background: #f8f9fa;">
                                <tr>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">#</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">Nom du Rôle</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">Créé le</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                <tr style="border-bottom: 1px solid #e9ecef;">
                                    <td style="padding: 1rem; vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td style="padding: 1rem; vertical-align: middle; font-weight: 500;">{{ $role->name }}</td>
                                    <td style="padding: 1rem; vertical-align: middle; color: #6c757d;">{{ $role->created_at->format('d/m/Y') }}</td>
                                    <td style="padding: 1rem; vertical-align: middle;">
                                        <div class="action-buttons">
                                            <button class="btn btn-icon btn-edit"
                                                onclick="editRole('{{ $role->id }}', '{{ $role->name }}')"
                                                title="Modifier le rôle">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-icon btn-delete"
                                                onclick="deleteRole('{{ $role->id }}', '{{ $role->name }}')"
                                                title="Supprimer le rôle">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4" style="color: #6c757d;">
                                        Aucun rôle créé pour le moment.
                                        <span style="color: #003366;">Utilisez le bouton "Nouveau Rôle" ci-dessus pour créer votre premier rôle</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour créer un nouveau rôle -->
<div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #003366; color: white;">
                <h5 class="modal-title" id="createRoleModalLabel">Créer un Nouveau Rôle</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createRoleForm">
                    @csrf
                    <div class="mb-3">
                        <label for="roleName" class="form-label">Nom du rôle</label>
                        <input type="text" class="form-control" id="roleName" name="name" placeholder="Ex: manager_hopital" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn" style="background: #003366; color: white; border: none;" onclick="createRole()">Créer le Rôle</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour éditer un rôle et attribuer des permissions -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: #003366; color: white;">
                <h5 class="modal-title" id="editRoleModalLabel">Modifier le Rôle: <span id="editRoleName"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editRoleForm" method="POST" action="" onsubmit="return validateForm()">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editRoleId" name="role_id">

                    <!-- Nom du rôle -->
                    <div class="mb-3">
                        <label for="editRoleNameInput" class="form-label">Nom du rôle</label>
                        <input type="text" class="form-control" id="editRoleNameInput" name="name" required>
                    </div>

                    <!-- Permissions -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Attribuer des Permissions</label>

                        <!-- En-tête des actions CRUD -->
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <strong>Module</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <strong>Voir</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <strong>Créer</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <strong>Modifier</strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <strong>Supprimer</strong>
                            </div>
                        </div>

                        <!-- Permissions dynamiques depuis la base -->
                        @php
                            // On groupe les permissions par "module" avec normalisation
                            $grouped = [];
                            
                            // Permissions qui existent vraiment dans votre base de données
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
                            
                            foreach($permissions as $permission) {
                                // Ne traiter que les permissions qui existent vraiment
                                if (!in_array($permission->name, $existingPermissions)) {
                                    continue;
                                }
                                
                                $parts = explode('_', $permission->name);
                                if(count($parts) >= 2) {
                                    $action = $parts[0]; // view, create, edit, delete, list, manage
                                    $module = implode('_', array_slice($parts, 1));
                                    
                                    // Normaliser le nom du module selon votre base de données
                                    $cleanModule = strtolower($module);
                                    
                                    // Mapping basé sur vos vraies permissions de la base
                                    $moduleMapping = [
                                        'hopital' => 'hopitals',
                                        'pharmacie' => 'pharmacies', 
                                        'banque_sang' => 'banque_sang',
                                        'centre' => 'centres',
                                        'patient' => 'patients',
                                        'dashboard' => 'dashboard',
                                        'permissions' => 'permissions',
                                        'roles' => 'roles',
                                        'reports' => 'reports',
                                        'users' => 'users',
                                        'utilisateurs' => 'users',
                                        'les_utilisateurs' => 'users',
                                        'gérer' => 'users', // pour gérer_les_utilisateurs
                                        // Nouvelles permissions
                                        'stocks' => 'stocks',
                                        'sang' => 'sang',
                                        'prescriptions' => 'prescriptions',
                                        'rendezvous' => 'rendezvous'
                                    ];
                                    
                                    if (isset($moduleMapping[$cleanModule])) {
                                        $cleanModule = $moduleMapping[$cleanModule];
                                    }
                                    
                                    // Ignorer les permissions trop génériques
                                    if (in_array($cleanModule, ['view', 'create', 'edit', 'delete', 'list', 'manage'])) {
                                        continue;
                                    }
                                    
                                    $grouped[$cleanModule][$action] = $permission->name;
                                }
                            }
                        @endphp
                        
                        @php
                            // Fonction pour obtenir le nom d'affichage d'un module
                            function getModuleDisplayName($moduleName) {
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
                                    'stocks' => 'Gérer les Stocks de Médicaments',
                                    'donors' => 'Gérer les Donneurs',
                                    'consultations' => 'Gérer les Consultations',
                                    'hopitals' => 'Gérer les Hôpitaux',
                                    'pharmacies' => 'Gérer les Pharmacies',
                                    'banque_sang' => 'Gérer les Banques de Sang',
                                    'centres' => 'Gérer les Centres',
                                    'permissions' => 'Gérer les Permissions',
                                    'dashboard' => 'Gérer le Tableau de Bord',
                                    'blood_reserves' => 'Gérer les Réserves de Sang',
                                    'services' => 'Gérer les Services',
                                    // Nouvelles permissions
                                    'sang' => 'Gérer les Sangs',
                                    'rendezvous' => 'Gérer les Rendez-vous'
                                ];
                                return $displayNames[$moduleName] ?? 'Gérer les ' . ucfirst(str_replace('_', ' ', $moduleName));
                            }
                        @endphp
                        
                        @php
                            // Trier les modules par ordre alphabétique
                            ksort($grouped);
                        @endphp
                        
                        <div id="permissionsContainer">
                            @foreach($grouped as $module => $actions)
                                <div class="row mb-3 align-items-center">
                                    <div class="col-md-3">
                                        <strong>{{ getModuleDisplayName($module) }}</strong>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        @if(isset($actions['view']))
                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $actions['view'] }}" id="perm_{{ $actions['view'] }}">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                    <div class="col-md-2 text-center">
                                        @if(isset($actions['create']))
                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $actions['create'] }}" id="perm_{{ $actions['create'] }}">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                    <div class="col-md-2 text-center">
                                        @if(isset($actions['edit']))
                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $actions['edit'] }}" id="perm_{{ $actions['edit'] }}">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                    <div class="col-md-2 text-center">
                                        @if(isset($actions['delete']))
                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $actions['delete'] }}" id="perm_{{ $actions['delete'] }}">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                        </div>
                    </div>
                            @endforeach
                        </div>
            </div>
                    
                    <!-- Boutons du formulaire -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn" style="background: #003366; color: white; border: none;">
                    Mettre à jour
                </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .table th {
        font-weight: 600;
        color: #003366;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-outline-warning:hover {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
        color: #fff;
    }
</style>

<script>
    function createRole() {
        const form = document.getElementById('createRoleForm');
        const formData = new FormData(form);

        fetch('{{ route("admin.permissions.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Rôle créé avec succès !');
                    // Fermer le modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createRoleModal'));
                    modal.hide();
                    // Recharger la page pour voir le nouveau rôle
                    location.reload();
                } else {
                    alert('Erreur lors de la création du rôle : ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la création du rôle');
            });
    }

    // Fonction pour décocher toutes les permissions lors de l'ouverture du modal de création
    function resetPermissions() {
        document.querySelectorAll('#permissionsContainer input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
            });
    }

    // Réinitialiser le formulaire quand le modal se ferme
    document.getElementById('createRoleModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('createRoleForm').reset();
    });

    // Fonction pour éditer un rôle
    function editRole(roleId, roleName) {
        console.log('=== ÉDITION DU RÔLE ===');
        console.log('Role ID:', roleId);
        console.log('Role Name:', roleName);
        
        // Définir l'action du formulaire
        const form = document.getElementById('editRoleForm');
        form.action = '{{ route("admin.permissions.update", ":id") }}'.replace(':id', roleId);
        
        // Remplir le modal avec les données du rôle
        document.getElementById('editRoleId').value = roleId;
        document.getElementById('editRoleName').textContent = roleName;
        document.getElementById('editRoleNameInput').value = roleName;

        // Vérifier que les champs sont bien remplis
        console.log('editRoleId value:', document.getElementById('editRoleId').value);
        console.log('editRoleNameInput value:', document.getElementById('editRoleNameInput').value);

        // Charger les permissions dynamiquement
        loadRolePermissions(roleId);

        // Ouvrir le modal
        const editModal = new bootstrap.Modal(document.getElementById('editRoleModal'));
        editModal.show();
    }

    // Fonction pour charger les permissions d'un rôle
    function loadRolePermissions(roleId) {
        fetch(`/admin/permissions/${roleId}/permissions`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('=== DEBUG PERMISSIONS ===');
            console.log('Permissions du rôle:', data.role_permissions);
            
            if (data.success) {
                console.log('=== CHARGEMENT DES PERMISSIONS ===');
                console.log('Permissions du rôle à charger:', data.role_permissions);
                
                // Décocher toutes les cases d'abord
                const allCheckboxes = document.querySelectorAll('#permissionsContainer input[type="checkbox"]');
                console.log('Total checkboxes trouvées:', allCheckboxes.length);
                
                allCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                
                // Cocher les permissions du rôle
                let permissionsCochees = 0;
                data.role_permissions.forEach(permissionName => {
                    const checkbox = document.getElementById(`perm_${permissionName}`);
                    if (checkbox) {
                        checkbox.checked = true;
                        permissionsCochees++;
                        console.log(`✅ Permission cochée: ${permissionName}`);
                    } else {
                        console.log(`❌ Checkbox non trouvée pour: ${permissionName}`);
                    }
                });
                
                console.log(`Permissions cochées: ${permissionsCochees}/${data.role_permissions.length}`);
            } else {
                console.error('Erreur lors du chargement des permissions:', data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
    }




    // Fonction simple pour valider le formulaire avant soumission
    function validateForm() {
        const roleName = document.getElementById('editRoleNameInput').value;
        if (!roleName || roleName.trim() === '') {
            alert('Le nom du rôle est obligatoire');
            return false;
        }
        return true;
    }

    // Fonction pour supprimer un rôle
    function deleteRole(roleId, roleName) {
        if (confirm(`Êtes-vous sûr de vouloir supprimer le rôle "${roleName}" ?\n\nCette action est irréversible.`)) {
            fetch('{{ route("admin.permissions.destroy", ":id") }}'.replace(':id', roleId), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Rôle supprimé avec succès !');
                        // Recharger la page pour voir les changements
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression : ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la suppression du rôle');
                });
        }
    }

    // Ajouter le CSS pour les boutons d'action
    document.addEventListener('DOMContentLoaded', function() {
        const style = document.createElement('style');
        style.textContent = `
        /* Boutons d'action stylisés */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .btn-edit {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: white;
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, #e0a800, #d39e00);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
        }

        .btn-delete {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }

        .btn-delete:hover {
            background: linear-gradient(135deg, #c82333, #bd2130);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }

        /* Animation au survol */
        .btn-icon:hover {
            transform: translateY(-2px);
        }

        .btn-icon:focus {
            outline: 2px solid #003366;
            outline-offset: 2px;
        }

        /* Animation d'apparition */
        .btn-icon {
            animation: fadeInUp 0.3s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive pour petits écrans */
        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
                gap: 0.25rem;
            }
            
            .btn-icon {
                width: 32px;
                height: 32px;
                font-size: 0.8rem;
            }
        }
    `;
        document.head.appendChild(style);
    });
</script>
@endsection