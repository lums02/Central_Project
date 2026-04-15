<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CENTRAL+ - Espace Patient')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --central-primary: #003366;
            --central-secondary: #00a8e8;
            --central-accent: #ff6b35;
            --central-light: #f8f9fa;
            --central-dark: #1a1a1a;
            --central-success: #28a745;
            --central-warning: #ffc107;
            --central-danger: #dc3545;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            color: var(--central-dark);
        }

        /* Header Patient */
        .patient-header {
            background: linear-gradient(135deg, var(--central-primary) 0%, #002244 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(0, 51, 102, 0.3);
        }

        .patient-header .navbar-brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: white !important;
        }

        .patient-header .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
        }

        .patient-header .navbar-nav .nav-link:hover {
            color: var(--central-secondary) !important;
            transform: translateY(-2px);
        }

        .patient-header .btn-login {
            background: var(--central-secondary);
            border: none;
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .patient-header .btn-login:hover {
            background: #0088cc;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 168, 232, 0.4);
        }

        /* Responsive pour le header */
        @media (max-width: 768px) {
            .patient-header {
                padding: 0.5rem 0;
            }
            
            .navbar-brand {
                font-size: 1.5rem !important;
            }
            
            .navbar-nav .nav-link {
                padding: 0.5rem 1rem !important;
                font-size: 0.9rem !important;
            }
            
            .btn-login {
                padding: 0.5rem 1rem !important;
                font-size: 0.85rem !important;
            }
        }

        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 1.3rem !important;
            }
            
            .navbar-nav .nav-link {
                padding: 0.4rem 0.8rem !important;
                font-size: 0.8rem !important;
            }
            
            .btn-login {
                padding: 0.4rem 0.8rem !important;
                font-size: 0.8rem !important;
            }
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--central-primary) 0%, #002244 100%);
            color: white;
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            background: linear-gradient(45deg, white, var(--central-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .btn-hero {
            background: var(--central-secondary);
            border: none;
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-hero:hover {
            background: #0088cc;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 168, 232, 0.4);
            color: white;
        }

        /* Features Section */
        .features-section {
            padding: 4rem 0;
            background: white;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 51, 102, 0.1);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 51, 102, 0.15);
        }

        /* Responsive pour les cartes */
        @media (max-width: 992px) {
            .feature-card {
                padding: 2rem;
            }
        }

        @media (max-width: 768px) {
            .feature-card {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }
            
            .feature-icon {
                width: 60px;
                height: 60px;
            }
            
            .feature-icon i {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 576px) {
            .feature-card {
                padding: 1.2rem;
            }
            
            .feature-icon {
                width: 50px;
                height: 50px;
            }
            
            .feature-icon i {
                font-size: 1.5rem;
            }
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--central-primary), var(--central-secondary));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: white;
            font-size: 2rem;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--central-primary);
            margin-bottom: 1rem;
        }

        .feature-description {
            color: #666;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, var(--central-light) 0%, #e9ecef 100%);
            padding: 3rem 0;
        }

        .stat-item {
            text-align: center;
            padding: 2rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--central-primary);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-weight: 500;
        }

        /* Footer */
        .patient-footer {
            background: var(--central-dark);
            color: white;
            padding: 3rem 0 2rem;
        }

        .footer-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--central-secondary);
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: var(--central-secondary);
        }

        /* Responsive */
        /* Responsive Design */
        @media (max-width: 1200px) {
            .hero-title {
                font-size: 3rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.8rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .btn-hero {
                padding: 0.9rem 2.2rem;
                font-size: 1rem;
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .btn-hero {
                padding: 0.8rem 2rem;
                font-size: 0.95rem;
                margin-bottom: 1rem;
                display: block;
                width: 100%;
                text-align: center;
            }
            
            .feature-card {
                margin-bottom: 2rem;
            }
            
            .stat-item {
                margin-bottom: 2rem;
            }
            
            .hero-section {
                padding: 3rem 0;
            }
            
            .features-section {
                padding: 3rem 0;
            }
            
            .stats-section {
                padding: 3rem 0;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
                line-height: 1.2;
            }
            
            .hero-subtitle {
                font-size: 0.95rem;
                margin-bottom: 1.5rem;
            }
            
            .btn-hero {
                padding: 0.7rem 1.5rem;
                font-size: 0.9rem;
            }
            
            .feature-title {
                font-size: 1.3rem;
            }
            
            .feature-description {
                font-size: 0.9rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            .stat-label {
                font-size: 0.9rem;
            }
            
            .hero-section {
                padding: 2rem 0;
            }
            
            .features-section {
                padding: 2rem 0;
            }
            
            .stats-section {
                padding: 2rem 0;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 1.8rem;
            }
            
            .hero-subtitle {
                font-size: 0.9rem;
            }
            
            .btn-hero {
                padding: 0.6rem 1.2rem;
                font-size: 0.85rem;
            }
            
            .feature-title {
                font-size: 1.2rem;
            }
            
            .feature-description {
                font-size: 0.85rem;
            }
            
            .stat-number {
                font-size: 1.8rem;
            }
            
            .stat-label {
                font-size: 0.8rem;
            }
        }

        /* Responsive pour l'image hero */
        @media (max-width: 992px) {
            .hero-image {
                height: 400px !important;
            }
            
            .hero-image h3 {
                font-size: 1.8rem !important;
            }
            
            .hero-image p {
                font-size: 1rem !important;
            }
            
            .hero-image .fas.fa-heartbeat {
                font-size: 2.5rem !important;
            }
            
            .hero-image div[style*="width: 100px"] {
                width: 80px !important;
                height: 80px !important;
            }
        }

        @media (max-width: 768px) {
            .hero-image {
                height: 350px !important;
            }
            
            .hero-image h3 {
                font-size: 1.6rem !important;
            }
            
            .hero-image p {
                font-size: 0.95rem !important;
            }
            
            .hero-image .fas.fa-heartbeat {
                font-size: 2rem !important;
            }
            
            .hero-image div[style*="width: 100px"] {
                width: 70px !important;
                height: 70px !important;
            }
            
            .hero-image .row {
                padding: 1rem !important;
            }
            
            .hero-image .col-4 div:first-child {
                font-size: 1.2rem !important;
            }
            
            .hero-image .col-4 div:last-child {
                font-size: 0.8rem !important;
            }
        }

        @media (max-width: 576px) {
            .hero-image {
                height: 300px !important;
            }
            
            .hero-image h3 {
                font-size: 1.4rem !important;
            }
            
            .hero-image p {
                font-size: 0.9rem !important;
            }
            
            .hero-image .fas.fa-heartbeat {
                font-size: 1.8rem !important;
            }
            
            .hero-image div[style*="width: 100px"] {
                width: 60px !important;
                height: 60px !important;
            }
            
            .hero-image .row {
                padding: 0.8rem !important;
            }
            
            .hero-image .col-4 div:first-child {
                font-size: 1rem !important;
            }
            
            .hero-image .col-4 div:last-child {
                font-size: 0.7rem !important;
            }
        }

        @media (max-width: 480px) {
            .hero-image {
                height: 250px !important;
            }
            
            .hero-image h3 {
                font-size: 1.2rem !important;
            }
            
            .hero-image p {
                font-size: 0.85rem !important;
            }
            
            .hero-image .fas.fa-heartbeat {
                font-size: 1.5rem !important;
            }
            
            .hero-image div[style*="width: 100px"] {
                width: 50px !important;
                height: 50px !important;
            }
            
            .hero-image .row {
                padding: 0.6rem !important;
            }
            
            .hero-image .col-4 div:first-child {
                font-size: 0.9rem !important;
            }
            
            .hero-image .col-4 div:last-child {
                font-size: 0.6rem !important;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

        .floating-element {
            animation: float 3s ease-in-out infinite;
        }

        .pulse-animation {
            animation: pulse 2s ease-in-out infinite;
        }

        .slide-in-left {
            animation: slideInLeft 0.8s ease-out;
        }

        .slide-in-right {
            animation: slideInRight 0.8s ease-out;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="patient-header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="{{ route('patient.index') }}">
                    <i class="fas fa-heartbeat me-2"></i>CENTRAL+
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('patient.dashboard') ? 'active' : '' }}" href="{{ route('patient.dashboard') }}">
                                    <i class="fas fa-home me-1"></i>Accueil
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('patient.dossiers*') ? 'active' : '' }}" href="{{ route('patient.dossiers') }}">
                                    <i class="fas fa-folder-open me-1"></i>Mon Dossier
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('patient.rendezvous') ? 'active' : '' }}" href="{{ route('patient.rendezvous') }}">
                                    <i class="fas fa-calendar-alt me-1"></i>Rendez-vous
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('patient.examens') ? 'active' : '' }}" href="{{ route('patient.examens') }}">
                                    <i class="fas fa-flask me-1"></i>Examens
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('patient.pharmacies') ? 'active' : '' }}" href="{{ route('patient.pharmacies') }}">
                                    <i class="fas fa-pills me-1"></i>Pharmacies
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('patient.banques-sang') ? 'active' : '' }}" href="{{ route('patient.banques-sang') }}">
                                    <i class="fas fa-tint me-1"></i>Banques de Sang
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="#services">Services</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#about">À propos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#contact">Contact</a>
                            </li>
                        @endauth
                    </ul>
                    
                    <div class="d-flex">
                        @auth
                            <span class="text-white me-3 align-self-center">{{ auth()->user()->nom }}</span>
                            <form action="{{ route('patient.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-login">
                                    <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-login">
                                <i class="fas fa-sign-in-alt me-1"></i>Se connecter
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="patient-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="footer-title">CENTRAL+</h5>
                    <p class="text-muted">Votre partenaire santé de confiance. Accès facile et sécurisé à vos informations médicales.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="footer-title">Services</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="footer-link">Consultation en ligne</a></li>
                        <li><a href="#" class="footer-link">Dossier médical</a></li>
                        <li><a href="#" class="footer-link">Rendez-vous</a></li>
                        <li><a href="#" class="footer-link">Prescriptions</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="footer-title">Contact</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone me-2"></i>+33 1 23 45 67 89</li>
                        <li><i class="fas fa-envelope me-2"></i>contact@central-plus.fr</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>Paris, France</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-0">&copy; 2025 CENTRAL+. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="#" class="footer-link me-3">Mentions légales</a>
                    <a href="#" class="footer-link">Politique de confidentialité</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation de comptage pour les statistiques
            function animateCounter(element, target, duration = 2000) {
                let start = 0;
                const increment = target / (duration / 16);
                
                function updateCounter() {
                    start += increment;
                    if (start < target) {
                        element.textContent = Math.floor(start).toLocaleString();
                        requestAnimationFrame(updateCounter);
                    } else {
                        element.textContent = target.toLocaleString();
                    }
                }
                
                updateCounter();
            }

            // Observer pour déclencher les animations
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counters = entry.target.querySelectorAll('[data-count]');
                        counters.forEach(counter => {
                            const target = parseFloat(counter.getAttribute('data-count'));
                            animateCounter(counter, target);
                        });
                    }
                });
            }, { threshold: 0.5 });

            // Observer la section des statistiques
            const statsSection = document.querySelector('.stats-section');
            if (statsSection) {
                observer.observe(statsSection);
            }

            // Animation au scroll
            window.addEventListener('scroll', function() {
                const elements = document.querySelectorAll('.fade-in-up');
                elements.forEach(element => {
                    const elementTop = element.getBoundingClientRect().top;
                    const elementVisible = 150;
                    
                    if (elementTop < window.innerHeight - elementVisible) {
                        element.style.opacity = '1';
                        element.style.transform = 'translateY(0)';
                    }
                });
            });
        });
    </script>
</body>
</html>
