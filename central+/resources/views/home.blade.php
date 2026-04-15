<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CENTRAL+ - Plateforme de Gestion de Santé</title>

    <!-- CSS externes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- CSS personnalisé -->
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">

    <style>
        :root {
            --central-primary: #003366;
            --central-secondary: #0066cc;
            --central-light: #e6f0ff;
            --central-gradient: linear-gradient(135deg, #003366 0%, #0066cc 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
/* Header Glassmorphism */
.header {
    background: rgba(255, 255, 255, 0.85); /* Semi-transparent */
    backdrop-filter: blur(12px); /* Effet de flou */
    -webkit-backdrop-filter: blur(12px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    position: sticky;
    top: 0;
    z-index: 1000;
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(0,0,0,0.02);
}

.logo {
    color: var(--central-primary);
    font-weight: 800;
    font-size: 1.6rem;
    letter-spacing: -0.5px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.nav-moyo .nav-link {
    margin: 0 0.8rem;
    font-weight: 600;
    color: var(--central-gradient);
    font-size: 0.95rem;
    opacity: 0.8;
    transition: all 0.2s ease;
}

.nav-moyo .nav-link:hover, .nav-moyo .nav-link.active {
    color: var(--central-primary);
    opacity: 1;
}

.btn-login {
    color: var(--central-gradient);
    font-weight: 600;
    background: transparent;
    border: 1px solid transparent;
    padding: 8px 20px;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.btn-login:hover {
    background: #f0fbfd;
    color: var(--central-primary);
}

.btn-signup {
    background: var(--central-primary);
    color: white;
    font-weight: 600;
    padding: 8px 24px;
    border-radius: 50px;
    box-shadow: 0 4px 12px rgba(0, 168, 201, 0.25);
    transition: all 0.3s ease;
}

.btn-signup:hover {
    background: var(--central-secondary);
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0, 168, 201, 0.3);
    color: white;
}

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        /* Hero Section */
        .hero-section {
            background: var(--central-gradient);
            padding: 80px 0 100px 0;
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
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="white" opacity="0.1"/></svg>');
            animation: float 20s linear infinite;
        }

        @keyframes float {
            0% { transform: translateY(0); }
            100% { transform: translateY(-100px); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
            text-align: center;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            animation: fadeInUp 1s ease;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 40px;
            opacity: 0.95;
            animation: fadeInUp 1s ease 0.2s backwards;
        }

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

        /* Section Entités */
        .entities-section {
            padding: 60px 0;
            margin-top: -50px;
            position: relative;
            z-index: 10;
        }

        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-header h2 {
            color: var(--central-primary);
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .section-header p {
            color: #666;
            font-size: 1.2rem;
        }

        /* Cartes Entités */
        .entity-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0, 51, 102, 0.1);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .entity-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--central-gradient);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .entity-card:hover::before {
            transform: scaleX(1);
        }

        .entity-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0, 51, 102, 0.2);
        }

        .entity-icon {
            width: 80px;
            height: 80px;
            background: var(--central-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            transition: all 0.4s ease;
        }

        .entity-card:hover .entity-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .entity-icon i {
            font-size: 2rem;
            color: white;
        }

        .entity-title {
            color: var(--central-primary);
            font-size: 1.8rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 20px;
        }

        .entity-description {
            color: #666;
            text-align: center;
            margin-bottom: 25px;
            line-height: 1.6;
            flex-grow: 1;
        }

        .entity-features {
            list-style: none;
            padding: 0;
            margin-bottom: 30px;
        }

        .entity-features li {
            padding: 10px 0;
            color: #555;
            display: flex;
            align-items: center;
        }

        .entity-features li i {
            color: var(--central-secondary);
            margin-right: 12px;
            font-size: 1.1rem;
        }

        .entity-btn {
            background: var(--central-gradient);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            width: 100%;
        }

        .entity-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(0, 51, 102, 0.3);
            color: white;
        }

        /* Prix */
        .entity-pricing {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            border: 2px solid #e0e0e0;
        }

        .price-tag {
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 5px;
            margin-bottom: 10px;
        }

        .price-amount {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--central-primary);
        }

        .price-currency {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--central-secondary);
        }

        .price-period {
            font-size: 0.9rem;
            color: #666;
        }

        .price-label {
            font-size: 0.85rem;
            color: #888;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .price-features {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }

        .price-feature {
            font-size: 0.85rem;
            color: #555;
            margin: 5px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .price-feature i {
            color: #28a745;
            font-size: 0.9rem;
        }

        /* Section Avantages */
        .benefits-section {
            background: white;
            padding: 80px 0;
        }

        .benefit-item {
            text-align: center;
            padding: 30px;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .benefit-item:hover {
            background: var(--central-light);
            transform: translateY(-5px);
        }

        .benefit-icon {
            width: 60px;
            height: 60px;
            background: var(--central-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .benefit-icon i {
            font-size: 1.5rem;
            color: white;
        }

        .benefit-title {
            color: var(--central-primary);
            font-weight: 700;
            margin-bottom: 10px;
        }

        /* Statistiques */
        .stat-box {
            text-align: center;
            padding: 30px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 51, 102, 0.1);
            transition: all 0.3s ease;
        }

        .stat-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 51, 102, 0.2);
        }

        .stat-number-big {
            font-size: 3rem;
            font-weight: 700;
            background: var(--central-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .stat-label-big {
            color: #666;
            font-weight: 600;
            font-size: 1rem;
        }

        /* Footer */
        .footer {
            background: var(--central-primary);
            color: white;
            padding: 40px 0 20px;
            text-align: center;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--central-light);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }

            .section-header h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
<!-- Header -->
<header class="header py-3">
  <div class="container d-flex justify-content-between align-items-center">
    <!-- Logo -->
    <a href="/" class="text-decoration-none">
        <h3 class="logo m-0">
            <i class="fa-solid fa-dna text-primary"></i> 
            <span>Central+</span>
        </h3>
    </a>


    <!-- Boutons -->
    <div class="header-buttons d-flex align-items-center gap-2">
      <a href="/login" class="btn-login text-decoration-none">Se connecter</a>
      <a href="/register" class="btn-signup text-decoration-none">
        Créer un compte <i class="fa-solid fa-arrow-right ms-2 small"></i>
      </a>
    </div>
  </div>
</header>
    <!-- SECTION HERO -->
    <div class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Bienvenue sur <strong>CENTRAL+</strong></h1>
                <p class="hero-subtitle">Plateforme Intégrée de Gestion de Santé</p>
                <p style="font-size: 1.1rem; opacity: 0.9; animation: fadeInUp 1s ease 0.4s backwards;">
                    Choisissez votre espace selon vos besoins
                </p>
            </div>
        </div>
    </div>

    <!-- SECTION ENTITÉS -->
    <section class="entities-section">
        <div class="container">
            <div class="section-header">
                <h2>Nos Solutions de Gestion</h2>
                <p>Des outils adaptés à chaque structure de santé</p>
            </div>

            <div class="row">
                <!-- Hôpital -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="entity-card" onclick="window.location.href='/hopital'">
                        <div class="entity-icon">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <h3 class="entity-title">Hôpitaux</h3>
                        <p class="entity-description">
                            Solution complète pour la gestion hospitalière : patients, personnel, services, équipements et finances.
                        </p>
                        
                        <!-- Pricing -->
                        <div class="entity-pricing">
                            <div class="price-tag">
                                <span class="price-currency">$</span>
                                <span class="price-amount">5.99</span>
                            </div>
                            <div class="price-period">/mois</div>
                            <div class="price-features">
                                <div class="price-feature">
                                    <i class="fas fa-check"></i>
                                    <span>Gestion complète</span>
                                </div>
                                <div class="price-feature">
                                    <i class="fas fa-check"></i>
                                    <span>Support 24/7</span>
                                </div>
                            </div>
                        </div>
                        
                        <a href="/hopital" class="entity-btn">
                            <span>Découvrir</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Pharmacie -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="entity-card" onclick="window.location.href='/pharmacie'">
                        <div class="entity-icon">
                            <i class="fas fa-pills"></i>
                        </div>
                        <h3 class="entity-title">Pharmacies</h3>
                        <p class="entity-description">
                            Gérez votre pharmacie efficacement : stock de médicaments, ventes, prescriptions et inventaire.
                        </p>
                        
                        <!-- Pricing -->
                        <div class="entity-pricing">
                            <div class="price-tag">
                                <span class="price-currency">$</span>
                                <span class="price-amount">4.99</span>
                            </div>
                            <div class="price-period">/mois</div>
                            <div class="price-features">
                                <div class="price-feature">
                                    <i class="fas fa-check"></i>
                                    <span>Gestion stock illimitée</span>
                                </div>
                                <div class="price-feature">
                                    <i class="fas fa-check"></i>
                                    <span>Point de vente intégré</span>
                                </div>
                            </div>
                        </div>
                        
                        <a href="/pharmacie" class="entity-btn">
                            <span>Découvrir</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Laboratoire / Banque de Sang -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="entity-card" onclick="window.location.href='/banque'">
                        <div class="entity-icon">
                            <i class="fas fa-flask"></i>
                        </div>
                        <h3 class="entity-title">Banques de Sang</h3>
                        <p class="entity-description">
                            Gestion des analyses médicales, résultats de laboratoire et gestion des réserves de sang.
                        </p>
                        
                        <!-- Pricing -->
                        <div class="entity-pricing">
                            <div class="price-tag">
                                <span class="price-currency">$</span>
                                <span class="price-amount">4.99</span>
                            </div>
                            <div class="price-period">/mois</div>
                            <div class="price-features">
                                <div class="price-feature">
                                    <i class="fas fa-check"></i>
                                    <span>Gestion réserves</span>
                                </div>
                                <div class="price-feature">
                                    <i class="fas fa-check"></i>
                                    <span>Traçabilité complète</span>
                                </div>
                            </div>
                        </div>
                        
                        <a href="/banque" class="entity-btn">
                            <span>Découvrir</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Patient -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="entity-card" onclick="window.location.href='/patient'">
                        <div class="entity-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h3 class="entity-title">Patients</h3>
                        <p class="entity-description">
                            Accédez à vos dossiers médicaux, recevez des rappels pour vos médicaments et rendez-vous.
                        </p>
                        
                        <!-- Pricing -->
                        <div class="entity-pricing">
                            <div class="price-tag">
                                <span class="price-currency">$</span>
                                <span class="price-amount">2.99</span>
                            </div>
                            <div class="price-period">/mois</div>
                            <div class="price-features">
                                <div class="price-feature">
                                    <i class="fas fa-check"></i>
                                    <span>Dossier médical 24/7</span>
                                </div>
                                <div class="price-feature">
                                    <i class="fas fa-check"></i>
                                    <span>Rappels automatiques</span>
                                </div>
                            </div>
                        </div>
                        
                        <a href="/patient" class="entity-btn">
                            <span>Découvrir</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION AVANTAGES -->
    <section class="benefits-section">
        <div class="container">
            <div class="section-header">
                <h2>Pourquoi choisir CENTRAL+ ?</h2>
                <p>La solution qui transforme la gestion de santé en RDC</p>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <h4 class="benefit-title">Prix Imbattables</h4>
                        <p>À partir de 2.99$/mois seulement - la solution santé la plus abordable du marché</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-cloud"></i>
                        </div>
                        <h4 class="benefit-title">100% Cloud</h4>
                        <p>Aucune installation requise - accédez à vos données partout, à tout moment</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h4 class="benefit-title">Démarrage Immédiat</h4>
                        <p>Créez votre compte et commencez en moins de 5 minutes - simplicité garantie</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="benefit-title">Déjà 500+ Utilisateurs</h4>
                        <p>Rejoignez la communauté CENTRAL+ et bénéficiez de notre expertise reconnue</p>
                    </div>
                </div>
            </div>

            <!-- Statistiques supplémentaires -->
            <div class="row mt-5">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stat-box">
                        <div class="stat-number-big">99.9%</div>
                        <div class="stat-label-big">Disponibilité</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stat-box">
                        <div class="stat-number-big">-70%</div>
                        <div class="stat-label-big">Temps de gestion</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stat-box">
                        <div class="stat-number-big">24/7</div>
                        <div class="stat-label-big">Support technique</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stat-box">
                        <div class="stat-number-big">100%</div>
                        <div class="stat-label-big">Données sécurisées</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="footer-links">
                <a href="#"><i class="fas fa-question-circle"></i> Aide</a>
                <a href="#"><i class="fas fa-phone"></i> Contact</a>
                <a href="#"><i class="fas fa-file-alt"></i> Conditions d'utilisation</a>
                <a href="#"><i class="fas fa-shield-alt"></i> Confidentialité</a>
            </div>
            <p>&copy; 2024 CENTRAL+ - Tous droits réservés</p>
        </div>
    </footer>

    <!-- JS externes -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>
