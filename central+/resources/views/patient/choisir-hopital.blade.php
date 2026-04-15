@extends('layouts.admin')

@section('title', 'Choisir mon Hôpital')
@section('page-title', 'Choisir mon Hôpital')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-hospital text-primary me-2"></i>Choisir mon Hôpital
        </h1>
    </div>

    <!-- Alerte d'information -->
    <div class="alert alert-info border-0 shadow-sm mb-4">
        <h5 class="alert-heading">
            <i class="fas fa-info-circle me-2"></i>Pourquoi choisir un hôpital ?
        </h5>
        <p class="mb-2">
            En sélectionnant un hôpital, vous pourrez :
        </p>
        <ul class="mb-0">
            <li>Prendre rendez-vous avec les médecins de cet hôpital</li>
            <li>Consulter vos dossiers médicaux créés par les médecins</li>
            <li>Recevoir des notifications pour vos consultations</li>
            <li>Accéder aux services de l'hôpital (laboratoire, etc.)</li>
        </ul>
        <p class="mb-0 mt-2">
            <small class="text-muted">
                <i class="fas fa-lightbulb me-1"></i>
                <strong>Note :</strong> Vous pourrez toujours changer d'hôpital plus tard si nécessaire.
            </small>
        </p>
    </div>

    <!-- Liste des hôpitaux -->
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-hospital me-2"></i>Hôpitaux Disponibles ({{ $hopitaux->count() }})
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('patient.enregistrer-hopital') }}" method="POST">
                @csrf
                <div class="row">
                    @foreach($hopitaux as $hopital)
                    <div class="col-lg-6 mb-3">
                        <div class="card h-100 hopital-card" style="cursor: pointer; transition: all 0.3s ease; border: 2px solid #e3f2fd;">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="hopital_id" id="hopital{{ $hopital->id }}" value="{{ $hopital->id }}" required>
                                    <label class="form-check-label w-100" for="hopital{{ $hopital->id }}" style="cursor: pointer;">
                                        <h5 class="text-primary mb-2">
                                            <i class="fas fa-hospital me-2"></i>{{ $hopital->nom }}
                                        </h5>
                                        <p class="text-muted mb-2">
                                            <i class="fas fa-map-marker-alt me-2"></i>{{ $hopital->adresse }}
                                        </p>
                                        @if($hopital->telephone)
                                        <p class="text-muted mb-2">
                                            <i class="fas fa-phone me-2"></i>{{ $hopital->telephone }}
                                        </p>
                                        @endif
                                        @if($hopital->type_hopital)
                                        <p class="mb-0">
                                            <span class="badge bg-info">{{ $hopital->type_hopital }}</span>
                                            @if($hopital->nombre_lits)
                                            <span class="badge bg-secondary ms-2">
                                                <i class="fas fa-bed me-1"></i>{{ $hopital->nombre_lits }} lits
                                            </span>
                                            @endif
                                        </p>
                                        @endif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('patient.dashboard') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-times me-2"></i>Plus tard
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>Confirmer mon Choix
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.hopital-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-color: #4e73df !important;
}

.hopital-card input[type="radio"]:checked + label {
    color: #4e73df;
}

.hopital-card input[type="radio"]:checked ~ * {
    border-color: #4e73df;
}

.hopital-card:has(input[type="radio"]:checked) {
    background: #f0f7ff;
    border-color: #4e73df !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter un événement click sur toute la carte
    document.querySelectorAll('.hopital-card').forEach(card => {
        card.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
        });
    });
});
</script>
@endsection

