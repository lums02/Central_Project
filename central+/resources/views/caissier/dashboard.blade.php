@extends('layouts.admin')

@section('title', 'Dashboard Caissier')

@section('content')
<div class="container-fluid">
    <!-- Actions rapides -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-bolt me-2"></i>Actions Rapides
        </div>
        <div class="card-body">
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.caissier.consultations') }}" class="btn btn-outline-primary">
                    <i class="fas fa-list me-2"></i>Toutes les consultations
                </a>
                <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#recherchePatientModal">
                    <i class="fas fa-search me-2"></i>Rechercher un patient
                </button>
                <a href="{{ route('admin.caissier.historique') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-history me-2"></i>Historique des paiements
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <!-- Consultations en attente -->
        <div class="col-md-3">
            <div class="card border-left-warning h-100">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-warning fw-bold text-uppercase" style="font-size: 0.65rem;">En Attente</div>
                            <div class="h5 mb-0">{{ $stats['consultations_en_attente'] }}</div>
                            <small class="text-muted" style="font-size: 0.75rem;">Consultations</small>
                        </div>
                        <div class="text-warning" style="font-size: 1.5rem; opacity: 0.3;">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payées aujourd'hui -->
        <div class="col-md-3">
            <div class="card border-left-success h-100">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-success fw-bold text-uppercase" style="font-size: 0.65rem;">Aujourd'hui</div>
                            <div class="h5 mb-0">{{ $stats['consultations_payees_aujourd_hui'] }}</div>
                            <small class="text-muted" style="font-size: 0.75rem;">Payées</small>
                        </div>
                        <div class="text-success" style="font-size: 1.5rem; opacity: 0.3;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Montant encaissé -->
        <div class="col-md-3">
            <div class="card border-left-primary h-100">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-primary fw-bold text-uppercase" style="font-size: 0.65rem;">Encaissé</div>
                            <div class="h5 mb-0">{{ number_format($stats['montant_encaisse_aujourd_hui'], 0, ',', ' ') }} FC</div>
                            <small class="text-muted" style="font-size: 0.75rem;">Du jour</small>
                        </div>
                        <div class="text-primary" style="font-size: 1.5rem; opacity: 0.3;">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Montant en attente -->
        <div class="col-md-3">
            <div class="card border-left-info h-100">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-info fw-bold text-uppercase" style="font-size: 0.65rem;">À Encaisser</div>
                            <div class="h5 mb-0">{{ number_format($stats['montant_en_attente'], 0, ',', ' ') }} FC</div>
                            <small class="text-muted" style="font-size: 0.75rem;">En attente</small>
                        </div>
                        <div class="text-info" style="font-size: 1.5rem; opacity: 0.3;">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Consultations en attente de paiement -->
        <div class="col-lg-7 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-exclamation-triangle me-2"></i>Consultations en Attente ({{ $consultationsEnAttente->count() }})</span>
                    <a href="{{ route('admin.caissier.consultations') }}" class="btn btn-sm btn-light">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    @if($consultationsEnAttente->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Médecin</th>
                                        <th>Motif</th>
                                        <th>Montant</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($consultationsEnAttente as $consultation)
                                    <tr>
                                        <td>
                                            <strong>{{ $consultation->patient->nom }} {{ $consultation->patient->prenom }}</strong><br>
                                            <small class="text-muted">{{ $consultation->patient->telephone }}</small>
                                        </td>
                                        <td>{{ $consultation->medecin->nom }} {{ $consultation->medecin->prenom }}</td>
                                        <td>
                                            <small>{{ \Str::limit($consultation->motif_consultation, 30) }}</small>
                                        </td>
                                        <td><strong>{{ number_format($consultation->frais_consultation, 0, ',', ' ') }} FC</strong></td>
                                        <td>
                                            <a href="{{ route('admin.caissier.consultations.show', $consultation->id) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-cash-register"></i> Encaisser
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                            <p class="mt-3 text-muted">Aucune consultation en attente de paiement</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Paiements récents -->
        <div class="col-lg-5 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-history me-2"></i>Paiements Récents
                </div>
                <div class="card-body p-0">
                    @if($paiementsRecents->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($paiementsRecents as $paiement)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <strong>{{ $paiement->patient->nom }} {{ $paiement->patient->prenom }}</strong><br>
                                        <small class="text-muted">
                                            <i class="fas fa-user-md"></i> {{ $paiement->medecin->nom }}<br>
                                            <i class="fas fa-clock"></i> {{ $paiement->date_paiement->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <strong class="text-success">{{ number_format($paiement->montant_paye, 0, ',', ' ') }} FC</strong><br>
                                        <span class="badge bg-success">{{ $paiement->numero_facture }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                            <p class="mt-3 text-muted">Aucun paiement récent</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de recherche patient -->
<div class="modal fade" id="recherchePatientModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-search me-2"></i>Rechercher un Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="recherchePatient" class="form-control" placeholder="Nom, prénom ou téléphone du patient...">
                <div id="resultatsRecherche" class="mt-3"></div>
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

<script>
// Recherche de patient
document.getElementById('recherchePatient').addEventListener('input', function(e) {
    let query = e.target.value;
    if (query.length < 2) {
        document.getElementById('resultatsRecherche').innerHTML = '';
        return;
    }

    fetch(`{{ route('admin.caissier.rechercher-patient') }}?q=${query}`)
        .then(response => response.json())
        .then(data => {
            let html = '';
            if (data.length > 0) {
                html = '<div class="list-group">';
                data.forEach(consultation => {
                    html += `
                        <a href="/admin/caissier/consultations/${consultation.id}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${consultation.patient_nom}</strong><br>
                                    <small class="text-muted">${consultation.patient_telephone} - Dr. ${consultation.medecin_nom}</small><br>
                                    <small>${consultation.motif}</small>
                                </div>
                                <div class="text-end">
                                    <strong>${new Intl.NumberFormat('fr-FR').format(consultation.montant)} FC</strong><br>
                                    <small class="text-muted">${consultation.date_creation}</small>
                                </div>
                            </div>
                        </a>
                    `;
                });
                html += '</div>';
            } else {
                html = '<p class="text-muted text-center">Aucun résultat trouvé</p>';
            }
            document.getElementById('resultatsRecherche').innerHTML = html;
        });
});
</script>
@endsection

