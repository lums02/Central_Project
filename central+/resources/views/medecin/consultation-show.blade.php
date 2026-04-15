@extends('layouts.admin')

@section('title', 'Consultation Patient')

@section('content')
<div class="container-fluid">
    <!-- Bouton retour -->
    <div class="text-end mb-3">
        <a href="{{ route('admin.medecin.patients') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour aux Patients
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Informations Patient et Consultation (Horizontal) -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-gradient-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-user-injured me-2"></i>Fiche de Consultation</h5>
                <span class="badge bg-white text-primary">{{ $consultation->created_at->format('d/m/Y à H:i') }}</span>
            </div>
        </div>
        <div class="card-body p-4">
            <!-- Ligne 1: Patient + Médecin -->
            <div class="row mb-3 pb-3 border-bottom">
                <div class="col-md-4">
                    <h6 class="text-uppercase text-muted mb-2" style="font-size: 0.75rem; font-weight: 600;">Patient</h6>
                    <h5 class="mb-1 text-primary">{{ $consultation->patient->nom }} {{ $consultation->patient->prenom }}</h5>
                    <p class="mb-0">
                        <i class="fas fa-phone text-muted me-2"></i>{{ $consultation->patient->telephone }}
                        @if($consultation->patient->groupe_sanguin)
                            <span class="badge bg-danger ms-2">{{ $consultation->patient->groupe_sanguin }}</span>
                        @endif
                    </p>
                    @if($consultation->patient->date_naissance)
                        <small class="text-muted">{{ \Carbon\Carbon::parse($consultation->patient->date_naissance)->age }} ans</small>
                        <small class="text-muted">• {{ ucfirst($consultation->patient->sexe ?? '') }}</small>
                    @endif
                </div>
                <div class="col-md-4 border-start">
                    <h6 class="text-uppercase text-muted mb-2" style="font-size: 0.75rem; font-weight: 600;">Médecin Traitant</h6>
                    <h5 class="mb-1">Dr. {{ $consultation->medecin->nom }} {{ $consultation->medecin->prenom }}</h5>
                    <p class="mb-0"><small class="text-muted">Médecin assigné</small></p>
                </div>
                <div class="col-md-4 border-start">
                    <h6 class="text-uppercase text-muted mb-2" style="font-size: 0.75rem; font-weight: 600;">Paiement</h6>
                    <h4 class="mb-1 text-success">{{ number_format($consultation->montant_paye, 0, ',', ' ') }} FC</h4>
                    <p class="mb-0">
                        <span class="badge bg-success">✓ Payé</span>
                        <small class="text-muted ms-2">{{ ucfirst(str_replace('_', ' ', $consultation->mode_paiement)) }}</small>
                    </p>
                </div>
            </div>

            <!-- Ligne 2: Signes Vitaux -->
            @if($consultation->poids || $consultation->taille || $consultation->temperature || $consultation->tension_arterielle || $consultation->frequence_cardiaque)
            <div class="row mb-3 pb-3 border-bottom">
                <div class="col-12">
                    <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; font-weight: 600;">
                        <i class="fas fa-heartbeat me-2"></i>Signes Vitaux
                    </h6>
                    <div class="d-flex flex-wrap gap-3">
                        @if($consultation->poids)
                            <div class="bg-light rounded px-3 py-2">
                                <small class="text-muted d-block">Poids</small>
                                <strong>{{ $consultation->poids }} kg</strong>
                            </div>
                        @endif
                        @if($consultation->taille)
                            <div class="bg-light rounded px-3 py-2">
                                <small class="text-muted d-block">Taille</small>
                                <strong>{{ $consultation->taille }} cm</strong>
                            </div>
                        @endif
                        @if($consultation->temperature)
                            <div class="bg-light rounded px-3 py-2">
                                <small class="text-muted d-block">Température</small>
                                <strong>{{ $consultation->temperature }} °C</strong>
                            </div>
                        @endif
                        @if($consultation->tension_arterielle)
                            <div class="bg-light rounded px-3 py-2">
                                <small class="text-muted d-block">Tension</small>
                                <strong>{{ $consultation->tension_arterielle }}</strong>
                            </div>
                        @endif
                        @if($consultation->frequence_cardiaque)
                            <div class="bg-light rounded px-3 py-2">
                                <small class="text-muted d-block">Pouls</small>
                                <strong>{{ $consultation->frequence_cardiaque }} bpm</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Ligne 3: Motif de Consultation -->
            <div class="row">
                <div class="col-12">
                    <h6 class="text-uppercase text-muted mb-2" style="font-size: 0.75rem; font-weight: 600;">
                        <i class="fas fa-comment-medical me-2"></i>Motif de Consultation
                    </h6>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-quote-left me-2"></i>{{ $consultation->motif_consultation }}
                    </div>
                </div>
            </div>

            @if($consultation->notes_receptionniste)
            <div class="row mt-3">
                <div class="col-12">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted fw-bold">Notes du réceptionniste :</small>
                        <p class="mb-0 mt-1">{{ $consultation->notes_receptionniste }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    @if(!$consultation->dossierMedical)
    <!-- Formulaire de Consultation Médicale -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-gradient-success text-white py-3">
            <h5 class="mb-0"><i class="fas fa-file-medical me-2"></i>Compléter la Consultation Médicale</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.medecin.consultations.creer-dossier', $consultation->id) }}" method="POST">
                @csrf

                <!-- Ligne 1: Antécédents -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-primary">
                            <i class="fas fa-history me-2"></i>Antécédents Médicaux
                        </label>
                        <textarea name="antecedents_medicaux" class="form-control" rows="3" placeholder="Maladies antérieures, opérations, allergies connues..."></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-primary">
                            <i class="fas fa-users me-2"></i>Antécédents Familiaux
                        </label>
                        <textarea name="antecedents_familiaux" class="form-control" rows="3" placeholder="Maladies héréditaires dans la famille..."></textarea>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-primary">
                            <i class="fas fa-allergies me-2"></i>Allergies
                        </label>
                        <textarea name="allergies" class="form-control" rows="2" placeholder="Allergies médicamenteuses, alimentaires..."></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-primary">
                            <i class="fas fa-pills me-2"></i>Traitement en Cours
                        </label>
                        <textarea name="traitement_actuel" class="form-control" rows="2" placeholder="Médicaments actuellement pris par le patient..."></textarea>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Ligne 2: Anamnèse + Examen Clinique -->
                <div class="row mb-4">
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold text-danger">
                            <i class="fas fa-comments me-2"></i>Anamnèse (Histoire de la Maladie) <span class="text-danger">*</span>
                        </label>
                        <textarea name="anamnese" class="form-control" rows="4" required placeholder="Questions détaillées : Depuis quand ? Comment ça a commencé ? Évolution des symptômes ? Facteurs déclenchants ?"></textarea>
                        <small class="text-muted">Racontez l'histoire complète de la maladie actuelle selon le patient</small>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold text-danger">
                            <i class="fas fa-stethoscope me-2"></i>Examen Clinique <span class="text-danger">*</span>
                        </label>
                        <textarea name="examen_clinique" class="form-control" rows="4" required placeholder="Examen général et examen physique : Aspect général, examen par systèmes, palpation, auscultation..."></textarea>
                        <small class="text-muted">Notez tous les éléments observés lors de l'examen physique</small>
                    </div>
                </div>

                <!-- Ligne 3: Diagnostic -->
                <div class="row mb-4">
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold text-danger">
                            <i class="fas fa-diagnoses me-2"></i>Diagnostic Préliminaire <span class="text-danger">*</span>
                        </label>
                        <textarea name="diagnostic" class="form-control" rows="3" required placeholder="Diagnostic basé sur l'anamnèse et l'examen clinique..."></textarea>
                        <small class="text-muted">Diagnostic initial avant examens complémentaires</small>
                    </div>
                </div>

                <!-- Notes -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-primary">
                            <i class="fas fa-sticky-note me-2"></i>Notes Complémentaires
                        </label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Observations supplémentaires..."></textarea>
                    </div>
                </div>

                <!-- Bouton de soumission -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-5">
                        <i class="fas fa-save me-2"></i>Enregistrer le Dossier Médical
                    </button>
                </div>
            </form>
        </div>
    </div>
    @else
    <!-- Dossier déjà créé -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-gradient-success text-white py-3">
            <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Dossier Médical Créé</h5>
        </div>
        <div class="card-body text-center p-5">
            <i class="fas fa-folder-open text-success mb-3" style="font-size: 4rem;"></i>
            <h4 class="mb-3">Le dossier médical a été créé avec succès !</h4>
            <p class="text-muted mb-4">Vous pouvez maintenant consulter le dossier complet ou prescrire des examens si nécessaire.</p>
            <a href="{{ route('admin.medecin.dossier.show', $consultation->dossierMedical->id) }}" class="btn btn-primary btn-lg">
                <i class="fas fa-folder-open me-2"></i>Voir le Dossier Complet
            </a>
        </div>
    </div>
    @endif
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.border-start {
    border-left: 2px solid #dee2e6 !important;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    border: none;
}
</style>
@endsection