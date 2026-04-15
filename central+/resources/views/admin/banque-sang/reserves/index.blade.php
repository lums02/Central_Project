@extends('layouts.admin')

@section('title', 'Réserves de Sang')
@section('page-title', 'Réserves de Sang')

@section('content')
<div class="container-fluid">
    <!-- En-tête avec statistiques globales -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1"><i class="fas fa-tint text-danger me-2"></i>Gestion des Réserves de Sang</h4>
                            <p class="text-muted mb-0">Vue d'ensemble des stocks disponibles par groupe sanguin</p>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajustementModal">
                                <i class="fas fa-plus me-2"></i>Ajustement Manuel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques globales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Réserves</h6>
                            <h3 class="mb-0 text-dark">{{ number_format($reserves->sum('quantite_disponible'), 1) }}L</h3>
                        </div>
                        <div>
                            <i class="fas fa-tint fa-2x text-primary" style="opacity: 0.2;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Poches</h6>
                            <h3 class="mb-0 text-dark">{{ $reserves->sum('nombre_poches') }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-box fa-2x text-success" style="opacity: 0.2;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Stocks Faibles</h6>
                            <h3 class="mb-0 text-dark">{{ $reserves->filter(fn($r) => $r->isFaible() && !$r->isCritique())->count() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-exclamation-triangle fa-2x text-warning" style="opacity: 0.2;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Stocks Critiques</h6>
                            <h3 class="mb-0 text-dark">{{ $reserves->filter(fn($r) => $r->isCritique())->count() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-exclamation-circle fa-2x text-danger" style="opacity: 0.2;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes des réserves par groupe sanguin -->
    <div class="row">
        @php
            $groupes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
            $couleurs = [
                'A+' => 'primary',
                'A-' => 'info',
                'B+' => 'success',
                'B-' => 'teal',
                'AB+' => 'warning',
                'AB-' => 'orange',
                'O+' => 'danger',
                'O-' => 'dark'
            ];
        @endphp
        
        @foreach($reserves as $reserve)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card shadow-sm border-0 reserve-card {{ $reserve->isCritique() ? 'border-danger-glow' : ($reserve->isFaible() ? 'border-warning-glow' : 'border-success-glow') }}">
                <div class="card-body text-center py-3">
                    <!-- Groupe sanguin et icône -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0 fw-bold text-dark">{{ $reserve->groupe_sanguin }}</h3>
                        <i class="fas fa-tint fa-2x" style="color: {{ $reserve->isCritique() ? '#dc3545' : ($reserve->isFaible() ? '#fd7e14' : '#6c757d') }}"></i>
                    </div>

                    <!-- Quantité et poches -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-start">
                            <h2 class="mb-0 fw-bold text-dark">{{ number_format($reserve->quantite_disponible, 1) }}L</h2>
                            <small class="text-muted">{{ $reserve->nombre_poches }} poche(s)</small>
                        </div>
                        <div>
                            @if($reserve->isCritique())
                                <span class="badge bg-danger">CRITIQUE</span>
                            @elseif($reserve->isFaible())
                                <span class="badge bg-warning">FAIBLE</span>
                            @else
                                <span class="badge bg-success">OK</span>
                            @endif
                        </div>
                    </div>

                    <!-- Barre de progression -->
                    @php
                        $pourcentage = 0;
                        if ($reserve->quantite_minimum > 0) {
                            $pourcentage = min(100, ($reserve->quantite_disponible / $reserve->quantite_minimum) * 100);
                        }
                        $barColor = $reserve->isCritique() ? 'danger' : ($reserve->isFaible() ? 'warning' : 'success');
                    @endphp
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-{{ $barColor }}" role="progressbar" style="width: {{ $pourcentage }}%"></div>
                    </div>

                    <!-- Seuils -->
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Min: {{ $reserve->quantite_minimum }}L</small>
                        <small class="text-danger">Critique: {{ $reserve->quantite_critique }}L</small>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Alertes si nécessaire -->
    @if($reserves->filter(fn($r) => $r->isCritique())->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-danger shadow-sm border-0 d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">Attention : Stocks Critiques Détectés</h5>
                    <p class="mb-0">
                        {{ $reserves->filter(fn($r) => $r->isCritique())->count() }} groupe(s) sanguin(s) en niveau critique. 
                        Contactez immédiatement les donneurs pour organiser des collectes d'urgence.
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal d'ajustement manuel (placeholder) -->
<div class="modal fade" id="ajustementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajustement Manuel des Réserves</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Fonctionnalité à venir...</p>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles personnalisés pour les réserves */
.reserve-card {
    transition: all 0.3s ease;
}

.reserve-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
}

.border-danger-glow {
    border-left: 4px solid #dc3545 !important;
}

.border-warning-glow {
    border-left: 4px solid #fd7e14 !important;
}

.border-success-glow {
    border-left: 4px solid #6c757d !important;
}

.progress {
    background-color: #e9ecef;
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
}
</style>
@endsection
