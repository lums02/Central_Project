@extends('layouts.admin')

@section('title', 'Mes Examens')
@section('page-title', 'Mes Examens')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-flask text-info me-2"></i>Mes Examens Médicaux
        </h1>
        <div class="text-muted">
            <i class="fas fa-flask me-1"></i>{{ $examens->total() }} examen(s)
        </div>
    </div>

    <div class="row">
        @forelse($examens as $examen)
        <div class="col-12 mb-3">
            <div class="card border-left-info shadow">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Informations principales -->
                        <div class="d-flex align-items-center flex-grow-1">
                            <div class="me-4">
                                <div class="text-xs text-uppercase text-muted mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    Nom de l'examen
                                </div>
                                <div class="font-weight-bold text-info">
                                    <i class="fas fa-flask me-1"></i>{{ $examen->nom_examen }}
                                </div>
                            </div>
                            
                            <div class="me-4">
                                <div class="text-xs text-uppercase text-muted mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    Type
                                </div>
                                <div class="font-weight-bold text-dark">
                                    <i class="fas fa-tag text-primary me-1"></i>
                                    {{ $examen->type_examen }}
                                </div>
                            </div>
                            
                            <div class="me-4">
                                <div class="text-xs text-uppercase text-muted mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    Numéro
                                </div>
                                <div class="font-weight-bold text-dark">
                                    <i class="fas fa-hashtag text-secondary me-1"></i>
                                    {{ $examen->numero_examen }}
                                </div>
                            </div>
                            
                            <div class="me-4">
                                <div class="text-xs text-uppercase text-muted mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    Prescrit par
                                </div>
                                <div class="font-weight-bold text-dark">
                                    <i class="fas fa-user-md text-success me-1"></i>
                                    Dr. {{ $examen->medecin->nom }}
                                </div>
                            </div>
                            
                            <div class="me-4">
                                <div class="text-xs text-uppercase text-muted mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    Date
                                </div>
                                <div class="font-weight-bold text-dark">
                                    <i class="fas fa-calendar-alt text-warning me-1"></i>
                                    {{ \Carbon\Carbon::parse($examen->date_prescription)->format('d/m/Y') }}
                                </div>
                            </div>
                            
                            @if($examen->indication)
                            <div class="flex-grow-1">
                                <div class="text-xs text-uppercase text-muted mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    Indication
                                </div>
                                <div class="text-muted small">
                                    {{ Str::limit($examen->indication, 50) }}
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Statut et Actions -->
                        <div class="d-flex align-items-center ms-3">
                            <span class="badge bg-{{ $examen->statut_examen === 'termine' ? 'success' : ($examen->statut_examen === 'en_cours' ? 'warning' : 'secondary') }} me-3">
                                {{ ucfirst(str_replace('_', ' ', $examen->statut_examen)) }}
                            </span>
                            
                            @if($examen->statut_examen === 'termine')
                                @if($examen->resultats_fichier)
                                <a href="{{ asset('storage/' . $examen->resultats_fichier) }}" target="_blank" class="btn btn-success btn-sm">
                                    <i class="fas fa-download me-1"></i>Télécharger
                                </a>
                                @else
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#resultatModal{{ $examen->id }}">
                                    <i class="fas fa-eye me-1"></i>Voir Résultats
                                </button>
                                @endif
                            @endif
                        </div>
                    </div>
                    
                    <!-- Résultats en ligne si disponibles -->
                    @if($examen->statut_examen === 'termine' && $examen->resultats_texte)
                    <div class="mt-3 pt-3 border-top">
                        <div class="alert alert-success mb-0 py-2">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                <div class="flex-grow-1">
                                    <strong class="d-block mb-1">Résultats :</strong>
                                    <p class="mb-0 small" style="white-space: pre-line;">{{ Str::limit($examen->resultats_texte, 150) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Modal pour voir les résultats complets -->
        @if($examen->statut_examen === 'termine' && $examen->resultats_texte)
        <div class="modal fade" id="resultatModal{{ $examen->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-check-circle me-2"></i>Résultats - {{ $examen->nom_examen }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <strong>Type d'examen :</strong> {{ $examen->type_examen }}<br>
                            <strong>Numéro :</strong> {{ $examen->numero_examen }}<br>
                            <strong>Date :</strong> {{ \Carbon\Carbon::parse($examen->date_prescription)->format('d/m/Y') }}
                        </div>
                        <hr>
                        <h6 class="text-success"><i class="fas fa-clipboard-check me-2"></i>Résultats</h6>
                        <p style="white-space: pre-line;">{{ $examen->resultats_texte }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        @empty
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-flask fa-4x mb-3" style="opacity: 0.2; color: #36b9cc;"></i>
                    <h5 class="text-gray-800 mb-2">Aucun examen prescrit</h5>
                    <p class="text-muted mb-0">Vous n'avez pas encore d'examen médical prescrit.</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    @if($examens->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $examens->links() }}
    </div>
    @endif
</div>
@endsection

