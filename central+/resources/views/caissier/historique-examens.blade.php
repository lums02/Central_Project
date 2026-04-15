@extends('layouts.admin')

@section('title', 'Historique des Examens Payés')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-end align-items-center mb-4">
        <div>
            <a href="{{ route('admin.caissier.examens') }}" class="btn btn-warning me-2">
                <i class="fas fa-clock me-2"></i>Examens en Attente
            </a>
            <a href="{{ route('admin.caissier.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-home me-2"></i>Dashboard
            </a>
        </div>
    </div>

    <!-- Tableau de l'historique -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>Historique Complet des Paiements
        </div>
        <div class="card-body p-0">
            @if($examens->count() > 0 || $consultations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="10%">Type</th>
                                <th width="12%">N° / Réf</th>
                                <th width="18%">Patient</th>
                                <th width="15%">Description</th>
                                <th width="12%">Montant</th>
                                <th width="13%">Date Paiement</th>
                                <th width="12%">Caissier</th>
                                <th width="8%">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Consultations --}}
                            @foreach($consultations as $consultation)
                            <tr class="table-info">
                                <td>
                                    <span class="badge bg-primary">
                                        <i class="fas fa-user-md"></i> Consultation
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $consultation->numero_facture }}</span>
                                </td>
                                <td>
                                    <strong>{{ $consultation->patient->nom }} {{ $consultation->patient->prenom }}</strong><br>
                                    <small class="text-muted">{{ $consultation->patient->telephone }}</small>
                                </td>
                                <td>
                                    <small>{{ \Str::limit($consultation->motif_consultation, 40) }}</small>
                                </td>
                                <td>
                                    <strong class="text-success">{{ number_format($consultation->montant_paye, 0, ',', ' ') }} FC</strong>
                                </td>
                                <td>
                                    <small>
                                        {{ $consultation->date_paiement->format('d/m/Y') }}<br>
                                        {{ $consultation->date_paiement->format('H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <small>{{ $consultation->caissier->nom }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-success">Payé</span>
                                </td>
                            </tr>
                            @endforeach

                            {{-- Examens --}}
                            @foreach($examens as $examen)
                            <tr>
                                <td>
                                    <span class="badge bg-warning">
                                        <i class="fas fa-flask"></i> Examen
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $examen->numero_examen }}</span>
                                </td>
                                <td>
                                    <strong>{{ $examen->patient->nom }} {{ $examen->patient->prenom }}</strong><br>
                                    <small class="text-muted">{{ $examen->patient->telephone }}</small>
                                </td>
                                <td>
                                    <strong>{{ $examen->nom_examen }}</strong><br>
                                    <small class="text-muted">{{ ucfirst($examen->type_examen) }}</small>
                                </td>
                                <td>
                                    <strong class="text-success">{{ number_format($examen->prix, 0, ',', ' ') }} FC</strong>
                                </td>
                                <td>
                                    <small>
                                        {{ $examen->date_paiement->format('d/m/Y') }}<br>
                                        {{ $examen->date_paiement->format('H:i') }}
                                    </small>
                                </td>
                                <td>
                                    @if($examen->laborantin)
                                        <small>{{ $examen->laborantin->nom }}</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td>
                                    @if($examen->statut_examen === 'termine')
                                        <span class="badge bg-success">Terminé</span>
                                    @elseif($examen->statut_examen === 'en_cours')
                                        <span class="badge bg-primary">En cours</span>
                                    @else
                                        <span class="badge bg-secondary">Prescrit</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totaux -->
                <div class="card-footer">
                    <div class="text-muted">
                        <strong>Total :</strong> {{ $consultations->count() }} consultation(s) + {{ $examens->count() }} examen(s) = {{ $consultations->count() + $examens->count() }} paiement(s)
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 text-muted">Aucun examen dans l'historique</h4>
                    <p class="text-muted">Les examens payés apparaîtront ici</p>
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

