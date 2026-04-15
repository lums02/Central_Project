@extends('layouts.admin')

@section('title', 'Gestion des Dons')
@section('page-title', 'Gestion des Dons')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-hand-holding-heart me-2"></i>Liste des Dons</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nouveauDonModal">
            <i class="fas fa-plus me-2"></i>Enregistrer un Don
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            @if($dons->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>N° Don</th>
                                <th>Donneur</th>
                                <th>Groupe</th>
                                <th>Date</th>
                                <th>Volume</th>
                                <th>Type</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dons as $don)
                            <tr>
                                <td><code>{{ $don->numero_don }}</code></td>
                                <td>{{ $don->donneur->nom }} {{ $don->donneur->prenom }}</td>
                                <td><span class="badge bg-danger">{{ $don->groupe_sanguin }}</span></td>
                                <td>{{ $don->date_don->format('d/m/Y') }}</td>
                                <td><strong>{{ $don->volume_preleve }}L</strong></td>
                                <td>{{ ucfirst(str_replace('_', ' ', $don->type_don)) }}</td>
                                <td><span class="badge bg-info">{{ ucfirst($don->statut) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $dons->links() }}
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucun don enregistré.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Nouveau Don -->
<div class="modal fade" id="nouveauDonModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-hand-holding-heart me-2"></i>Enregistrer un Don</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="nouveauDonForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Donneur <span class="text-danger">*</span></label>
                        <select name="donneur_id" class="form-select" required>
                            <option value="">-- Sélectionner --</option>
                            @foreach($donneurs as $don)
                                <option value="{{ $don->id }}" {{ !$don->peutDonner() ? 'disabled' : '' }}>
                                    {{ $don->nom }} {{ $don->prenom }} ({{ $don->groupe_sanguin }})
                                    {{ !$don->peutDonner() ? ' - Non éligible' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date du Don <span class="text-danger">*</span></label>
                        <input type="date" name="date_don" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Volume Prélevé (L) <span class="text-danger">*</span></label>
                        <input type="number" name="volume_preleve" class="form-control" step="0.01" min="0.1" max="0.5" value="0.45" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type de Don <span class="text-danger">*</span></label>
                        <select name="type_don" class="form-select" required>
                            <option value="sang_total">Sang Total</option>
                            <option value="plasma">Plasma</option>
                            <option value="plaquettes">Plaquettes</option>
                            <option value="globules_rouges">Globules Rouges</option>
                        </select>
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
document.getElementById('nouveauDonForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    fetch('{{ route("admin.banque-sang.dons.store") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: new FormData(this)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Don enregistré avec succès !');
            window.location.reload();
        } else {
            alert('Erreur : ' + data.message);
        }
    });
});
</script>
@endsection
