@extends('layouts.hopital')

@section('title', 'Hôpital - CENTRAL+ Système de Gestion Hospitalière')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-background">
        <div class="hero-pattern"></div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="hero-content">
                    <h1 class="hero-title fade-in-up">
                        Bienvenue sur <strong>CENTRAL+</strong>
                    </h1>
                    <p class="hero-subtitle fade-in-up">
                        La solution complète de gestion hospitalière
                    </p>
                    <div class="fade-in-up">
                        <a href="#pricing" class="btn-hero me-3">
                            <i class="fas fa-star me-2"></i>Découvrir nos offres
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Presentation Section -->
@include('partials.presentation')

<!-- Pricing Section -->
@include('partials.pricing')

<!-- Testimonials Section -->
<section class="testimonials-section py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Témoignages Directeurs d'Hôpitaux</h2>
                <p class="section-subtitle">Découvrez comment CENTRAL+ transforme la gestion hospitalière</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <p class="testimonial-text">"CENTRAL+ a révolutionné la gestion de notre hôpital. La coordination entre les services est parfaite et les patients sont mieux pris en charge."</p>
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="author-info">
                        <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="Dr. Marie Dubois" class="author-avatar">
                        <div>
                            <h5 class="author-name">Dr. Marie Dubois</h5>
                            <p class="author-title">Directrice, Hôpital du Centre</p>
                            <p class="author-location">
                                <i class="fas fa-map-marker-alt me-1"></i>Kinshasa, RDC
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="testimonial-card featured">
                    <div class="testimonial-badge">Recommandé</div>
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <p class="testimonial-text">"L'interface est intuitive et les rapports nous aident à prendre de meilleures décisions. Un outil indispensable pour tout hôpital moderne."</p>
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="author-info">
                        <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="Dr. Jean Dupont" class="author-avatar">
                        <div>
                            <h5 class="author-name">Dr. Jean Dupont</h5>
                            <p class="author-title">Directeur, Hôpital Saint-Pierre</p>
                            <p class="author-location">
                                <i class="fas fa-map-marker-alt me-1"></i>Lubumbashi, RDC
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <p class="testimonial-text">"Le support client est exceptionnel et les mises à jour régulières ajoutent toujours de nouvelles fonctionnalités utiles. Je recommande vivement !"</p>
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="author-info">
                        <img src="https://images.unsplash.com/photo-1594824377892-c34ca5b2d934?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="Dr. Claire Martin" class="author-avatar">
                        <div>
                            <h5 class="author-name">Dr. Claire Martin</h5>
                            <p class="author-title">Directrice, Hôpital des Lilas</p>
                            <p class="author-location">
                                <i class="fas fa-map-marker-alt me-1"></i>Goma, RDC
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistiques de satisfaction -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="satisfaction-stats">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-number">98%</div>
                                <div class="stat-label">Satisfaction Client</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-number">500+</div>
                                <div class="stat-label">Hôpitaux Actifs</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-number">24/7</div>
                                <div class="stat-label">Support Technique</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-number">5★</div>
                                <div class="stat-label">Note Moyenne</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Payment Modal -->
@include('partials.payment_modal')
@endsection
