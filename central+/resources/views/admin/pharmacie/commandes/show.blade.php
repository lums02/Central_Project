@extends('layouts.admin')

@section('title', 'Détails de la Commande')
@section('page-title', 'Détails de la Commande')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-shopping-cart me-2"></i>{{ $commande->numero_commande }}</h2>
            <span class="badge bg-{{ $commande->getStatutClass() }} fs-6">{{ $commande->getStatutLabel() }}</span>
        </div>
        <div>
            <a href="{{ route('admin.pharmacie.commandes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            @if($commande->peutEtreValidee())
                <button class="btn btn-success" onclick="validerCommande()">
                    <i class="fas fa-check me-2"></i>Valider
                </button>
            @endif
            @if($commande->peutEtreReceptionnee())
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#receptionModal">
                    <i class="fas fa-truck-loading me-2"></i>Réceptionner
                </button>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-md-8">
            <!-- Informations générales -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations Générales</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Fournisseur:</strong><br>
                            {{ $commande->fournisseur->nom }}
                            @if($commande->fournisseur->telephone)
                                <br><small class="text-muted">{{ $commande->fournisseur->telephone }}</small>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Créée par:</strong><br>
                            {{ $commande->user->nom }}
                            <br><small class="text-muted">{{ $commande->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>Date Commande:</strong><br>
                            {{ $commande->date_commande->format('d/m/Y') }}
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>Livraison Prévue:</strong><br>
                            {{ $commande->date_livraison_prevue ? $commande->date_livraison_prevue->format('d/m/Y') : '-' }}
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>Livraison Réelle:</strong><br>
                            {{ $commande->date_livraison_reelle ? $commande->date_livraison_reelle->format('d/m/Y') : '-' }}
                        </div>
                        @if($commande->notes)
                        <div class="col-md-12">
                            <strong>Notes:</strong><br>
                            {{ $commande->notes }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Lignes de commande -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Détails de la Commande</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Médicament</th>
                                    <th>Qté Commandée</th>
                                    <th>Qté Reçue</th>
                                    <th>Prix Unit.</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($commande->lignes as $ligne)
                                <tr>
                                    <td>
                                        <strong>{{ $ligne->medicament->nom }}</strong>
                                        @if($ligne->medicament->dosage)
                                            <br><small class="text-muted">{{ $ligne->medicament->dosage }}</small>
                                        @endif
                                    </td>
                                    <td><strong>{{ $ligne->quantite_commandee }}</strong></td>
                                    <td>
                                        <strong class="{{ $ligne->estComplete() ? 'text-success' : 'text-warning' }}">
                                            {{ $ligne->quantite_recue }}
                                        </strong>
                                    </td>
                                    <td>${{ number_format($ligne->prix_unitaire, 2) }}</td>
                                    <td><strong>${{ number_format($ligne->montant_ligne, 2) }}</strong></td>
                                    <td>
                                        @if($ligne->estComplete())
                                            <span class="badge bg-success">Complet</span>
                                        @elseif($ligne->quantite_recue > 0)
                                            <span class="badge bg-warning">Partiel ({{ round($ligne->getPourcentageReception()) }}%)</span>
                                        @else
                                            <span class="badge bg-secondary">En attente</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panneau latéral -->
        <div class="col-md-4">
            <!-- Montants -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-dollar-sign me-2"></i>Montants</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Montant Total:</span>
                        <strong>${{ number_format($commande->montant_total, 2) }}</strong>
                    </div>
                    @if($commande->remise > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Remise:</span>
                        <strong class="text-danger">-${{ number_format($commande->remise, 2) }}</strong>
                    </div>
                    @endif
                    @if($commande->frais_livraison > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Frais Livraison:</span>
                        <strong>+${{ number_format($commande->frais_livraison, 2) }}</strong>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Montant Final:</strong>
                        <h4 class="text-success mb-0">${{ number_format($commande->montant_final, 2) }}</h4>
                    </div>
                </div>
            </div>

            <!-- Progression -->
            @if($commande->lignes->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Progression</h5>
                </div>
                <div class="card-body">
                    @php
                        $totalCommandee = $commande->lignes->sum('quantite_commandee');
                        $totalRecue = $commande->lignes->sum('quantite_recue');
                        $pourcentage = $totalCommandee > 0 ? ($totalRecue / $totalCommandee) * 100 : 0;
                    @endphp
                    <div class="mb-2">
                        <strong>Articles reçus:</strong> {{ $totalRecue }} / {{ $totalCommandee }}
                    </div>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-{{ $pourcentage == 100 ? 'success' : 'warning' }}" 
                             style="width: {{ $pourcentage }}%">
                            {{ round($pourcentage) }}%
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Historique -->
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Historique</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <i class="fas fa-plus-circle text-primary"></i>
                            <div>
                                <strong>Créée</strong><br>
                                <small>{{ $commande->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @if($commande->validee_at)
                        <div class="timeline-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <div>
                                <strong>Validée</strong><br>
                                <small>{{ $commande->validee_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @endif
                        @if($commande->receptionnee_at)
                        <div class="timeline-item">
                            <i class="fas fa-truck text-info"></i>
                            <div>
                                <strong>Réceptionnée</strong><br>
                                <small>{{ $commande->receptionnee_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Réception -->
<div class="modal fade" id="receptionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-truck-loading me-2"></i>Réceptionner la Commande</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="receptionForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Entrez les quantités réellement reçues. Le stock sera automatiquement mis à jour.
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Médicament</th>
                                    <th>Commandée</th>
                                    <th>Déjà Reçue</th>
                                    <th>À Recevoir</th>
                                    <th>Qté Reçue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($commande->lignes as $ligne)
                                <tr>
                                    <td>
                                        <strong>{{ $ligne->medicament->nom }}</strong>
                                        <input type="hidden" name="lignes[{{ $loop->index }}][ligne_id]" value="{{ $ligne->id }}">
                                    </td>
                                    <td>{{ $ligne->quantite_commandee }}</td>
                                    <td>{{ $ligne->quantite_recue }}</td>
                                    <td><strong>{{ $ligne->getQuantiteRestante() }}</strong></td>
                                    <td>
                                        <input 
                                            type="number" 
                                            name="lignes[{{ $loop->index }}][quantite_recue]" 
                                            class="form-control" 
                                            min="0" 
                                            max="{{ $ligne->getQuantiteRestante() }}"
                                            value="{{ $ligne->getQuantiteRestante() }}"
                                            required
                                        >
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes de Réception</label>
                        <textarea name="notes_reception" class="form-control" rows="3" placeholder="Observations, produits endommagés, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>Confirmer la Réception
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function validerCommande() {
    if (confirm('Êtes-vous sûr de vouloir valider cette commande ?')) {
        fetch('{{ route("admin.pharmacie.commandes.valider", $commande->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Commande validée avec succès !');
                window.location.reload();
            } else {
                alert('Erreur : ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la validation');
        });
    }
}

document.getElementById('receptionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!confirm('Confirmer la réception de cette commande ? Le stock sera automatiquement mis à jour.')) {
        return;
    }
    
    const formData = new FormData(this);
    
    fetch('{{ route("admin.pharmacie.commandes.receptionner", $commande->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Réception enregistrée avec succès ! Le stock a été mis à jour.');
            window.location.reload();
        } else {
            alert('Erreur : ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la réception');
    });
});
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
    display: flex;
    align-items: start;
    gap: 15px;
}

.timeline-item i {
    font-size: 1.5rem;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 11px;
    top: 30px;
    width: 2px;
    height: calc(100% - 10px);
    background: #ddd;
}
</style>
@endsection

