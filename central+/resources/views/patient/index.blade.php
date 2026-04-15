@extends('layouts.patient')

@section('title', 'CENTRAL+ - Votre Espace Santé')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title fade-in-up">
                        Votre Santé,<br>
                        <span style="color: var(--central-secondary);">Notre Priorité</span>
                    </h1>
                    <p class="hero-subtitle fade-in-up">
                        Accédez facilement à vos informations médicales, prenez rendez-vous en ligne et gérez votre santé en toute simplicité avec CENTRAL+.
                    </p>
                    <div class="fade-in-up">
                        @auth
                            <a href="{{ route('login') }}?type=patient" class="btn-hero me-3">
                                <i class="fas fa-tachometer-alt me-2"></i>Mon Espace Personnel
                            </a>
                        @else
                            <a href="{{ route('login') }}?type=patient" class="btn-hero me-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Mon Espace Personnel
                            </a>
                            <a href="{{ route('register.form') }}?type=patient" class="btn-hero" style="background: transparent; border: 2px solid var(--central-secondary);">
                                <i class="fas fa-user-plus me-2"></i>S'inscrire
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center fade-in-up">
                    <!-- Image Hero avec overlay -->
                    <div style="position: relative; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
                        <!-- Image de fond -->
                        <div style="
                            background: linear-gradient(135deg, rgba(0, 51, 102, 0.8), rgba(0, 168, 232, 0.6)), 
                                        url('https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80');
                            background-size: cover;
                            background-position: center;
                            background-repeat: no-repeat;
                            height: 500px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            position: relative;
                        " class="hero-image">
                            <!-- Overlay avec contenu -->
                            <div style="text-align: center; color: white; padding: 2rem; position: relative; z-index: 2;">
                                <!-- Icône principale -->
                                <div style="width: 100px; height: 100px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; backdrop-filter: blur(10px); border: 2px solid rgba(255, 255, 255, 0.3);">
                                    <i class="fas fa-heartbeat" style="font-size: 3rem; color: white;"></i>
                                </div>
                                
                                <!-- Titre -->
                                <h3 style="font-size: 2rem; font-weight: 700; margin-bottom: 1rem; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                    Votre Santé, Notre Engagement
                                </h3>
                                
                                <!-- Description -->
                                <p style="font-size: 1.1rem; opacity: 0.9; line-height: 1.6; margin-bottom: 2rem; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">
                                    Rejoignez des milliers de patients qui nous font confiance pour une gestion optimale de leur santé.
                                </p>
                                
                                <!-- Statistiques en overlay -->
                                <div class="row" style="background: rgba(255, 255, 255, 0.15); border-radius: 15px; padding: 1.5rem; backdrop-filter: blur(10px); margin-top: 2rem;">
                                    <div class="col-4">
                                        <div style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">50K+</div>
                                        <div style="font-size: 0.9rem; opacity: 0.8;">Patients</div>
                                    </div>
                                    <div class="col-4">
                                        <div style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">500+</div>
                                        <div style="font-size: 0.9rem; opacity: 0.8;">Médecins</div>
                                    </div>
                                    <div class="col-4">
                                        <div style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">24/7</div>
                                        <div style="font-size: 0.9rem; opacity: 0.8;">Support</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Éléments décoratifs flottants -->
                            <div class="floating-element" style="position: absolute; top: 30px; right: 30px; width: 80px; height: 80px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; animation: float 4s ease-in-out infinite;"></div>
                            <div class="floating-element" style="position: absolute; bottom: 30px; left: 30px; width: 60px; height: 60px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; animation: float 3s ease-in-out infinite reverse;"></div>
                            <div class="floating-element" style="position: absolute; top: 50%; left: 20px; width: 40px; height: 40px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; animation: float 2.5s ease-in-out infinite;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section" id="services">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 style="font-size: 2.5rem; font-weight: 700; color: var(--central-primary); margin-bottom: 1rem;">
                    Nos Services
                </h2>
                <p style="font-size: 1.2rem; color: #666; line-height: 1.6;">
                    Découvrez tous les services que CENTRAL+ met à votre disposition pour une gestion optimale de votre santé.
                </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card fade-in-up" style="transition: all 0.3s ease; cursor: pointer;" 
                     onmouseover="this.style.transform='translateY(-10px) scale(1.02)'; this.style.boxShadow='0 25px 50px rgba(0, 51, 102, 0.2)'"
                     onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 10px 30px rgba(0, 0, 0, 0.1)'">
                    <div class="feature-icon pulse-animation">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="feature-title">Rendez-vous en ligne</h3>
                    <p class="feature-description">
                        Prenez rendez-vous avec vos médecins en quelques clics. Disponibilité en temps réel et rappels automatiques.
                    </p>
                    <div style="margin-top: 1rem;">
                        <span style="background: var(--central-secondary); color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-clock me-1"></i>Disponible 24/7
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card fade-in-up" style="transition: all 0.3s ease; cursor: pointer;" 
                     onmouseover="this.style.transform='translateY(-10px) scale(1.02)'; this.style.boxShadow='0 25px 50px rgba(0, 51, 102, 0.2)'"
                     onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 10px 30px rgba(0, 0, 0, 0.1)'">
                    <div class="feature-icon pulse-animation">
                        <i class="fas fa-file-medical"></i>
                    </div>
                    <h3 class="feature-title">Dossier médical</h3>
                    <p class="feature-description">
                        Consultez votre historique médical, vos examens, vos prescriptions et tous vos documents de santé.
                    </p>
                    <div style="margin-top: 1rem;">
                        <span style="background: var(--central-success); color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-shield-alt me-1"></i>Sécurisé
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card fade-in-up" style="transition: all 0.3s ease; cursor: pointer;" 
                     onmouseover="this.style.transform='translateY(-10px) scale(1.02)'; this.style.boxShadow='0 25px 50px rgba(0, 51, 102, 0.2)'"
                     onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 10px 30px rgba(0, 0, 0, 0.1)'">
                    <div class="feature-icon pulse-animation">
                        <i class="fas fa-prescription-bottle-alt"></i>
                    </div>
                    <h3 class="feature-title">Prescriptions</h3>
                    <p class="feature-description">
                        Gérez vos prescriptions médicamenteuses, recevez des rappels de prise et suivez votre traitement.
                    </p>
                    <div style="margin-top: 1rem;">
                        <span style="background: var(--central-warning); color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-bell me-1"></i>Rappels
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card fade-in-up" style="transition: all 0.3s ease; cursor: pointer;" 
                     onmouseover="this.style.transform='translateY(-10px) scale(1.02)'; this.style.boxShadow='0 25px 50px rgba(0, 51, 102, 0.2)'"
                     onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 10px 30px rgba(0, 0, 0, 0.1)'">
                    <div class="feature-icon pulse-animation">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="feature-title">Suivi de santé</h3>
                    <p class="feature-description">
                        Visualisez l'évolution de vos paramètres de santé avec des graphiques et des rapports détaillés.
                    </p>
                    <div style="margin-top: 1rem;">
                        <span style="background: var(--central-accent); color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-chart-bar me-1"></i>Analytics
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card fade-in-up" style="transition: all 0.3s ease; cursor: pointer;" 
                     onmouseover="this.style.transform='translateY(-10px) scale(1.02)'; this.style.boxShadow='0 25px 50px rgba(0, 51, 102, 0.2)'"
                     onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 10px 30px rgba(0, 0, 0, 0.1)'">
                    <div class="feature-icon pulse-animation">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="feature-title">Consultation en ligne</h3>
                    <p class="feature-description">
                        Consultez vos médecins à distance via notre plateforme sécurisée de téléconsultation.
                    </p>
                    <div style="margin-top: 1rem;">
                        <span style="background: var(--central-primary); color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-video me-1"></i>Vidéo
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card fade-in-up" style="transition: all 0.3s ease; cursor: pointer;" 
                     onmouseover="this.style.transform='translateY(-10px) scale(1.02)'; this.style.boxShadow='0 25px 50px rgba(0, 51, 102, 0.2)'"
                     onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 10px 30px rgba(0, 0, 0, 0.1)'">
                    <div class="feature-icon pulse-animation">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3 class="feature-title">Alertes et rappels</h3>
                    <p class="feature-description">
                        Recevez des notifications personnalisées pour vos rendez-vous, prises de médicaments et examens.
                    </p>
                    <div style="margin-top: 1rem;">
                        <span style="background: var(--central-danger); color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-mobile-alt me-1"></i>Smart
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="stat-item fade-in-up" style="transition: all 0.3s ease;" 
                     onmouseover="this.style.transform='scale(1.05)'" 
                     onmouseout="this.style.transform='scale(1)'">
                    <div class="stat-number" data-count="50000">0</div>
                    <div class="stat-label">Patients satisfaits</div>
                    <div style="margin-top: 0.5rem;">
                        <i class="fas fa-users" style="color: var(--central-secondary); font-size: 1.2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item fade-in-up" style="transition: all 0.3s ease;" 
                     onmouseover="this.style.transform='scale(1.05)'" 
                     onmouseout="this.style.transform='scale(1)'">
                    <div class="stat-number" data-count="500">0</div>
                    <div class="stat-label">Professionnels de santé</div>
                    <div style="margin-top: 0.5rem;">
                        <i class="fas fa-user-md" style="color: var(--central-secondary); font-size: 1.2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item fade-in-up" style="transition: all 0.3s ease;" 
                     onmouseover="this.style.transform='scale(1.05)'" 
                     onmouseout="this.style.transform='scale(1)'">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Disponibilité</div>
                    <div style="margin-top: 0.5rem;">
                        <i class="fas fa-clock" style="color: var(--central-secondary); font-size: 1.2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item fade-in-up" style="transition: all 0.3s ease;" 
                     onmouseover="this.style.transform='scale(1.05)'" 
                     onmouseout="this.style.transform='scale(1)'">
                    <div class="stat-number" data-count="99.9">0</div>
                    <div class="stat-label">Fiabilité</div>
                    <div style="margin-top: 0.5rem;">
                        <i class="fas fa-shield-alt" style="color: var(--central-secondary); font-size: 1.2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 style="font-size: 2.5rem; font-weight: 700; color: var(--central-primary); margin-bottom: 1rem;">
                    Ce que disent nos patients
                </h2>
                <p style="font-size: 1.2rem; color: #666; line-height: 1.6;">
                    Découvrez les témoignages de patients qui font confiance à CENTRAL+ pour leur santé.
                </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-10px)'" 
                     onmouseout="this.style.transform='translateY(0)'">
                    <div class="text-center mb-3">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--central-secondary), #0088cc); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="fas fa-user text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div style="text-align: center; margin-bottom: 1rem;">
                        <h4 style="color: var(--central-primary); margin-bottom: 0.5rem;">Marie Dubois</h4>
                        <p style="color: #666; font-size: 0.9rem; margin: 0;">Patient depuis 2 ans</p>
                    </div>
                    <p style="color: #666; font-style: italic; line-height: 1.6; margin-bottom: 1rem;">
                        "CENTRAL+ a révolutionné ma gestion de santé. Prendre rendez-vous est devenu un jeu d'enfant et j'ai accès à tous mes documents médicaux en un clic."
                    </p>
                    <div class="text-center">
                        <div style="color: var(--central-warning); font-size: 1.2rem;">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-10px)'" 
                     onmouseout="this.style.transform='translateY(0)'">
                    <div class="text-center mb-3">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--central-success), #20c997); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="fas fa-user text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div style="text-align: center; margin-bottom: 1rem;">
                        <h4 style="color: var(--central-primary); margin-bottom: 0.5rem;">Jean Martin</h4>
                        <p style="color: #666; font-size: 0.9rem; margin: 0;">Patient depuis 1 an</p>
                    </div>
                    <p style="color: #666; font-style: italic; line-height: 1.6; margin-bottom: 1rem;">
                        "L'interface est intuitive et les rappels de rendez-vous sont très pratiques. Je recommande vivement CENTRAL+ à tous mes proches."
                    </p>
                    <div class="text-center">
                        <div style="color: var(--central-warning); font-size: 1.2rem;">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-10px)'" 
                     onmouseout="this.style.transform='translateY(0)'">
                    <div class="text-center mb-3">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--central-accent), #ff8c42); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="fas fa-user text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div style="text-align: center; margin-bottom: 1rem;">
                        <h4 style="color: var(--central-primary); margin-bottom: 0.5rem;">Sophie Laurent</h4>
                        <p style="color: #666; font-size: 0.9rem; margin: 0;">Patient depuis 3 ans</p>
                    </div>
                    <p style="color: #666; font-style: italic; line-height: 1.6; margin-bottom: 1rem;">
                        "La sécurité des données et la facilité d'utilisation font de CENTRAL+ la solution idéale pour gérer ma santé au quotidien."
                    </p>
                    <div class="text-center">
                        <div style="color: var(--central-warning); font-size: 1.2rem;">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="features-section" id="about">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 style="font-size: 2.5rem; font-weight: 700; color: var(--central-primary); margin-bottom: 1.5rem;">
                    Pourquoi choisir CENTRAL+ ?
                </h2>
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div style="width: 50px; height: 50px; background: var(--central-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <div>
                            <h4 style="color: var(--central-primary); margin-bottom: 0.5rem;">Sécurité maximale</h4>
                            <p style="color: #666; margin: 0;">Vos données sont protégées par un cryptage de niveau bancaire.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div style="width: 50px; height: 50px; background: var(--central-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                            <i class="fas fa-mobile-alt text-white"></i>
                        </div>
                        <div>
                            <h4 style="color: var(--central-primary); margin-bottom: 0.5rem;">Accessible partout</h4>
                            <p style="color: #666; margin: 0;">Accédez à vos informations depuis n'importe quel appareil.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div style="width: 50px; height: 50px; background: var(--central-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div>
                            <h4 style="color: var(--central-primary); margin-bottom: 0.5rem;">Équipe dédiée</h4>
                            <p style="color: #666; margin: 0;">Une équipe de professionnels à votre service 24h/24.</p>
                        </div>
                    </div>
                </div>
                
                @guest
                <a href="{{ route('login') }}?type=patient" class="btn-hero">
                    <i class="fas fa-rocket me-2"></i>Mon Espace Personnel
                </a>
                @endguest
            </div>
            
            <div class="col-lg-6">
                <div style="background: linear-gradient(135deg, var(--central-primary), var(--central-secondary)); border-radius: 20px; padding: 3rem; color: white; text-align: center;">
                    <i class="fas fa-heartbeat" style="font-size: 4rem; margin-bottom: 2rem; opacity: 0.8;"></i>
                    <h3 style="margin-bottom: 1.5rem;">Votre santé, notre mission</h3>
                    <p style="font-size: 1.1rem; opacity: 0.9; line-height: 1.6;">
                        CENTRAL+ révolutionne la gestion de votre santé en mettant la technologie au service de votre bien-être. 
                        Rejoignez des milliers de patients qui nous font confiance.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="stats-section" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 style="font-size: 2.5rem; font-weight: 700; color: var(--central-primary); margin-bottom: 1rem;">
                    Besoin d'aide ?
                </h2>
                <p style="font-size: 1.2rem; color: #666; margin-bottom: 3rem;">
                    Notre équipe support est là pour vous accompagner dans l'utilisation de CENTRAL+.
                </p>
                
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <i class="fas fa-phone" style="font-size: 2rem; color: var(--central-secondary); margin-bottom: 1rem;"></i>
                            <h4 style="color: var(--central-primary); margin-bottom: 0.5rem;">Téléphone</h4>
                            <p style="color: #666; margin: 0;">+33 1 23 45 67 89</p>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <i class="fas fa-envelope" style="font-size: 2rem; color: var(--central-secondary); margin-bottom: 1rem;"></i>
                            <h4 style="color: var(--central-primary); margin-bottom: 0.5rem;">Email</h4>
                            <p style="color: #666; margin: 0;">support@central-plus.fr</p>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <i class="fas fa-clock" style="font-size: 2rem; color: var(--central-secondary); margin-bottom: 1rem;"></i>
                            <h4 style="color: var(--central-primary); margin-bottom: 0.5rem;">Horaires</h4>
                            <p style="color: #666; margin: 0;">24h/24, 7j/7</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection