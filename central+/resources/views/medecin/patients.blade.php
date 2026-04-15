@extends('layouts.admin')

@section('title', 'Mes Patients')
@section('page-title', 'Mes Patients')

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Mes Patients</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPatientModal">
                        <i class="fas fa-user-plus me-2"></i>Nouveau Patient
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Statut</th>
                                <th>Dossiers</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patients as $patient)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-title bg-primary rounded-circle">
                                                {{ substr($patient->nom, 0, 1) }}
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $patient->nom }}</h6>
                                            <small class="text-muted">Patient</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $patient->email }}</td>
                                <td>{{ $patient->telephone ?? 'Non renseigné' }}</td>
                                <td>
                                    @if($patient->consultations_en_attente > 0)
                                        <span class="badge bg-warning text-dark animate-badge">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $patient->consultations_en_attente }} Consultation(s) en attente
                                        </span>
                                    @else
                                        @php
                                            $dernierDossier = $patient->dossiers()->where('medecin_id', auth()->id())->latest()->first();
                                        @endphp
                                        @if($dernierDossier)
                                            <span class="badge bg-success">Dernière : {{ $dernierDossier->date_consultation->format('d/m/Y') }}</span>
                                        @else
                                            <span class="badge bg-secondary">Aucune consultation</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $patient->dossiers()->where('medecin_id', auth()->id())->count() }} dossier(s)</span>
                                </td>
                                <td>
                                    @if($patient->consultations_en_attente > 0)
                                        @php
                                            $consultation = \App\Models\Consultation::where('patient_id', $patient->id)
                                                ->where('medecin_id', auth()->id())
                                                ->where('statut_paiement', 'paye')
                                                ->whereIn('statut_consultation', ['paye_en_attente', 'en_cours'])
                                                ->first();
                                        @endphp
                                        @if($consultation)
                                            @if($consultation->dossier_medical_id)
                                                {{-- Dossier déjà créé, afficher le lien vers le dossier --}}
                                                <a href="{{ route('admin.medecin.dossier.show', $consultation->dossier_medical_id) }}" class="btn btn-sm btn-success">
                                                    <i class="fas fa-file-medical"></i> Voir le Dossier
                                                </a>
                                            @else
                                                {{-- Pas encore de dossier, afficher le bouton consulter --}}
                                                <a href="{{ route('admin.medecin.consultations.show', $consultation->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-stethoscope"></i> Consulter
                                                </a>
                                            @endif
                                        @endif
                                    @else
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.medecin.dossiers') }}?patient={{ $patient->id }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-file-medical"></i> Dossiers
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-users fa-3x mb-3"></i>
                                        <p>Aucun patient trouvé</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour créer un nouveau patient -->
<div class="modal fade" id="createPatientModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.hopital.patients.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Nouveau Patient</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" required placeholder="Nom du patient">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="prenom" class="form-control" placeholder="Prénom du patient">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required placeholder="patient@email.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="telephone" class="form-control" placeholder="+243 XXX XXX XXX">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de Naissance <span class="text-danger">*</span></label>
                            <input type="date" name="date_naissance" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sexe <span class="text-danger">*</span></label>
                            <select name="sexe" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="masculin">Masculin</option>
                                <option value="feminin">Féminin</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Adresse</label>
                        <textarea name="adresse" class="form-control" rows="2" placeholder="Adresse complète du patient"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                        <input type="password" name="mot_de_passe" class="form-control" required placeholder="Mot de passe pour le compte patient" minlength="6">
                        <small class="text-muted">Le patient pourra se connecter avec cet email et ce mot de passe (minimum 6 caractères)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Créer le Patient
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour créer un nouveau dossier -->
<div class="modal fade" id="createDossierModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau Dossier Médical</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createDossierForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="patient_id" name="patient_id">
                    <div class="mb-3">
                        <label class="form-label">Patient</label>
                        <input type="text" id="patient_name" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date de Consultation <span class="text-danger">*</span></label>
                        <input type="date" name="date_consultation" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Diagnostic <span class="text-danger">*</span></label>
                        <textarea name="diagnostic" class="form-control" rows="3" required placeholder="Décrivez le diagnostic..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Traitement <span class="text-danger">*</span></label>
                        <textarea name="traitement" class="form-control" rows="3" required placeholder="Décrivez le traitement prescrit..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observations</label>
                        <textarea name="observations" class="form-control" rows="2" placeholder="Observations supplémentaires..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer le Dossier</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Création d'un nouveau dossier
function createDossier(patientId, patientName) {
    document.getElementById('patient_id').value = patientId;
    document.getElementById('patient_name').value = patientName;
    new bootstrap.Modal(document.getElementById('createDossierModal')).show();
}

document.getElementById('createDossierForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("admin.medecin.dossier.create") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Dossier médical créé avec succès !');
            location.reload();
        } else {
            alert('Erreur lors de la création du dossier: ' + (data.message || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la création du dossier');
    });
});
</script>

<style>
@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.05);
        opacity: 0.8;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.animate-badge {
    animation: pulse 2s infinite;
}
</style>
@endsection
