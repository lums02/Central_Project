<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'CENTRAL+ - Pour les Pharmacies')</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- CENTRAL+ Pharmacie CSS -->
    <link href="{{ asset('css/pharmacie.css') }}?v={{ time() }}" rel="stylesheet">
    
    <style>
        :root {
            --central-primary: #003366;      /* Bleu CENTRAL+ */
            --central-secondary: #00a8e8;    /* Bleu clair CENTRAL+ */
            --central-accent: #4A90E2;       /* Bleu accent */
            --central-dark: #002244;        /* Bleu foncé */
            --central-light: #E3F2FD;        /* Bleu très clair */
            --central-text: #2E2E2E;
            --central-text-light: #666;
            --central-bg: #F8F9FA;
            --central-white: #FFFFFF;
            --central-shadow: rgba(0, 51, 102, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--central-text);
            background-color: var(--central-bg);
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero-section {
            background: var(--central-primary);
            color: white;
            position: relative;
            overflow: hidden;
        }


        .hero-content {
            position: relative;
            z-index: 2;
            padding: 2rem 0;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }
        
        .hero-title .text-primary {
            color: white !important;
        }
        
        .hero-title .text-secondary {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        
        /* Force CENTRAL+ colors with high specificity */
        body .text-primary {
            color: #003366 !important;
        }
        
        body .text-secondary {
            color: #00a8e8 !important;
        }
        
        body .btn-primary {
            background-color: #003366 !important;
            border-color: #003366 !important;
        }
        
        body .btn-primary:hover {
            background-color: #002244 !important;
            border-color: #002244 !important;
        }
        
        body .bg-primary {
            background-color: #003366 !important;
        }
        
        body .border-primary {
            border-color: #003366 !important;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            font-weight: 400;
            opacity: 0.9;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn-hero {
            display: inline-flex;
            align-items: center;
            padding: 1rem 2rem;
            background: var(--central-white);
            color: var(--central-primary);
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: none;
        }

        .btn-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            color: var(--central-primary);
        }

        /* Hero Image */
        .hero-image {
            position: relative;
            z-index: 2;
            padding: 2rem 0;
        }

        .pharmacy-image-container {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            background: white;
        }

        .pharmacy-hero-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            display: block;
        }

        .image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 51, 102, 0.9));
            padding: 2rem;
            color: white;
        }

        .overlay-stats {
            display: flex;
            justify-content: space-around;
            align-items: center;
            gap: 1rem;
        }

        .overlay-stat {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 1rem;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            min-width: 100px;
        }

        .overlay-stat .stat-number {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.25rem;
        }

        .overlay-stat .stat-text {
            display: block;
            font-size: 0.9rem;
            opacity: 0.9;
            color: white;
        }


        /* Services Section */
        .services-section {
            padding: 5rem 0;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--central-primary);
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1.2rem;
            color: var(--central-text-light);
            margin-bottom: 3rem;
        }

        .service-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            text-align: center;
            box-shadow: 0 10px 30px var(--central-shadow);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid rgba(46, 125, 50, 0.1);
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px var(--central-shadow);
        }

        .service-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--central-primary), var(--central-secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
        }

        .service-card h4 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--central-primary);
            margin-bottom: 1rem;
        }

        .service-card p {
            color: var(--central-text-light);
            line-height: 1.6;
        }

        /* Features Section */
        .features-section {
            padding: 5rem 0;
        }

        .feature-list {
            margin-top: 2rem;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            transform: translateX(10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--central-primary), var(--central-secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
            font-size: 1.2rem;
            color: white;
            flex-shrink: 0;
        }

        .feature-content h5 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--central-primary);
            margin-bottom: 0.5rem;
        }

        .feature-content p {
            color: var(--central-text-light);
            margin: 0;
        }

        /* Dashboard Preview */
        .pharmacy-dashboard-preview {
            position: relative;
            padding: 2rem;
        }

        .dashboard-mockup {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            border: 1px solid rgba(46, 125, 50, 0.1);
        }

        .mockup-header {
            background: var(--central-primary);
            color: white;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mockup-title {
            font-weight: 600;
        }

        .mockup-controls {
            display: flex;
            gap: 0.5rem;
        }

        .control-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
        }

        .mockup-content {
            display: flex;
            min-height: 300px;
        }

        .mockup-sidebar {
            width: 200px;
            background: #f8f9fa;
            padding: 1rem 0;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--central-text-light);
            transition: all 0.3s ease;
        }

        .sidebar-item.active {
            background: var(--central-light);
            color: var(--central-primary);
            font-weight: 600;
        }

        .sidebar-item i {
            margin-right: 0.75rem;
            width: 20px;
        }

        .mockup-main {
            flex: 1;
            padding: 1.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .stat-box {
            background: var(--central-light);
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--central-primary);
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--central-text-light);
            margin-top: 0.5rem;
        }

        /* Testimonials Section */
        .testimonials-section {
            padding: 5rem 0;
        }

        .testimonial-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px var(--central-shadow);
            transition: all 0.3s ease;
            height: 100%;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px var(--central-shadow);
        }

        .stars {
            color: #FFD700;
            margin-bottom: 1rem;
        }

        .testimonial-content p {
            font-style: italic;
            color: var(--central-text-light);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--central-primary), var(--central-secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: white;
            font-size: 1.2rem;
        }

        .author-info h5 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--central-primary);
            margin: 0;
        }

        .author-info span {
            font-size: 0.9rem;
            color: var(--central-text-light);
        }

        /* CTA Section */
        .cta-section {
            padding: 5rem 0;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta-subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .cta-buttons {
            margin-top: 2rem;
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

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

        /* Pricing Section */
        .pricing-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .pricing-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .pricing-card.featured {
            border: 3px solid var(--central-secondary);
            transform: scale(1.05);
        }

        .pricing-card.featured:hover {
            transform: scale(1.05) translateY(-10px);
        }

        .pricing-badge {
            position: absolute;
            top: -1px;
            right: 20px;
            background: var(--central-secondary);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 0 0 15px 15px;
            font-size: 0.9rem;
            font-weight: 600;
            z-index: 2;
        }

        .pricing-header {
            padding: 2rem 2rem 1rem;
            text-align: center;
            background: linear-gradient(135deg, var(--central-light) 0%, rgba(0, 168, 232, 0.1) 100%);
        }

        .plan-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--central-primary);
            margin-bottom: 1rem;
        }

        .plan-price {
            margin-bottom: 1rem;
        }

        .currency {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--central-text-light);
            vertical-align: top;
        }

        .amount {
            font-size: 3rem;
            font-weight: 800;
            color: var(--central-primary);
            line-height: 1;
        }

        .period {
            font-size: 1rem;
            color: var(--central-text-light);
            font-weight: 500;
        }

        .plan-description {
            color: var(--central-text-light);
            font-size: 1rem;
            margin: 0;
        }

        .pricing-features {
            padding: 1rem 2rem;
            flex-grow: 1;
        }

        .pricing-features .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pricing-features .feature-list li {
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
            color: var(--central-text);
            font-size: 0.95rem;
        }

        .pricing-features .feature-list li:last-child {
            border-bottom: none;
        }

        .pricing-footer {
            padding: 1rem 2rem 2rem;
            margin-top: auto;
        }

        .pricing-info {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            max-width: 800px;
            margin: 0 auto;
        }

        .pricing-info p {
            color: var(--central-text);
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .pricing-info i {
            color: var(--central-primary);
        }

        /* Price Toggle */
        .price-options {
            margin-bottom: 1rem;
        }

        .price-toggle {
            display: flex;
            background: rgba(0, 51, 102, 0.1);
            border-radius: 25px;
            padding: 0.25rem;
            margin-bottom: 0.5rem;
        }

        .price-option {
            flex: 1;
            text-align: center;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--central-text-light);
        }

        .price-option.active {
            background: var(--central-primary);
            color: white;
        }

        .yearly-savings {
            font-size: 0.8rem;
        }

        /* Payment Modal */
        .subscription-summary {
            background: var(--central-light);
            border-radius: 15px;
            padding: 1.5rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(0, 51, 102, 0.1);
        }

        .summary-item.total {
            border-bottom: none;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--central-primary);
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px solid var(--central-primary);
        }

        .item-name {
            color: var(--central-text-light);
        }

        .item-value {
            color: var(--central-primary);
            font-weight: 600;
        }

        .trial-info {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(0, 51, 102, 0.1);
        }

        .payment-methods {
            padding: 1rem;
        }

        .payment-method-group {
            margin-bottom: 2rem;
        }

        .method-group-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--central-primary);
            margin-bottom: 1rem;
        }

        .payment-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .payment-option {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            background: white;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: var(--central-text);
        }

        .payment-option:hover {
            border-color: var(--central-secondary);
            background: var(--central-light);
        }

        .payment-option.selected {
            border-color: var(--central-primary);
            background: var(--central-light);
        }

        .payment-option img {
            margin-right: 0.75rem;
            border-radius: 4px;
        }

        .payment-option span {
            font-weight: 500;
        }

        /* Payment Methods */
        .payment-method {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method:hover {
            border-color: var(--central-secondary);
            background: var(--central-light);
        }

        .payment-method.selected {
            border-color: var(--central-primary);
            background: var(--central-light);
        }

        .payment-icon {
            font-size: 1.5rem;
            margin-right: 1rem;
            color: var(--central-primary);
        }

        /* Orange Money Forms */
        .orange-money-form {
            background: #fff3e0;
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid #ffb74d;
        }

        .orange-money-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .orange-money-logo {
            width: 60px;
            height: 60px;
            background: #ff6b00;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }

        /* Airtel Money Forms */
        .airtel-money-form {
            background: #ffebee;
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid #f44336;
        }

        .airtel-money-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .airtel-money-logo {
            width: 60px;
            height: 60px;
            background: #e60012;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }

        /* M-Pesa Forms */
        .mpesa-form {
            background: #e8f5e8;
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid #4caf50;
        }

        .mpesa-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .mpesa-logo {
            width: 60px;
            height: 60px;
            background: #00a651;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .pharmacy-hero-image {
                height: 300px;
            }
            
            .overlay-stats {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .overlay-stat {
                min-width: auto;
                padding: 0.75rem;
            }
            
            .overlay-stat .stat-number {
                font-size: 1.2rem;
            }
            
            .overlay-stat .stat-text {
                font-size: 0.8rem;
            }
            
            .mockup-content {
                flex-direction: column;
            }
            
            .mockup-sidebar {
                width: 100%;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .pricing-card.featured {
                transform: none;
            }
            
            .pricing-card.featured:hover {
                transform: translateY(-10px);
            }
            
            .pricing-header {
                padding: 1.5rem 1.5rem 1rem;
            }
            
            .amount {
                font-size: 2.5rem;
            }
            
            .pricing-features {
                padding: 1rem 1.5rem;
            }
            
            .pricing-footer {
                padding: 1rem 1.5rem 1.5rem;
            }
            
            .pricing-info {
                padding: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .btn-hero {
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
            }
            
            .service-card,
            .testimonial-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    @yield('content')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Pharmacie Payment JS -->
    <script src="{{ asset('js/pharmacie-payment.js') }}?v={{ time() }}"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Animation des compteurs
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number[data-count]');
            
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-count'));
                const duration = 2000; // 2 seconds
                const increment = target / (duration / 16); // 60fps
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    counter.textContent = Math.floor(current);
                }, 16);
            });
        }

        // Intersection Observer pour les animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in-up');
                    
                    // Animer les compteurs quand ils sont visibles
                    if (entry.target.querySelector('.stat-number[data-count]')) {
                        animateCounters();
                    }
                }
            });
        }, observerOptions);

        // Observer les éléments
        document.addEventListener('DOMContentLoaded', () => {
            const elementsToAnimate = document.querySelectorAll('.hero-content, .service-card, .feature-item, .testimonial-card, .pharmacy-stats-overlay');
            elementsToAnimate.forEach(el => observer.observe(el));
        });

        // Effet de parallaxe léger
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.hero-section');
            
            parallaxElements.forEach(element => {
                const speed = 0.5;
                element.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });

        // Gestion des toggles de prix
        document.addEventListener('DOMContentLoaded', function() {
            // Toggles de prix
            document.querySelectorAll('.price-option').forEach(option => {
                option.addEventListener('click', function() {
                    const toggle = this.parentElement;
                    const card = toggle.closest('.pricing-card');
                    
                    // Retirer la classe active de tous les options du même toggle
                    toggle.querySelectorAll('.price-option').forEach(opt => opt.classList.remove('active'));
                    
                    // Ajouter la classe active à l'option cliquée
                    this.classList.add('active');
                    
                    // Mettre à jour le prix
                    const period = this.dataset.period;
                    const amountElement = card.querySelector('.amount');
                    const periodElement = card.querySelector('.period');
                    const savingsElement = card.querySelector('.yearly-savings');
                    const button = card.querySelector('.start-subscription');
                    
                    if (period === 'yearly') {
                        const monthlyPrice = parseInt(button.dataset.priceMonthly);
                        const yearlyPrice = parseInt(button.dataset.priceYearly);
                        amountElement.textContent = yearlyPrice;
                        periodElement.textContent = '/an';
                        savingsElement.style.display = 'block';
                    } else {
                        const monthlyPrice = parseInt(button.dataset.priceMonthly);
                        amountElement.textContent = monthlyPrice;
                        periodElement.textContent = '/mois';
                        savingsElement.style.display = 'none';
                    }
                });
            });

            // Gestion des boutons d'abonnement
            document.querySelectorAll('.start-subscription').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const plan = this.dataset.plan;
                    const card = this.closest('.pricing-card');
                    const activePeriod = card.querySelector('.price-option.active').dataset.period;
                    const priceMonthly = parseInt(this.dataset.priceMonthly);
                    const priceYearly = parseInt(this.dataset.priceYearly);
                    
                    // Mettre à jour la modal avec les informations du plan
                    document.getElementById('selectedPlanName').textContent = plan.charAt(0).toUpperCase() + plan.slice(1);
                    document.getElementById('selectedPeriod').textContent = activePeriod === 'yearly' ? 'Annuel' : 'Mensuel';
                    document.getElementById('selectedPrice').textContent = activePeriod === 'yearly' ? `€${priceYearly}/an` : `€${priceMonthly}/mois`;
                    document.getElementById('totalPrice').textContent = activePeriod === 'yearly' ? `€${priceYearly}` : `€${priceMonthly}`;
                    
                    // Stocker les données pour le paiement
                    window.selectedPlanData = {
                        plan: plan,
                        period: activePeriod,
                        price: activePeriod === 'yearly' ? priceYearly : priceMonthly
                    };
                    
                    // Ouvrir la modal de paiement
                    const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
                    paymentModal.show();
                });
            });

            // Gestion de la sélection des méthodes de paiement
            document.querySelectorAll('.payment-option').forEach(option => {
                option.addEventListener('click', function() {
                    // Retirer la sélection de toutes les options
                    document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
                    
                    // Ajouter la sélection à l'option cliquée
                    this.classList.add('selected');
                    
                    // Activer le bouton de paiement
                    document.getElementById('proceedToPayment').disabled = false;
                });
            });

            // Gestion du bouton "Procéder au paiement"
            document.getElementById('proceedToPayment').addEventListener('click', function() {
                const selectedMethod = document.querySelector('.payment-option.selected');
                if (!selectedMethod) return;
                
                const method = selectedMethod.dataset.method;
                
                // Fermer la modal de paiement
                const paymentModal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                paymentModal.hide();
                
                // Ouvrir la modal de traitement
                const processingModal = new bootstrap.Modal(document.getElementById('processingModal'));
                processingModal.show();
                
                // Simuler le traitement du paiement
                setTimeout(() => {
                    processingModal.hide();
                    
                    // Rediriger vers l'inscription avec les données du plan
                    const planData = window.selectedPlanData;
                    const registerUrl = `/register?type=pharmacie&plan=${planData.plan}&period=${planData.period}&payment_method=${method}&amount=${planData.price}`;
                    window.location.href = registerUrl;
                }, 3000);
            });
        });
    </script>
</body>
</html>
