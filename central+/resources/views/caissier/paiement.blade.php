@extends('layouts.admin')

@section('title', 'Effectuer le Paiement')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.caissier.consultations') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Informations de la consultation (horizontal) -->
    <div class="card border-primary mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-info-circle me-2"></i>Informations de la Consultation
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <!-- Patient -->
                <div class="col-md-3 border-end">
                    <h6 class="text-muted text-uppercase mb-2" style="font-size: 0.75rem;">Patient</h6>
                    <p class="mb-1"><strong>{{ $consultation->patient->nom }} {{ $consultation->patient->prenom }}</strong></p>
                    <p class="mb-1"><small><i class="fas fa-phone me-1"></i>{{ $consultation->patient->telephone }}</small></p>
                    @if($consultation->patient->groupe_sanguin)
                        <span class="badge bg-danger">{{ $consultation->patient->groupe_sanguin }}</span>
                    @endif
                </div>

                <!-- Médecin -->
                <div class="col-md-2 border-end">
                    <h6 class="text-muted text-uppercase mb-2" style="font-size: 0.75rem;">Médecin</h6>
                    <p class="mb-0"><strong>Dr. {{ $consultation->medecin->nom }} {{ $consultation->medecin->prenom }}</strong></p>
                </div>

                <!-- Signes Vitaux -->
                <div class="col-md-3 border-end">
                    <h6 class="text-muted text-uppercase mb-2" style="font-size: 0.75rem;">Signes Vitaux</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @if($consultation->poids)
                            <span class="badge bg-light text-dark">{{ $consultation->poids }} kg</span>
                        @endif
                        @if($consultation->taille)
                            <span class="badge bg-light text-dark">{{ $consultation->taille }} cm</span>
                        @endif
                        @if($consultation->temperature)
                            <span class="badge bg-light text-dark">{{ $consultation->temperature }}°C</span>
                        @endif
                        @if($consultation->tension_arterielle)
                            <span class="badge bg-light text-dark">{{ $consultation->tension_arterielle }}</span>
                        @endif
                        @if($consultation->frequence_cardiaque)
                            <span class="badge bg-light text-dark">{{ $consultation->frequence_cardiaque }} bpm</span>
                        @endif
                    </div>
                </div>

                <!-- Motif -->
                <div class="col-md-4">
                    <h6 class="text-muted text-uppercase mb-2" style="font-size: 0.75rem;">Motif</h6>
                    <p class="mb-0">{{ $consultation->motif_consultation }}</p>
                </div>
            </div>

            @if($consultation->notes_receptionniste)
                <hr class="my-3">
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-muted text-uppercase mb-2" style="font-size: 0.75rem;">Notes du Réceptionniste</h6>
                        <div class="bg-light p-2 rounded">
                            <small>{{ $consultation->notes_receptionniste }}</small>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Formulaire de paiement -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-cash-register me-2"></i>Encaissement
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.caissier.consultations.encaisser', $consultation->id) }}" method="POST">
                        @csrf

                        <!-- Montant -->
                        <div class="alert alert-warning">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><strong>Montant à encaisser :</strong></span>
                                <span class="h3 mb-0 text-dark">{{ number_format($consultation->frais_consultation, 0, ',', ' ') }} FC</span>
                            </div>
                        </div>

                        <!-- Mode de paiement -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mode de Paiement <span class="text-danger">*</span></label>
                            <select name="mode_paiement" class="form-select form-select-lg" required>
                                <option value="">-- Sélectionner le mode de paiement --</option>
                                <option value="especes">Espèces</option>
                                <option value="carte">Carte Bancaire</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="cheque">Chèque</option>
                                <option value="virement">Virement</option>
                            </select>
                        </div>

                        <!-- Montant payé -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Montant Payé <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg">
                                <input type="number" 
                                       name="montant_paye" 
                                       class="form-control" 
                                       value="{{ $consultation->frais_consultation }}" 
                                       step="0.01" 
                                       required>
                                <span class="input-group-text">FC</span>
                            </div>
                            <small class="text-muted">Vous pouvez modifier le montant si nécessaire</small>
                        </div>

                        <!-- Notes caissier -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Notes (optionnel)</label>
                            <textarea name="notes_caissier" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="Informations complémentaires sur le paiement..."></textarea>
                        </div>

                        <!-- Boutons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check-circle me-2"></i>Valider le Paiement et Imprimer la Facture
                            </button>
                            <a href="{{ route('admin.caissier.consultations') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informations complémentaires -->
            <div class="card mt-3 border-info">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Créé par :</strong> {{ $consultation->receptionniste->nom }} {{ $consultation->receptionniste->prenom }}</p>
                            <p class="mb-0"><strong>Date création :</strong> {{ $consultation->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Statut actuel :</strong> {!! $consultation->statut_paiement_badge !!}</p>
                            <p class="mb-0"><strong>Statut consultation :</strong> {!! $consultation->statut_consultation_badge !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

