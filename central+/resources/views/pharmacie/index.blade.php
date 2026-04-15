@extends('layouts.pharmacie')

@section('title', 'CENTRAL+ - Pour les Pharmacies')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title fade-in-up">
                        <span class="text-primary">CENTRAL+</span><br>
                        <span class="text-secondary">Pour les Pharmacies</span>
                    </h1>
                    <p class="hero-subtitle fade-in-up">
                        Gérez votre pharmacie avec efficacité, optimisez vos stocks de médicaments et offrez un service de qualité à vos patients avec CENTRAL+.
                    </p>
                    <div class="fade-in-up">
                        @auth
                            <a href="{{ route('login') }}?type=pharmacie" class="btn-hero me-3">
                                <i class="fas fa-tachometer-alt me-2"></i>Mon Espace Pharmacie
                            </a>
                        @else
                            <a href="{{ route('login') }}?type=pharmacie" class="btn-hero me-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Mon Espace Pharmacie
                            </a>
                            <a href="#pricing" class="btn-hero" style="background: transparent; border: 2px solid var(--central-secondary);">
                                <i class="fas fa-user-plus me-2"></i>S'inscrire
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <div class="pharmacy-image-container">
                        <img src="https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                             alt="Pharmacie moderne avec CENTRAL+" 
                             class="pharmacy-hero-image">
                        <div class="image-overlay">
                            <div class="overlay-stats">
                                <div class="overlay-stat">
                                    <span class="stat-number">5000+</span>
                                    <span class="stat-text">Médicaments</span>
                                </div>
                                <div class="overlay-stat">
                                    <span class="stat-number">250+</span>
                                    <span class="stat-text">Pharmacies</span>
                                </div>
                                <div class="overlay-stat">
                                    <span class="stat-number">24/7</span>
                                    <span class="stat-text">Support</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Services CENTRAL+ pour Pharmacies</h2>
                <p class="section-subtitle">Des outils complets pour optimiser votre gestion pharmaceutique</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <h4>Gestion des Stocks</h4>
                    <p>Suivez vos stocks en temps réel, recevez des alertes de réapprovisionnement et optimisez votre inventaire.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-capsules"></i>
                    </div>
                    <h4>Gestion des Médicaments</h4>
                    <p>Base de données complète des médicaments avec informations détaillées et interactions.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-file-prescription"></i>
                    </div>
                    <h4>Prescriptions</h4>
                    <p>Gérez les prescriptions électroniques et les ordonnances de vos patients en toute sécurité.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4>Analytics & Rapports</h4>
                    <p>Tableaux de bord et rapports détaillés pour analyser vos performances et ventes.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h4>Conseil Pharmaceutique</h4>
                    <p>Outils d'aide au conseil pour optimiser l'accompagnement de vos patients.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Sécurité & Conformité</h4>
                    <p>Respect des réglementations pharmaceutiques et protection des données patients.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="features-content">
                    <h2 class="section-title">Pourquoi choisir CENTRAL+ ?</h2>
                    <div class="feature-list">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="feature-content">
                                <h5>Interface Intuitive</h5>
                                <p>Design moderne et ergonomique pour une prise en main rapide</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div class="feature-content">
                                <h5>Accessible Partout</h5>
                                <p>Accédez à votre pharmacie depuis n'importe où, sur tous vos appareils</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-cloud"></i>
                            </div>
                            <div class="feature-content">
                                <h5>Sauvegarde Sécurisée</h5>
                                <p>Vos données sont protégées et sauvegardées automatiquement</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div class="feature-content">
                                <h5>Support Dédié</h5>
                                <p>Équipe de support spécialisée en pharmacie disponible 24/7</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="features-image">
                    <div class="pharmacy-dashboard-preview">
                        <div class="dashboard-mockup">
                            <div class="mockup-header">
                                <div class="mockup-title">Tableau de Bord Pharmacie</div>
                                <div class="mockup-controls">
                                    <span class="control-dot"></span>
                                    <span class="control-dot"></span>
                                    <span class="control-dot"></span>
                                </div>
                            </div>
                            <div class="mockup-content">
                                <div class="mockup-sidebar">
                                    <div class="sidebar-item active">
                                        <i class="fas fa-tachometer-alt"></i>
                                        <span>Dashboard</span>
                                    </div>
                                    <div class="sidebar-item">
                                        <i class="fas fa-boxes"></i>
                                        <span>Stocks</span>
                                    </div>
                                    <div class="sidebar-item">
                                        <i class="fas fa-pills"></i>
                                        <span>Médicaments</span>
                                    </div>
                                    <div class="sidebar-item">
                                        <i class="fas fa-file-prescription"></i>
                                        <span>Prescriptions</span>
                                    </div>
                                </div>
                                <div class="mockup-main">
                                    <div class="stats-grid">
                                        <div class="stat-box">
                                            <div class="stat-value">1,247</div>
                                            <div class="stat-label">Médicaments</div>
                                        </div>
                                        <div class="stat-box">
                                            <div class="stat-value">89%</div>
                                            <div class="stat-label">Stock OK</div>
                                        </div>
                                        <div class="stat-box">
                                            <div class="stat-value">156</div>
                                            <div class="stat-label">Prescriptions</div>
                                        </div>
                                        <div class="stat-box">
                                            <div class="stat-value">$12,450</div>
                                            <div class="stat-label">Chiffre d'affaires</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section id="pricing" class="pricing-section py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Nos Abonnements CENTRAL+</h2>
                <p class="section-subtitle">Choisissez l'abonnement qui correspond à votre pharmacie</p>
            </div>
        </div>
        <div class="row g-4 justify-content-center">
            <!-- Plan Starter -->
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3 class="plan-name">Starter</h3>
                        <div class="plan-price">
                            <span class="currency">$</span>
                            <span class="amount">29</span>
                            <span class="period">/mois</span>
                        </div>
                        <div class="price-options">
                            <div class="price-toggle">
                                <span class="price-option active" data-period="monthly">Mensuel</span>
                                <span class="price-option" data-period="yearly">Annuel</span>
                            </div>
                            <div class="yearly-savings" style="display: none;">
                                <small class="text-success">Économisez 20% avec l'abonnement annuel</small>
                            </div>
                        </div>
                        <p class="plan-description">Parfait pour les petites pharmacies</p>
                    </div>
                    <div class="pricing-features">
                        <ul class="feature-list">
                            <li><i class="fas fa-check text-success me-2"></i>Gestion des stocks (jusqu'à 1,000 produits)</li>
                            <li><i class="fas fa-check text-success me-2"></i>Gestion des prescriptions</li>
                            <li><i class="fas fa-check text-success me-2"></i>Rapports de base</li>
                            <li><i class="fas fa-check text-success me-2"></i>Support email</li>
                            <li><i class="fas fa-check text-success me-2"></i>1 utilisateur</li>
                        </ul>
                    </div>
                    <div class="pricing-footer">
                        <a href="#" class="btn btn-outline-primary btn-lg w-100 start-subscription" data-plan="starter" data-price-monthly="29" data-price-yearly="279">
                            <i class="fas fa-rocket me-2"></i>Commencer
                        </a>
                    </div>
                </div>
            </div>

            <!-- Plan Professional -->
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card featured">
                    <div class="pricing-badge">Recommandé</div>
                    <div class="pricing-header">
                        <h3 class="plan-name">Professional</h3>
                        <div class="plan-price">
                            <span class="currency">$</span>
                            <span class="amount">59</span>
                            <span class="period">/mois</span>
                        </div>
                        <div class="price-options">
                            <div class="price-toggle">
                                <span class="price-option active" data-period="monthly">Mensuel</span>
                                <span class="price-option" data-period="yearly">Annuel</span>
                            </div>
                            <div class="yearly-savings" style="display: none;">
                                <small class="text-success">Économisez 20% avec l'abonnement annuel</small>
                            </div>
                        </div>
                        <p class="plan-description">Idéal pour les pharmacies moyennes</p>
                    </div>
                    <div class="pricing-features">
                        <ul class="feature-list">
                            <li><i class="fas fa-check text-success me-2"></i>Gestion des stocks (jusqu'à 5,000 produits)</li>
                            <li><i class="fas fa-check text-success me-2"></i>Gestion des prescriptions avancée</li>
                            <li><i class="fas fa-check text-success me-2"></i>Analytics et rapports détaillés</li>
                            <li><i class="fas fa-check text-success me-2"></i>Gestion des fournisseurs</li>
                            <li><i class="fas fa-check text-success me-2"></i>Support téléphonique</li>
                            <li><i class="fas fa-check text-success me-2"></i>Jusqu'à 5 utilisateurs</li>
                            <li><i class="fas fa-check text-success me-2"></i>Formation en ligne</li>
                        </ul>
                    </div>
                    <div class="pricing-footer">
                        <a href="#" class="btn btn-primary btn-lg w-100 start-subscription" data-plan="professional" data-price-monthly="59" data-price-yearly="567">
                            <i class="fas fa-star me-2"></i>Choisir Professional
                        </a>
                    </div>
                </div>
            </div>

            <!-- Plan Enterprise -->
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3 class="plan-name">Enterprise</h3>
                        <div class="plan-price">
                            <span class="currency">$</span>
                            <span class="amount">99</span>
                            <span class="period">/mois</span>
                        </div>
                        <div class="price-options">
                            <div class="price-toggle">
                                <span class="price-option active" data-period="monthly">Mensuel</span>
                                <span class="price-option" data-period="yearly">Annuel</span>
                            </div>
                            <div class="yearly-savings" style="display: none;">
                                <small class="text-success">Économisez 20% avec l'abonnement annuel</small>
                            </div>
                        </div>
                        <p class="plan-description">Pour les grandes pharmacies et chaînes</p>
                    </div>
                    <div class="pricing-features">
                        <ul class="feature-list">
                            <li><i class="fas fa-check text-success me-2"></i>Gestion des stocks illimitée</li>
                            <li><i class="fas fa-check text-success me-2"></i>Gestion multi-pharmacies</li>
                            <li><i class="fas fa-check text-success me-2"></i>Analytics avancés et BI</li>
                            <li><i class="fas fa-check text-success me-2"></i>Intégrations API</li>
                            <li><i class="fas fa-check text-success me-2"></i>Support prioritaire 24/7</li>
                            <li><i class="fas fa-check text-success me-2"></i>Utilisateurs illimités</li>
                            <li><i class="fas fa-check text-success me-2"></i>Formation personnalisée</li>
                            <li><i class="fas fa-check text-success me-2"></i>Gestionnaire de compte dédié</li>
                        </ul>
                    </div>
                    <div class="pricing-footer">
                        <a href="#" class="btn btn-outline-primary btn-lg w-100 start-subscription" data-plan="enterprise" data-price-monthly="99" data-price-yearly="951">
                            <i class="fas fa-crown me-2"></i>Choisir Enterprise
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="row mt-5">
            <div class="col-12 text-center">
                <div class="pricing-info">
                    <p class="mb-3">
                        <i class="fas fa-shield-alt text-primary me-2"></i>
                        <strong>Tous les plans incluent :</strong> Sauvegarde sécurisée, mises à jour automatiques, et conformité RGPD
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-calendar-check text-primary me-2"></i>
                        <strong>Essai gratuit de 1 mois</strong> - Aucune carte de crédit requise
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Témoignages Pharmaciens</h2>
                <p class="section-subtitle">Découvrez comment CENTRAL+ transforme la gestion pharmaceutique</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p>"CENTRAL+ a révolutionné la gestion de ma pharmacie. La gestion des stocks est maintenant un jeu d'enfant !"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="author-info">
                            <h5>Dr. Marie Dubois</h5>
                            <span>Pharmacienne, Pharmacie du Centre</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p>"Interface intuitive et fonctionnalités complètes. Je recommande CENTRAL+ à tous mes collègues pharmaciens."</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="author-info">
                            <h5>Dr. Pierre Martin</h5>
                            <span>Pharmacien, Pharmacie Saint-Pierre</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p>"La gestion des prescriptions électroniques est parfaite. Plus d'erreurs, plus d'efficacité !"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="author-info">
                            <h5>Dr. Sophie Laurent</h5>
                            <span>Pharmacienne, Pharmacie des Lilas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5" style="background: var(--central-primary);">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="cta-title" style="color: white;">Prêt à transformer votre pharmacie ?</h2>
                <p class="cta-subtitle" style="color: rgba(255,255,255,0.9);">Rejoignez des centaines de pharmaciens qui font confiance à CENTRAL+</p>
                <div class="cta-buttons">
                    @guest
                    <a href="{{ route('login') }}?type=pharmacie" class="btn-hero">
                        <i class="fas fa-rocket me-2"></i>Mon Espace Pharmacie
                    </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">
                    <i class="fas fa-credit-card me-2"></i>Paiement de l'abonnement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Méthodes de paiement</h6>
                        <div class="payment-method" onclick="selectPayment('card')">
                            <i class="fas fa-credit-card payment-icon"></i>
                            <span>Carte bancaire</span>
                        </div>
                        <div class="payment-method" onclick="selectPayment('orange')">
                            <i class="fas fa-wallet payment-icon"></i>
                            <span>Orange Money</span>
                        </div>
                        <div class="payment-method" onclick="selectPayment('airtel')">
                            <i class="fas fa-mobile-alt payment-icon"></i>
                            <span>Airtel Money</span>
                        </div>
                        <div class="payment-method" onclick="selectPayment('mpesa')">
                            <i class="fas fa-mobile-alt payment-icon"></i>
                            <span>M-Pesa</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Récapitulatif</h6>
                        <div class="card">
                            <div class="card-body">
                                <h5 id="planName">Plan Professional</h5>
                                <p id="planPrice">€59/mois</p>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span>Total</span>
                                    <strong id="totalPrice">€59</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaires de paiement -->
                <div class="mt-4">
                    <!-- Carte bancaire -->
                    <div id="cardForm" class="payment-form">
                        <div class="mb-3">
                            <label class="form-label">Numéro de carte</label>
                            <input type="text" class="form-control" placeholder="1234 5678 9012 3456">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date d'expiration</label>
                                    <input type="text" class="form-control" placeholder="MM/AA">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">CVV</label>
                                    <input type="text" class="form-control" placeholder="123">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary w-100" onclick="processCardPayment()">
                            <i class="fas fa-lock me-2"></i>Payer avec ma carte
                        </button>
                    </div>

                    <!-- Orange Money -->
                    <div id="orangeForm" class="payment-form" style="display: none;">
                        <div class="orange-money-form">
                            <div class="orange-money-header">
                                <div class="orange-money-logo">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <h5>Paiement Orange Money</h5>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Numéro Orange Money</label>
                                <input type="tel" class="form-control" id="orangeNumber" placeholder="+225 07 12 34 56 78">
                                <small class="text-muted">Entrez votre numéro Orange Money enregistré</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Montant</label>
                                <input type="text" class="form-control" id="orangeAmount" readonly>
                                <small class="text-muted">Montant à débiter de votre compte Orange Money</small>
                            </div>
                            <button class="btn btn-warning w-100" onclick="requestOrangePayment()">
                                Continuer
                            </button>
                        </div>
                    </div>

                    <!-- Airtel Money -->
                    <div id="airtelForm" class="payment-form" style="display: none;">
                        <div class="airtel-money-form">
                            <div class="airtel-money-header">
                                <div class="airtel-money-logo">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <h5>Paiement Airtel Money</h5>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Numéro Airtel Money</label>
                                <input type="tel" class="form-control" id="airtelNumber" placeholder="+225 05 12 34 56 78">
                                <small class="text-muted">Entrez votre numéro Airtel Money enregistré</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Montant</label>
                                <input type="text" class="form-control" id="airtelAmount" readonly>
                                <small class="text-muted">Montant à débiter de votre compte Airtel Money</small>
                            </div>
                            <button class="btn btn-danger w-100" onclick="requestAirtelPayment()">
                                Continuer
                            </button>
                        </div>
                    </div>

                    <!-- M-Pesa -->
                    <div id="mpesaForm" class="payment-form" style="display: none;">
                        <div class="mpesa-form">
                            <div class="mpesa-header">
                                <div class="mpesa-logo">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <h5>Paiement M-Pesa</h5>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Numéro M-Pesa</label>
                                <input type="tel" class="form-control" id="mpesaNumber" placeholder="+254 7XX XXX XXX">
                                <small class="text-muted">Entrez votre numéro M-Pesa enregistré</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Montant</label>
                                <input type="text" class="form-control" id="mpesaAmount" readonly>
                                <small class="text-muted">Montant à débiter de votre compte M-Pesa</small>
                            </div>
                            <button class="btn btn-success w-100" onclick="requestMpesaPayment()">
                                Continuer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Orange Money Password Modal -->
<div class="modal fade" id="orangePasswordModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <i class="fas fa-lock me-2"></i>
                    Code secret
                </h5>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="orange-money-logo mb-3">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h6>Entrez votre code secret</h6>
                    <p class="text-muted small">Pour valider la transaction</p>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control form-control-lg text-center" 
                           id="orangePassword" maxlength="4" placeholder="****"
                           style="letter-spacing: 8px; font-size: 24px;">
                    <small class="text-muted d-block text-center mt-2">Code secret à 4 chiffres</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-warning" onclick="confirmOrangePayment()">
                    Valider
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Airtel Money Password Modal -->
<div class="modal fade" id="airtelPasswordModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-lock me-2"></i>
                    Code secret
                </h5>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="airtel-money-logo mb-3">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h6>Entrez votre code secret</h6>
                    <p class="text-muted small">Pour valider la transaction</p>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control form-control-lg text-center" 
                           id="airtelPassword" maxlength="4" placeholder="****"
                           style="letter-spacing: 8px; font-size: 24px;">
                    <small class="text-muted d-block text-center mt-2">Code secret à 4 chiffres</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" onclick="confirmAirtelPayment()">
                    Valider
                </button>
            </div>
        </div>
    </div>
</div>

<!-- M-Pesa Password Modal -->
<div class="modal fade" id="mpesaPasswordModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-lock me-2"></i>
                    Code secret
                </h5>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="mpesa-logo mb-3">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h6>Entrez votre code secret</h6>
                    <p class="text-muted small">Pour valider la transaction</p>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control form-control-lg text-center" 
                           id="mpesaPassword" maxlength="4" placeholder="****"
                           style="letter-spacing: 8px; font-size: 24px;">
                    <small class="text-muted d-block text-center mt-2">Code secret à 4 chiffres</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" onclick="confirmMpesaPayment()">
                    Valider
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
