@extends('layouts.admin')

@section('title', 'Inventaire Physique')
@section('page-title', 'Inventaire Physique')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-clipboard-list me-2"></i>Inventaire Physique</h2>
            <p class="text-muted mb-0">Comptez physiquement les médicaments et entrez les quantités réelles</p>
        </div>
        <a href="{{ route('admin.pharmacie.stocks.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Instructions :</strong> Entrez la quantité réelle comptée pour chaque médicament. Le système calculera automatiquement les écarts et créera les ajustements nécessaires.
    </div>

    <form id="inventaireForm">
        @csrf
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des Médicaments ({{ $medicaments->count() }})</h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <input type="text" id="searchInventaire" class="form-control form-control-sm d-inline-block" style="width: 300px;" placeholder="Rechercher un médicament...">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="inventaireTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: 30%">Médicament</th>
                                <th style="width: 10%">Emplacement</th>
                                <th style="width: 10%">Stock Système</th>
                                <th style="width: 15%">Quantité Réelle <span class="text-danger">*</span></th>
                                <th style="width: 10%">Écart</th>
                                <th style="width: 20%">Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicaments as $index => $med)
                            <tr class="medicament-row" data-nom="{{ strtolower($med->nom) }}">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $med->nom }}</strong>
                                    @if($med->dosage)
                                        <br><small class="text-muted">{{ $med->dosage }}</small>
                                    @endif
                                    @if($med->forme)
                                        <br><span class="badge bg-info">{{ $med->forme }}</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $med->emplacement ?? '-' }}</small>
                                </td>
                                <td>
                                    <strong class="stock-systeme">{{ $med->stock_actuel }}</strong>
                                </td>
                                <td>
                                    <input type="hidden" name="stocks[{{ $index }}][medicament_id]" value="{{ $med->id }}">
                                    <input 
                                        type="number" 
                                        name="stocks[{{ $index }}][quantite_reelle]" 
                                        class="form-control quantite-reelle" 
                                        min="0" 
                                        value="{{ $med->stock_actuel }}"
                                        data-stock-systeme="{{ $med->stock_actuel }}"
                                        data-index="{{ $index }}"
                                        required
                                    >
                                </td>
                                <td>
                                    <span class="ecart-badge badge bg-secondary" id="ecart-{{ $index }}">0</span>
                                </td>
                                <td>
                                    <input 
                                        type="text" 
                                        name="stocks[{{ $index }}][notes]" 
                                        class="form-control form-control-sm" 
                                        placeholder="Notes..."
                                    >
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-warning mb-0">
                            <strong>Ajustements à effectuer :</strong> <span id="totalAjustements">0</span>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <button type="button" class="btn btn-secondary me-2" onclick="resetInventaire()">
                            <i class="fas fa-undo me-2"></i>Réinitialiser
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Enregistrer l'Inventaire
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Recherche en temps réel
document.getElementById('searchInventaire').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.medicament-row');
    
    rows.forEach(row => {
        const nom = row.getAttribute('data-nom');
        if (nom.includes(search)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Calculer les écarts en temps réel
document.querySelectorAll('.quantite-reelle').forEach(input => {
    input.addEventListener('input', function() {
        calculerEcart(this);
        calculerTotalAjustements();
    });
    
    // Calculer l'écart initial
    calculerEcart(input);
});

function calculerEcart(input) {
    const index = input.getAttribute('data-index');
    const stockSysteme = parseInt(input.getAttribute('data-stock-systeme'));
    const quantiteReelle = parseInt(input.value) || 0;
    const ecart = quantiteReelle - stockSysteme;
    
    const ecartBadge = document.getElementById('ecart-' + index);
    ecartBadge.textContent = (ecart > 0 ? '+' : '') + ecart;
    
    // Changer la couleur selon l'écart
    ecartBadge.classList.remove('bg-secondary', 'bg-success', 'bg-danger', 'bg-warning');
    if (ecart === 0) {
        ecartBadge.classList.add('bg-secondary');
    } else if (ecart > 0) {
        ecartBadge.classList.add('bg-success');
    } else if (Math.abs(ecart) <= 5) {
        ecartBadge.classList.add('bg-warning');
    } else {
        ecartBadge.classList.add('bg-danger');
    }
}

function calculerTotalAjustements() {
    let total = 0;
    document.querySelectorAll('.quantite-reelle').forEach(input => {
        const stockSysteme = parseInt(input.getAttribute('data-stock-systeme'));
        const quantiteReelle = parseInt(input.value) || 0;
        if (stockSysteme !== quantiteReelle) {
            total++;
        }
    });
    document.getElementById('totalAjustements').textContent = total;
}

function resetInventaire() {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser toutes les quantités ?')) {
        document.querySelectorAll('.quantite-reelle').forEach(input => {
            const stockSysteme = input.getAttribute('data-stock-systeme');
            input.value = stockSysteme;
            calculerEcart(input);
        });
        calculerTotalAjustements();
    }
}

// Soumettre le formulaire
document.getElementById('inventaireForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!confirm('Êtes-vous sûr de vouloir enregistrer cet inventaire ? Les ajustements seront appliqués aux stocks.')) {
        return;
    }
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement en cours...';
    
    fetch('{{ route("admin.pharmacie.stocks.inventaire.enregistrer") }}', {
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
            alert(data.message);
            window.location.href = '{{ route("admin.pharmacie.stocks.index") }}';
        } else {
            alert('Erreur : ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Enregistrer l\'Inventaire';
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'enregistrement de l\'inventaire');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Enregistrer l\'Inventaire';
    });
});

// Calculer le total initial
calculerTotalAjustements();
</script>

<style>
.table td {
    vertical-align: middle;
}

.quantite-reelle {
    font-weight: bold;
    font-size: 1.1rem;
}

.ecart-badge {
    font-size: 1rem;
    padding: 0.5rem 0.75rem;
}

.medicament-row:hover {
    background-color: #f8f9fa;
}
</style>
@endsection

