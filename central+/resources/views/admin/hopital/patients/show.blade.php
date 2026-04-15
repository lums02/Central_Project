@extends('layouts.admin')

@section('title', 'Dossier Patient - ' . $patient->nom)
@section('page-title', 'Dossier Patient')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête avec retour -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.hopital.patients.index') }}" class="btn btn-outline-primary mb-3">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
            
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="fas fa-user-injured text-primary"></i> Dossier Patient
                </h1>
            </div>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Informations du patient -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Informations Personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-large bg-primary text-white mx-auto">
                            {{ strtoupper(substr($patient->nom, 0, 2)) }}
                        </div>
                        <h4 class="mt-3 mb-1">{{ $patient->nom }} {{ $patient->prenom ?? '' }}</h4>
                        <span class="badge bg-{{ $patient->status == 'actif' ? 'success' : 'danger' }}">
                            {{ ucfirst($patient->status) }}
                        </span>
                    </div>

                    <hr>

                    <div class="info-item">
                        <i class="fas fa-envelope text-primary"></i>
                        <div>
                            <small class="text-muted">Email</small>
                            <p class="mb-0">{{ $patient->email }}</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-phone text-primary"></i>
                        <div>
                            <small class="text-muted">Téléphone</small>
                            <p class="mb-0">{{ $patient->telephone ?? 'Non renseigné' }}</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-birthday-cake text-primary"></i>
                        <div>
                            <small class="text-muted">Date de naissance</small>
                            <p class="mb-0">
                                {{ $patient->date_naissance ? \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') : 'Non renseigné' }}
                                @if($patient->date_naissance)
                                    <span class="text-muted">({{ \Carbon\Carbon::parse($patient->date_naissance)->age }} ans)</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-venus-mars text-primary"></i>
                        <div>
                            <small class="text-muted">Sexe</small>
                            <p class="mb-0">{{ ucfirst($patient->sexe ?? 'Non renseigné') }}</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-calendar-plus text-primary"></i>
                        <div>
                            <small class="text-muted">Inscrit le</small>
                            <p class="mb-0">{{ $patient->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <hr>

                    <button type="button" class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#createDossierModal">
                        <i class="fas fa-plus"></i> Créer un Dossier Médical
                    </button>
                </div>
            </div>
        </div>

        <!-- Dossiers médicaux -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-folder-open"></i> Dossiers Médicaux
                        <span class="badge bg-primary">{{ $patient->dossiersMedicaux->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($patient->dossiersMedicaux->count() > 0)
                        @foreach($patient->dossiersMedicaux as $dossier)
                            <div class="dossier-card mb-3">
                                <div class="dossier-header">
                                    <div>
                                        <h6 class="mb-1">
                                            <i class="fas fa-file-medical text-primary"></i>
                                            {{ $dossier->numero_dossier }}
                                        </h6>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i>
                                            {{ \Carbon\Carbon::parse($dossier->date_consultation)->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-{{ $dossier->statut == 'actif' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($dossier->statut) }}
                                    </span>
                                </div>

                                <div class="dossier-content">
                                    <div class="mb-2">
                                        <strong>Médecin :</strong> 
                                        <span class="badge bg-info">
                                            Dr. {{ $dossier->medecin->nom ?? 'Non assigné' }}
                                        </span>
                                    </div>

                                    <div class="mb-2">
                                        <strong>Motif :</strong> 
                                        <p class="mb-0">{{ $dossier->motif_consultation }}</p>
                                    </div>

                                    @if($dossier->diagnostic)
                                        <div class="mb-2">
                                            <strong>Diagnostic :</strong>
                                            <p class="mb-0">{{ $dossier->diagnostic }}</p>
                                        </div>
                                    @endif

                                    @if($dossier->traitement)
                                        <div class="mb-2">
                                            <strong>Traitement :</strong>
                                            <p class="mb-0">{{ $dossier->traitement }}</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="dossier-footer">
                                    <a href="{{ route('admin.hopital.patients.dossier', [$patient->id, $dossier->id]) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Voir Détails
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#assignDossierModal{{ $dossier->id }}">
                                        <i class="fas fa-user-md"></i> Assigner à un Médecin
                                    </button>
                                </div>
                            </div>

                            <!-- Modal d'assignation -->
                            <div class="modal fade" id="assignDossierModal{{ $dossier->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.hopital.patients.assign-dossier', [$patient->id, $dossier->id]) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Assigner le Dossier à un Médecin</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="medecin_id{{ $dossier->id }}" class="form-label">Sélectionner un Médecin</label>
                                                    <select name="medecin_id" id="medecin_id{{ $dossier->id }}" class="form-select" required>
                                                        <option value="">-- Choisir un médecin --</option>
                                                        @foreach($medecins as $medecin)
                                                            <option value="{{ $medecin->id }}" {{ $dossier->medecin_id == $medecin->id ? 'selected' : '' }}>
                                                                Dr. {{ $medecin->nom }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="notes{{ $dossier->id }}" class="form-label">Notes (optionnel)</label>
                                                    <textarea name="notes" id="notes{{ $dossier->id }}" class="form-control" rows="3" placeholder="Instructions ou notes pour le médecin..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-paper-plane"></i> Envoyer
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun dossier médical pour ce patient.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDossierModal">
                                <i class="fas fa-plus"></i> Créer le Premier Dossier
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de création de dossier -->
<div class="modal fade" id="createDossierModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.hopital.patients.create-dossier', $patient->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Créer un Dossier Médical</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="medecin_id" class="form-label">Médecin Responsable *</label>
                        <select name="medecin_id" id="medecin_id" class="form-select" required>
                            <option value="">-- Choisir un médecin --</option>
                            @foreach($medecins as $medecin)
                                <option value="{{ $medecin->id }}">Dr. {{ $medecin->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="motif_consultation" class="form-label">Motif de Consultation *</label>
                        <textarea name="motif_consultation" id="motif_consultation" class="form-control" rows="3" required placeholder="Ex: Douleurs abdominales, fièvre..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="diagnostic" class="form-label">Diagnostic</label>
                        <textarea name="diagnostic" id="diagnostic" class="form-control" rows="3" placeholder="Diagnostic initial ou préliminaire..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="traitement" class="form-label">Traitement Prescrit</label>
                        <textarea name="traitement" id="traitement" class="form-control" rows="3" placeholder="Médicaments, posologie, durée..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="observations" class="form-label">Observations</label>
                        <textarea name="observations" id="observations" class="form-control" rows="2" placeholder="Notes complémentaires..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Créer le Dossier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.avatar-large {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 700;
}

.info-item {
    display: flex;
    align-items: start;
    gap: 12px;
    margin-bottom: 15px;
}

.info-item i {
    font-size: 1.2rem;
    margin-top: 3px;
}

.dossier-card {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.dossier-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.dossier-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #dee2e6;
}

.dossier-content {
    margin-bottom: 15px;
}

.dossier-content strong {
    color: #003366;
}

.dossier-footer {
    display: flex;
    gap: 10px;
    padding-top: 10px;
    border-top: 1px solid #dee2e6;
}
</style>
@endsection

