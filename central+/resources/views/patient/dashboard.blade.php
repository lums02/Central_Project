@extends('layouts.admin')

@section('title', 'Mon Espace Santé')
@section('page-title', 'Mon Espace Santé')

@section('content')
<div class="container-fluid">
    <!-- Alerte Prochain Rendez-vous -->
    @if($prochainRdv)
    <div class="card shadow-sm mb-4" style="border-left: 4px solid #4e73df;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="text-primary mb-2">
                        <i class="fas fa-calendar-check me-2"></i>Prochain Rendez-vous
                    </h5>
                    <p class="mb-1">
                        <strong class="text-dark">{{ \Carbon\Carbon::parse($prochainRdv->date_rendezvous)->format('d/m/Y') }}</strong> à 
                        <strong class="text-dark">{{ substr($prochainRdv->heure_rendezvous, 0, 5) }}</strong>
                    </p>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-user-md me-1"></i>Dr. {{ $prochainRdv->medecin->nom }}
                        <span class="mx-2">•</span>
                        <i class="fas fa-hospital me-1"></i>{{ $prochainRdv->hopital->nom }}
                    </p>
                    <small class="text-muted">{{ $prochainRdv->motif }}</small>
                </div>
                <a href="{{ route('patient.rendezvous') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm h-100" style="border-left: 4px solid #4e73df; border-radius: 8px; background: white;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px; background: #4e73df;">
                                <i class="fas fa-file-medical text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                Mes Dossiers
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-primary">{{ $stats['total_dossiers'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm h-100" style="border-left: 4px solid #4e73df; border-radius: 8px; background: white;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px; background: #4e73df;">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                RDV à Venir
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-primary">{{ $stats['rendez_vous_a_venir'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm h-100" style="border-left: 4px solid #4e73df; border-radius: 8px; background: white;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px; background: #4e73df;">
                                <i class="fas fa-stethoscope text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                Consultations
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-primary">{{ $stats['total_consultations'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm h-100" style="border-left: 4px solid #4e73df; border-radius: 8px; background: white;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px; background: #4e73df;">
                                <i class="fas fa-flask text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                Examens en Cours
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-primary">{{ $stats['examens_en_attente'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Mon Dossier Médical -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100" style="border: 1px solid #d1e7fd; border-radius: 8px;">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: #f0f7ff; border-bottom: 2px solid #4e73df;">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-folder-open me-2"></i>Mon Dossier Médical
                    </h6>
                    <a href="{{ route('patient.dossiers') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-right me-1"></i>Voir Tout
                    </a>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($dossiers->take(3) as $dossier)
                    <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-2 text-dark">{{ $dossier->numero_dossier }}</h6>
                                <div class="text-muted small mb-2">
                                    <i class="fas fa-user-md me-1"></i>Dr. {{ $dossier->medecin->nom }}
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-calendar me-1"></i>{{ $dossier->date_consultation->format('d/m/Y') }}
                                </div>
                                <p class="mb-0 text-muted small">
                                    <strong>Diagnostic :</strong> {{ Str::limit($dossier->diagnostic, 70) }}
                                </p>
                            </div>
                            <a href="{{ route('patient.dossier.show', $dossier->id) }}" class="btn btn-sm btn-info ms-2">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-folder-open fa-3x mb-3" style="opacity: 0.2;"></i>
                        <p class="mb-0">Aucun dossier médical</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Mes Rendez-vous -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100" style="border: 1px solid #d1e7fd; border-radius: 8px;">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: #f0f7ff; border-bottom: 2px solid #4e73df;">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-alt me-2"></i>Mes Rendez-vous
                    </h6>
                    <a href="{{ route('patient.rendezvous') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-right me-1"></i>Voir Tout
                    </a>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($rendezvous->where('date_rendezvous', '>=', now())->take(3) as $rdv)
                    <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-2 text-dark">
                                    {{ \Carbon\Carbon::parse($rdv->date_rendezvous)->format('d/m/Y') }} à {{ substr($rdv->heure_rendezvous, 0, 5) }}
                                </h6>
                                <div class="text-muted small mb-2">
                                    <i class="fas fa-user-md me-1"></i>Dr. {{ $rdv->medecin->nom }}
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-hospital me-1"></i>{{ $rdv->hopital->nom }}
                                </div>
                                <p class="mb-0 text-muted small">{{ $rdv->motif }}</p>
                            </div>
                            <span class="badge bg-{{ $rdv->statut === 'confirme' ? 'success' : 'warning' }} ms-2">
                                {{ ucfirst($rdv->statut) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-calendar-times fa-3x mb-3" style="opacity: 0.2;"></i>
                        <p class="mb-0">Aucun rendez-vous à venir</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Mes Examens -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100" style="border: 1px solid #d1e7fd; border-radius: 8px;">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: #f0f7ff; border-bottom: 2px solid #4e73df;">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-flask me-2"></i>Mes Examens
                    </h6>
                    <a href="{{ route('patient.examens') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-right me-1"></i>Voir Tout
                    </a>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($examens->take(3) as $examen)
                    <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-2 text-dark">{{ $examen->nom_examen }}</h6>
                                <div class="text-muted small mb-2">
                                    {{ $examen->type_examen }} • {{ $examen->numero_examen }}
                                </div>
                                @if($examen->statut_examen === 'termine')
                                <p class="mb-0 text-success small">
                                    <i class="fas fa-check-circle me-1"></i>Résultats disponibles
                                </p>
                                @endif
                            </div>
                            <span class="badge bg-{{ $examen->statut_examen === 'termine' ? 'success' : 'warning' }} ms-2">
                                {{ ucfirst($examen->statut_examen) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-flask fa-3x mb-3" style="opacity: 0.2;"></i>
                        <p class="mb-0">Aucun examen prescrit</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Services à Proximité -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100" style="border: 1px solid #d1e7fd; border-radius: 8px;">
                <div class="card-header py-3" style="background: #f0f7ff; border-bottom: 2px solid #4e73df;">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-map-marker-alt me-2"></i>Services à Proximité
                    </h6>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    <!-- Pharmacies -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-pills me-2 text-primary"></i>Pharmacies ({{ $pharmacies->count() }})
                        </h6>
                        @forelse($pharmacies->take(2) as $pharmacie)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="flex-grow-1">
                                <strong class="d-block mb-1">{{ $pharmacie->nom }}</strong>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ Str::limit($pharmacie->adresse, 40) }}
                                </small>
                            </div>
                            <a href="{{ route('patient.pharmacies') }}" class="btn btn-sm btn-outline-primary ms-2">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        @empty
                        <p class="text-muted mb-0 small">Aucune pharmacie enregistrée</p>
                        @endforelse
                    </div>

                    <!-- Banques de Sang -->
                    <div>
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-tint me-2 text-primary"></i>Banques de Sang ({{ $banquesSang->count() }})
                        </h6>
                        @forelse($banquesSang->take(2) as $banque)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="flex-grow-1">
                                <strong class="d-block mb-1">{{ $banque->nom }}</strong>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ Str::limit($banque->adresse, 40) }}
                                </small>
                            </div>
                            <a href="{{ route('patient.banques-sang') }}" class="btn btn-sm btn-outline-primary ms-2">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        @empty
                        <p class="text-muted mb-0 small">Aucune banque de sang enregistrée</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
