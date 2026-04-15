@extends('layouts.admin')

@section('title', 'Dossier Médical - ' . $dossier->numero_dossier)
@section('page-title', 'Dossier Médical')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.hopital.patients.show', $patient->id) }}" class="btn btn-outline-primary mb-3">
                <i class="fas fa-arrow-left"></i> Retour au Dossier Patient
            </a>
            
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="fas fa-file-medical text-primary"></i> {{ $dossier->numero_dossier }}
                </h1>
                <span class="badge bg-{{ $dossier->statut == 'actif' ? 'success' : 'secondary' }} fs-6">
                    {{ ucfirst($dossier->statut) }}
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informations du Dossier -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Détails de la Consultation</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="info-box">
                                <label><i class="fas fa-calendar-alt"></i> Date de Consultation</label>
                                <p>{{ \Carbon\Carbon::parse($dossier->date_consultation)->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <label><i class="fas fa-user-md"></i> Médecin Responsable</label>
                                <p>Dr. {{ $dossier->medecin->nom ?? 'Non assigné' }}</p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="medical-section">
                        <h6 class="section-title"><i class="fas fa-notes-medical"></i> Motif de Consultation</h6>
                        <div class="section-content">
                            {{ $dossier->motif_consultation }}
                        </div>
                    </div>

                    @if($dossier->diagnostic)
                        <div class="medical-section">
                            <h6 class="section-title"><i class="fas fa-stethoscope"></i> Diagnostic</h6>
                            <div class="section-content">
                                {{ $dossier->diagnostic }}
                            </div>
                        </div>
                    @endif

                    @if($dossier->traitement)
                        <div class="medical-section">
                            <h6 class="section-title"><i class="fas fa-pills"></i> Traitement Prescrit</h6>
                            <div class="section-content prescription">
                                {{ $dossier->traitement }}
                            </div>
                        </div>
                    @endif

                    @if($dossier->observations)
                        <div class="medical-section">
                            <h6 class="section-title"><i class="fas fa-comment-medical"></i> Observations</h6>
                            <div class="section-content">
                                {!! nl2br(e($dossier->observations)) !!}
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white">
                    <small class="text-muted">
                        <i class="fas fa-clock"></i> Créé le {{ $dossier->created_at->format('d/m/Y à H:i') }}
                        @if($dossier->updated_at != $dossier->created_at)
                            • Modifié le {{ $dossier->updated_at->format('d/m/Y à H:i') }}
                        @endif
                    </small>
                </div>
            </div>
        </div>

        <!-- Informations du Patient -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Patient</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-medium bg-info text-white mx-auto">
                            {{ strtoupper(substr($patient->nom, 0, 2)) }}
                        </div>
                        <h5 class="mt-2 mb-1">{{ $patient->nom }} {{ $patient->prenom ?? '' }}</h5>
                    </div>

                    <hr>

                    <div class="patient-info">
                        <div class="info-row">
                            <span class="label"><i class="fas fa-envelope"></i> Email</span>
                            <span class="value">{{ $patient->email }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label"><i class="fas fa-phone"></i> Téléphone</span>
                            <span class="value">{{ $patient->telephone ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label"><i class="fas fa-birthday-cake"></i> Âge</span>
                            <span class="value">
                                {{ $patient->date_naissance ? \Carbon\Carbon::parse($patient->date_naissance)->age . ' ans' : 'N/A' }}
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="label"><i class="fas fa-venus-mars"></i> Sexe</span>
                            <span class="value">{{ ucfirst($patient->sexe ?? 'N/A') }}</span>
                        </div>
                    </div>

                    <hr>

                    <a href="{{ route('admin.hopital.patients.show', $patient->id) }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-folder-open"></i> Voir Tous les Dossiers
                    </a>
                </div>
            </div>

            <!-- Actions -->
            <div class="card mt-3">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-cog"></i> Actions</h5>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-info w-100 mb-2" data-bs-toggle="modal" data-bs-target="#printDossier">
                        <i class="fas fa-print"></i> Imprimer le Dossier
                    </button>
                    <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#shareDossier">
                        <i class="fas fa-share"></i> Partager avec un Médecin
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-medium {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
}

.info-box {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #003366;
}

.info-box label {
    display: block;
    font-weight: 600;
    color: #003366;
    margin-bottom: 5px;
    font-size: 0.9rem;
}

.info-box p {
    margin: 0;
    font-size: 1.1rem;
    color: #495057;
}

.medical-section {
    margin-bottom: 25px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #0066cc;
}

.section-title {
    color: #003366;
    font-weight: 700;
    margin-bottom: 15px;
    font-size: 1.1rem;
}

.section-content {
    color: #495057;
    line-height: 1.6;
    white-space: pre-wrap;
}

.section-content.prescription {
    background: white;
    padding: 15px;
    border-radius: 5px;
    border: 1px solid #dee2e6;
}

.patient-info {
    margin: 15px 0;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #dee2e6;
}

.info-row:last-child {
    border-bottom: none;
}

.info-row .label {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.9rem;
}

.info-row .value {
    color: #003366;
    font-weight: 500;
}
</style>
@endsection

