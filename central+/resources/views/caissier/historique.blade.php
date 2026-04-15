@extends('layouts.admin')

@section('title', 'Historique des Paiements')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-history me-2"></i>Historique des Paiements</h1>
        <a href="{{ route('admin.caissier.consultations') }}" class="btn btn-primary">
            <i class="fas fa-cash-register me-2"></i>Retour aux Consultations
        </a>
    </div>

    <!-- Tableau de l'historique -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>Paiements Effectués
        </div>
        <div class="card-body p-0">
            @if($paiements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="12%">N° Facture</th>
                                <th width="15%">Patient</th>
                                <th width="12%">Médecin</th>
                                <th width="20%">Motif</th>
                                <th width="10%">Montant</th>
                                <th width="10%">Mode</th>
                                <th width="11%">Date/Heure</th>
                                <th width="5%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paiements as $index => $paiement)
                            <tr>
                                <td>{{ $paiements->firstItem() + $index }}</td>
                                <td>
                                    <span class="badge bg-success">{{ $paiement->numero_facture }}</span>
                                </td>
                                <td>
                                    <strong>{{ $paiement->patient->nom }} {{ $paiement->patient->prenom }}</strong><br>
                                    <small class="text-muted">{{ $paiement->patient->telephone }}</small>
                                </td>
                                <td>
                                    <small>Dr. {{ $paiement->medecin->nom }} {{ $paiement->medecin->prenom }}</small>
                                </td>
                                <td>
                                    <small>{{ \Str::limit($paiement->motif_consultation, 40) }}</small>
                                </td>
                                <td>
                                    <strong class="text-success">{{ number_format($paiement->montant_paye, 0, ',', ' ') }} FC</strong>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ ucfirst(str_replace('_', ' ', $paiement->mode_paiement)) }}
                                    </span>
                                </td>
                                <td>
                                    <small>
                                        {{ $paiement->date_paiement->format('d/m/Y') }}<br>
                                        {{ $paiement->date_paiement->format('H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.caissier.facture', $paiement->id) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Voir la facture">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer">
                    {{ $paiements->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 text-muted">Aucun paiement dans l'historique</h4>
                    <p class="text-muted">Les paiements effectués apparaîtront ici</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.table tbody tr {
    transition: all 0.2s;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}
</style>
@endsection

