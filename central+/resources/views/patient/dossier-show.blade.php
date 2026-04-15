@extends('layouts.admin')

@section('title', 'Dossier Médical - ' . $dossier->numero_dossier)
@section('page-title', 'Dossier Médical - ' . $dossier->numero_dossier)

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <a href="{{ route('patient.dossiers') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à Mes Dossiers
        </a>
    </div>

    <!-- En-tête -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-file-medical me-2"></i>{{ $dossier->numero_dossier }}
                <span class="badge bg-light text-dark ms-2">{{ ucfirst($dossier->statut) }}</span>
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <small class="text-muted d-block">Date de consultation</small>
                    <strong>{{ $dossier->date_consultation->format('d/m/Y') }}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Médecin traitant</small>
                    <strong>Dr. {{ $dossier->medecin->nom }}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Hôpital</small>
                    <strong>{{ $dossier->hopital->nom }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Anamnèse -->
            @if($dossier->motif_consultation)
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Motif de Consultation</h6>
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
                    <h6 class="mb-0"><i class="fas fa-history me-2"></i>Antécédents Médicaux</h6>
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
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-diagnoses me-2"></i>Diagnostic</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0" style="white-space: pre-line;">{{ $dossier->diagnostic }}</p>
                </div>
            </div>

            <!-- Examens et Résultats -->
            @if($examens->count() > 0)
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-flask me-2"></i>Examens Prescrits</h6>
                </div>
                <div class="card-body">
                    @foreach($examens as $examen)
                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="d-flex justify-content-between mb-2">
                            <h6 class="mb-0">{{ $examen->nom_examen }}</h6>
                            <span class="badge bg-{{ $examen->statut_examen === 'termine' ? 'success' : 'warning' }}">
                                {{ ucfirst($examen->statut_examen) }}
                            </span>
                        </div>
                        <small class="text-muted d-block mb-2">{{ $examen->type_examen }} • {{ $examen->numero_examen }}</small>
                        
                        @if($examen->indication)
                        <p class="mb-2"><strong>Indication :</strong> {{ $examen->indication }}</p>
                        @endif

                        @if($examen->statut_examen === 'termine' && $examen->resultats)
                        <div class="alert alert-success mt-2 mb-0">
                            <h6 class="alert-heading"><i class="fas fa-check-circle me-2"></i>Résultats</h6>
                            <p class="mb-2" style="white-space: pre-line;">{{ $examen->resultats }}</p>
                            
                            @if($examen->interpretation)
                            <p class="mb-2"><strong>Interprétation :</strong></p>
                            <p class="mb-2" style="white-space: pre-line;">{{ $examen->interpretation }}</p>
                            @endif
                            
                            @if($examen->fichier_resultat)
                            <a href="{{ asset('storage/' . $examen->fichier_resultat) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-file-pdf me-1"></i>Télécharger
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Traitement -->
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-pills me-2"></i>Traitement Prescrit</h6>
                </div>
                <div class="card-body">
                    @if($dossier->traitement && $dossier->traitement !== 'En attente des résultats d\'examens')
                        <p class="mb-0" style="white-space: pre-line;">{{ $dossier->traitement }}</p>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Traitement en attente des résultats d'examens
                        </div>
                    @endif
                </div>
            </div>

            <!-- Observations -->
            @if($dossier->observations)
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-notes-medical me-2"></i>Observations et Suivi</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0" style="white-space: pre-line;">{{ $dossier->observations }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header bg-light">
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

            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistiques</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Examens prescrits</span>
                        <span class="badge bg-primary">{{ $examens->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Examens terminés</span>
                        <span class="badge bg-success">{{ $examens->where('statut_examen', 'termine')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

