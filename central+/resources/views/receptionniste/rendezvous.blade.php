@extends('layouts.admin')

@section('title', 'Gestion des Rendez-vous')
@section('page-title', 'Gestion des Rendez-vous')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar-alt text-primary me-2"></i>Gestion des Rendez-vous
        </h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nouveauRdvModal">
            <i class="fas fa-calendar-plus me-2"></i>Nouveau Rendez-vous
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Liste des rendez-vous -->
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Tous les Rendez-vous ({{ $rendezvous->total() }})
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date & Heure</th>
                            <th>Patient</th>
                            <th>Médecin</th>
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
                            <td>{{ $rdv->patient->nom }} {{ $rdv->patient->prenom ?? '' }}</td>
                            <td>Dr. {{ $rdv->medecin->nom }}</td>
                            <td>{{ Str::limit($rdv->motif, 40) }}</td>
                            <td>
                                <span class="badge bg-{{ $rdv->statut == 'confirme' ? 'success' : ($rdv->statut == 'annule' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($rdv->statut) }}
                                </span>
                            </td>
                            <td>
                                @if($rdv->statut == 'en_attente')
                                <form action="{{ route('admin.receptionniste.rendezvous.confirmer', $rdv->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Confirmer">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                                @if($rdv->statut != 'annule')
                                <form action="{{ route('admin.receptionniste.rendezvous.annuler', $rdv->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" title="Annuler">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-calendar-times fa-3x mb-3 d-block" style="opacity: 0.2;"></i>
                                Aucun rendez-vous enregistré
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($rendezvous->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $rendezvous->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Nouveau Rendez-vous -->
<div class="modal fade" id="nouveauRdvModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-plus me-2"></i>Nouveau Rendez-vous
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.receptionniste.rendezvous.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Patient *</label>
                            <select name="patient_id" class="form-control" required>
                                <option value="">-- Sélectionnez un patient --</option>
                                @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">
                                    {{ $patient->nom }} {{ $patient->prenom ?? '' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Médecin *</label>
                            <select name="medecin_id" class="form-control" required>
                                <option value="">-- Sélectionnez un médecin --</option>
                                @foreach($medecins as $medecin)
                                <option value="{{ $medecin->id }}">Dr. {{ $medecin->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date *</label>
                            <input type="date" name="date_rendezvous" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Heure *</label>
                            <input type="time" name="heure_rendezvous" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motif de Consultation *</label>
                        <textarea name="motif" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Créer le Rendez-vous
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

