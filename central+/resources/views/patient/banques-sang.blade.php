@extends('layouts.admin')

@section('title', 'Banques de Sang')
@section('page-title', 'Banques de Sang')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tint text-danger me-2"></i>Banques de Sang
        </h1>
    </div>

    <!-- Formulaire de recherche -->
    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-danger">
                <i class="fas fa-search me-2"></i>Rechercher par Groupe Sanguin
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('patient.banques-sang') }}" method="GET">
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Recherchez les banques de sang qui ont des réserves disponibles pour votre groupe sanguin.
                </div>

                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label class="form-label"><i class="fas fa-tint me-1"></i>Groupe Sanguin</label>
                        <select name="groupe_sanguin" class="form-control" required>
                            <option value="">-- Sélectionnez --</option>
                            <option value="A" {{ isset($groupeSanguin) && $groupeSanguin == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ isset($groupeSanguin) && $groupeSanguin == 'B' ? 'selected' : '' }}>B</option>
                            <option value="AB" {{ isset($groupeSanguin) && $groupeSanguin == 'AB' ? 'selected' : '' }}>AB</option>
                            <option value="O" {{ isset($groupeSanguin) && $groupeSanguin == 'O' ? 'selected' : '' }}>O</option>
                        </select>
                    </div>

                    <div class="col-md-5 mb-3">
                        <label class="form-label"><i class="fas fa-plus-circle me-1"></i>Rhésus</label>
                        <select name="rhesus" class="form-control" required>
                            <option value="">-- Sélectionnez --</option>
                            <option value="+" {{ isset($rhesus) && $rhesus == '+' ? 'selected' : '' }}>Positif (+)</option>
                            <option value="-" {{ isset($rhesus) && $rhesus == '-' ? 'selected' : '' }}>Négatif (-)</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-search me-2"></i>Rechercher
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Résultats -->
    @if(isset($groupeSanguin) && isset($rhesus) && $groupeSanguin && $rhesus)
        @if($banquesSang->count() > 0)
        <div class="alert alert-success border-0 mb-4">
            <i class="fas fa-check-circle me-2"></i>
            <strong>{{ $banquesSang->count() }} banque(s)</strong> trouvée(s) avec des réserves pour le groupe <strong>{{ $groupeSanguin }}{{ $rhesus }}</strong>
        </div>

        @foreach($banquesSang as $banque)
        <div class="card shadow mb-3 border-left-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h5 class="text-danger mb-2">
                            <i class="fas fa-hospital me-2"></i>{{ $banque->nom }}
                        </h5>
                        
                        <div class="mb-2">
                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                            <strong>Adresse :</strong> {{ $banque->adresse }}
                        </div>
                        
                        @if($banque->telephone)
                        <div class="mb-2">
                            <i class="fas fa-phone text-primary me-2"></i>
                            <strong>Téléphone :</strong> {{ $banque->telephone }}
                        </div>
                        @endif
                        
                        @if($banque->email)
                        <div class="mb-3">
                            <i class="fas fa-envelope text-info me-2"></i>
                            <strong>Email :</strong> {{ $banque->email }}
                        </div>
                        @endif

                        <!-- Réserves disponibles -->
                        @if(isset($reservesDisponibles[$banque->id]) && $reservesDisponibles[$banque->id]->count() > 0)
                        <div class="mt-3">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-tint me-2"></i>Réserves Disponibles :
                            </h6>
                            @foreach($reservesDisponibles[$banque->id] as $reserve)
                            <div class="border rounded p-3 bg-light mb-2">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <h5 class="mb-0 text-danger">{{ $reserve->groupe_sanguin }}</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <strong class="text-primary">{{ number_format($reserve->quantite_disponible, 1) }} L</strong>
                                        <small class="text-muted d-block">{{ number_format($reserve->quantite_disponible * 1000, 0, ',', ' ') }} ml</small>
                                    </div>
                                    <div class="col-md-3">
                                        <strong class="text-success">{{ $reserve->nombre_poches }}</strong>
                                        <small class="text-muted d-block">Poches</small>
                                    </div>
                                    <div class="col-md-3">
                                        @php
                                            $qteLitres = $reserve->quantite_disponible;
                                            $qteMin = $reserve->quantite_minimum ?? 5;
                                            $qteCritique = $reserve->quantite_critique ?? 2;
                                            
                                            if ($qteLitres <= $qteCritique) {
                                                $status = 'critique';
                                            } elseif ($qteLitres <= $qteMin) {
                                                $status = 'faible';
                                            } else {
                                                $status = 'normal';
                                            }
                                        @endphp
                                        <span class="badge bg-{{ $status == 'critique' ? 'danger' : ($status == 'faible' ? 'warning' : 'success') }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    
                    <div class="ms-3">
                        @if($banque->telephone)
                        <a href="tel:{{ $banque->telephone }}" class="btn btn-danger btn-sm">
                            <i class="fas fa-phone me-1"></i>Appeler
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @else
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-tint fa-4x mb-3" style="opacity: 0.2; color: #dc3545;"></i>
                <h5 class="text-gray-800 mb-2">Aucune banque de sang trouvée</h5>
                <p class="text-muted mb-0">
                    Aucune banque de sang ne dispose actuellement de réserves pour le groupe {{ $groupeSanguin }}{{ $rhesus }}.
                </p>
            </div>
        </div>
        @endif
    @else
    <!-- Message initial -->
    <div class="card shadow">
        <div class="card-body text-center py-5">
            <i class="fas fa-tint fa-4x mb-3" style="opacity: 0.2; color: #dc3545;"></i>
            <h5 class="text-gray-800 mb-2">Recherchez des Banques de Sang</h5>
            <p class="text-muted mb-0">
                Sélectionnez votre groupe sanguin et rhésus pour trouver les banques qui ont des réserves disponibles.
            </p>
        </div>
    </div>
    @endif
</div>
@endsection
