@extends('layouts.admin')

@section('title', 'Demandes de Sang')
@section('page-title', 'Demandes de Sang')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-file-medical me-2"></i>Demandes de Sang</h2>
    </div>

    <div class="card">
        <div class="card-body">
            @if($demandes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>N° Demande</th>
                                <th>Hôpital</th>
                                <th>Patient</th>
                                <th>Groupe</th>
                                <th>Quantité</th>
                                <th>Date Besoin</th>
                                <th>Urgence</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($demandes as $dem)
                            <tr>
                                <td><code>{{ $dem->numero_demande }}</code></td>
                                <td>{{ $dem->hopital->nom }}</td>
                                <td>{{ $dem->nom_patient ?? '-' }}</td>
                                <td><span class="badge bg-danger fs-6">{{ $dem->groupe_sanguin }}</span></td>
                                <td><strong>{{ $dem->quantite_demandee }}L</strong></td>
                                <td>{{ $dem->date_besoin->format('d/m/Y') }}</td>
                                <td><span class="badge bg-{{ $dem->getUrgenceClass() }}">{{ ucfirst($dem->urgence) }}</span></td>
                                <td><span class="badge bg-info">{{ ucfirst($dem->statut) }}</span></td>
                                <td>
                                    @if($dem->statut === 'en_attente')
                                        <button class="btn btn-sm btn-success" onclick="traiterDemande({{ $dem->id }})">
                                            <i class="fas fa-check"></i> Traiter
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $demandes->links() }}
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucune demande enregistrée.
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function traiterDemande(id) {
    const quantite = prompt('Quantité à fournir (en litres):');
    if (quantite) {
        fetch(`{{ url('admin/banque-sang/demandes') }}/${id}/traiter`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify({ quantite_fournie: quantite })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Demande traitée avec succès !');
                window.location.reload();
            } else {
                alert('Erreur : ' + data.message);
            }
        });
    }
}
</script>
@endsection
