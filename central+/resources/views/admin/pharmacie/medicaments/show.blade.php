@extends('layouts.admin')

@section('title', 'Détails du Médicament')
@section('page-title', 'Détails du Médicament')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-pills me-2"></i>{{ $medicament->nom }}</h2>
        <div>
            <a href="{{ route('admin.pharmacie.medicaments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            <button class="btn btn-warning" onclick="editerMedicament()">
                <i class="fas fa-edit me-2"></i>Modifier
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations Générales</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Nom:</strong><br>
                            {{ $medicament->nom }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Nom Générique (DCI):</strong><br>
                            {{ $medicament->nom_generique ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Code/Référence:</strong><br>
                            <code>{{ $medicament->code ?? '-' }}</code>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Fabricant:</strong><br>
                            {{ $medicament->fabricant ?? '-' }}
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>Catégorie:</strong><br>
                            <span class="badge bg-info">{{ $medicament->categorie }}</span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>Forme:</strong><br>
                            <span class="badge bg-secondary">{{ $medicament->forme }}</span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>Dosage:</strong><br>
                            {{ $medicament->dosage ?? '-' }}
                        </div>
                        <div class="col-md-12 mb-3">
                            <strong>Prescription Requise:</strong><br>
                            @if($medicament->prescription_requise)
                                <span class="badge bg-warning">Oui</span>
                            @else
                                <span class="badge bg-success">Non</span>
                            @endif
                        </div>
                        @if($medicament->description)
                        <div class="col-md-12 mb-3">
                            <strong>Description:</strong><br>
                            {{ $medicament->description }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informations médicales -->
            @if($medicament->indication || $medicament->contre_indication || $medicament->effets_secondaires || $medicament->posologie)
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-stethoscope me-2"></i>Informations Médicales</h5>
                </div>
                <div class="card-body">
                    @if($medicament->indication)
                    <div class="mb-3">
                        <strong>Indications:</strong><br>
                        {{ $medicament->indication }}
                    </div>
                    @endif
                    @if($medicament->contre_indication)
                    <div class="mb-3">
                        <strong>Contre-indications:</strong><br>
                        <span class="text-danger">{{ $medicament->contre_indication }}</span>
                    </div>
                    @endif
                    @if($medicament->effets_secondaires)
                    <div class="mb-3">
                        <strong>Effets Secondaires:</strong><br>
                        {{ $medicament->effets_secondaires }}
                    </div>
                    @endif
                    @if($medicament->posologie)
                    <div class="mb-3">
                        <strong>Posologie:</strong><br>
                        {{ $medicament->posologie }}
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Informations de lot -->
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Informations de Lot</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Numéro de Lot:</strong><br>
                            {{ $medicament->numero_lot ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Emplacement:</strong><br>
                            {{ $medicament->emplacement ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Date de Fabrication:</strong><br>
                            {{ $medicament->date_fabrication ? $medicament->date_fabrication->format('d/m/Y') : '-' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Date d'Expiration:</strong><br>
                            @if($medicament->date_expiration)
                                {{ $medicament->date_expiration->format('d/m/Y') }}
                                @if($medicament->isPerime())
                                    <span class="badge bg-danger ms-2">Périmé</span>
                                @elseif($medicament->isBientotPerime())
                                    <span class="badge bg-warning ms-2">Bientôt périmé</span>
                                @endif
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panneau latéral -->
        <div class="col-md-4">
            <!-- Prix -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-dollar-sign me-2"></i>Prix</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Prix d'Achat:</strong><br>
                        <h4 class="text-muted">${{ number_format($medicament->prix_achat ?? 0, 2) }}</h4>
                    </div>
                    <div class="mb-3">
                        <strong>Prix de Vente:</strong><br>
                        <h3 class="text-success">${{ number_format($medicament->prix_unitaire, 2) }}</h3>
                    </div>
                    @if($medicament->prix_achat)
                    <div>
                        <strong>Marge Bénéficiaire:</strong><br>
                        <h5 class="text-info">{{ number_format($medicament->getMarge(), 1) }}%</h5>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Stock -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Stock</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Stock Actuel:</strong><br>
                        @php $status = $medicament->getStockStatus(); @endphp
                        <h3 class="text-{{ $status['class'] }}">
                            {{ $medicament->stock_actuel }} unités
                        </h3>
                        <span class="badge bg-{{ $status['class'] }}">{{ $status['label'] }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Stock Minimum:</strong><br>
                        {{ $medicament->stock_minimum }} unités
                    </div>
                    @if($medicament->isStockFaible())
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention !</strong> Le stock est en dessous du minimum.
                    </div>
                    @endif
                </div>
            </div>

            <!-- Statut -->
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-toggle-on me-2"></i>Statut</h5>
                </div>
                <div class="card-body">
                    @if($medicament->actif)
                        <span class="badge bg-success fs-5">Actif</span>
                    @else
                        <span class="badge bg-danger fs-5">Inactif</span>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Actions Rapides</h5>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary w-100 mb-2" onclick="ajusterStock()">
                        <i class="fas fa-exchange-alt me-2"></i>Ajuster le Stock
                    </button>
                    <button class="btn btn-info w-100 mb-2" onclick="historiqueStock()">
                        <i class="fas fa-history me-2"></i>Historique
                    </button>
                    <button class="btn btn-danger w-100" onclick="supprimerMedicament()">
                        <i class="fas fa-trash me-2"></i>Désactiver
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editerMedicament() {
    alert('Fonctionnalité de modification en cours de développement');
}

function ajusterStock() {
    alert('Fonctionnalité d\'ajustement de stock en cours de développement');
}

function historiqueStock() {
    alert('Fonctionnalité d\'historique en cours de développement');
}

function supprimerMedicament() {
    if (confirm('Êtes-vous sûr de vouloir désactiver ce médicament ?')) {
        fetch('{{ route("admin.pharmacie.medicaments.destroy", $medicament->id) }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Médicament désactivé avec succès !');
                window.location.href = '{{ route("admin.pharmacie.medicaments.index") }}';
            } else {
                alert('Erreur : ' + (data.message || 'Une erreur est survenue'));
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression');
        });
    }
}
</script>
@endsection

