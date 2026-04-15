@extends('layouts.admin')

@section('title', 'Mes Rendez-vous')
@section('page-title', 'Mes Rendez-vous')

@section('content')
<div class="container-fluid">
    <!-- Messages de succès -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <strong>Succès !</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Mes Rendez-vous</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRendezVousModal">
                        <i class="fas fa-plus me-2"></i>Nouveau Rendez-vous
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date & Heure</th>
                                    <th>Patient</th>
                                    <th>Type</th>
                                    <th>Motif</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rendezvous as $rdv)
                                <tr>
                                    <td>
                                        <strong>{{ \Carbon\Carbon::parse($rdv->date_rendezvous)->format('d/m/Y') }}</strong><br>
                                        <small class="text-muted">{{ substr($rdv->heure_rendezvous, 0, 5) }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div style="width: 35px; height: 35px; border-radius: 50%; background: #e9ecef; color: #495057; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 10px;">
                                                {{ substr($rdv->patient->nom ?? 'N', 0, 1) }}
                                            </div>
                                            <div>
                                                <div>{{ $rdv->patient->nom ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $rdv->patient->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $rdv->type_consultation)) }}</span>
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($rdv->motif, 50) }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($rdv->statut) {
                                                'confirme' => 'success',
                                                'termine' => 'secondary',
                                                'annule' => 'danger',
                                                default => 'warning'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">{{ ucfirst($rdv->statut) }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if($rdv->statut == 'en_attente')
                                            <form action="{{ route('admin.medecin.rendezvous.update-statut', $rdv->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="statut" value="confirme">
                                                <button type="submit" class="btn btn-sm btn-success" title="Confirmer">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            @endif
                                            
                                            @if($rdv->statut != 'termine' && $rdv->statut != 'annule')
                                            <form action="{{ route('admin.medecin.rendezvous.update-statut', $rdv->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="statut" value="termine">
                                                <button type="submit" class="btn btn-sm btn-info" title="Terminer">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('admin.medecin.rendezvous.update-statut', $rdv->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Annuler ce rendez-vous ?')">
                                                @csrf
                                                <input type="hidden" name="statut" value="annule">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Annuler">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Aucun rendez-vous trouvé</p>
                                        <small class="text-muted">Créez un nouveau rendez-vous ou fixez-en un depuis un dossier médical</small>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Nouveau Rendez-vous -->
<div class="modal fade" id="createRendezVousModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-calendar-plus me-2"></i>Nouveau Rendez-vous</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.medecin.rendezvous.create') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Patient <span class="text-danger">*</span></label>
                        <select name="patient_id" class="form-select" required>
                            <option value="">-- Sélectionner un patient --</option>
                            @foreach($patients ?? [] as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->nom }} - {{ $patient->email }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date_rendezvous" class="form-control" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Heure <span class="text-danger">*</span></label>
                            <input type="time" name="heure_rendezvous" class="form-control" value="09:00" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type de Consultation <span class="text-danger">*</span></label>
                        <select name="type_rendezvous" class="form-select" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="consultation_generale">Consultation Générale</option>
                            <option value="consultation_specialisee">Consultation Spécialisée</option>
                            <option value="controle">Contrôle Post-Traitement</option>
                            <option value="suivi">Suivi de Pathologie</option>
                            <option value="urgence">Urgence</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Motif du Rendez-vous <span class="text-danger">*</span></label>
                        <textarea name="motif" class="form-control" rows="3" required placeholder="Ex: Contrôle post-opératoire, Suivi diabète, Résultats d'examens..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes Supplémentaires</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Informations complémentaires..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Créer le Rendez-vous
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
