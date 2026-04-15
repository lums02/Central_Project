@extends('layouts.admin')

@section('title', 'Gestion des Commandes')
@section('page-title', 'Gestion des Commandes')

@section('content')
<div class="container-fluid">
    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-warning">{{ $stats['en_attente'] }}</h3>
                    <p class="mb-0">En Attente</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary">{{ $stats['en_cours'] }}</h3>
                    <p class="mb-0">En Cours</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success">{{ $stats['livrees'] }}</h3>
                    <p class="mb-0">Livrées</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info">${{ number_format($stats['total_mois'], 2) }}</h3>
                    <p class="mb-0">Total Mois</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-shopping-cart me-2"></i>Liste des Commandes</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nouvelleCommandeModal">
            <i class="fas fa-plus me-2"></i>Nouvelle Commande
        </button>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row">
                    <div class="col-md-5">
                        <select name="statut" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En Attente</option>
                            <option value="validee" {{ request('statut') == 'validee' ? 'selected' : '' }}>Validée</option>
                            <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En Cours</option>
                            <option value="livree" {{ request('statut') == 'livree' ? 'selected' : '' }}>Livrée</option>
                            <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <select name="fournisseur_id" class="form-select">
                            <option value="">Tous les fournisseurs</option>
                            @foreach($fournisseurs as $four)
                                <option value="{{ $four->id }}" {{ request('fournisseur_id') == $four->id ? 'selected' : '' }}>
                                    {{ $four->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100">
                            <i class="fas fa-search me-1"></i>Filtrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des commandes -->
    <div class="card">
        <div class="card-body">
            @if($commandes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>N° Commande</th>
                                <th>Fournisseur</th>
                                <th>Date</th>
                                <th>Livraison Prévue</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commandes as $cmd)
                            <tr>
                                <td><code>{{ $cmd->numero_commande }}</code></td>
                                <td>
                                    <strong>{{ $cmd->fournisseur->nom }}</strong>
                                    @if($cmd->fournisseur->telephone)
                                        <br><small class="text-muted">{{ $cmd->fournisseur->telephone }}</small>
                                    @endif
                                </td>
                                <td>{{ $cmd->date_commande->format('d/m/Y') }}</td>
                                <td>
                                    @if($cmd->date_livraison_prevue)
                                        {{ $cmd->date_livraison_prevue->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td><strong>${{ number_format($cmd->montant_final, 2) }}</strong></td>
                                <td>
                                    <span class="badge bg-{{ $cmd->getStatutClass() }}">
                                        {{ $cmd->getStatutLabel() }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.pharmacie.commandes.show', $cmd->id) }}" class="btn btn-sm btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($cmd->peutEtreValidee())
                                        <button class="btn btn-sm btn-success" onclick="validerCommande({{ $cmd->id }})" title="Valider">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    @if($cmd->peutEtreReceptionnee())
                                        <button class="btn btn-sm btn-primary" onclick="receptionnerCommande({{ $cmd->id }})" title="Réceptionner">
                                            <i class="fas fa-truck-loading"></i>
                                        </button>
                                    @endif
                                    @if($cmd->peutEtreModifiee())
                                        <button class="btn btn-sm btn-danger" onclick="annulerCommande({{ $cmd->id }})" title="Annuler">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $commandes->links() }}
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucune commande trouvée.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Nouvelle Commande -->
<div class="modal fade" id="nouvelleCommandeModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-shopping-cart me-2"></i>Nouvelle Commande</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="nouvelleCommandeForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fournisseur <span class="text-danger">*</span></label>
                            <select name="fournisseur_id" id="fournisseur_id" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach($fournisseurs as $four)
                                    <option value="{{ $four->id }}" data-delai="{{ $four->delai_livraison_jours }}">
                                        {{ $four->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Date Commande <span class="text-danger">*</span></label>
                            <input type="date" name="date_commande" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Livraison Prévue</label>
                            <input type="date" name="date_livraison_prevue" id="date_livraison_prevue" class="form-control">
                        </div>
                    </div>

                    <hr>
                    <h6>Médicaments à Commander</h6>
                    
                    <div id="lignesCommande">
                        <div class="ligne-commande mb-3 border p-3 rounded">
                            <div class="row">
                                <div class="col-md-5">
                                    <label class="form-label">Médicament <span class="text-danger">*</span></label>
                                    <select name="lignes[0][medicament_id]" class="form-select medicament-select" required>
                                        <option value="">-- Sélectionner --</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Quantité <span class="text-danger">*</span></label>
                                    <input type="number" name="lignes[0][quantite]" class="form-control quantite-input" min="1" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Prix Unitaire <span class="text-danger">*</span></label>
                                    <input type="number" name="lignes[0][prix_unitaire]" class="form-control prix-input" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger w-100" onclick="retirerLigne(this)" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <small class="text-muted">Montant ligne: <strong class="montant-ligne">$0.00</strong></small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-success btn-sm" onclick="ajouterLigne()">
                        <i class="fas fa-plus me-2"></i>Ajouter un médicament
                    </button>

                    <hr>
                    <div class="row">
                        <div class="col-md-8">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-info mb-0">
                                <strong>Montant Total:</strong><br>
                                <h3 class="mb-0" id="montantTotal">$0.00</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Créer la Commande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let ligneIndex = 1;
let medicaments = [];

// Charger les médicaments
fetch('{{ route("admin.pharmacie.medicaments.index") }}')
    .then(response => response.text())
    .then(() => {
        // Charger via une requête simple
        loadMedicaments();
    });

function loadMedicaments() {
    // Pour l'instant, on va utiliser un système simple
    const select = document.querySelector('.medicament-select');
    // Les médicaments seront chargés dynamiquement plus tard
}

// Calculer le délai de livraison automatiquement
document.getElementById('fournisseur_id').addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    const delai = parseInt(option.getAttribute('data-delai')) || 7;
    const dateCommande = new Date(document.querySelector('[name="date_commande"]').value);
    dateCommande.setDate(dateCommande.getDate() + delai);
    document.getElementById('date_livraison_prevue').value = dateCommande.toISOString().split('T')[0];
});

// Calculer le montant d'une ligne
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('quantite-input') || e.target.classList.contains('prix-input')) {
        const ligne = e.target.closest('.ligne-commande');
        const quantite = parseFloat(ligne.querySelector('.quantite-input').value) || 0;
        const prix = parseFloat(ligne.querySelector('.prix-input').value) || 0;
        const montant = quantite * prix;
        
        ligne.querySelector('.montant-ligne').textContent = '$' + montant.toFixed(2);
        calculerMontantTotal();
    }
});

function calculerMontantTotal() {
    let total = 0;
    document.querySelectorAll('.ligne-commande').forEach(ligne => {
        const quantite = parseFloat(ligne.querySelector('.quantite-input').value) || 0;
        const prix = parseFloat(ligne.querySelector('.prix-input').value) || 0;
        total += quantite * prix;
    });
    document.getElementById('montantTotal').textContent = '$' + total.toFixed(2);
}

function ajouterLigne() {
    const container = document.getElementById('lignesCommande');
    const nouvelleLigne = document.querySelector('.ligne-commande').cloneNode(true);
    
    // Mettre à jour les noms
    nouvelleLigne.querySelectorAll('[name^="lignes[0]"]').forEach(input => {
        input.name = input.name.replace('[0]', '[' + ligneIndex + ']');
        input.value = '';
    });
    
    // Activer le bouton supprimer
    nouvelleLigne.querySelector('.btn-danger').disabled = false;
    
    container.appendChild(nouvelleLigne);
    ligneIndex++;
}

function retirerLigne(btn) {
    btn.closest('.ligne-commande').remove();
    calculerMontantTotal();
}

// Soumettre la commande
document.getElementById('nouvelleCommandeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("admin.pharmacie.commandes.store") }}', {
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
            alert('Commande créée avec succès !');
            window.location.reload();
        } else {
            alert('Erreur : ' + (data.message || 'Une erreur est survenue'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la création de la commande');
    });
});

function validerCommande(id) {
    if (confirm('Êtes-vous sûr de vouloir valider cette commande ?')) {
        fetch(`{{ url('admin/pharmacie/commandes') }}/${id}/valider`, {
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

function receptionnerCommande(id) {
    window.location.href = `{{ url('admin/pharmacie/commandes') }}/${id}`;
}

function annulerCommande(id) {
    if (confirm('Êtes-vous sûr de vouloir annuler cette commande ?')) {
        fetch(`{{ url('admin/pharmacie/commandes') }}/${id}/annuler`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Commande annulée avec succès !');
                window.location.reload();
            } else {
                alert('Erreur : ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'annulation');
        });
    }
}
</script>
@endsection
