@extends('layouts.admin')

@section('title', 'Facture')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Boutons d'action -->
            <div class="d-flex justify-content-between align-items-center mb-3 no-print">
                <a href="{{ route('admin.caissier.consultations') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
                <div>
                    <button onclick="window.print()" class="btn btn-primary me-2">
                        <i class="fas fa-print me-2"></i>Imprimer
                    </button>
                    <a href="{{ route('admin.caissier.facture.pdf', $consultation->id) }}" 
                       class="btn btn-success" 
                       target="_blank">
                        <i class="fas fa-download me-2"></i>Télécharger PDF
                    </a>
                </div>
            </div>

            <!-- Facture Simplifiée -->
            <div class="card border-0" id="facture" style="background: white;">
                <div class="card-body p-5">
                    <!-- En-tête simple -->
                    <div class="text-center mb-5">
                        <h1 class="text-primary mb-2" style="font-weight: 900;">{{ $consultation->hopital->nom ?? 'HÔPITAL' }}</h1>
                        <h2 class="text-success" style="font-weight: 700;">FACTURE DE CONSULTATION</h2>
                        <p class="text-muted mb-0">N° {{ $consultation->numero_facture }}</p>
                    </div>

                    <hr class="my-4">

                    <!-- Informations essentielles -->
                    <div class="mb-4">
                        <table class="table table-borderless" style="font-size: 1.1rem;">
                            <tbody>
                                <tr>
                                    <td width="30%"><strong>Patient :</strong></td>
                                    <td><strong style="font-size: 1.3rem;">{{ $consultation->patient->nom }} {{ $consultation->patient->prenom }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Motif :</strong></td>
                                    <td>{{ $consultation->motif_consultation }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date :</strong></td>
                                    <td>{{ $consultation->date_paiement->format('d/m/Y à H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Caissier(ère) :</strong></td>
                                    <td>{{ $consultation->caissier->nom }} {{ $consultation->caissier->prenom }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <hr class="my-4">

                    <!-- Montant -->
                    <div class="text-center mb-5">
                        <div class="bg-success text-white rounded p-4 d-inline-block" style="min-width: 400px;">
                            <p class="mb-2 text-white" style="font-size: 1rem; opacity: 0.9;">MONTANT PAYÉ</p>
                            <h1 class="mb-0 text-white" style="font-size: 3rem; font-weight: 900;">{{ number_format($consultation->montant_paye, 0, ',', ' ') }} FC</h1>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Cachet -->
                    <div class="text-center">
                        <div class="d-inline-block border border-3 border-success rounded-circle p-4">
                            <h2 class="text-success mb-0" style="font-weight: 900;">✓ PAYÉ</h2>
                        </div>
                    </div>

                    <!-- Pied de page -->
                    <div class="text-center mt-5">
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">Merci de votre confiance</p>
                    </div>
                </div>
            </div>

            <!-- Message de succès -->
            <div class="alert alert-success mt-3 no-print">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Paiement enregistré avec succès !</strong>
                <p class="mb-0 mt-2">Le patient peut maintenant se rendre chez le médecin. Une notification a été envoyée au médecin.</p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        background: white !important;
    }
    
    .card {
        box-shadow: none !important;
        border: none !important;
    }
    
    @page {
        size: A4;
        margin: 1cm;
    }
}

.card {
    border: none;
}

.border-left-primary {
    border-left: 4px solid #007bff !important;
}
</style>

<script>
// Imprimer automatiquement au chargement de la page
window.addEventListener('load', function() {
    // Attendre 500ms pour que la page soit complètement chargée
    setTimeout(function() {
        window.print();
    }, 500);
});
</script>
@endsection

