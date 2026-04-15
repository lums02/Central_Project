@extends('layouts.admin')

@section('title', 'Gestion des Stocks')
@section('page-title', 'Gestion des Stocks')

@section('content')
<div class="container-fluid">
    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary">{{ $stats['total_medicaments'] }}</h3>
                    <p class="mb-0">Médicaments</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-warning">{{ $stats['stock_faible'] }}</h3>
                    <p class="mb-0">Stock Faible</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-danger">{{ $stats['rupture'] }}</h3>
                    <p class="mb-0">Rupture</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success">${{ number_format($stats['valeur_stock'], 2) }}</h3>
                    <p class="mb-0">Valeur Stock</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-boxes me-2"></i>Gestion des Stocks</h2>
        <div>
            <a href="{{ route('admin.pharmacie.stocks.inventaire') }}" class="btn btn-warning">
                <i class="fas fa-clipboard-list me-2"></i>Inventaire
            </a>
        </div>
    </div>

    <!-- Alertes -->
    @if($stats['rupture'] > 0)
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Attention !</strong> {{ $stats['rupture'] }} médicament(s) en rupture de stock.
    </div>
    @endif

    @if($stats['stock_faible'] > 0)
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Alerte !</strong> {{ $stats['stock_faible'] }} médicament(s) en stock faible.
    </div>
    @endif

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pharmacie.stocks.index') }}">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher un médicament..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="statut" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="stock_faible" {{ request('statut') == 'stock_faible' ? 'selected' : '' }}>Stock Faible</option>
                            <option value="rupture" {{ request('statut') == 'rupture' ? 'selected' : '' }}>Rupture</option>
                            <option value="perime" {{ request('statut') == 'perime' ? 'selected' : '' }}>Périmé</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary me-2">
                            <i class="fas fa-search me-1"></i>Rechercher
                        </button>
                        <a href="{{ route('admin.pharmacie.stocks.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo me-1"></i>Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Liste des stocks -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des Stocks</h5>
                </div>
                <div class="card-body">
                    @if($medicaments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Médicament</th>
                                        <th>Stock Actuel</th>
                                        <th>Stock Min</th>
                                        <th>Statut</th>
                                        <th>Valeur</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($medicaments as $med)
                                    <tr>
                                        <td>
                                            <strong>{{ $med->nom }}</strong>
                                            @if($med->dosage)
                                                <br><small class="text-muted">{{ $med->dosage }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <h5 class="mb-0">
                                                @if($med->stock_actuel == 0)
                                                    <span class="text-danger">{{ $med->stock_actuel }}</span>
                                                @elseif($med->isStockFaible())
                                                    <span class="text-warning">{{ $med->stock_actuel }}</span>
                                                @else
                                                    <span class="text-success">{{ $med->stock_actuel }}</span>
                                                @endif
                                            </h5>
                                        </td>
                                        <td>{{ $med->stock_minimum }}</td>
                                        <td>
                                            @php $status = $med->getStockStatus(); @endphp
                                            <span class="badge bg-{{ $status['class'] }}">
                                                {{ $status['label'] }}
                                            </span>
                                        </td>
                                        <td>${{ number_format($med->stock_actuel * $med->prix_unitaire, 2) }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" onclick="ajusterStock({{ $med->id }}, '{{ $med->nom }}', {{ $med->stock_actuel }})" title="Ajuster">
                                                <i class="fas fa-exchange-alt"></i>
                                            </button>
                                            <a href="{{ route('admin.pharmacie.stocks.historique', $med->id) }}" class="btn btn-sm btn-info" title="Historique">
                                                <i class="fas fa-history"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $medicaments->links() }}
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Aucun médicament trouvé.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Mouvements récents -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Mouvements Récents</h5>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    @if($mouvementsRecents->count() > 0)
                        @foreach($mouvementsRecents as $mouv)
                        <div class="border-bottom pb-2 mb-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="badge bg-{{ $mouv->getTypeClass() }}">
                                        <i class="fas fa-{{ $mouv->getTypeIcon() }} me-1"></i>
                                        {{ $mouv->getTypeLabel() }}
                                    </span>
                                    <strong class="d-block mt-1">{{ $mouv->medicament->nom }}</strong>
                                    <small class="text-muted">
                                        {{ $mouv->quantite > 0 ? '+' : '' }}{{ $mouv->quantite }} unités
                                        ({{ $mouv->stock_avant }} → {{ $mouv->stock_apres }})
                                    </small>
                                </div>
                                <small class="text-muted">{{ $mouv->created_at->diffForHumans() }}</small>
                            </div>
                            @if($mouv->motif)
                                <small class="text-muted d-block mt-1">{{ $mouv->motif }}</small>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">Aucun mouvement récent</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajuster Stock -->
<div class="modal fade" id="ajusterStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-exchange-alt me-2"></i>Ajuster le Stock</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="ajusterStockForm">
                @csrf
                <input type="hidden" id="medicament_id" name="medicament_id">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Médicament :</strong> <span id="medicament_nom"></span><br>
                        <strong>Stock actuel :</strong> <span id="stock_actuel"></span> unités
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type de Mouvement <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="entree">Entrée (Réception)</option>
                            <option value="sortie">Sortie</option>
                            <option value="ajustement">Ajustement</option>
                            <option value="vente">Vente</option>
                            <option value="retour">Retour</option>
                            <option value="perime">Périmé/Détruit</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantité <span class="text-danger">*</span></label>
                        <input type="number" name="quantite" class="form-control" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Référence (N° Facture, Bon, etc.)</label>
                        <input type="text" name="reference" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Motif</label>
                        <textarea name="motif" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function ajusterStock(id, nom, stockActuel) {
    document.getElementById('medicament_id').value = id;
    document.getElementById('medicament_nom').textContent = nom;
    document.getElementById('stock_actuel').textContent = stockActuel;
    
    new bootstrap.Modal(document.getElementById('ajusterStockModal')).show();
}

document.getElementById('ajusterStockForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("admin.pharmacie.stocks.ajuster") }}', {
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
            alert(data.message + '\nNouveau stock: ' + data.nouveau_stock);
            window.location.reload();
        } else {
            alert('Erreur : ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'ajustement du stock');
    });
});
</script>
@endsection
