@extends('layouts.patient')
@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4"><i class="fas fa-file-signature text-primary"></i> Demandes de Consentement pour Transfert</h2>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @forelse($demandes as $demande)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-{{ $demande->statut_color }} text-white">
                        <h5 class="mb-0">{{ $demande->statut_format }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-hospital"></i> Hôpital demandeur:</strong><br>
                                {{ $demande->hopitalDemandeur->nom }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-hospital-alt"></i> Votre hôpital actuel:</strong><br>
                                {{ $demande->hopitalDetenteur->nom }}</p>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <strong><i class="fas fa-info-circle"></i> Motif de la demande:</strong><br>
                            {{ $demande->motif_demande }}
                        </div>
                        
                        <p><small class="text-muted"><i class="fas fa-calendar"></i> Demande effectuée le {{ $demande->date_demande->format('d/m/Y à H:i') }}</small></p>
                        
                        @if($demande->statut == 'en_attente_patient')
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> <strong>Votre consentement est requis</strong>
                                <p class="mb-0 mt-2">L'hôpital {{ $demande->hopitalDemandeur->nom }} souhaite accéder à votre dossier médical.</p>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <form action="{{ route('patient.consentement-transfert.accepter', $demande->id) }}" method="POST" class="flex-fill">
                                    @csrf
                                    <button class="btn btn-success w-100" onclick="return confirm('Accepter le transfert de votre dossier médical ?')">
                                        <i class="fas fa-check-circle"></i> Accepter le Transfert
                                    </button>
                                </form>
                                <button class="btn btn-danger flex-fill" data-bs-toggle="modal" data-bs-target="#refuseModal{{ $demande->id }}">
                                    <i class="fas fa-times-circle"></i> Refuser
                                </button>
                            </div>
                            
                            <!-- Modal de refus -->
                            <div class="modal fade" id="refuseModal{{ $demande->id }}">
                                <div class="modal-dialog">
                                    <form action="{{ route('patient.consentement-transfert.refuser', $demande->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Refuser le Transfert</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Vous êtes sur le point de refuser le transfert de votre dossier médical.</p>
                                                <div class="mb-3">
                                                    <label class="form-label">Raison du refus (optionnel)</label>
                                                    <textarea name="reponse_patient" class="form-control" rows="3" placeholder="Indiquez pourquoi vous refusez..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-danger">Confirmer le Refus</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @elseif($demande->statut == 'accepte_patient')
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Vous avez accepté cette demande. En attente du transfert par {{ $demande->hopitalDetenteur->nom }}.
                            </div>
                        @elseif($demande->statut == 'transfere')
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> Dossier transféré avec succès le {{ $demande->date_transfert->format('d/m/Y') }}.
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <p class="text-muted">Aucune demande de consentement pour le moment.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

