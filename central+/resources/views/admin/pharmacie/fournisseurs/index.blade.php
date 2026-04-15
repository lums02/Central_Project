@extends('layouts.admin')

@section('title', 'Gestion des Fournisseurs')
@section('page-title', 'Gestion des Fournisseurs')

@section('content')
<div class="container-fluid">
    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary">{{ $stats['total'] }}</h3>
                    <p class="mb-0">Total Fournisseurs</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success">{{ $stats['actifs'] }}</h3>
                    <p class="mb-0">Actifs</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-danger">{{ $stats['inactifs'] }}</h3>
                    <p class="mb-0">Inactifs</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-truck me-2"></i>Liste des Fournisseurs</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nouveauFournisseurModal">
            <i class="fas fa-plus me-2"></i>Nouveau Fournisseur
        </button>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher un fournisseur..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="actif" class="form-select">
                            <option value="">Tous</option>
                            <option value="1" {{ request('actif') === '1' ? 'selected' : '' }}>Actifs</option>
                            <option value="0" {{ request('actif') === '0' ? 'selected' : '' }}>Inactifs</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100">
                            <i class="fas fa-search me-1"></i>Rechercher
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des fournisseurs -->
    <div class="card">
        <div class="card-body">
            @if($fournisseurs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Contact</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>Délai Livraison</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fournisseurs as $four)
                            <tr>
                                <td><code>{{ $four->code ?? '-' }}</code></td>
                                <td>
                                    <strong>{{ $four->nom }}</strong>
                                    @if($four->ville)
                                        <br><small class="text-muted">{{ $four->ville }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($four->contact_nom)
                                        {{ $four->contact_nom }}
                                        @if($four->contact_fonction)
                                            <br><small class="text-muted">{{ $four->contact_fonction }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $four->telephone ?? '-' }}</td>
                                <td>{{ $four->email ?? '-' }}</td>
                                <td>{{ $four->delai_livraison_jours }} jours</td>
                                <td>
                                    @if($four->actif)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-danger">Inactif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.pharmacie.fournisseurs.show', $four->id) }}" class="btn btn-sm btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-warning" onclick="modifierFournisseur({{ $four->id }})" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="supprimerFournisseur({{ $four->id }}, '{{ $four->nom }}')" title="Désactiver">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $fournisseurs->links() }}
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucun fournisseur trouvé.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Nouveau Fournisseur -->
<div class="modal fade" id="nouveauFournisseurModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-truck me-2"></i>Nouveau Fournisseur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="nouveauFournisseurForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom du Fournisseur <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Code</label>
                            <input type="text" name="code" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="telephone" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Principal</label>
                            <input type="text" name="contact_nom" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fonction</label>
                            <input type="text" name="contact_fonction" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Adresse</label>
                        <textarea name="adresse" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ville</label>
                            <input type="text" name="ville" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pays</label>
                            <input type="text" name="pays" class="form-control" value="RDC">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Délai de Livraison (jours)</label>
                            <input type="number" name="delai_livraison_jours" class="form-control" value="7" min="1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Montant Minimum Commande (USD)</label>
                            <input type="number" name="montant_minimum_commande" class="form-control" step="0.01" min="0">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Spécialités</label>
                        <textarea name="specialites" class="form-control" rows="2" placeholder="Ex: Antibiotiques, Matériel médical..."></textarea>
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
document.getElementById('nouveauFournisseurForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("admin.pharmacie.fournisseurs.store") }}', {
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
            alert('Fournisseur ajouté avec succès !');
            window.location.reload();
        } else {
            alert('Erreur : ' + (data.message || 'Une erreur est survenue'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'ajout du fournisseur');
    });
});

function modifierFournisseur(id) {
    window.location.href = `{{ url('admin/pharmacie/fournisseurs') }}/${id}`;
}

function supprimerFournisseur(id, nom) {
    if (confirm(`Êtes-vous sûr de vouloir désactiver le fournisseur "${nom}" ?`)) {
        fetch(`{{ url('admin/pharmacie/fournisseurs') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Fournisseur désactivé avec succès !');
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
