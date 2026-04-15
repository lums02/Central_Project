@extends('layouts.admin')

@section('title', 'Gestion des Patients')
@section('page-title', 'Gestion des Patients')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nouveauPatientModal">
            <i class="fas fa-user-plus me-2"></i>Nouveau Patient
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Liste des patients -->
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Liste des Patients ({{ $patients->total() }})
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nom Complet</th>
                            <th>Date Naissance</th>
                            <th>Sexe</th>
                            <th>Téléphone</th>
                            <th>Groupe Sanguin</th>
                            <th>Date Inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                        <tr>
                            <td class="font-weight-bold">{{ $patient->nom }} {{ $patient->prenom ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') }}</td>
                            <td>{{ ucfirst($patient->sexe) }}</td>
                            <td>{{ $patient->telephone }}</td>
                            <td>
                                @if($patient->groupe_sanguin)
                                <span class="badge bg-danger">{{ $patient->groupe_sanguin }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $patient->created_at->format('d/m/Y') }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editPatientModal{{ $patient->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Modal Modifier Patient -->
                        <div class="modal fade" id="editPatientModal{{ $patient->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-edit me-2"></i>Modifier Patient
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.receptionniste.patients.update', $patient->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Nom</label>
                                                    <input type="text" name="nom" class="form-control" value="{{ $patient->nom }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Prénom</label>
                                                    <input type="text" name="prenom" class="form-control" value="{{ $patient->prenom }}">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Téléphone</label>
                                                <input type="tel" name="telephone" class="form-control" value="{{ $patient->telephone }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Adresse</label>
                                                <textarea name="adresse" class="form-control" rows="2" required>{{ $patient->adresse }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Groupe Sanguin</label>
                                                <select name="groupe_sanguin" class="form-control">
                                                    <option value="">-- Non renseigné --</option>
                                                    <option value="A+" {{ $patient->groupe_sanguin == 'A+' ? 'selected' : '' }}>A+</option>
                                                    <option value="A-" {{ $patient->groupe_sanguin == 'A-' ? 'selected' : '' }}>A-</option>
                                                    <option value="B+" {{ $patient->groupe_sanguin == 'B+' ? 'selected' : '' }}>B+</option>
                                                    <option value="B-" {{ $patient->groupe_sanguin == 'B-' ? 'selected' : '' }}>B-</option>
                                                    <option value="AB+" {{ $patient->groupe_sanguin == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                    <option value="AB-" {{ $patient->groupe_sanguin == 'AB-' ? 'selected' : '' }}>AB-</option>
                                                    <option value="O+" {{ $patient->groupe_sanguin == 'O+' ? 'selected' : '' }}>O+</option>
                                                    <option value="O-" {{ $patient->groupe_sanguin == 'O-' ? 'selected' : '' }}>O-</option>
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
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-users fa-3x mb-3 d-block" style="opacity: 0.2;"></i>
                                Aucun patient enregistré
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($patients->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $patients->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Nouveau Patient + Consultation -->
<div class="modal fade" id="nouveauPatientModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>Nouveau Patient + Consultation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.receptionniste.patients.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Informations Patient -->
                    <h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Informations du Patient</h6>
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
                            <label class="form-label">Date de Naissance <span class="text-danger">*</span></label>
                            <input type="date" name="date_naissance" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Sexe <span class="text-danger">*</span></label>
                            <select name="sexe" class="form-control" required>
                                <option value="">-- Sélectionnez --</option>
                                <option value="masculin">Masculin</option>
                                <option value="feminin">Féminin</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Groupe Sanguin</label>
                            <select name="groupe_sanguin" class="form-control">
                                <option value="">-- Non renseigné --</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                            <input type="tel" name="telephone" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Adresse <span class="text-danger">*</span></label>
                            <textarea name="adresse" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mot de Passe <span class="text-danger">*</span></label>
                            <input type="password" name="mot_de_passe" class="form-control" required>
                            <small class="text-muted">Le patient pourra se connecter avec son email et ce mot de passe</small>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Signes Vitaux -->
                    <h6 class="text-primary mb-3"><i class="fas fa-heartbeat me-2"></i>Signes Vitaux</h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Poids (kg)</label>
                            <input type="number" name="poids" class="form-control" step="0.01" placeholder="Ex: 70.5">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Taille (cm)</label>
                            <input type="number" name="taille" class="form-control" step="0.01" placeholder="Ex: 175">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Température (°C)</label>
                            <input type="number" name="temperature" class="form-control" step="0.1" placeholder="Ex: 37.5">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Pouls (bpm)</label>
                            <input type="number" name="frequence_cardiaque" class="form-control" placeholder="Ex: 80">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Tension Artérielle</label>
                            <input type="text" name="tension_arterielle" class="form-control" placeholder="Ex: 120/80">
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Informations de Consultation -->
                    <h6 class="text-primary mb-3"><i class="fas fa-stethoscope me-2"></i>Informations de Consultation</h6>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Motif de Consultation <span class="text-danger">*</span></label>
                            <textarea name="motif_consultation" class="form-control" rows="3" required placeholder="Pourquoi le patient consulte-t-il aujourd'hui ?"></textarea>
                            <small class="text-muted">Notez ce que le patient vous dit (symptômes, plaintes, etc.)</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Médecin <span class="text-danger">*</span></label>
                            <select name="medecin_id" class="form-control" required>
                                <option value="">-- Sélectionnez un médecin --</option>
                                @foreach(\App\Models\Utilisateur::where('entite_id', auth()->user()->entite_id)->where('type_utilisateur', 'hopital')->where('role', 'medecin')->get() as $medecin)
                                    <option value="{{ $medecin->id }}">Dr. {{ $medecin->nom }} {{ $medecin->prenom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Frais de Consultation (FC) <span class="text-danger">*</span></label>
                            <input type="number" name="frais_consultation" class="form-control" required min="0" placeholder="Ex: 5000">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Notes Complémentaires</label>
                            <textarea name="notes_receptionniste" class="form-control" rows="2" placeholder="Observations ou informations supplémentaires..."></textarea>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Information :</strong> Après validation, le patient sera enregistré et devra se rendre à la <strong>caisse</strong> pour payer les frais de consultation avant de consulter le médecin.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save me-2"></i>Enregistrer et Envoyer à la Caisse
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

