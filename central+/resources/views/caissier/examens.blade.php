@extends('layouts.admin')

@section('page-title', 'Examens en Attente de Paiement')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Examens en Attente de Paiement</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N° Examen</th>
                            <th>Patient</th>
                            <th>Médecin</th>
                            <th>Examen</th>
                            <th>Prix</th>
                            <th>Date Prescription</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examens as $examen)
                        <tr>
                            <td><span class="badge bg-info">{{ $examen->numero_examen }}</span></td>
                            <td>{{ $examen->patient->nom }}</td>
                            <td>Dr. {{ $examen->medecin->nom }}</td>
                            <td>
                                <strong>{{ $examen->nom_examen }}</strong>
                                <br><small class="text-muted">{{ ucfirst($examen->type_examen) }}</small>
                            </td>
                            <td>
                                @if($examen->prix > 0)
                                    <strong>${{ number_format($examen->prix, 2) }}</strong>
                                @else
                                    <span class="badge bg-warning">À définir</span>
                                @endif
                            </td>
                            <td>{{ $examen->date_prescription->format('d/m/Y') }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm" onclick="fixerPrix({{ $examen->id }}, '{{ $examen->patient->nom }}', '{{ $examen->nom_examen }}')">
                                    <i class="fas fa-dollar-sign"></i> Fixer Prix & Valider
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>Aucun examen en attente de paiement</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $examens->links() }}
        </div>
    </div>
</div>

<!-- Modal Fixer Prix -->
<div class="modal fade" id="fixerPrixModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-dollar-sign me-2"></i>Fixer le Prix et Valider</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="fixerPrixForm">
                @csrf
                <input type="hidden" id="fixerPrixExamenId">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Patient :</strong> <span id="fixerPrixPatient"></span><br>
                        <strong>Examen :</strong> <span id="fixerPrixExamen"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prix de l'Examen (USD) <span class="text-danger">*</span></label>
                        <input type="number" name="prix" id="fixerPrixMontant" class="form-control" step="0.01" required placeholder="0.00" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mode de Paiement</label>
                        <select name="mode_paiement" class="form-select">
                            <option value="espece">Espèces</option>
                            <option value="carte">Carte Bancaire</option>
                            <option value="mobile">Mobile Money</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Valider le Paiement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function fixerPrix(examenId, patientNom, examenNom) {
    document.getElementById('fixerPrixExamenId').value = examenId;
    document.getElementById('fixerPrixPatient').textContent = patientNom;
    document.getElementById('fixerPrixExamen').textContent = examenNom;
    new bootstrap.Modal(document.getElementById('fixerPrixModal')).show();
}

document.getElementById('fixerPrixForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const examenId = document.getElementById('fixerPrixExamenId').value;
    const formData = new FormData(this);
    
    fetch(`/admin/caissier/examens/${examenId}/valider-paiement`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        }
    });
});
</script>
@endsection

