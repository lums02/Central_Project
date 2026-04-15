@extends('layouts.banque')

@section('title', 'CENTRAL+ - Pour les Banques de Sang')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title fade-in-up">
                        <span class="text-primary">CENTRAL+</span><br>
                        <span class="text-secondary">Pour les Banques de Sang</span>
                    </h1>
                    <p class="hero-subtitle fade-in-up">
                        Gérez votre banque de sang avec efficacité, optimisez vos réserves et sauvez des vies avec CENTRAL+.
                    </p>
                    <div class="fade-in-up">
                        @auth
                            <a href="{{ route('login') }}?type=banque_sang" class="btn-hero me-3">
                                <i class="fas fa-tachometer-alt me-2"></i>Mon Espace Banque
                            </a>
                        @else
                            <a href="{{ route('login') }}?type=banque_sang" class="btn-hero me-3" style="background: white; color: var(--central-primary);">
                                <i class="fas fa-sign-in-alt me-2"></i>Mon Espace Banque
                            </a>
                            <a href="#pricing" class="btn-hero" style="background: var(--central-secondary); color: white; border: 2px solid var(--central-secondary);">
                                <i class="fas fa-user-plus me-2"></i>S'inscrire
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <div class="banque-image-container">
                        <img src="https://images.unsplash.com/photo-1559757175-0eb30cd8c063?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                             alt="Banque de sang moderne avec CENTRAL+" 
                             class="banque-hero-image">
                        <div class="image-overlay">
                            <div class="overlay-stats">
                                <div class="overlay-stat">
                                    <span class="stat-number">10K+</span>
                                    <span class="stat-text">Poches de sang</span>
                                </div>
                                <div class="overlay-stat">
                                    <span class="stat-number">150+</span>
                                    <span class="stat-text">Banques</span>
                                </div>
                                <div class="overlay-stat">
                                    <span class="stat-number">24/7</span>
                                    <span class="stat-text">Urgences</span>
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
                <h2 class="section-title">Services CENTRAL+ pour Banques de Sang</h2>
                <p class="section-subtitle">Des outils complets pour optimiser votre gestion des réserves sanguines</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-tint"></i>
                    </div>
                    <h4>Gestion des Réserves</h4>
                    <p>Suivez vos stocks de sang en temps réel, gérez les groupes sanguins et optimisez vos réserves.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <h4>Gestion des Donneurs</h4>
                    <p>Base de données complète des donneurs avec historique médical et disponibilité.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-ambulance"></i>
                    </div>
                    <h4>Urgences Médicales</h4>
                    <p>Système d'alerte automatique pour les urgences et coordination avec les hôpitaux.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4>Analytics & Rapports</h4>
                    <p>Tableaux de bord et rapports détaillés pour analyser vos performances et besoins.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Sécurité & Traçabilité</h4>
                    <p>Respect des normes de sécurité et traçabilité complète des produits sanguins.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h4>Planification des Collectes</h4>
                    <p>Organisez vos collectes de sang et gérez les rendez-vous des donneurs.</p>
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
                                <p>Accédez à votre banque de sang depuis n'importe où, sur tous vos appareils</p>
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
                                <p>Équipe de support spécialisée en banques de sang disponible 24/7</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="features-image">
                    <div class="banque-dashboard-preview">
                        <div class="dashboard-mockup">
                            <div class="mockup-header">
                                <div class="mockup-title">Tableau de Bord Banque de Sang</div>
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
                                        <i class="fas fa-tint"></i>
                                        <span>Réserves</span>
                                    </div>
                                    <div class="sidebar-item">
                                        <i class="fas fa-user-friends"></i>
                                        <span>Donneurs</span>
                                    </div>
                                    <div class="sidebar-item">
                                        <i class="fas fa-ambulance"></i>
                                        <span>Urgences</span>
                                    </div>
                                </div>
                                <div class="mockup-main">
                                    <div class="stats-grid">
                                        <div class="stat-box">
                                            <div class="stat-value">2,847</div>
                                            <div class="stat-label">Poches A+</div>
                                        </div>
                                        <div class="stat-box">
                                            <div class="stat-value">1,523</div>
                                            <div class="stat-label">Poches O-</div>
                                        </div>
                                        <div class="stat-box">
                                            <div class="stat-value">156</div>
                                            <div class="stat-label">Urgences</div>
                                        </div>
                                        <div class="stat-box">
                                            <div class="stat-value">98%</div>
                                            <div class="stat-label">Disponibilité</div>
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
                <p class="section-subtitle">Choisissez l'abonnement qui correspond à votre banque de sang</p>
            </div>
        </div>
        <div class="row g-4 justify-content-center">
            <!-- Plan Découverte -->
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card">
                    <div class="pricing-badge">Essai gratuit</div>
                    <div class="pricing-header">
                        <h3 class="plan-name">Plan Découverte</h3>
                        <div class="plan-price">
                            <span class="currency"></span>
                            <span class="amount">Gratuit</span>
                            <span class="period"></span>
                        </div>
                        <p class="plan-description">Pendant 30 jours</p>
                    </div>
                    <div class="pricing-features">
                        <ul class="feature-list">
                            <li><i class="fas fa-check text-success me-2"></i>Gestion des donneurs</li>
                            <li><i class="fas fa-check text-success me-2"></i>Suivi des poches de sang</li>
                            <li><i class="fas fa-check text-success me-2"></i>Alertes d'expiration</li>
                            <li><i class="fas fa-check text-success me-2"></i>Support par email</li>
                        </ul>
                    </div>
                    <div class="pricing-footer">
                        <a href="#" class="btn btn-outline-primary btn-lg w-100 start-subscription" data-plan="decouverte" data-price-monthly="0" data-price-yearly="0">
                            <i class="fas fa-rocket me-2"></i>Commencer l'essai gratuit
                        </a>
                    </div>
                </div>
            </div>

            <!-- Plan Mensuel -->
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card featured">
                    <div class="pricing-badge">Recommandé</div>
                    <div class="pricing-header">
                        <h3 class="plan-name">Plan Mensuel</h3>
                        <div class="plan-price">
                            <span class="currency">€</span>
                            <span class="amount">29</span>
                            <span class="period">/mois</span>
                        </div>
                        <p class="plan-description">Idéal pour les banques moyennes</p>
                    </div>
                    <div class="pricing-features">
                        <ul class="feature-list">
                            <li><i class="fas fa-check text-success me-2"></i>Toutes les fonctionnalités du plan gratuit</li>
                            <li><i class="fas fa-check text-success me-2"></i>Gestion des urgences</li>
                            <li><i class="fas fa-check text-success me-2"></i>Rapports avancés</li>
                            <li><i class="fas fa-check text-success me-2"></i>Support prioritaire 24/7</li>
                        </ul>
                    </div>
                    <div class="pricing-footer">
                        <a href="#" class="btn btn-primary btn-lg w-100 start-subscription" data-plan="mensuel" data-price-monthly="29" data-price-yearly="279">
                            <i class="fas fa-star me-2"></i>Choisir ce plan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Plan Annuel -->
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3 class="plan-name">Plan Annuel</h3>
                        <div class="plan-price">
                            <span class="currency">€</span>
                            <span class="amount">279</span>
                            <span class="period">/an</span>
                        </div>
                        <div class="yearly-savings">
                            <small class="text-success">Économisez 20%</small>
                        </div>
                        <p class="plan-description">Pour les grandes banques et réseaux</p>
                    </div>
                    <div class="pricing-features">
                        <ul class="feature-list">
                            <li><i class="fas fa-check text-success me-2"></i>Toutes les fonctionnalités du plan mensuel</li>
                            <li><i class="fas fa-check text-success me-2"></i>Intégration API</li>
                            <li><i class="fas fa-check text-success me-2"></i>Formation personnalisée</li>
                            <li><i class="fas fa-check text-success me-2"></i>Support dédié</li>
                        </ul>
                    </div>
                    <div class="pricing-footer">
                        <a href="#" class="btn btn-outline-primary btn-lg w-100 start-subscription" data-plan="annuel" data-price-monthly="29" data-price-yearly="279">
                            <i class="fas fa-crown me-2"></i>Choisir ce plan
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
                        <strong>Essai gratuit de 30 jours</strong> - Aucune carte de crédit requise
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
                <h2 class="section-title">Témoignages Banques de Sang</h2>
                <p class="section-subtitle">Découvrez comment CENTRAL+ transforme la gestion des banques de sang</p>
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
                        <p>"CENTRAL+ a révolutionné la gestion de notre banque de sang. La traçabilité est parfaite et les urgences sont gérées en temps réel !"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="author-info">
                            <h5>Dr. Marie Dubois</h5>
                            <span>Directrice, Banque de Sang du Centre</span>
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
                        <p>"Interface intuitive et fonctionnalités complètes. Je recommande CENTRAL+ à tous mes collègues des banques de sang."</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="author-info">
                            <h5>Dr. Pierre Martin</h5>
                            <span>Responsable, Banque de Sang Saint-Pierre</span>
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
                        <p>"La gestion des donneurs est parfaite. Plus d'erreurs, plus d'efficacité et surtout, plus de vies sauvées !"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="author-info">
                            <h5>Dr. Sophie Laurent</h5>
                            <span>Coordinatrice, Banque de Sang des Lilas</span>
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
                <h2 class="cta-title" style="color: white;">Prêt à sauver des vies ?</h2>
                <p class="cta-subtitle" style="color: rgba(255,255,255,0.9);">Rejoignez des centaines de banques de sang qui font confiance à CENTRAL+</p>
                <div class="cta-buttons">
                    @guest
                    <a href="{{ route('login') }}?type=banque_sang" class="btn-hero">
                        <i class="fas fa-rocket me-2"></i>Mon Espace Banque
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
                                <h5 id="planName">Plan Mensuel</h5>
                                <p id="planPrice">€29/mois</p>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span>Total</span>
                                    <strong id="totalPrice">€29</strong>
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
