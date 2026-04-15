@extends('layouts.admin')

@section('title', $module . ' - CENTRAL+')
@section('page-title', $module)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-tools fa-4x text-muted"></i>
                    </div>
                    
                    <h3 class="card-title text-muted mb-3">
                        Module {{ $module }} en cours de développement
                    </h3>
                    
                    <p class="card-text text-muted mb-4">
                        Ce module sera bientôt disponible. Notre équipe travaille actuellement sur son développement.
                    </p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Fonctionnalités prévues :</strong>
                        <ul class="mt-2 mb-0 text-start">
                            <li>Gestion complète des {{ strtolower($module) }}</li>
                            <li>Interface intuitive et moderne</li>
                            <li>Rapports et statistiques</li>
                            <li>Intégration avec les autres modules</li>
                        </ul>
                    </div>
                    
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour au Tableau de Bord
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    border-radius: 15px;
}

.card-body {
    padding: 3rem;
}

.fa-tools {
    color: #6c757d;
}

.alert-info {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #495057;
}
</style>
@endsection
