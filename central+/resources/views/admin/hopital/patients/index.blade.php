@extends('layouts.admin')

@section('title', 'Gestion des Patients')
@section('page-title', 'Gestion des Patients')

@section('content')
<div class="container-fluid py-4">
    <!-- Boutons Actions -->
    <div class="row mb-3">
        <div class="col-12 text-end">
            <button type="button" class="btn btn-info me-2" data-bs-toggle="modal" data-bs-target="#rechercherDossierModal">
                <i class="fas fa-search"></i> Demander un Dossier Externe
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nouveauPatientModal">
                <i class="fas fa-plus"></i> Nouveau Patient
            </button>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon bg-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $stats['total_patients'] }}</h3>
                    <p>Total Patients</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon bg-success">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $stats['patients_actifs'] }}</h3>
                    <p>Patients Actifs</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon bg-info">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $stats['nouveaux_patients'] }}</h3>
                    <p>Nouveaux (7 jours)</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon bg-warning">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $stats['total_dossiers'] }}</h3>
                    <p>Dossiers Médicaux</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.hopital.patients.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Rechercher</label>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search" 
                           placeholder="Nom, email, téléphone..."
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <label for="sexe" class="form-label">Sexe</label>
                    <select class="form-select" id="sexe" name="sexe">
                        <option value="">Tous</option>
                        <option value="masculin" {{ request('sexe') == 'masculin' ? 'selected' : '' }}>Masculin</option>
                        <option value="feminin" {{ request('sexe') == 'feminin' ? 'selected' : '' }}>Féminin</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="statut" class="form-label">Statut</label>
                    <select class="form-select" id="statut" name="statut">
                        <option value="">Tous</option>
                        <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                        <option value="disabled" {{ request('statut') == 'disabled' ? 'selected' : '' }}>Désactivé</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Liste des patients -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-list"></i> Liste des Patients
                <span class="badge bg-primary ms-2">{{ $patients->total() }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            @if($patients->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">ID</th>
                                <th style="width: 25%;">Patient</th>
                                <th style="width: 20%;">Contact</th>
                                <th style="width: 15%;">Info</th>
                                <th style="width: 10%;">Dossiers</th>
                                <th style="width: 10%;">Statut</th>
                                <th style="width: 15%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patients as $patient)
                                <tr>
                                    <td><strong>#{{ $patient->id }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div style="width: 35px; height: 35px; border-radius: 50%; background: #007bff; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 10px; flex-shrink: 0;">
                                                {{ strtoupper(substr($patient->nom, 0, 1)) }}
                                            </div>
                                            <div style="min-width: 0;">
                                                <strong class="d-block text-truncate">{{ $patient->nom }} {{ $patient->prenom ?? '' }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="d-block text-truncate">{{ $patient->email }}</small>
                                        <small class="d-block text-muted">{{ $patient->telephone ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <small class="d-block">
                                            <i class="fas fa-{{ $patient->sexe == 'masculin' ? 'mars' : 'venus' }} me-1"></i>
                                            {{ ucfirst($patient->sexe ?? 'N/A') }}
                                        </small>
                                        <small class="d-block text-muted">
                                            {{ $patient->date_naissance ? \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') : 'N/A' }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">
                                            {{ $patient->dossiersMedicaux->count() }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($patient->status == 'approved' || $patient->status == 'actif')
                                            <span class="badge bg-success">Actif</span>
                                        @elseif($patient->status == 'pending')
                                            <span class="badge bg-warning">En attente</span>
                                        @else
                                            <span class="badge bg-danger">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.hopital.patients.show', $patient->id) }}" 
                                               class="btn btn-sm btn-primary" 
                                               title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#createDossierModal{{ $patient->id }}"
                                                    title="Dossier">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer bg-white">
                    {{ $patients->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun patient trouvé.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.stats-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 15px;
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stats-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    color: #003366;
}

.stats-content p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

.avatar-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
}

.bg-pink {
    background-color: #e91e63 !important;
}

.table th {
    font-weight: 600;
    color: #003366;
}
</style>

<!-- Modal Nouveau Patient -->
<div class="modal fade" id="nouveauPatientModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.hopital.patients.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-user-plus"></i> Nouveau Patient</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom *</label>
                            <input type="text" name="nom" class="form-control" required placeholder="Nom du patient">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="prenom" class="form-control" placeholder="Prénom du patient">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" required placeholder="patient@email.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="telephone" class="form-control" placeholder="+243 XXX XXX XXX">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de Naissance *</label>
                            <input type="date" name="date_naissance" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sexe *</label>
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
                        <label class="form-label">Médecin Traitant</label>
                        <select name="medecin_id" class="form-select">
                            <option value="">-- Sélectionner un médecin (optionnel) --</option>
                            @php
                                $medecins = \App\Models\Utilisateur::where('type_utilisateur', 'hopital')
                                    ->where('entite_id', auth()->user()->entite_id)
                                    ->where('role', 'medecin')
                                    ->get();
                            @endphp
                            @foreach($medecins as $medecin)
                                <option value="{{ $medecin->id }}">Dr. {{ $medecin->nom }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Le médecin qui prendra en charge ce patient</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                        <input type="password" name="mot_de_passe" class="form-control" required placeholder="Mot de passe pour le compte patient" minlength="6">
                        <small class="text-muted">Le patient utilisera cet email et ce mot de passe pour se connecter (minimum 6 caractères)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Créer le Patient
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Rechercher Dossier Externe -->
<div class="modal fade" id="rechercherDossierModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-search"></i> Rechercher un Patient d'un Autre Hôpital</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Rechercher par nom ou email</label>
                    <div class="input-group">
                        <input type="text" id="searchPatientExterne" class="form-control" placeholder="Nom ou email du patient...">
                        <button class="btn btn-primary" onclick="rechercherPatientExterne()">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                    </div>
                </div>
                
                <div id="resultatsRecherche"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Demander Transfert -->
<div class="modal fade" id="demandeTransfertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="demandeTransfertForm">
                @csrf
                <input type="hidden" id="transfer_patient_id" name="patient_id">
                <input type="hidden" id="transfer_hopital_detenteur_id" name="hopital_detenteur_id">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fas fa-file-export"></i> Demander un Transfert</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Patient:</strong> <span id="transfer_patient_name"></span><br>
                        <strong>Hôpital actuel:</strong> <span id="transfer_hopital_name"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motif de la demande *</label>
                        <textarea name="motif_demande" class="form-control" rows="3" required placeholder="Ex: Patient transféré dans notre hôpital pour traitement..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes additionnelles</label>
                        <textarea name="notes_demandeur" class="form-control" rows="2" placeholder="Notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-paper-plane"></i> Envoyer la Demande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function rechercherPatientExterne() {
    const search = document.getElementById('searchPatientExterne').value;
    
    if (!search || search.length < 3) {
        alert('Veuillez entrer au moins 3 caractères');
        return;
    }
    
    fetch(`/admin/hopital/transferts/rechercher-ajax?search=${encodeURIComponent(search)}`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        afficherResultats(data.patients);
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la recherche');
    });
}

function afficherResultats(patients) {
    const container = document.getElementById('resultatsRecherche');
    
    if (patients.length === 0) {
        container.innerHTML = '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Aucun patient trouvé</div>';
        return;
    }
    
    container.innerHTML = patients.map(patient => `
        <div class="card mb-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="mb-1"><i class="fas fa-user"></i> ${patient.nom}</h6>
                        <small class="text-muted">${patient.email}</small>
                    </div>
                    <div class="col-md-4">
                        <small><i class="fas fa-hospital"></i> <strong>Hôpital:</strong></small><br>
                        <span class="badge bg-info">${patient.hopital_nom}</span>
                    </div>
                    <div class="col-md-2 text-end">
                        <button class="btn btn-sm btn-warning" onclick="demanderTransfert(${patient.id}, '${patient.nom}', ${patient.hopital_id}, '${patient.hopital_nom}')">
                            <i class="fas fa-file-export"></i> Demander
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function demanderTransfert(patientId, patientName, hopitalId, hopitalName) {
    document.getElementById('transfer_patient_id').value = patientId;
    document.getElementById('transfer_hopital_detenteur_id').value = hopitalId;
    document.getElementById('transfer_patient_name').textContent = patientName;
    document.getElementById('transfer_hopital_name').textContent = hopitalName;
    
    // Fermer le modal de recherche
    bootstrap.Modal.getInstance(document.getElementById('rechercherDossierModal')).hide();
    
    // Ouvrir le modal de demande
    new bootstrap.Modal(document.getElementById('demandeTransfertModal')).show();
}

document.getElementById('demandeTransfertForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("admin.hopital.transferts.creer-demande") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Demande de transfert envoyée avec succès !');
            bootstrap.Modal.getInstance(document.getElementById('demandeTransfertModal')).hide();
            this.reset();
        } else {
            alert(data.message || 'Erreur lors de l\'envoi');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'envoi de la demande');
    });
});
</script>
@endsection

