@extends('layouts.admin')

@section('title', 'Gestion des Donneurs')
@section('page-title', 'Gestion des Donneurs')

@section('content')
<div class="container-fluid">
    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary">{{ $stats['total'] }}</h3>
                    <p class="mb-0">Total Donneurs</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success">{{ $stats['eligibles'] }}</h3>
                    <p class="mb-0">Éligibles</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info">{{ $stats['dons_mois'] }}</h3>
                    <p class="mb-0">Dons ce Mois</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-friends me-2"></i>Liste des Donneurs</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nouveauDonneurModal">
            <i class="fas fa-plus me-2"></i>Nouveau Donneur
        </button>
    </div>

    <!-- Liste des donneurs -->
    <div class="card">
        <div class="card-body">
            @if($donneurs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>N° Donneur</th>
                                <th>Nom Complet</th>
                                <th>Groupe Sanguin</th>
                                <th>Téléphone</th>
                                <th>Dernier Don</th>
                                <th>Nb Dons</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($donneurs as $don)
                            <tr>
                                <td><code>{{ $don->numero_donneur }}</code></td>
                                <td><strong>{{ $don->nom }} {{ $don->prenom }}</strong></td>
                                <td><span class="badge bg-danger fs-6">{{ $don->groupe_sanguin }}</span></td>
                                <td>{{ $don->telephone }}</td>
                                <td>{{ $don->derniere_date_don ? $don->derniere_date_don->format('d/m/Y') : '-' }}</td>
                                <td><strong>{{ $don->nombre_dons }}</strong></td>
                                <td>
                                    @if($don->eligible && $don->peutDonner())
                                        <span class="badge bg-success">Peut donner</span>
                                    @elseif($don->eligible)
                                        <span class="badge bg-warning">Attente ({{ $don->derniere_date_don->addDays(56)->diffInDays(now()) }} jours)</span>
                                    @else
                                        <span class="badge bg-danger">Non éligible</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $donneurs->links() }}
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucun donneur enregistré.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Nouveau Donneur -->
<div class="modal fade" id="nouveauDonneurModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Nouveau Donneur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="nouveauDonneurForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" name="prenom" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Sexe <span class="text-danger">*</span></label>
                            <select name="sexe" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date Naissance <span class="text-danger">*</span></label>
                            <input type="date" name="date_naissance" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Poids (kg)</label>
                            <input type="number" name="poids" class="form-control" step="0.1" min="50">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Groupe Sanguin <span class="text-danger">*</span></label>
                            <select name="groupe_sanguin" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                <option>A+</option>
                                <option>A-</option>
                                <option>B+</option>
                                <option>B-</option>
                                <option>AB+</option>
                                <option>AB-</option>
                                <option>O+</option>
                                <option>O-</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                            <input type="text" name="telephone" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adresse <span class="text-danger">*</span></label>
                        <textarea name="adresse" class="form-control" rows="2" required></textarea>
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
document.getElementById('nouveauDonneurForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    fetch('{{ route("admin.banque-sang.donneurs.store") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: new FormData(this)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Donneur enregistré avec succès !');
            window.location.reload();
        } else {
            alert('Erreur : ' + data.message);
        }
    });
});
</script>
@endsection
