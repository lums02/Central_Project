@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs - CENTRAL+')
@section('page-title', 'Gestion des Utilisateurs')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Entête simple -->
            <div class="page-header mb-4" style="background: #003366; padding: 1.5rem; border-radius: 8px;">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 style="color: white; margin: 0; font-size: 1.8rem; font-weight: 500;">Gestion des Utilisateurs</h1>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn" style="background: #28a745; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 4px; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#createUserModal">
                            <i class="fas fa-user-plus me-2"></i>Nouvel Utilisateur
                        </button>
                        <a href="{{ route('admin.users.pending') }}" class="btn" style="background: #ffc107; color: #000; border: none; padding: 0.75rem 1.5rem; border-radius: 4px; font-weight: 600;">
                            <i class="fas fa-clock me-2"></i>Utilisateurs en Attente
                            <span class="badge bg-danger text-white ms-2" id="pendingBadge">0</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tableau des utilisateurs -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background: #f8f9fa;">
                                <tr>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">#</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">Nom</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">Email</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">Type</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">Rôle</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">Inscrit le</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600; text-align: center; width: 120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($utilisateurs as $utilisateur)
                                <tr style="border-bottom: 1px solid #e9ecef;">
                                    <td style="padding: 1rem; vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td style="padding: 1rem; vertical-align: middle; font-weight: 500;">{{ $utilisateur->nom }}</td>
                                    <td style="padding: 1rem; vertical-align: middle;">{{ $utilisateur->email }}</td>
                                    <td style="padding: 1rem; vertical-align: middle;">
                                        <span class="badge" style="background: #17a2b8; color: white; padding: 0.5rem 0.75rem; border-radius: 4px;">
                                            {{ ucfirst($utilisateur->type_utilisateur) }}
                                        </span>
                                        <br>
                                        <span class="badge mt-1" style="background: {{ $utilisateur->status === 'approved' ? '#28a745' : ($utilisateur->status === 'disabled' ? '#dc3545' : '#ffc107') }}; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">
                                            {{ $utilisateur->status === 'approved' ? 'Actif' : ($utilisateur->status === 'disabled' ? 'Désactivé' : 'En attente') }}
                                        </span>
                                    </td>
                                    <td style="padding: 1rem; vertical-align: middle;">
                                        <span class="badge" style="background: #28a745; color: white; padding: 0.5rem 0.75rem; border-radius: 4px;">
                                            {{ ucfirst($utilisateur->role) }}
                                        </span>
                                    </td>
                                    <td style="padding: 1rem; vertical-align: middle; color: #6c757d;">
                                        {{ $utilisateur->created_at ? $utilisateur->created_at->format('d/m/Y H:i') : 'N/A' }}
                                    </td>
                                    <td style="padding: 1rem; vertical-align: middle;">
                                        <div class="action-buttons">
                                            @if($utilisateur->role !== 'superadmin' && $utilisateur->email !== 'admin@central.com')
                                                <button class="btn btn-icon {{ $utilisateur->status === 'approved' ? 'btn-success' : 'btn-warning' }}" 
                                                        onclick="toggleUserStatus({{ $utilisateur->id }}, '{{ $utilisateur->status }}')" 
                                                        title="{{ $utilisateur->status === 'approved' ? 'Désactiver l\'utilisateur' : 'Activer l\'utilisateur' }}">
                                                    <i class="fas {{ $utilisateur->status === 'approved' ? 'fa-user-check' : 'fa-user-times' }}"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-icon btn-success" disabled title="Le superadmin est toujours actif" style="opacity: 0.5; cursor: not-allowed;">
                                                    <i class="fas fa-shield-alt"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-icon btn-permissions" onclick="openPermissionsModal({{ $utilisateur->id }}, '{{ addslashes($utilisateur->nom) }}')" title="Gérer les permissions">
                                                <i class="fas fa-shield-alt"></i>
                                            </button>
                                            <button class="btn btn-icon btn-edit" onclick="openEditModal({{ $utilisateur->id }})" title="Modifier l'utilisateur">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if($utilisateur->role !== 'superadmin' && $utilisateur->email !== 'admin@central.com')
                                                <button class="btn btn-icon btn-delete" onclick="deleteUser({{ $utilisateur->id }})" title="Supprimer l'utilisateur">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-icon btn-delete" disabled title="Le superadmin ne peut pas être supprimé" style="opacity: 0.5; cursor: not-allowed;">
                                                    <i class="fas fa-shield-alt"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4" style="color: #6c757d;">
                                        Aucun utilisateur trouvé.
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

<!-- Modal pour les permissions (format tableau CRUD) -->
<div class="modal fade" id="permissionsModal" tabindex="-1" aria-labelledby="permissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: #003366; color: white;">
                <h5 class="modal-title" id="permissionsModalLabel">Gérer les Permissions - <span id="userName"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="permissionsForm">
                    @csrf
                    <input type="hidden" id="userId" name="user_id">
                    
                    <!-- Informations de l'utilisateur -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Rôle de l'utilisateur</label>
                                <select name="role" id="userRole" class="form-select">
                                    <option value="user">Utilisateur</option>
                                    <option value="admin">Administrateur</option>
                                    <option value="manager">Manager</option>
                                    <option value="moderator">Modérateur</option>
                                    <option value="superadmin">Super Administrateur</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Type d'utilisateur</label>
                                <select name="type_utilisateur" id="userType" class="form-select">
                                    <option value="hopital">Hôpital</option>
                                    <option value="pharmacie">Pharmacie</option>
                                    <option value="banque_sang">Banque de Sang</option>
                                    <option value="centre">Centre</option>
                                    <option value="patient">Patient</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Attribuer des Permissions</label>

                        <!-- En-tête des actions CRUD -->
                        <div class="row mb-2">
                            <div class="col-md-3"><strong>Module</strong></div>
                            <div class="col-md-2 text-center"><strong>Voir</strong></div>
                            <div class="col-md-2 text-center"><strong>Créer</strong></div>
                            <div class="col-md-2 text-center"><strong>Modifier</strong></div>
                            <div class="col-md-2 text-center"><strong>Supprimer</strong></div>
                        </div>

                        <!-- Permissions dynamiques -->
                        <div id="permissionsContainer">
                            <!-- Les permissions seront générées dynamiquement ici -->
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn" style="background: #003366; color: white; border: none;" onclick="saveUserPermissions()">
                    Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour modifier l'utilisateur -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #003366; color: white;">
                <h5 class="modal-title" id="editUserModalLabel">Modifier l'utilisateur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editUserId" name="user_id">
                    
                    <div class="mb-3">
                        <label for="editUserName" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="editUserName" name="nom" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editUserEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editUserEmail" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editUserRole" class="form-label">Rôle</label>
                        <select name="role" id="editUserRole" class="form-select">
                            <option value="user">Utilisateur</option>
                            <option value="admin">Administrateur</option>
                            <option value="manager">Manager</option>
                            <option value="moderator">Modérateur</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn" style="background: #003366; color: white; border: none;" onclick="updateUser()">
                    Mettre à jour
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles pour les modales Bootstrap 5 */
.modal {
    display: none;
}

.modal.show {
    display: block;
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1040;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-dialog {
    position: relative;
    width: auto;
    margin: 0.5rem;
    pointer-events: none;
}

.modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 0.3rem;
    outline: 0;
}

/* Styles pour le tableau */
.table th {
    font-weight: 600;
    color: #003366;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.8rem;
}

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

.btn-permissions {
    background: linear-gradient(135deg, #17a2b8, #138496);
    color: white;
}

.btn-permissions:hover {
    background: linear-gradient(135deg, #138496, #117a8b);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
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

/* Style pour les boutons outline (gardé pour compatibilité) */
.btn-outline-primary:hover {
    background-color: #003366;
    border-color: #003366;
    color: white;
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

/* Styles pour le modal des permissions */
.modal-xl {
    max-width: 1200px;
}

.form-check-input {
    cursor: pointer;
}

.form-check-input:checked {
    background-color: #003366;
    border-color: #003366;
}

.row.align-items-center {
    border-bottom: 1px solid #f0f0f0;
    padding: 0.5rem 0;
}

.row.align-items-center:hover {
    background-color: #f8f9fa;
}

.row.align-items-center:last-child {
    border-bottom: none;
}
</style>

<script>
// Récupérer le token CSRF depuis la meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function openPermissionsModal(userId, userName) {
    console.log('Opening permissions modal for user:', userId, userName);
    
    document.getElementById('userId').value = userId;
    document.getElementById('userName').textContent = userName;
    
    // Charger les permissions de l'utilisateur
    loadUserPermissions(userId);
    
    // Charger les informations actuelles de l'utilisateur
    loadUserInfo(userId);
    
    const modal = new bootstrap.Modal(document.getElementById('permissionsModal'));
    modal.show();
}

function openEditModal(userId) {
    console.log('Opening edit modal for user:', userId);
    
    // Charger les informations de l'utilisateur
    loadUserInfo(userId);
    
    const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
    modal.show();
}

function loadUserPermissions(userId) {
    // Charger les permissions depuis l'API sécurisée
    fetch(`/admin/users/${userId}/permissions`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            // Générer dynamiquement les permissions dans le conteneur
            generatePermissionsUI(data.permissions, data.userPermissions);
            
            // Mettre à jour les informations utilisateur
            document.getElementById('userRole').value = data.user.role || 'admin';
            document.getElementById('userType').value = data.user.type_utilisateur || 'hopital';
            
            // Appliquer les permissions par défaut
            setDefaultPermissions();
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des permissions');
        });
}

// Fonction pour générer l'interface des permissions dynamiquement
function generatePermissionsUI(permissions, userPermissions) {
    const container = document.getElementById('permissionsContainer');
    container.innerHTML = '';
    
    // Grouper les permissions par module
    const permissionGroups = groupPermissionsByModule(permissions);
    
    // Générer l'interface pour chaque groupe
    Object.keys(permissionGroups).forEach(moduleName => {
        const modulePermissions = permissionGroups[moduleName];
        
        // Créer la ligne d'en-tête du module
        const moduleRow = document.createElement('div');
        moduleRow.className = 'row mb-3 align-items-center';
        moduleRow.innerHTML = `
            <div class="col-md-3">
                <strong>${getModuleDisplayName(moduleName)}</strong>
            </div>
            <div class="col-md-2 text-center">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_${moduleName}" id="perm_view_${moduleName}">
            </div>
            <div class="col-md-2 text-center">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="create_${moduleName}" id="perm_create_${moduleName}">
            </div>
            <div class="col-md-2 text-center">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="edit_${moduleName}" id="perm_edit_${moduleName}">
            </div>
            <div class="col-md-2 text-center">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="delete_${moduleName}" id="perm_delete_${moduleName}">
            </div>
        `;
        
        container.appendChild(moduleRow);
    });
    
    // Cocher les permissions existantes de l'utilisateur
    if (userPermissions && userPermissions.length > 0) {
        userPermissions.forEach(permissionName => {
            const checkbox = document.getElementById(`perm_${permissionName}`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
    }
}

// Fonction pour grouper les permissions par module
function groupPermissionsByModule(permissions) {
    const groups = {};
    
    permissions.forEach(permission => {
        const parts = permission.name.split('_');
        if (parts.length >= 2) {
            const action = parts[0]; // view, create, edit, delete
            const module = parts.slice(1).join('_'); // users, patients, etc.
            
            if (!groups[module]) {
                groups[module] = [];
            }
            groups[module].push(permission);
        }
    });
    
    return groups;
}

// Fonction pour obtenir le nom d'affichage du module
function getModuleDisplayName(moduleName) {
    const displayNames = {
        'users': 'Gérer les Utilisateurs',
        'roles': 'Gérer les Rôles et Permissions',
        'patients': 'Gérer les Patients',
        'appointments': 'Gérer les Rendez-vous',
        'medical_records': 'Gérer les Dossiers Médicaux',
        'prescriptions': 'Gérer les Prescriptions',
        'invoices': 'Gérer les Factures',
        'reports': 'Gérer les Rapports',
        'medicines': 'Gérer les Médicaments',
        'stocks': 'Gérer les Stocks',
        'donors': 'Gérer les Donneurs de Sang',
        'blood_reserves': 'Gérer les Réserves de Sang',
        'services': 'Gérer les Services',
        'consultations': 'Gérer les Consultations'
    };
    
    return displayNames[moduleName] || `Gérer les ${moduleName.replace('_', ' ')}`;
}

function loadUserInfo(userId) {
    // Charger les informations de l'utilisateur pour les modals
    fetch(`/admin/users/${userId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('editUserId').value = data.id;
            document.getElementById('editUserName').value = data.nom;
            document.getElementById('editUserEmail').value = data.email;
            document.getElementById('editUserRole').value = data.role;
            document.getElementById('userRole').value = data.role;
            document.getElementById('userType').value = data.type_utilisateur;
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des informations utilisateur');
        });
}

// Fonction pour définir les permissions par défaut selon le type d'entité
function setDefaultPermissions() {
    const userType = document.getElementById('userType').value;
    const userRole = document.getElementById('userRole').value;
    
    // Réinitialiser toutes les checkboxes
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
        checkbox.disabled = false;
    });
    
    // Si c'est un superadmin, cocher toutes les permissions disponibles
    if (userRole === 'superadmin') {
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
            checkbox.disabled = true; // Désactiver les checkboxes pour le superadmin
        });
        
        // Afficher un message informatif
        const permissionsSection = document.querySelector('.mb-3');
        let infoDiv = document.getElementById('superadmin-info');
        if (!infoDiv) {
            infoDiv = document.createElement('div');
            infoDiv.id = 'superadmin-info';
            infoDiv.className = 'alert alert-info mt-3';
            infoDiv.innerHTML = '<i class="fas fa-shield-alt me-2"></i>Le Super Administrateur a automatiquement toutes les permissions.';
            permissionsSection.appendChild(infoDiv);
        }
        infoDiv.style.display = 'block';
        
        return;
    } else {
        // Masquer le message informatif pour les autres rôles
        const infoDiv = document.getElementById('superadmin-info');
        if (infoDiv) {
            infoDiv.style.display = 'none';
        }
        
        // Réactiver toutes les checkboxes
        checkboxes.forEach(checkbox => {
            checkbox.disabled = false;
        });
    }
    
    // Permissions par défaut pour un administrateur
    if (userRole === 'admin') {
        // Permissions de base pour tous les administrateurs
        const defaultPermissions = [
            'view_users', 'create_users', 'edit_users',
            'view_roles', 'create_roles', 'edit_roles'
        ];
        
        // Permissions spécifiques selon le type d'entité
        switch(userType) {
            case 'hopital':
                defaultPermissions.push(
                    'view_patients', 'create_patients', 'edit_patients',
                    'view_appointments', 'create_appointments', 'edit_appointments',
                    'view_medical_records', 'create_medical_records', 'edit_medical_records',
                    'view_prescriptions', 'create_prescriptions', 'edit_prescriptions',
                    'view_consultations', 'create_consultations', 'edit_consultations',
                    'view_services', 'create_services', 'edit_services',
                    'view_invoices', 'create_invoices', 'edit_invoices',
                    'view_reports', 'create_reports'
                );
                break;
            case 'pharmacie':
                defaultPermissions.push(
                    'view_medicines', 'create_medicines', 'edit_medicines',
                    'view_stocks', 'create_stocks', 'edit_stocks',
                    'view_invoices', 'create_invoices', 'edit_invoices',
                    'view_reports', 'create_reports'
                );
                break;
            case 'banque_sang':
                defaultPermissions.push(
                    'view_donors', 'create_donors', 'edit_donors',
                    'view_blood_reserves', 'create_blood_reserves', 'edit_blood_reserves',
                    'view_reports', 'create_reports'
                );
                break;
            case 'centre':
                defaultPermissions.push(
                    'view_patients', 'create_patients', 'edit_patients',
                    'view_consultations', 'create_consultations', 'edit_consultations',
                    'view_prescriptions', 'create_prescriptions', 'edit_prescriptions',
                    'view_reports', 'create_reports'
                );
                break;
        }
        
        // Cocher seulement les permissions qui existent dans l'interface
        defaultPermissions.forEach(permission => {
            const checkbox = document.getElementById(`perm_${permission}`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
    }
}

function saveUserPermissions() {
    const form = document.getElementById('permissionsForm');
    const formData = new FormData(form);
    
    // Récupérer les permissions sélectionnées
    const selectedPermissions = [];
    const checkboxes = form.querySelectorAll('input[name="permissions[]"]:checked');
    checkboxes.forEach(checkbox => {
        selectedPermissions.push(checkbox.value);
    });
    
    // Récupérer le rôle et le type d'utilisateur
    const role = document.getElementById('userRole').value;
    const typeUtilisateur = document.getElementById('userType').value;
    
    // Ajouter toutes les données au formData
    formData.append('permissions', JSON.stringify(selectedPermissions));
    formData.append('role', role);
    formData.append('type_utilisateur', typeUtilisateur);
    
    console.log('Envoi des données:', {
        permissions: selectedPermissions,
        role: role,
        typeUtilisateur: typeUtilisateur
    });
    
    fetch('/admin/users/permissions', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur réseau');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Permissions mises à jour avec succès !');
            bootstrap.Modal.getInstance(document.getElementById('permissionsModal')).hide();
            // Ne pas recharger la page, les permissions sont déjà affichées correctement
        } else {
            alert('Erreur lors de la mise à jour des permissions: ' + (data.message || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la mise à jour des permissions: ' + error.message);
    });
}

function updateUser() {
    const form = document.getElementById('editUserForm');
    const formData = new FormData(form);
    const userId = document.getElementById('editUserId').value;
    
    fetch(`/admin/users/${userId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur réseau');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Utilisateur mis à jour avec succès !');
            bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
            location.reload();
        } else {
            alert('Erreur lors de la mise à jour de l\'utilisateur: ' + (data.message || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la mise à jour de l\'utilisateur');
    });
}

function deleteUser(userId) {
    console.log('Tentative de suppression de l\'utilisateur:', userId);
    
    // Vérifier si c'est le superadmin avant de supprimer
    const userRow = document.querySelector(`tr[data-user-id="${userId}"]`);
    if (userRow) {
        const userEmail = userRow.querySelector('td:nth-child(3)').textContent.trim();
        const userRole = userRow.querySelector('td:nth-child(5)').textContent.trim();
        
        console.log('Email:', userEmail, 'Rôle:', userRole);
        
        if (userEmail === 'admin@central.com' || userRole === 'Super Administrateur') {
            alert('Impossible de supprimer le Super Administrateur !');
            return;
        }
    }
    
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
        console.log('Envoi de la requête DELETE...');
        
        fetch(`/admin/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            console.log('Réponse reçue:', response.status, response.statusText);
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status} ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Données reçues:', data);
            if (data.success) {
                alert('Utilisateur supprimé avec succès !');
                location.reload();
            } else {
                alert('Erreur lors de la suppression de l\'utilisateur: ' + (data.message || 'Erreur inconnue'));
            }
        })
        .catch(error => {
            console.error('Erreur lors de la suppression:', error);
            alert('Erreur lors de la suppression de l\'utilisateur: ' + error.message);
        });
    }
}

// Charger le nombre d'utilisateurs en attente
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM chargé - Test Bootstrap 5');
    
    // Vérifier si Bootstrap est chargé
    if (typeof bootstrap !== 'undefined') {
        console.log('✅ Bootstrap 5 est chargé');
    } else {
        console.log('❌ Bootstrap 5 n\'est pas chargé');
    }
    
    // Vérifier les modales
    const permissionsModal = document.getElementById('permissionsModal');
    const editUserModal = document.getElementById('editUserModal');
    
    if (permissionsModal) {
        console.log('✅ Modal permissions trouvée');
    } else {
        console.log('❌ Modal permissions manquante');
    }
    
    if (editUserModal) {
        console.log('✅ Modal edit trouvée');
    } else {
        console.log('❌ Modal edit manquante');
    }
    
    // Événements pour mettre à jour les permissions par défaut
    document.getElementById('userRole').addEventListener('change', setDefaultPermissions);
    document.getElementById('userType').addEventListener('change', setDefaultPermissions);
    
    loadPendingCount();
    
    // Charger les entités selon le type sélectionné (pour superadmin)
    @if(auth()->user()->isSuperAdmin())
    document.getElementById('createType').addEventListener('change', function() {
        const type = this.value;
        const entiteSelect = document.getElementById('createEntite');
        
        if (!type) {
            entiteSelect.innerHTML = '<option value="">-- Sélectionner une entité --</option>';
            return;
        }
        
        // Charger les entités selon le type
        let url = '';
        switch(type) {
            case 'hopital':
                url = '/admin/api/hopitaux';
                break;
            case 'pharmacie':
                url = '/admin/api/pharmacies';
                break;
            case 'banque_sang':
                url = '/admin/api/banque-sangs';
                break;
            default:
                entiteSelect.innerHTML = '<option value="">Aucune entité requise</option>';
                entiteSelect.removeAttribute('required');
                return;
        }
        
        entiteSelect.setAttribute('required', 'required');
        
        console.log('Chargement des entités depuis:', url);
        
        fetch(url)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Entités reçues:', data);
                entiteSelect.innerHTML = '<option value="">-- Sélectionner une entité --</option>';
                data.forEach(entite => {
                    entiteSelect.innerHTML += `<option value="${entite.id}">${entite.nom}</option>`;
                });
            })
            .catch(error => {
                console.error('Erreur chargement entités:', error);
                alert('Erreur: ' + error.message + '\nURL: ' + url);
                entiteSelect.innerHTML = '<option value="">Erreur de chargement</option>';
            });
    });
    @endif
    
    // Gestion du formulaire de création d'utilisateur
    document.getElementById('createUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const userData = {
            nom: formData.get('nom'),
            email: formData.get('email'),
            password: formData.get('password'),
            role: formData.get('role'),
            type_utilisateur: formData.get('type_utilisateur')
        };
        
        // Ajouter l'entité_id
        @if(auth()->user()->isSuperAdmin())
            userData.entite_id = formData.get('entite_id');
        @else
            userData.entite_id = {{ auth()->user()->entite_id }};
        @endif
        
        fetch('{{ route("admin.users.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(userData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Utilisateur créé avec succès !');
                location.reload(); // Recharger la page pour voir le nouvel utilisateur
            } else {
                alert('Erreur lors de la création: ' + (data.message || 'Erreur inconnue'));
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la création de l\'utilisateur: ' + error.message);
        });
    });
});

function loadPendingCount() {
    fetch('{{ route("admin.users.pending") }}')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            const pendingBadge = document.getElementById('pendingBadge');
            if (pendingBadge) {
                pendingBadge.textContent = data.length || 0;
                // Masquer le badge s'il n'y a pas d'utilisateurs en attente
                if (data.length === 0) {
                    pendingBadge.style.display = 'none';
                } else {
                    pendingBadge.style.display = 'inline';
                }
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            // En cas d'erreur, masquer le badge
            const pendingBadge = document.getElementById('pendingBadge');
            if (pendingBadge) {
                pendingBadge.style.display = 'none';
            }
        });
}
</script>
<!-- Modal pour créer un nouvel utilisateur -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #003366; color: white;">
                <h5 class="modal-title" id="createUserModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Créer un Nouvel Utilisateur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createUserForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="createNom" class="form-label">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="createNom" name="nom" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="createEmail" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="createEmail" name="email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="createPassword" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="createPassword" name="password" required minlength="8">
                        </div>
                        
                        @if(auth()->user()->isSuperAdmin())
                        {{-- SUPERADMIN: Sélectionne le type d'entité --}}
                        <div class="col-md-6 mb-3">
                            <label for="createType" class="form-label">Type d'entité <span class="text-danger">*</span></label>
                            <select class="form-select" id="createType" name="type_utilisateur" required>
                                <option value="">-- Sélectionner un type --</option>
                                <option value="hopital">Hôpital</option>
                                <option value="pharmacie">Pharmacie</option>
                                <option value="banque_sang">Banque de Sang</option>
                            </select>
                        </div>
                    </div>
                    
                    {{-- SUPERADMIN: Sélectionne l'entité --}}
                    <div class="row mb-3" id="entiteSelection">
                        <div class="col-12">
                            <label for="createEntite" class="form-label">Entité <span class="text-danger">*</span></label>
                            <select class="form-select" id="createEntite" name="entite_id" required>
                                <option value="">-- Sélectionner une entité --</option>
                            </select>
                            <small class="form-text text-muted">Sélectionnez d'abord le type d'entité</small>
                        </div>
                    </div>
                    
                    {{-- SUPERADMIN: Rôle = admin uniquement --}}
                    <div class="mb-3">
                        <label for="createRole" class="form-label">Rôle <span class="text-danger">*</span></label>
                        <select class="form-select" id="createRole" name="role" required>
                            <option value="admin">Administrateur de l'entité</option>
                        </select>
                        <div class="alert alert-info mt-2">
                            <i class="fas fa-info-circle me-2"></i>
                            En tant que superadmin, vous créez uniquement les <strong>administrateurs</strong> de chaque entité.
                            Ces administrateurs pourront ensuite créer leur propre personnel.
                        </div>
                    </div>
                    @else
                    {{-- ADMIN D'ENTITÉ: Type fixé à son entité --}}
                        <input type="hidden" name="type_utilisateur" value="{{ auth()->user()->type_utilisateur }}">
                    </div>
                    
                    {{-- ADMIN D'ENTITÉ: Sélectionne le rôle de son personnel --}}
                    <div class="mb-3">
                        <label for="createRole" class="form-label">Rôle du personnel <span class="text-danger">*</span></label>
                        <select class="form-select" id="createRole" name="role" required>
                            <option value="">-- Sélectionner un rôle --</option>
                            @if(auth()->user()->type_utilisateur === 'hopital')
                                <option value="medecin">Médecin</option>
                                <option value="infirmier">Infirmier(ère)</option>
                                <option value="laborantin">Laborantin</option>
                                <option value="caissier">Caissier(ère)</option>
                                <option value="receptionniste">Réceptionniste</option>
                            @elseif(auth()->user()->type_utilisateur === 'pharmacie')
                                <option value="pharmacien">Pharmacien(ne)</option>
                                <option value="assistant_pharmacie">Assistant(e) Pharmacie</option>
                            @elseif(auth()->user()->type_utilisateur === 'banque_sang')
                                <option value="technicien_labo">Technicien Laboratoire</option>
                                <option value="gestionnaire_donneurs">Gestionnaire Donneurs</option>
                            @endif
                        </select>
                        <div class="alert alert-info mt-2">
                            <i class="fas fa-info-circle me-2"></i>
                            Vous créez un membre de votre personnel pour <strong>{{ auth()->user()->getEntiteName() }}</strong>.
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Créer l'Utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

<script>
// Fonction pour activer/désactiver un utilisateur
function toggleUserStatus(userId, currentStatus) {
    const newStatus = currentStatus === 'approved' ? 'disabled' : 'approved';
    const action = newStatus === 'approved' ? 'activer' : 'désactiver';
    
    if (confirm(`Êtes-vous sûr de vouloir ${action} cet utilisateur ?`)) {
        fetch(`/admin/users/${userId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(`Utilisateur ${action} avec succès !`);
                location.reload(); // Recharger la page pour voir les changements
            } else {
                alert('Erreur lors de la modification du statut: ' + (data.message || 'Erreur inconnue'));
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            console.error('Response status:', error.status);
            alert('Erreur lors de la modification du statut: ' + error.message);
        });
    }
}
</script>