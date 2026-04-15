@extends('layouts.admin')

@section('title', 'Historique des Mouvements')
@section('page-title', 'Historique des Mouvements')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-history me-2"></i>Historique des Mouvements</h2>
            <p class="text-muted mb-0">{{ $medicament->nom }} @if($medicament->dosage) - {{ $medicament->dosage }} @endif</p>
        </div>
        <a href="{{ route('admin.pharmacie.stocks.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <!-- Informations du médicament -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Stock Actuel:</strong><br>
                    <h3 class="text-primary">{{ $medicament->stock_actuel }} unités</h3>
                </div>
                <div class="col-md-3">
                    <strong>Stock Minimum:</strong><br>
                    <h4>{{ $medicament->stock_minimum }} unités</h4>
                </div>
                <div class="col-md-3">
                    <strong>Prix Unitaire:</strong><br>
                    <h4>${{ number_format($medicament->prix_unitaire, 2) }}</h4>
                </div>
                <div class="col-md-3">
                    <strong>Valeur Stock:</strong><br>
                    <h4>${{ number_format($medicament->stock_actuel * $medicament->prix_unitaire, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des mouvements -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Historique Complet</h5>
        </div>
        <div class="card-body">
            @if($mouvements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Quantité</th>
                                <th>Stock Avant</th>
                                <th>Stock Après</th>
                                <th>Référence</th>
                                <th>Utilisateur</th>
                                <th>Motif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mouvements as $mouv)
                            <tr>
                                <td>
                                    <strong>{{ $mouv->created_at->format('d/m/Y') }}</strong><br>
                                    <small class="text-muted">{{ $mouv->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $mouv->getTypeClass() }}">
                                        <i class="fas fa-{{ $mouv->getTypeIcon() }} me-1"></i>
                                        {{ $mouv->getTypeLabel() }}
                                    </span>
                                </td>
                                <td>
                                    @if($mouv->quantite > 0)
                                        <span class="text-success"><strong>+{{ $mouv->quantite }}</strong></span>
                                    @else
                                        <span class="text-danger"><strong>{{ $mouv->quantite }}</strong></span>
                                    @endif
                                </td>
                                <td>{{ $mouv->stock_avant }}</td>
                                <td><strong>{{ $mouv->stock_apres }}</strong></td>
                                <td>
                                    @if($mouv->reference)
                                        <code>{{ $mouv->reference }}</code>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $mouv->user->nom ?? '-' }}</small>
                                </td>
                                <td>
                                    @if($mouv->motif)
                                        <small>{{ $mouv->motif }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $mouvements->links() }}
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucun mouvement enregistré pour ce médicament.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

