@extends('layouts.admin')

@section('title', 'Dashboard Laborantin')

@section('content')
<!--div class="container-fluid">
    <!-- Actions rapides -->
    <!--div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-bolt me-2"></i>Actions Rapides
        </div>
        <div class="card-body">
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.laborantin.examens') }}" class="btn btn-outline-primary">
                    <i class="fas fa-flask me-2"></i>Examens à Réaliser
                </a>
                <a href="{{ route('admin.laborantin.historique') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-history me-2"></i>Historique des Examens
                </a>
            </div>
        </div>
    </div!-->

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <!-- Examens en attente -->
        <div class="col-md-3">
            <div class="card border-left-warning h-100">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-warning fw-bold text-uppercase" style="font-size: 0.65rem;">En Attente</div>
                            <div class="h5 mb-0">{{ $stats['examens_en_attente'] }}</div>
                            <small class="text-muted" style="font-size: 0.75rem;">Examens payés</small>
                        </div>
                        <div class="text-warning" style="font-size: 1.5rem; opacity: 0.3;">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Examens en cours -->
        <div class="col-md-3">
            <div class="card border-left-info h-100">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-info fw-bold text-uppercase" style="font-size: 0.65rem;">En Cours</div>
                            <div class="h5 mb-0">{{ $stats['examens_en_cours'] }}</div>
                            <small class="text-muted" style="font-size: 0.75rem;">Mes examens</small>
                        </div>
                        <div class="text-info" style="font-size: 1.5rem; opacity: 0.3;">
                            <i class="fas fa-spinner"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Terminés aujourd'hui -->
        <div class="col-md-3">
            <div class="card border-left-success h-100">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-success fw-bold text-uppercase" style="font-size: 0.65rem;">Aujourd'hui</div>
                            <div class="h5 mb-0">{{ $stats['examens_termines_aujourd_hui'] }}</div>
                            <small class="text-muted" style="font-size: 0.75rem;">Terminés</small>
                        </div>
                        <div class="text-success" style="font-size: 1.5rem; opacity: 0.3;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total examens -->
        <div class="col-md-3">
            <div class="card border-left-primary h-100">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-primary fw-bold text-uppercase" style="font-size: 0.65rem;">Total</div>
                            <div class="h5 mb-0">{{ $stats['total_examens'] }}</div>
                            <small class="text-muted" style="font-size: 0.75rem;">Examens réalisés</small>
                        </div>
                        <div class="text-primary" style="font-size: 1.5rem; opacity: 0.3;">
                            <i class="fas fa-vial"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations utiles -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-info-circle me-2"></i>Bienvenue sur votre Espace Laborantin
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fas fa-tasks text-primary me-2"></i>Vos Tâches</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Consulter les examens payés en attente de réalisation
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Prendre en charge les examens et les marquer comme "En cours"
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Saisir les résultats et télécharger les fichiers de résultats
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Notifier automatiquement le médecin prescripteur
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fas fa-lightbulb text-warning me-2"></i>Conseils</h5>
                            <div class="alert alert-light border">
                                <p class="mb-2"><strong>Workflow :</strong></p>
                                <ol class="mb-0">
                                    <li>Le caissier valide le paiement de l'examen</li>
                                    <li>Vous recevez une notification</li>
                                    <li>Vous prenez en charge l'examen (statut : "En cours")</li>
                                    <li>Vous réalisez l'examen et saisissez les résultats</li>
                                    <li>Le médecin est notifié automatiquement</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-warning {
    border-left: 4px solid #ffc107 !important;
}
.border-left-success {
    border-left: 4px solid #28a745 !important;
}
.border-left-primary {
    border-left: 4px solid #007bff !important;
}
.border-left-info {
    border-left: 4px solid #17a2b8 !important;
}
</style>
@endsection

