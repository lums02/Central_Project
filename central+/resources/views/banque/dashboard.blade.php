@extends('layouts.banque')

@section('title', 'Tableau de Bord - Banque de Sang')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">
                <i class="fas fa-tint me-2"></i>
                Tableau de Bord Banque de Sang
            </h1>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Bienvenue dans votre espace banque de sang ! Ce tableau de bord sera bientôt enrichi avec toutes les fonctionnalités nécessaires à la gestion de votre banque de sang.
            </div>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-tint text-primary me-2"></i>
                                Réserves de Sang
                            </h5>
                            <p class="card-text">Gérez vos stocks de sang par groupe sanguin.</p>
                            <a href="#" class="btn btn-primary">Accéder</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-user-friends text-primary me-2"></i>
                                Gestion des Donneurs
                            </h5>
                            <p class="card-text">Suivez vos donneurs et leurs dons.</p>
                            <a href="#" class="btn btn-primary">Accéder</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('banque.logout') }}" class="btn btn-outline-primary">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Déconnexion
                </a>
            </div>
        </div>
    </div>
</div>
@endsection