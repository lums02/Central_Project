<!-- resources/views/layouts/partials/leftsidebar.blade.php -->
<div>
    <!-- Bouton hamburger (visible sur petits écrans) -->
    <button id="sidebarToggle" class="btn btn-primary d-md-none m-2">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar bg-dark text-white vh-100 position-fixed start-0 top-0 d-flex flex-column"
         style="width: 250px; transform: translateX(0); transition: transform 0.3s ease;">
        <div class="sidebar-header p-3 text-center border-bottom border-secondary">
            <div class="hospital-logo mx-auto mb-2"
                 style="width: 60px; height: 60px; border-radius: 50%; background-color: #0d6efd; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 24px;">
                CP
            </div>
            <h3>CENTRAL+</h3>
        </div>

        <nav class="nav flex-column mt-3 px-2 flex-grow-1">
            <a href="{{ url('index') }}"
               class="nav-link text-white mb-2 {{ request()->is('index') ? 'active bg-primary rounded' : '' }}">
                <i class="fas fa-home me-2"></i> Tableau de bord
            </a>
            <a href="{{ url('hoppat') }}"
               class="nav-link text-white mb-2 {{ request()->is('hoppat') ? 'active bg-primary rounded' : '' }}">
                <i class="fas fa-users me-2"></i> Patients
            </a>
            <a href="{{ url('paiement') }}"
               class="nav-link text-white mb-2 {{ request()->is('paiement') ? 'active bg-primary rounded' : '' }}">
                <i class="fas fa-money-bill-wave me-2"></i> Paiements
            </a>
            <a href="{{ url('notif') }}"
               class="nav-link text-white mb-2 {{ request()->is('notif') ? 'active bg-primary rounded' : '' }}">
                <i class="fas fa-bell me-2"></i> Notifications
            </a>
            <a href="{{ url('rdv') }}"
               class="nav-link text-white mb-2 {{ request()->is('rdv') ? 'active bg-primary rounded' : '' }}">
                <i class="fas fa-calendar-check me-2"></i> Rendez-vous
            </a>
            <a href="{{ url('abonnement') }}"
               class="nav-link text-white mb-2 {{ request()->is('abonnement') ? 'active bg-primary rounded' : '' }}">
                <i class="fas fa-crown me-2"></i> Abonnement
            </a>
            <a href="{{ url('../login/index') }}" class="nav-link text-white mb-2">
                <i class="fas fa-user-md me-2"></i> Espace Médecin
            </a>
            <a href="{{ url('medecin') }}"
               class="nav-link text-white mb-2 {{ request()->is('medecin') ? 'active bg-primary rounded' : '' }}">
                <i class="fas fa-user-md me-2"></i> Médecins
            </a>
            <a href="{{ url('../connexion-hopitaux.php') }}"
               class="nav-link text-white mt-auto pt-3 border-top border-secondary">
                <i class="fas fa-hospital me-2"></i> Page de centralisation
            </a>
        </nav>
    </div>
</div>


<!-- Overlay to close sidebar on mobile -->
<div id="sidebarOverlay"></div>

<script>
    
</script>
