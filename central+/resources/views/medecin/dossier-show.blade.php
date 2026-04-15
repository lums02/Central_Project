@extends('layouts.admin')

@section('title', 'Dossier Médical - ' . $dossier->numero_dossier)
@section('page-title', 'Dossier Médical')

@section('content')
<div class="container-fluid">
    <!-- Messages de succès/erreur -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <strong>Succès !</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Erreur !</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Bouton retour -->
    <div class="mb-3">
        <a href="{{ route('admin.medecin.dossiers') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour aux Dossiers
        </a>
    </div>

    <!-- En-tête du dossier -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1"><i class="fas fa-file-medical me-2"></i>{{ $dossier->numero_dossier }}</h5>
                    <small>Patient: {{ $dossier->patient->nom }} • Date: {{ $dossier->date_consultation->format('d/m/Y') }}</small>
                </div>
                <span class="badge bg-light text-dark">{{ ucfirst($dossier->statut) }}</span>
            </div>
        </div>
        <div class="card-body">
            <div class="btn-group" role="group">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#prescrireExamensModal">
                    <i class="fas fa-flask me-1"></i>Prescrire Examens
                </button>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ajouterTraitementModal">
                    <i class="fas fa-pills me-1"></i>Ajouter Traitement
                </button>
                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#ajouterConsultationModal">
                    <i class="fas fa-plus me-1"></i>Nouvelle Consultation
                </button>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modifierDossierModal">
                    <i class="fas fa-edit me-1"></i>Modifier
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Colonne principale -->
        <div class="col-lg-9">
            <!-- Anamnèse -->
            @if($dossier->motif_consultation)
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Anamnèse</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0" style="white-space: pre-line;">{{ $dossier->motif_consultation }}</p>
                </div>
            </div>
            @endif

            <!-- Antécédents -->
            @if($dossier->antecedents)
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-history me-2"></i>Antécédents</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0" style="white-space: pre-line;">{{ $dossier->antecedents }}</p>
                </div>
            </div>
            @endif

            <!-- Examen Clinique -->
            @if($dossier->examen_clinique)
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-stethoscope me-2"></i>Examen Clinique</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0" style="white-space: pre-line;">{{ $dossier->examen_clinique }}</p>
                </div>
            </div>
            @endif

            <!-- Diagnostic -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-diagnoses me-2"></i>Diagnostic</h6>
                </div>
                <div class="card-body">
                    @if(!str_contains($dossier->diagnostic, 'DIAGNOSTIC FINAL'))
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Hypothèse diagnostique</strong> - En attente de confirmation
                    </div>
                    @endif
                    <p class="mb-0" style="white-space: pre-line;">{{ $dossier->diagnostic }}</p>
                </div>
            </div>

            <!-- Examens -->
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-flask me-2"></i>Examens Complémentaires</h6>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#prescrireExamensModal">
                        <i class="fas fa-plus me-1"></i>Prescrire
                    </button>
                </div>
                <div class="card-body">
                    @php
                        $examens = \App\Models\ExamenPrescrit::where('dossier_medical_id', $dossier->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
                    @endphp

                    @forelse($examens as $examen)
                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="d-flex justify-content-between mb-2">
                            <h6 class="mb-0">{{ $examen->nom_examen }}</h6>
                            @if($examen->statut_examen === 'termine')
                                <span class="badge bg-success">Terminé</span>
                            @elseif($examen->statut_examen === 'en_cours')
                                <span class="badge bg-info">En cours</span>
                            @elseif($examen->statut_examen === 'paye')
                                <span class="badge bg-warning">Payé</span>
                            @else
                                <span class="badge bg-secondary">Prescrit</span>
                            @endif
                        </div>
                        <small class="text-muted d-block mb-2">
                            {{ $examen->type_examen }} • {{ $examen->numero_examen }}
                        </small>
                        
                        @if($examen->indication)
                        <p class="mb-2"><strong>Indication :</strong> {{ $examen->indication }}</p>
                        @endif

                        <!-- Résultats -->
                        @if($examen->statut_examen === 'termine' && $examen->resultats)
                        <div class="alert alert-success mt-3 mb-0">
                            <h6 class="alert-heading"><i class="fas fa-check-circle me-2"></i>Résultats</h6>
                            <p class="mb-2" style="white-space: pre-line;">{{ $examen->resultats }}</p>
                            
                            @if($examen->interpretation)
                            <p class="mb-2"><strong>Interprétation :</strong></p>
                            <p class="mb-2" style="white-space: pre-line;">{{ $examen->interpretation }}</p>
                            @endif
                            
                            @if($examen->fichier_resultat)
                            <a href="{{ asset('storage/' . $examen->fichier_resultat) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-file-pdf me-1"></i>Télécharger le fichier
                            </a>
                            @endif
                            
                            <hr>
                            <small class="text-muted mb-0">
                                <i class="fas fa-clock me-1"></i>Résultats reçus le {{ $examen->updated_at->format('d/m/Y à H:i') }}
                            </small>
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-flask fa-2x mb-2"></i>
                        <p class="mb-0">Aucun examen prescrit</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Traitement -->
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-pills me-2"></i>Traitement</h6>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#ajouterTraitementModal">
                        <i class="fas fa-plus me-1"></i>Ajouter
                    </button>
                </div>
                <div class="card-body">
                    @if($dossier->traitement && $dossier->traitement !== 'En attente des résultats d\'examens')
                        <p class="mb-0" style="white-space: pre-line;">{{ $dossier->traitement }}</p>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            En attente des résultats d'examens
                        </div>
                    @endif
                </div>
            </div>

            <!-- Observations -->
            @if($dossier->observations)
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-notes-medical me-2"></i>Observations et Consultations</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0" style="white-space: pre-line;">{{ $dossier->observations }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Patient -->
        <div class="col-lg-3">
            <!-- Patient -->
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Patient</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div style="width: 80px; height: 80px; border-radius: 50%; background: #e9ecef; color: #495057; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: bold; margin: 0 auto;">
                            {{ substr($dossier->patient->nom, 0, 1) }}
                        </div>
                    </div>
                    <h6 class="mb-3">{{ $dossier->patient->nom }}</h6>
                    
                    <div class="text-start">
                        <p class="mb-2">
                            <i class="fas fa-envelope text-muted me-2"></i>
                            <small>{{ $dossier->patient->email }}</small>
                        </p>
                        @if($dossier->patient->telephone)
                        <p class="mb-2">
                            <i class="fas fa-phone text-muted me-2"></i>
                            <small>{{ $dossier->patient->telephone }}</small>
                        </p>
                        @endif
                        @if($dossier->patient->date_naissance)
                        <p class="mb-2">
                            <i class="fas fa-birthday-cake text-muted me-2"></i>
                            <small>{{ \Carbon\Carbon::parse($dossier->patient->date_naissance)->age }} ans</small>
                        </p>
                        @endif
                        @if($dossier->patient->sexe)
                        <p class="mb-2">
                            <i class="fas fa-venus-mars text-muted me-2"></i>
                            <small>{{ ucfirst($dossier->patient->sexe) }}</small>
                        </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistiques</h6>
                </div>
                <div class="card-body">
                    @php
                        $examens = \App\Models\ExamenPrescrit::where('dossier_medical_id', $dossier->id)->get();
                    @endphp
                    <div class="d-flex justify-content-between mb-2">
                        <small>Examens prescrits</small>
                        <span class="badge bg-primary">{{ $examens->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <small>Examens terminés</small>
                        <span class="badge bg-success">{{ $examens->where('statut_examen', 'termine')->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small>En attente</small>
                        <span class="badge bg-warning">{{ $examens->whereIn('statut_examen', ['prescrit', 'paye', 'en_cours'])->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Informations -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <small class="text-muted">Créé le</small><br>
                        <strong>{{ $dossier->created_at->format('d/m/Y H:i') }}</strong>
                    </p>
                    @if($dossier->date_prochain_rdv)
                    <p class="mb-2">
                        <small class="text-muted">Prochain RDV</small><br>
                        <strong>{{ $dossier->date_prochain_rdv->format('d/m/Y') }}</strong>
                    </p>
                    @endif
                    @if($dossier->urgence)
                    <p class="mb-0">
                        <small class="text-muted">Urgence</small><br>
                        <span class="badge bg-{{ $dossier->urgence === 'tres_urgente' ? 'danger' : ($dossier->urgence === 'urgente' ? 'warning' : 'secondary') }}">
                            {{ ucfirst(str_replace('_', ' ', $dossier->urgence)) }}
                        </span>
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Prescrire des Examens -->
<div class="modal fade" id="prescrireExamensModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-flask me-2"></i>Prescrire des Examens</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.medecin.examens.prescrire', $dossier->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div id="examensContainer">
                        <div class="border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Type <span class="text-danger">*</span></label>
                                    <select name="examens[0][type]" class="form-select" required>
                                        <option value="">Sélectionner...</option>
                                        <option value="Biologie">Biologie</option>
                                        <option value="Imagerie">Imagerie</option>
                                        <option value="Cardiologie">Cardiologie</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" name="examens[0][nom]" class="form-control" required placeholder="Ex: NFS, Radiographie">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Indication</label>
                                    <textarea name="examens[0][indication]" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="ajouterExamen()">
                        <i class="fas fa-plus me-1"></i>Ajouter un examen
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Envoyer au Caissier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Ajouter Traitement -->
<div class="modal fade" id="ajouterTraitementModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-pills me-2"></i>Ajouter un Traitement</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.medecin.dossier.update', $dossier->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Diagnostic Final Confirmé</label>
                        <textarea name="diagnostic_final" class="form-control" rows="2" placeholder="Ex: Appendicite aiguë confirmée"></textarea>
                        <small class="text-muted">Laissez vide pour garder le diagnostic initial</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Traitement <span class="text-danger">*</span></label>
                        <textarea name="traitement" class="form-control" rows="5" required placeholder="Ex:&#10;1. Paracétamol 500mg: 1cp x 3/jour - 7 jours&#10;2. Amoxicilline 1g: 1cp x 2/jour - 7 jours"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Soins et Procédures</label>
                        <textarea name="soins" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Recommandations</label>
                        <textarea name="recommandations" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Nouvelle Consultation -->
<div class="modal fade" id="ajouterConsultationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Nouvelle Consultation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.medecin.dossier.update', $dossier->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date_consultation" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type</label>
                            <select name="type_consultation" class="form-select">
                                <option value="suivi">Suivi</option>
                                <option value="controle">Contrôle</option>
                                <option value="urgence">Urgence</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Motif <span class="text-danger">*</span></label>
                        <textarea name="motif" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Évolution <span class="text-danger">*</span></label>
                        <textarea name="evolution" class="form-control" rows="3" required placeholder="Comment le patient évolue..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nouveaux Symptômes</label>
                        <textarea name="nouveaux_symptomes" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Examen Clinique</label>
                        <textarea name="examen_clinique_suivi" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ajustement Traitement</label>
                        <textarea name="ajustement_traitement" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-info">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Modifier -->
<div class="modal fade" id="modifierDossierModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Modifier le Dossier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.medecin.dossier.update', $dossier->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Diagnostic</label>
                        <textarea name="diagnostic" class="form-control" rows="3">{{ $dossier->diagnostic }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Traitement</label>
                        <textarea name="traitement" class="form-control" rows="4">{{ $dossier->traitement }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observations</label>
                        <textarea name="observations" class="form-control" rows="3">{{ $dossier->observations }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select name="statut" class="form-select">
                            <option value="actif" {{ $dossier->statut === 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="archive" {{ $dossier->statut === 'archive' ? 'selected' : '' }}>Archivé</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let examenCount = 1;

function ajouterExamen() {
    const container = document.getElementById('examensContainer');
    const newExamen = `
        <div class="border rounded p-3 mb-3">
            <div class="d-flex justify-content-between mb-2">
                <strong>Examen ${examenCount + 1}</strong>
                <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.border').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <label class="form-label">Type <span class="text-danger">*</span></label>
                    <select name="examens[${examenCount}][type]" class="form-select" required>
                        <option value="">Sélectionner...</option>
                        <option value="Biologie">Biologie</option>
                        <option value="Imagerie">Imagerie</option>
                        <option value="Cardiologie">Cardiologie</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>
                <div class="col-md-8 mb-2">
                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" name="examens[${examenCount}][nom]" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Indication</label>
                    <textarea name="examens[${examenCount}][indication]" class="form-control" rows="2"></textarea>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newExamen);
    examenCount++;
}
</script>
@endsection
