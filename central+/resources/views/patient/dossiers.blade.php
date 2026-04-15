@extends('layouts.admin')

@section('title', 'Mon Dossier Médical')
@section('page-title', 'Mon Dossier Médical')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-folder-open text-primary me-2"></i>Mon Dossier Médical
        </h1>
        <div class="text-muted">
            <i class="fas fa-file-medical me-1"></i>{{ $dossiers->total() }} dossier(s)
        </div>
    </div>

    <div class="row">
        @forelse($dossiers as $dossier)
        <div class="col-12 mb-3">
            <div class="card border-left-primary shadow">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Numéro et Date -->
                        <div class="d-flex align-items-center flex-grow-1">
                            <div class="me-4">
                                <div class="text-xs text-uppercase text-muted mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    Numéro Dossier
                                </div>
                                <div class="font-weight-bold text-primary">
                                    <i class="fas fa-file-medical me-1"></i>{{ $dossier->numero_dossier }}
                                </div>
                            </div>
                            
                            <div class="me-4">
                                <div class="text-xs text-uppercase text-muted mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    Date
                                </div>
                                <div class="font-weight-bold text-dark">
                                    <i class="fas fa-calendar-alt text-primary me-1"></i>
                                    {{ $dossier->date_consultation->format('d/m/Y') }}
                                </div>
                            </div>
                            
                            <div class="me-4">
                                <div class="text-xs text-uppercase text-muted mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    Médecin
                                </div>
                                <div class="font-weight-bold text-dark">
                                    <i class="fas fa-user-md text-success me-1"></i>
                                    Dr. {{ $dossier->medecin->nom }}
                                </div>
                            </div>
                            
                            <div class="me-4">
                                <div class="text-xs text-uppercase text-muted mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    Hôpital
                                </div>
                                <div class="font-weight-bold text-dark">
                                    <i class="fas fa-hospital text-info me-1"></i>
                                    {{ Str::limit($dossier->hopital->nom, 25) }}
                                </div>
                            </div>
                            
                            <div class="flex-grow-1">
                                <div class="text-xs text-uppercase text-muted mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    Diagnostic
                                </div>
                                <div class="text-muted small">
                                    {{ Str::limit($dossier->diagnostic, 60) }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="d-flex align-items-center ms-3">
                            <span class="badge bg-success me-3">{{ ucfirst($dossier->statut) }}</span>
                            <a href="{{ route('patient.dossier.show', $dossier->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>Consulter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-folder-open fa-4x mb-3" style="opacity: 0.2; color: #4e73df;"></i>
                    <h5 class="text-gray-800 mb-2">Aucun dossier médical</h5>
                    <p class="text-muted mb-0">Vous n'avez pas encore de dossier médical enregistré.</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($dossiers->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $dossiers->links() }}
    </div>
    @endif
</div>
@endsection

