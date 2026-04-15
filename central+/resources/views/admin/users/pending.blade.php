@extends('layouts.admin')

@section('title', 'Utilisateurs en Attente - CENTRAL+')
@section('page-title', 'Utilisateurs en Attente')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Entête simple -->
            <div class="page-header mb-4" style="background: #003366; padding: 1.5rem; border-radius: 8px;">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 style="color: white; margin: 0; font-size: 1.8rem; font-weight: 500;">Utilisateurs en Attente d'Approbation</h1>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn" style="background: white; color: #003366; border: none; padding: 0.75rem 1.5rem; border-radius: 4px; font-weight: 600;">
                            <i class="fas fa-users me-2"></i>Voir Tous les Utilisateurs
                        </a>
                        <button class="btn" style="background: #28a745; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 4px; font-weight: 600;" onclick="refreshPendingUsers()">
                            <i class="fas fa-sync-alt me-2"></i>Actualiser
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3 class="text-primary mb-1" id="pendingCount">0</h3>
                            <p class="text-muted mb-0">En attente</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3 class="text-success mb-1" id="approvedCount">0</h3>
                            <p class="text-muted mb-0">Approuvés</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3 class="text-danger mb-1" id="rejectedCount">0</h3>
                            <p class="text-muted mb-0">Rejetés</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h3 class="text-info mb-1" id="totalCount">0</h3>
                            <p class="text-muted mb-0">Total</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des utilisateurs en attente -->
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
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600;">Inscrit le</th>
                                    <th style="border: none; padding: 1rem; color: #003366; font-weight: 600; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="pendingUsersTable">
                                <!-- Les utilisateurs en attente seront chargés ici -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour rejeter un utilisateur -->
<div class="modal fade" id="rejectUserModal" tabindex="-1" aria-labelledby="rejectUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #dc3545; color: white;">
                <h5 class="modal-title" id="rejectUserModalLabel">Rejeter l'utilisateur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rejectUserForm">
                    @csrf
                    <input type="hidden" id="rejectUserId" name="user_id">
                    
                    <div class="mb-3">
                        <label for="rejectionReason" class="form-label">Raison du rejet (optionnel)</label>
                        <textarea class="form-control" id="rejectionReason" name="rejection_reason" rows="3" placeholder="Expliquez pourquoi cet utilisateur est rejeté..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" onclick="confirmRejectUser()">
                    <i class="fas fa-times me-2"></i>Rejeter
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les permissions (format tableau CRUD) -->
<div class="modal fade" id="permissionsModal" tabindex="-1" aria-labelledby="permissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: #003366; color: white;">
                <h5 class="modal-title" id="permissionsModalLabel">Attribuer les Permissions - <span id="userName"></span></h5>
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

                        <!-- Permissions dynamiques -->
                        <div id="permissionsContainer">
                            <!-- Les permissions seront générées dynamiquement ici -->
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn" style="background: #003366; color: white; border: none;" onclick="saveUserPermissions()" id="savePermissionsBtn">
                    Enregistrer
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

.btn-approve {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.btn-approve:hover {
    background: linear-gradient(135deg, #20c997, #17a2b8);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.btn-reject {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
}

.btn-reject:hover {
    background: linear-gradient(135deg, #c82333, #bd2130);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
}

.btn-view {
    background: linear-gradient(135deg, #17a2b8, #138496);
    color: white;
}

.btn-view:hover {
    background: linear-gradient(135deg, #138496, #117a8b);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
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

// Variables globales
let currentRejectUserId = null;

// Charger les utilisateurs en attente au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM chargé - Chargement des utilisateurs en attente');
    loadPendingUsers();
    loadStats();
    
    // Remettre le bouton à son état normal quand le modal se ferme
    document.getElementById('permissionsModal').addEventListener('hidden.bs.modal', function () {
        resetPermissionsButton();
    });
    
    // Événements pour mettre à jour les permissions par défaut
    document.getElementById('userRole').addEventListener('change', setDefaultPermissions);
    document.getElementById('userType').addEventListener('change', setDefaultPermissions);
});

// Charger les utilisateurs en attente
function loadPendingUsers() {
    fetch('{{ route("admin.users.pending") }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('Erreur réseau: ' + response.status);
        }
        return response.json();
    })
        .then(data => {
        console.log('Data received:', data);
            displayPendingUsers(data);
        })
        .catch(error => {
            console.error('Erreur:', error);
        document.getElementById('pendingUsersTable').innerHTML = 
            '<tr><td colspan="6" class="text-center py-4 text-danger">Erreur lors du chargement des utilisateurs: ' + error.message + '</td></tr>';
        });
}

// Afficher les utilisateurs en attente
function displayPendingUsers(users) {
    const tbody = document.getElementById('pendingUsersTable');
    
    if (!users || users.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4" style="color: #6c757d;">Aucun utilisateur en attente d\'approbation</td></tr>';
        return;
    }
    
    tbody.innerHTML = '';

    users.forEach((user, index) => {
        const row = document.createElement('tr');
        row.setAttribute('data-user-id', user.id);
        row.style.borderBottom = '1px solid #e9ecef';
        
        row.innerHTML = `
            <td style="padding: 1rem; vertical-align: middle;">${index + 1}</td>
            <td style="padding: 1rem; vertical-align: middle; font-weight: 500;">${user.nom}</td>
            <td style="padding: 1rem; vertical-align: middle;">${user.email}</td>
            <td style="padding: 1rem; vertical-align: middle;">
                <span class="badge" style="background: #17a2b8; color: white; padding: 0.5rem 0.75rem; border-radius: 4px;">
                    ${user.type_utilisateur.charAt(0).toUpperCase() + user.type_utilisateur.slice(1)}
                </span>
            </td>
            <td style="padding: 1rem; vertical-align: middle; color: #6c757d;">
                ${new Date(user.created_at).toLocaleDateString('fr-FR')}
            </td>
            <td style="padding: 1rem; vertical-align: middle;">
                <div class="action-buttons">
                    <button class="btn btn-icon btn-view" onclick="viewUserDetails(${user.id})" title="Voir les détails">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-icon btn-approve" onclick="approveUser(${user.id})" title="Approuver l'utilisateur">
                        <i class="fas fa-check"></i>
                    </button>
                    <button class="btn btn-icon btn-reject" onclick="rejectUser(${user.id})" title="Rejeter l'utilisateur">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </td>
        `;
        
        tbody.appendChild(row);
    });
}

// Approuver un utilisateur - Ouvre le modal des permissions avec format tableau CRUD
function approveUser(userId) {
    // Trouver l'utilisateur dans la liste pour récupérer ses informations
    const userRow = document.querySelector(`tr[data-user-id="${userId}"]`);
    if (!userRow) {
        alert('Erreur: Utilisateur non trouvé');
        return;
    }
    
    // Récupérer les informations de l'utilisateur
    const userName = userRow.querySelector('td:nth-child(2)').textContent;
    const userEmail = userRow.querySelector('td:nth-child(3)').textContent;
    const userType = userRow.querySelector('td:nth-child(4) .badge').textContent.toLowerCase();
    
    // Pré-remplir le modal avec les informations de l'utilisateur
    document.getElementById('userName').textContent = userName;
    document.getElementById('userId').value = userId;
    document.getElementById('userRole').value = 'admin'; // Par défaut admin
    document.getElementById('userType').value = userType;
    
    // Changer le texte du bouton pour indiquer qu'il s'agit d'une approbation
    document.getElementById('savePermissionsBtn').innerHTML = '<i class="fas fa-check me-2"></i>Approuver avec Permissions';
    document.getElementById('savePermissionsBtn').style.background = '#28a745';
    
    // Charger les permissions par défaut selon le type d'entité
    loadUserPermissions(userId);
    
    // Charger les informations actuelles de l'utilisateur
    loadUserInfo(userId);
    
    // Ouvrir le modal
    const modal = new bootstrap.Modal(document.getElementById('permissionsModal'));
    modal.show();
}

// Rejeter un utilisateur
function rejectUser(userId) {
    currentRejectUserId = userId;
    const modal = new bootstrap.Modal(document.getElementById('rejectUserModal'));
    modal.show();
}

// Confirmer le rejet d'un utilisateur
function confirmRejectUser() {
    const reason = document.getElementById('rejectionReason').value;
    
    fetch(`{{ route("admin.users.reject", ":id") }}`.replace(':id', currentRejectUserId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            rejection_reason: reason
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur réseau');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Utilisateur rejeté avec succès !');
            bootstrap.Modal.getInstance(document.getElementById('rejectUserModal')).hide();
            document.getElementById('rejectionReason').value = '';
            currentRejectUserId = null;
            loadPendingUsers();
            loadStats();
        } else {
            alert('Erreur lors du rejet: ' + (data.message || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors du rejet de l\'utilisateur');
    });
}

// Voir les détails d'un utilisateur
function viewUserDetails(userId) {
    // Rediriger vers la page de détails de l'utilisateur
    window.location.href = `{{ route("admin.users.index") }}?user=${userId}`;
}

// Actualiser la liste
function refreshPendingUsers() {
    loadPendingUsers();
    loadStats();
}

// Charger les statistiques
function loadStats() {
    fetch('{{ route("admin.users.stats") }}')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            // Mettre à jour les compteurs
            document.getElementById('pendingCount').textContent = data.pending || 0;
            document.getElementById('approvedCount').textContent = data.approved || 0;
            document.getElementById('rejectedCount').textContent = data.rejected || 0;
            document.getElementById('totalCount').textContent = data.total || 0;
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
}

// Fonction pour approuver avec permissions (utilise le modal existant)
function approveWithPermissions() {
    const userId = document.getElementById('userId').value;
    const role = document.getElementById('userRole').value;
    const typeUtilisateur = document.getElementById('userType').value;
    
    if (!role || !typeUtilisateur) {
        alert('Veuillez sélectionner un rôle et un type d\'entité');
        return;
    }
    
    // Pour le superadmin, toutes les permissions sont automatiquement attribuées
    let selectedPermissions = [];
    if (role === 'superadmin') {
        // Récupérer toutes les permissions disponibles
        selectedPermissions = Array.from(document.querySelectorAll('input[name="permissions[]"]'))
            .map(checkbox => checkbox.value);
    } else {
        // Récupérer les permissions sélectionnées pour les autres rôles
        selectedPermissions = Array.from(document.querySelectorAll('input[name="permissions[]"]:checked'))
            .map(checkbox => checkbox.value);
    }
    
    // Créer les données pour l'approbation
    const formData = new FormData();
    formData.append('permissions', JSON.stringify(selectedPermissions));
    formData.append('role', role);
    formData.append('type_utilisateur', typeUtilisateur);
    
    // Approuver l'utilisateur avec permissions
    fetch(`/admin/users/${userId}/approve`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Fermer le modal
            bootstrap.Modal.getInstance(document.getElementById('permissionsModal')).hide();
            
            // Afficher un message de succès
            const message = role === 'superadmin' 
                ? 'Super Administrateur approuvé avec succès et toutes les permissions attribuées !'
                : 'Utilisateur approuvé avec succès et permissions attribuées !';
            
            alert(message);
            
            // Recharger la page pour mettre à jour la liste
            window.location.reload();
        } else {
            alert('Erreur lors de l\'approbation: ' + (data.message || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'approbation: ' + error.message);
    });
}

// Fonctions pour le modal des permissions avec format tableau CRUD
function loadUserPermissions(userId) {
    // Charger les permissions depuis l'API sécurisée
    fetch(`{{ route("admin.users.permissions", ":id") }}`.replace(':id', userId))
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
    fetch(`{{ route("admin.users.show", ":id") }}`.replace(':id', userId))
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('userRole').value = data.role || 'admin';
            document.getElementById('userType').value = data.type_utilisateur || 'hopital';
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

// Surcharger la fonction saveUserPermissions pour l'approbation
function saveUserPermissions() {
    // Si on est dans le contexte d'approbation, utiliser approveWithPermissions
    const userId = document.getElementById('userId').value;
    const isPendingUser = document.querySelector(`tr[data-user-id="${userId}"]`) !== null;
    
    if (isPendingUser) {
        approveWithPermissions();
    } else {
        // Utiliser la fonction normale pour les utilisateurs existants
        const form = document.getElementById('permissionsForm');
        const formData = new FormData(form);
        
        // Récupérer les permissions sélectionnées
        const selectedPermissions = [];
        const checkboxes = form.querySelectorAll('input[name="permissions[]"]:checked');
        checkboxes.forEach(checkbox => {
            selectedPermissions.push(checkbox.value);
        });
        
        // Ajouter les permissions au formData
        formData.append('permissions', JSON.stringify(selectedPermissions));
        
        fetch('{{ route("admin.users.updatePermissions") }}', {
            method: 'POST',
            body: formData
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
            } else {
                alert('Erreur lors de la mise à jour: ' + (data.message || 'Erreur inconnue'));
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la sauvegarde des permissions');
        });
    }
}



// Fonction pour rejeter un utilisateur
function rejectUser(userId) {
    Swal.fire({
        title: 'Rejeter l\'utilisateur',
        input: 'text',
        inputLabel: 'Raison du rejet',
        inputPlaceholder: 'Entrez la raison du rejet...',
        inputValidator: (value) => {
            if (!value) {
                return 'Vous devez entrer une raison pour le rejet';
            }
        },
        showCancelButton: true,
        confirmButtonText: 'Rejeter',
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('rejection_reason', result.value);
            
            fetch(`/admin/users/${userId}/reject`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Utilisateur rejeté',
                        text: data.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: data.message || 'Erreur lors du rejet',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Erreur lors du rejet',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

// Fonction pour remettre le bouton à son état normal
function resetPermissionsButton() {
    document.getElementById('savePermissionsBtn').innerHTML = 'Enregistrer';
    document.getElementById('savePermissionsBtn').style.background = '#003366';
}
</script>
@endsection

