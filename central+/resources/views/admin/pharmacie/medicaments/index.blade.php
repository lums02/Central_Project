@extends('layouts.admin')

@section('title', 'Gestion des Médicaments')
@section('page-title', 'Gestion des Médicaments')

@section('content')
<div class="container-fluid">
    <!-- En-tête avec statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary">{{ $stats['total'] }}</h3>
                    <p class="mb-0">Total Médicaments</p>
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
                    <h3 class="text-danger">{{ $stats['perimes'] }}</h3>
                    <p class="mb-0">Périmés</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info">{{ $stats['bientot_perimes'] }}</h3>
                    <p class="mb-0">Bientôt Périmés</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-pills me-2"></i>Liste des Médicaments</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nouveauMedicamentModal">
            <i class="fas fa-plus me-2"></i>Nouveau Médicament
        </button>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pharmacie.medicaments.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="categorie" class="form-select">
                            <option value="">Toutes catégories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('categorie') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="forme" class="form-select">
                            <option value="">Toutes formes</option>
                            @foreach($formes as $forme)
                                <option value="{{ $forme }}" {{ request('forme') == $forme ? 'selected' : '' }}>{{ $forme }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="statut" class="form-select">
                            <option value="">Tous statuts</option>
                            <option value="stock_faible" {{ request('statut') == 'stock_faible' ? 'selected' : '' }}>Stock Faible</option>
                            <option value="perime" {{ request('statut') == 'perime' ? 'selected' : '' }}>Périmé</option>
                            <option value="bientot_perime" {{ request('statut') == 'bientot_perime' ? 'selected' : '' }}>Bientôt Périmé</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary me-2">
                            <i class="fas fa-search me-1"></i>Rechercher
                        </button>
                        <a href="{{ route('admin.pharmacie.medicaments.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo me-1"></i>Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des médicaments -->
    <div class="card">
        <div class="card-body">
            @if($medicaments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Catégorie</th>
                                <th>Forme</th>
                                <th>Dosage</th>
                                <th>Prix (USD)</th>
                                <th>Stock</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicaments as $med)
                            <tr>
                                <td><code>{{ $med->code ?? '-' }}</code></td>
                                <td>
                                    <strong>{{ $med->nom }}</strong>
                                    @if($med->nom_generique)
                                        <br><small class="text-muted">{{ $med->nom_generique }}</small>
                                    @endif
                                </td>
                                <td><span class="badge bg-info">{{ $med->categorie }}</span></td>
                                <td>{{ $med->forme }}</td>
                                <td>{{ $med->dosage ?? '-' }}</td>
                                <td><strong>${{ number_format($med->prix_unitaire, 2) }}</strong></td>
                                <td>
                                    @php $status = $med->getStockStatus(); @endphp
                                    <span class="badge bg-{{ $status['class'] }}">
                                        {{ $med->stock_actuel }} unités
                                    </span>
                                    @if($med->isStockFaible())
                                        <i class="fas fa-exclamation-triangle text-warning ms-1" title="Stock faible"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($med->isPerime())
                                        <span class="badge bg-danger">Périmé</span>
                                    @elseif($med->isBientotPerime())
                                        <span class="badge bg-warning">Bientôt périmé</span>
                                    @else
                                        <span class="badge bg-success">Actif</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick="voirMedicament({{ $med->id }})" title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="modifierMedicament({{ $med->id }})" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="supprimerMedicament({{ $med->id }}, '{{ $med->nom }}')" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    @if(request()->hasAny(['search', 'categorie', 'forme', 'statut']))
                        Aucun médicament ne correspond à vos critères de recherche.
                    @else
                        Aucun médicament enregistré pour le moment. Cliquez sur "Nouveau Médicament" pour commencer.
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Nouveau Médicament -->
<div class="modal fade" id="nouveauMedicamentModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-pills me-2"></i>Nouveau Médicament</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="nouveauMedicamentForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Informations de base -->
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Informations de Base</h6>
                            
                            <div class="mb-3">
                                <label class="form-label">Nom du Médicament <span class="text-danger">*</span></label>
                                <input type="text" name="nom" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nom Générique (DCI)</label>
                                <input type="text" name="nom_generique" class="form-control">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Code/Référence</label>
                                    <input type="text" name="code" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fabricant</label>
                                    <input type="text" name="fabricant" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                                    <select name="categorie" class="form-select" required>
                                        <option value="">-- Sélectionner --</option>
                                        <option>Antibiotiques</option>
                                        <option>Antalgiques</option>
                                        <option>Anti-inflammatoires</option>
                                        <option>Antipaludéens</option>
                                        <option>Antihypertenseurs</option>
                                        <option>Antidiabétiques</option>
                                        <option>Vitamines et Suppléments</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Forme <span class="text-danger">*</span></label>
                                    <select name="forme" class="form-select" required>
                                        <option value="">-- Sélectionner --</option>
                                        <option>Comprimé</option>
                                        <option>Gélule</option>
                                        <option>Sirop</option>
                                        <option>Injection</option>
                                        <option>Pommade</option>
                                        <option>Crème</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Dosage</label>
                                <input type="text" name="dosage" class="form-control" placeholder="Ex: 500mg, 10ml">
                            </div>
                        </div>

                        <!-- Prix et Stock -->
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Prix et Stock</h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Prix Achat (USD)</label>
                                    <input type="number" name="prix_achat" class="form-control" step="0.01" min="0">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Prix Vente (USD) <span class="text-danger">*</span></label>
                                    <input type="number" name="prix_unitaire" class="form-control" step="0.01" min="0" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Stock Initial</label>
                                    <input type="number" name="stock_actuel" class="form-control" value="0" min="0">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Stock Minimum</label>
                                    <input type="number" name="stock_minimum" class="form-control" value="10" min="0">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Emplacement</label>
                                <input type="text" name="emplacement" class="form-control" placeholder="Ex: Étagère A3">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date Fabrication</label>
                                    <input type="date" name="date_fabrication" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date Expiration</label>
                                    <input type="date" name="date_expiration" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Numéro de Lot</label>
                                <input type="text" name="numero_lot" class="form-control">
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="prescription_requise" id="prescriptionRequise">
                                <label class="form-check-label" for="prescriptionRequise">
                                    Prescription médicale requise
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Informations médicales -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3">Informations Médicales (Optionnel)</h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Indications</label>
                            <textarea name="indication" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contre-indications</label>
                            <textarea name="contre_indication" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Effets Secondaires</label>
                            <textarea name="effets_secondaires" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Posologie</label>
                            <textarea name="posologie" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
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

<!-- Modal Modifier Médicament -->
<div class="modal fade" id="modifierMedicamentModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Modifier Médicament</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="modifierMedicamentForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_medicament_id">
                <div class="modal-body" id="editMedicamentContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Voir Médicament -->
<div class="modal fade" id="voirMedicamentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-eye me-2"></i>Détails du Médicament</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="voirMedicamentContent">
                <!-- Le contenu sera chargé dynamiquement -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
// Ajouter un médicament
document.getElementById('nouveauMedicamentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("admin.pharmacie.medicaments.store") }}', {
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
            alert('Médicament ajouté avec succès !');
            window.location.reload();
        } else {
            alert('Erreur : ' + (data.message || 'Une erreur est survenue'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'ajout du médicament');
    });
});

// Voir un médicament
function voirMedicament(id) {
    fetch(`{{ url('admin/pharmacie/medicaments') }}/${id}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('voirMedicamentContent').innerHTML = html;
            new bootstrap.Modal(document.getElementById('voirMedicamentModal')).show();
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des détails');
        });
}

// Modifier un médicament
function modifierMedicament(id) {
    // Pour l'instant, rediriger vers la page de détails
    window.location.href = `{{ url('admin/pharmacie/medicaments') }}/${id}`;
}

// Supprimer un médicament
function supprimerMedicament(id, nom) {
    if (confirm(`Êtes-vous sûr de vouloir désactiver le médicament "${nom}" ?`)) {
        fetch(`{{ url('admin/pharmacie/medicaments') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Médicament désactivé avec succès !');
                window.location.reload();
            } else {
                alert('Erreur : ' + (data.message || 'Une erreur est survenue'));
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression');
        });
    }
}
</script>
@endsection
