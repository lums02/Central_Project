@extends('layouts.admin')

@section('page-title', 'Examens à Réaliser')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-flask me-2"></i>Examens à Réaliser</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N° Examen</th>
                            <th>Patient</th>
                            <th>Examen</th>
                            <th>Indication</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examens as $examen)
                        <tr>
                            <td><span class="badge bg-info">{{ $examen->numero_examen }}</span></td>
                            <td>{{ $examen->patient->nom }}</td>
                            <td>
                                <strong>{{ $examen->nom_examen }}</strong>
                                <br><small class="text-muted">{{ ucfirst($examen->type_examen) }}</small>
                            </td>
                            <td>{{ Str::limit($examen->indication, 50) }}</td>
                            <td>{{ $examen->date_prescription->format('d/m/Y') }}</td>
                            <td>
                                @if($examen->statut_examen == 'paye')
                                    <span class="badge bg-warning">En attente</span>
                                @elseif($examen->statut_examen == 'en_cours')
                                    <span class="badge bg-info">En cours</span>
                                @else
                                    <span class="badge bg-success">Terminé</span>
                                @endif
                            </td>
                            <td>
                                @if($examen->statut_examen == 'paye')
                                    <button class="btn btn-info btn-sm" onclick="marquerEnCours({{ $examen->id }})">
                                        <i class="fas fa-play"></i> Commencer
                                    </button>
                                @endif
                                @if($examen->statut_examen != 'termine')
                                    <button class="btn btn-success btn-sm" onclick="uploaderResultats({{ $examen->id }}, '{{ $examen->patient->nom }}', '{{ $examen->nom_examen }}')">
                                        <i class="fas fa-upload"></i> Résultats
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-flask fa-3x mb-3"></i>
                                <p>Aucun examen à réaliser</p>
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

<!-- Modal Upload Résultats -->
<div class="modal fade" id="uploadResultatsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-upload me-2"></i>Uploader les Résultats</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadResultatsForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="examenId" name="examen_id">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Patient :</strong> <span id="uploadPatientNom"></span><br>
                        <strong>Examen :</strong> <span id="uploadExamenNom"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Résultats <span class="text-danger">*</span></label>
                        <textarea name="resultats" class="form-control" rows="5" required placeholder="Résultats détaillés de l'examen..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Interprétation</label>
                        <textarea name="interpretation" class="form-control" rows="3" placeholder="Interprétation des résultats..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fichier (PDF, Image)</label>
                        <input type="file" name="fichier" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">Max 5MB - PDF, JPG, PNG</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane me-2"></i>Envoyer les Résultats
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function marquerEnCours(examenId) {
    fetch(`/admin/laborantin/examens/${examenId}/marquer-en-cours`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Examen marqué comme en cours');
            location.reload();
        }
    });
}

function uploaderResultats(examenId, patientNom, examenNom) {
    document.getElementById('examenId').value = examenId;
    document.getElementById('uploadPatientNom').textContent = patientNom;
    document.getElementById('uploadExamenNom').textContent = examenNom;
    new bootstrap.Modal(document.getElementById('uploadResultatsModal')).show();
}

document.getElementById('uploadResultatsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const examenId = document.getElementById('examenId').value;
    
    fetch(`/admin/laborantin/examens/${examenId}/uploader-resultats`, {
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

