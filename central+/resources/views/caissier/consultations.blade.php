@extends('layouts.admin')

@section('title', 'Consultations - Caissier')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-cash-register me-2"></i>Gestion des Consultations</h1>
        <a href="{{ route('admin.caissier.historique') }}" class="btn btn-outline-secondary">
            <i class="fas fa-history me-2"></i>Historique
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Barre de recherche -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" 
                               id="recherchePatient" 
                               class="form-control form-control-lg" 
                               placeholder="Rechercher par nom, prénom ou téléphone du patient..."
                               autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-secondary w-100" onclick="afficherTout()">
                        <i class="fas fa-list me-2"></i>Afficher tout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Message initial -->
    <div id="messageInitial" class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-search text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3 text-muted">Recherchez un patient</h4>
            <p class="text-muted">Tapez le nom, prénom ou téléphone du patient dans la barre de recherche</p>
        </div>
    </div>

    <!-- Tableau des résultats -->
    <div id="resultatRecherche" style="display: none;">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-users me-2"></i>Consultations en Attente de Paiement
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%">Patient</th>
                                <th width="15%">Médecin</th>
                                <th width="25%">Motif de Consultation</th>
                                <th width="10%">Poids/Taille</th>
                                <th width="10%">Montant</th>
                                <th width="10%">Date</th>
                                <th width="5%">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableauConsultations">
                            <!-- Les résultats seront insérés ici via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Message si aucun résultat -->
        <div id="aucunResultat" class="card" style="display: none;">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 text-muted">Aucune consultation trouvée</h4>
                <p class="text-muted">Aucune consultation en attente de paiement pour cette recherche</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Paiement -->
<div class="modal fade" id="modalPaiement" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-cash-register me-2"></i>Effectuer le Paiement</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formPaiement" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Informations patient -->
                    <div class="card mb-3 border-primary">
                        <div class="card-header bg-light">
                            <strong><i class="fas fa-user me-2"></i>Informations Patient</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Nom complet :</strong> <span id="modalPatientNom"></span></p>
                                    <p class="mb-1"><strong>Téléphone :</strong> <span id="modalPatientTel"></span></p>
                                    <p class="mb-0"><strong>Médecin :</strong> <span id="modalMedecinNom"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Poids :</strong> <span id="modalPoids"></span></p>
                                    <p class="mb-1"><strong>Taille :</strong> <span id="modalTaille"></span></p>
                                    <p class="mb-0"><strong>Date :</strong> <span id="modalDate"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Motif de consultation -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong><i class="fas fa-stethoscope me-2"></i>Motif de Consultation</strong>
                        </div>
                        <div class="card-body">
                            <p id="modalMotif" class="mb-0"></p>
                        </div>
                    </div>

                    <!-- Informations de paiement -->
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <strong><i class="fas fa-money-bill-wave me-2"></i>Paiement</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Mode de Paiement <span class="text-danger">*</span></label>
                                    <select name="mode_paiement" class="form-select" required>
                                        <option value="">-- Sélectionner --</option>
                                        <option value="especes">Espèces</option>
                                        <option value="carte">Carte Bancaire</option>
                                        <option value="mobile_money">Mobile Money</option>
                                        <option value="cheque">Chèque</option>
                                        <option value="virement">Virement</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Montant à Payer <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" 
                                               name="montant_paye" 
                                               id="modalMontant" 
                                               class="form-control" 
                                               step="0.01" 
                                               required 
                                               readonly>
                                        <span class="input-group-text">FC</span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Notes (optionnel)</label>
                                    <textarea name="notes_caissier" 
                                              class="form-control" 
                                              rows="2" 
                                              placeholder="Informations complémentaires..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-check-circle me-2"></i>Valider le Paiement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let consultationSelectionnee = null;

// Recherche en temps réel
document.getElementById('recherchePatient').addEventListener('input', function(e) {
    let query = e.target.value.trim();
    
    if (query.length < 2) {
        document.getElementById('messageInitial').style.display = 'block';
        document.getElementById('resultatRecherche').style.display = 'none';
        return;
    }

    rechercherConsultations(query);
});

// Fonction de recherche
function rechercherConsultations(query) {
    fetch(`{{ route('admin.caissier.rechercher-patient') }}?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            afficherResultats(data);
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
}

// Afficher tous les résultats
function afficherTout() {
    document.getElementById('recherchePatient').value = '';
    
    fetch(`{{ route('admin.caissier.rechercher-patient') }}?q=all`)
        .then(response => response.json())
        .then(data => {
            afficherResultats(data);
        });
}

// Afficher les résultats dans le tableau
function afficherResultats(consultations) {
    document.getElementById('messageInitial').style.display = 'none';
    document.getElementById('resultatRecherche').style.display = 'block';
    
    const tbody = document.getElementById('tableauConsultations');
    const aucunResultat = document.getElementById('aucunResultat');
    
    if (consultations.length === 0) {
        tbody.innerHTML = '';
        aucunResultat.style.display = 'block';
        return;
    }
    
    aucunResultat.style.display = 'none';
    
    let html = '';
    consultations.forEach((consultation, index) => {
        html += `
            <tr>
                <td><strong>${index + 1}</strong></td>
                <td>
                    <strong>${consultation.patient_nom}</strong><br>
                    <small class="text-muted"><i class="fas fa-phone"></i> ${consultation.patient_telephone}</small>
                </td>
                <td>
                    <small>Dr. ${consultation.medecin_nom}</small>
                </td>
                <td>
                    <small>${consultation.motif}</small>
                </td>
                <td>
                    <small>${consultation.poids || '-'} kg<br>${consultation.taille || '-'} cm</small>
                </td>
                <td>
                    <strong class="text-primary">${new Intl.NumberFormat('fr-FR').format(consultation.montant)} FC</strong>
                </td>
                <td>
                    <small>${consultation.date_creation}</small>
                </td>
                <td>
                    <button type="button" 
                            class="btn btn-success btn-sm" 
                            onclick="ouvrirModalPaiement(${consultation.id}, '${consultation.patient_nom}', '${consultation.patient_telephone}', '${consultation.medecin_nom}', '${consultation.motif}', ${consultation.montant}, '${consultation.date_creation}', '${consultation.poids || '-'}', '${consultation.taille || '-'}')">
                        <i class="fas fa-cash-register"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

// Ouvrir le modal de paiement
function ouvrirModalPaiement(id, patientNom, patientTel, medecinNom, motif, montant, date, poids, taille) {
    consultationSelectionnee = id;
    
    // Remplir les informations dans le modal
    document.getElementById('modalPatientNom').textContent = patientNom;
    document.getElementById('modalPatientTel').textContent = patientTel;
    document.getElementById('modalMedecinNom').textContent = medecinNom;
    document.getElementById('modalMotif').textContent = motif;
    document.getElementById('modalMontant').value = montant;
    document.getElementById('modalDate').textContent = date;
    document.getElementById('modalPoids').textContent = poids + ' kg';
    document.getElementById('modalTaille').textContent = taille + ' cm';
    
    // Définir l'action du formulaire
    document.getElementById('formPaiement').action = `/admin/caissier/consultations/${id}/encaisser`;
    
    // Ouvrir le modal
    const modal = new bootstrap.Modal(document.getElementById('modalPaiement'));
    modal.show();
}

// Soumission du formulaire de paiement
document.getElementById('formPaiement').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const url = this.action;
    
    // Afficher un indicateur de chargement
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement en cours...';
    submitBtn.disabled = true;
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (response.redirected) {
            // Redirection vers la facture
            window.location.href = response.url;
        } else {
            return response.json();
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        alert('Une erreur est survenue lors du paiement');
    });
});

// Charger toutes les consultations au chargement de la page
window.addEventListener('load', function() {
    // Optionnel : afficher automatiquement toutes les consultations
    // afficherTout();
});
</script>

<style>
.table tbody tr {
    transition: all 0.2s;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.input-group-text.bg-primary {
    border-color: #007bff;
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
@endsection

