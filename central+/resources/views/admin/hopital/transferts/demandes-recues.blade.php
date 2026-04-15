@extends('layouts.admin')
@section('title', 'Demandes Reçues')
@section('page-title', 'Demandes de Transfert Reçues')
@section('content')
<div class="container-fluid py-4">
    <div class="row mb-3">
        <div class="col-md-6"><div class="alert alert-warning">En Attente: {{ $stats['en_attente'] }}</div></div>
        <div class="col-md-6"><div class="alert alert-info">Acceptées: {{ $stats['accepte'] }}</div></div>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card">
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr><th>Patient</th><th>Hôpital Demandeur</th><th>Motif</th><th>Statut</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($demandes as $demande)
                        <tr>
                            <td>{{ $demande->patient->nom }}</td>
                            <td>{{ $demande->hopitalDemandeur->nom }}</td>
                            <td>{{ \Str::limit($demande->motif_demande, 50) }}</td>
                            <td><span class="badge bg-{{ $demande->statut_color }}">{{ $demande->statut_format }}</span></td>
                            <td>
                                @if($demande->statut == 'en_attente_patient')
                                    <span class="badge bg-warning">En attente du patient</span>
                                @elseif($demande->statut == 'accepte_patient')
                                    <button class="btn btn-sm btn-success" onclick="confirmerTransfert({{ $demande->id }}, '{{ $demande->patient->nom }}')">
                                        <i class="fas fa-check"></i> Transférer
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="refuserTransfert({{ $demande->id }})">
                                        <i class="fas fa-times"></i> Refuser
                                    </button>
                                @elseif($demande->statut == 'transfere')
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Transféré</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $demandes->links() }}
</div>

<script>
function confirmerTransfert(demandeId, patientNom) {
    if (confirm(`Confirmer le transfert du dossier de ${patientNom} ?`)) {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        fetch(`/admin/hopital/transferts/${demandeId}/transferer`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success !== false) {
                alert('Dossier transféré avec succès !');
                location.reload();
            } else {
                alert(data.message || 'Erreur');
            }
        })
        .catch(() => location.reload());
    }
}

function refuserTransfert(demandeId) {
    const notes = prompt('Raison du refus (optionnel):');
    
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    if (notes) formData.append('notes_detenteur', notes);
    
    fetch(`/admin/hopital/transferts/${demandeId}/refuser`, {
        method: 'POST',
        body: formData
    })
    .then(() => {
        alert('Demande refusée');
        location.reload();
    });
}
</script>
@endsection

