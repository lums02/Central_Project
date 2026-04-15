<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Espace Médecin - CENTRAL+')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- App CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --central-primary: #003366;
            --central-secondary: #ff6b35;
            --central-light: #f8f9fa;
            --central-dark: #2c3e50;
            --central-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --sidebar-gradient: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
            --card-shadow: 0 8px 32px rgba(0,0,0,0.1);
            --hover-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(180deg, var(--central-primary) 0%, #004080 100%);
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
            backdrop-filter: blur(10px);
        }
        
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            pointer-events: none;
        }
        
        .sidebar-header {
            padding: 35px 25px 30px 25px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            position: relative;
            z-index: 1;
            margin-bottom: 10px;
        }
        
        .sidebar-header h4 {
            color: white;
            margin: 0;
            font-weight: 700;
            font-size: 1.15rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            line-height: 1.5;
        }
        
        .sidebar-header h4 i {
            margin-right: 12px;
            font-size: 1.2rem;
            color: #ffd700;
        }
        
        .sidebar-nav {
            padding: 15px 0;
            position: relative;
            z-index: 1;
        }
        
        .nav-item {
            margin: 5px 0;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.85);
            padding: 14px 25px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 0 30px 30px 0;
            margin-right: 20px;
            position: relative;
            font-weight: 500;
            letter-spacing: 0.3px;
            font-size: 0.95rem;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #0056b3;
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.15);
            transform: translateX(8px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .nav-link:hover::before {
            transform: scaleY(1);
        }
        
        .nav-link.active {
            color: white;
            background: linear-gradient(135deg, #0056b3 0%, #0066cc 100%);
            box-shadow: 0 6px 20px rgba(0,86,179,0.4);
            transform: translateX(5px);
        }
        
        .nav-link.active::before {
            transform: scaleY(1);
            background: white;
        }
        
        .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }
        
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .topbar {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            padding: 25px 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .topbar-welcome h5 {
            margin: 0;
            color: var(--central-primary);
            font-weight: 700;
            font-size: 1.5rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .topbar-welcome small {
            color: #6c757d;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px 20px;
            background: linear-gradient(135deg, rgba(0,51,102,0.05) 0%, rgba(0,64,128,0.05) 100%);
            border-radius: 50px;
            border: 1px solid rgba(0,51,102,0.1);
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--central-secondary) 0%, #ff8c42 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: 0 4px 15px rgba(255,107,53,0.3);
        }
        
        .user-details {
            text-align: left;
        }
        
        .user-details .name {
            font-weight: 600;
            color: var(--central-primary);
            font-size: 0.95rem;
        }
        
        .user-details .role {
            font-size: 0.8rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Notification bell in topbar */
        .notification-bell .btn-link {
            color: var(--central-primary) !important;
        }
        
        .notification-bell .btn-link:hover {
            color: #0056b3 !important;
        }
        
        .content-area {
            padding: 40px;
            background: transparent;
        }
        
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: var(--hover-shadow);
            transform: translateY(-2px);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--central-primary) 0%, #004080 100%);
            color: white;
            border-radius: 20px 20px 0 0 !important;
            padding: 20px 25px;
            border: none;
            position: relative;
        }
        
        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            pointer-events: none;
        }
        
        .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
        }
        
        .card-body {
            padding: 25px;
        }
        
        .btn {
            border-radius: 12px;
            font-weight: 600;
            padding: 10px 20px;
            transition: all 0.3s ease;
            border: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--central-primary) 0%, #004080 100%);
            box-shadow: 0 4px 15px rgba(0,51,102,0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #004080 0%, #0056b3 100%);
            box-shadow: 0 6px 20px rgba(0,51,102,0.4);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, var(--central-secondary) 0%, #ff8c42 100%);
            box-shadow: 0 4px 15px rgba(255,107,53,0.3);
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, #ff8c42 0%, #ffa726 100%);
            box-shadow: 0 6px 20px rgba(255,107,53,0.4);
            transform: translateY(-2px);
        }
        
        /* Welcome Card */
        .welcome-card {
            background: linear-gradient(135deg, var(--central-primary) 0%, #0056b3 100%);
            border-radius: 20px;
            padding: 30px;
            color: white;
            box-shadow: 0 10px 40px rgba(0,51,102,0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        
        .welcome-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            pointer-events: none;
        }
        
        .welcome-content h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .welcome-date {
            background: rgba(255,255,255,0.15);
            padding: 15px 25px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            font-weight: 600;
        }
        
        /* Stats Cards - Design sobre et professionnel */
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 51, 102, 0.08);
            border: 1px solid rgba(0, 51, 102, 0.08);
            display: flex;
            align-items: center;
            gap: 20px;
            height: 140px;
        }
        
        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0,51,102,0.12);
            border-color: var(--central-primary);
        }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            position: relative;
        }
        
        .stats-card-blue .stats-icon {
            background: rgba(0, 51, 102, 0.1);
            color: var(--central-primary);
        }
        
        .stats-card-green .stats-icon {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .stats-card-orange .stats-icon {
            background: rgba(253, 126, 20, 0.1);
            color: #fd7e14;
        }
        
        .stats-card-purple .stats-icon {
            background: rgba(111, 66, 193, 0.1);
            color: #6f42c1;
        }
        
        .stats-info {
            flex: 1;
        }
        
        .stats-info h3 {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0 0 8px 0;
            color: var(--central-primary);
            line-height: 1;
        }
        
        .stats-info p {
            margin: 0;
            color: #6c757d;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        /* Modern Cards */
        .modern-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,51,102,0.1);
            border: 1px solid rgba(0,51,102,0.05);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .modern-card:hover {
            box-shadow: 0 12px 40px rgba(0,51,102,0.15);
            transform: translateY(-2px);
        }
        
        .modern-card-header {
            padding: 25px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        }
        
        .modern-card-header h5 {
            margin: 0;
            color: var(--central-primary);
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        .modern-card-body {
            padding: 20px;
        }
        
        /* Patient Item */
        .patient-item, .dossier-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 18px;
            border-radius: 15px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
            background: #f8f9fa;
            border: 1px solid transparent;
        }
        
        .patient-item:hover, .dossier-item:hover {
            background: white;
            border-color: var(--central-primary);
            box-shadow: 0 4px 15px rgba(0,51,102,0.1);
            transform: translateX(5px);
        }
        
        .patient-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--central-primary) 0%, #0056b3 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.3rem;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(0,51,102,0.3);
        }
        
        .dossier-icon {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(17,153,142,0.3);
        }
        
        .patient-info, .dossier-info {
            flex: 1;
        }
        
        .patient-info h6, .dossier-info h6 {
            margin: 0 0 5px 0;
            color: var(--central-primary);
            font-weight: 600;
            font-size: 1rem;
        }
        
        .patient-meta, .dossier-meta {
            text-align: right;
        }
        
        .patient-actions, .dossier-actions {
            display: flex;
            gap: 8px;
        }
        
        .table {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .table thead th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: none;
            font-weight: 600;
            color: var(--central-primary);
            padding: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }
        
        .table tbody td {
            padding: 15px;
            border: none;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background: rgba(0,51,102,0.05);
        }
        
        .badge {
            border-radius: 20px;
            padding: 6px 12px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .avatar-sm {
            width: 40px;
            height: 40px;
        }
        
        .avatar-title {
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }
        
        /* Notification styles */
        .notification-bell.has-notification .fa-bell {
            animation: ring 2s ease-in-out infinite;
            color: var(--central-secondary);
        }
        
        @keyframes ring {
            0%, 100% { transform: rotate(0deg); }
            10%, 30% { transform: rotate(-10deg); }
            20%, 40% { transform: rotate(10deg); }
        }
        
        .notification-item {
            cursor: pointer;
            transition: background 0.2s ease;
        }
        
        .notification-item:hover {
            background: rgba(0,51,102,0.05);
        }
        
        .notification-item.unread {
            background: rgba(0,86,179,0.1);
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .content-area {
                padding: 20px;
            }
            
            .topbar {
                padding: 15px 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-user-md me-2"></i>{{ auth()->user()->getEntiteName() }}</h4>
        </div>
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.medecin.dashboard') ? 'active' : '' }}" href="{{ route('admin.medecin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.medecin.patients*') ? 'active' : '' }}" href="{{ route('admin.medecin.patients') }}">
                        <i class="fas fa-users"></i>
                        Mes Patients
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.medecin.dossiers*') ? 'active' : '' }}" href="{{ route('admin.medecin.dossiers') }}">
                        <i class="fas fa-file-medical"></i>
                        Dossiers Médicaux
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.medecin.rendezvous*') ? 'active' : '' }}" href="{{ route('admin.medecin.rendezvous') }}">
                        <i class="fas fa-calendar-alt"></i>
                        Mes Rendez-vous
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        Déconnexion
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-welcome">
                <h5 class="mb-1">Bienvenue, Dr. {{ auth()->user()->nom }}</h5>
                <small class="text-muted">
                    <i class="fas fa-calendar-alt me-1"></i>
                    {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                </small>
            </div>
            <div class="d-flex align-items-center gap-3">
                <!-- Notification Bell -->
                <div class="position-relative notification-bell">
                    <button class="btn btn-link p-2" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell" style="font-size: 1.5rem;"></i>
                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill" id="notificationBadge" style="display: none;">
                            0
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="notificationDropdown" style="width: 350px; max-height: 400px; overflow-y: auto;">
                        <li class="dropdown-header bg-primary text-white">
                            <strong>Notifications</strong>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <div id="notificationsList">
                            <li class="text-center py-3 text-muted">
                                <i class="fas fa-inbox"></i><br>
                                Aucune notification
                            </li>
                        </div>
                    </ul>
                </div>
                
                <!-- User Info -->
                <div class="user-info">
                    <div class="user-avatar">
                        {{ substr(auth()->user()->nom, 0, 1) }}
                    </div>
                    <div class="user-details">
                        <div class="name">{{ auth()->user()->nom }}</div>
                        <div class="role">Médecin</div>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Système de notifications pour médecin
    document.addEventListener('DOMContentLoaded', function() {
        loadNotifications();
        setInterval(loadNotifications, 30000); // Actualiser toutes les 30 secondes
    });

    function loadNotifications() {
        fetch('{{ route("admin.medecin.notifications.get") }}')
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
        
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'inline-block';
            if (bell) bell.classList.add('has-notification');
        } else {
            badge.style.display = 'none';
            if (bell) bell.classList.remove('has-notification');
        }
    }

    function displayNotifications(notifications) {
        const list = document.getElementById('notificationsList');
        
        if (notifications.length === 0) {
            list.innerHTML = `
                <li class="text-center py-3 text-muted">
                    <i class="fas fa-inbox"></i><br>
                    Aucune notification
                </li>
            `;
        } else {
            list.innerHTML = notifications.map(notif => `
                <li class="notification-item ${notif.read ? '' : 'unread'}" onclick="handleNotificationClick(${notif.id}, '${notif.type}', ${JSON.stringify(notif.data).replace(/"/g, '&quot;')})">
                    <div class="d-flex p-3 border-bottom">
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
        fetch(`/admin/medecin/notifications/${notifId}/mark-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        // Rediriger selon le type
        if (type === 'nouveau_patient') {
            window.location.href = '{{ route("admin.medecin.patients") }}';
        } else if (type === 'rendez_vous') {
            window.location.href = '{{ route("admin.medecin.rendezvous") }}';
        } else if (type === 'dossier_assigne') {
            window.location.href = '{{ route("admin.medecin.dossiers") }}';
        }
        
        setTimeout(() => loadNotifications(), 500);
    }
    </script>
    
    @yield('scripts')
</body>
</html>
