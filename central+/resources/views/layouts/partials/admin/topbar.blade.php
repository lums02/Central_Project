<header class="topbar">
    <div class="topbar-content">
        <h5 class="page-title">@yield('page-title', 'Dashboard')</h5>
        <div class="user-section">
            {{-- Affiche le nom de l'entité et l'utilisateur si connecté --}}
            @auth
                <span class="entity-name" style="font-weight: bold; color: #003366; margin-right: 15px;">
                    {{ Auth::user()->getEntiteName() }}
                </span>
                <span class="welcome-text">
                    Bienvenue, {{ Auth::user()->nom ?? 'Utilisateur' }}
                </span>
                
                {{-- Cloche de notifications --}}
                <div class="notification-bell" style="position: relative; margin: 0 20px;">
                    <button type="button" class="btn btn-link text-dark p-0" data-bs-toggle="dropdown" aria-expanded="false" style="position: relative; font-size: 1.5rem;">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" style="width: 350px; max-height: 400px; overflow-y: auto;">
                        <li class="dropdown-header bg-primary text-white">
                            <strong><i class="fas fa-bell me-2"></i>Notifications</strong>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li id="notificationsList">
                            <div class="text-center py-3 text-muted">
                                <i class="fas fa-inbox"></i><br>
                                Aucune notification
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="text-center">
                            <a href="#" class="dropdown-item text-primary">
                                <small>Voir toutes les notifications</small>
                            </a>
                        </li>
                    </ul>
                </div>
            @else
                <span class="welcome-text">
                    Bienvenue, Invité
                </span>
            @endauth

            {{-- Bouton de déconnexion, uniquement si connecté --}}
            @auth
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger">Déconnexion</button>
                </form>
            @endauth
        </div>
    </div>
</header>

<style>
.notification-badge {
    position: absolute;
    top: -5px;
    right: -8px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 0.7rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    border: 2px solid white;
}

.notification-bell .btn-link:hover i {
    color: #003366 !important;
    transform: scale(1.1);
    transition: all 0.3s ease;
}

.notification-dropdown {
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    border: none;
    border-radius: 10px;
}

.notification-item {
    padding: 12px 20px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background 0.2s ease;
}

.notification-item:hover {
    background: #f8f9fa;
}

.notification-item.unread {
    background: #e3f2fd;
}

@keyframes ring {
    0% { transform: rotate(0deg); }
    10% { transform: rotate(15deg); }
    20% { transform: rotate(-15deg); }
    30% { transform: rotate(10deg); }
    40% { transform: rotate(-10deg); }
    50% { transform: rotate(0deg); }
}

.notification-bell.has-notification .btn-link i {
    animation: ring 2s ease-in-out infinite;
    color: #ffc107;
}
</style>

<script>
// Charger les notifications au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    
    // Actualiser toutes les 30 secondes
    setInterval(loadNotifications, 30000);
});

function loadNotifications() {
    fetch('{{ route("admin.notifications.get") }}')
        .then(response => response.json())
        .then(data => {
            updateNotificationBadge(data.unread_count);
            displayNotifications(data.notifications);
        })
        .catch(error => {
            console.error('Erreur chargement notifications:', error);
        });
}

function updateNotificationBadge(count) {
    const badge = document.getElementById('notificationBadge');
    const bell = document.querySelector('.notification-bell');
    
    if (badge) {
        if (count > 0) {
            badge.textContent = count > 9 ? '9+' : count;
            badge.style.display = 'flex';
            bell.classList.add('has-notification');
        } else {
            badge.style.display = 'none';
            bell.classList.remove('has-notification');
        }
    }
}

function displayNotifications(notifications) {
    const list = document.getElementById('notificationsList');
    
    if (notifications.length === 0) {
        list.innerHTML = `
            <div class="text-center py-3 text-muted">
                <i class="fas fa-inbox"></i><br>
                Aucune notification
            </div>
        `;
    } else {
        list.innerHTML = notifications.map(notif => `
            <li class="notification-item ${notif.read ? '' : 'unread'}" onclick="handleNotificationClick(${notif.id}, '${notif.type}', ${JSON.stringify(notif.data)})">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-${notif.icon} text-primary"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <strong>${notif.title}</strong>
                        <p class="mb-0 small text-muted">${notif.message}</p>
                        <small class="text-muted">${notif.time}</small>
                    </div>
                </div>
            </li>
        `).join('');
    }
}

function handleNotificationClick(notifId, type, data) {
    // Marquer comme lu
    fetch(`/admin/notifications/${notifId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(() => {
        // Recharger immédiatement les notifications pour mettre à jour le badge
        loadNotifications();
    });
    
    // Rediriger selon le type
    if (type === 'demande_transfert_recue') {
        window.location.href = '{{ route("admin.hopital.transferts.demandes-recues") }}';
    } else if (type === 'transfert_complete') {
        window.location.href = '{{ route("admin.hopital.patients.index") }}';
    } else if (type === 'nouvelle_consultation') {
        window.location.href = '{{ route("admin.caissier.consultations") }}';
    } else if (type === 'consultation_payee') {
        window.location.href = '{{ route("admin.medecin.patients") }}';
    } else if (type === 'resultats_examen' && data && data.dossier_id) {
        window.location.href = `/admin/medecin/dossiers/${data.dossier_id}`;
    } else if (type === 'examens_a_payer') {
        window.location.href = '{{ route("admin.caissier.examens") }}';
    } else if (type === 'examen_a_realiser') {
        window.location.href = '{{ route("admin.laborantin.examens") }}';
    } else if (type === 'rappel_rdv_24h' || type === 'rappel_rdv_2h') {
        window.location.href = '{{ route("admin.medecin.rendezvous") }}';
    }
}
</script>
