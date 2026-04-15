@extends('layouts.admin')

@section('title', 'Dossiers Médicaux')
@section('page-title', 'Dossiers Médicaux')

@section('content')
<style>
.form-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    border-left: 4px solid #003366;
}

.section-title {
    color: #003366;
    font-weight: 700;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #dee2e6;
}

.signes-vitaux-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
}
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-file-medical me-2"></i>Dossiers Médicaux</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDossierModal">
                    <i class="fas fa-plus me-2"></i>Nouveau Dossier
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>N° Dossier</th>
                                <th>Patient</th>
                                <th>Date Consultation</th>
                                <th>Diagnostic</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dossiers as $dossier)
                            <tr>
                                <td>
                                    <span class="badge bg-primary">{{ $dossier->numero_dossier }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-title bg-secondary rounded-circle">
                                                {{ substr($dossier->patient->nom, 0, 1) }}
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $dossier->patient->nom }}</h6>
                                            <small class="text-muted">{{ $dossier->patient->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $dossier->date_consultation->format('d/m/Y') }}</span>
                                </td>
                                <td>
                                    <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                        {{ Str::limit($dossier->diagnostic, 50) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $dossier->statut === 'actif' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($dossier->statut) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.medecin.dossier.show', $dossier->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-file-medical fa-3x mb-3"></i>
                                        <p>Aucun dossier médical trouvé</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($dossiers->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $dossiers->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal pour créer un nouveau dossier médical complet -->
<div class="modal fade" id="createDossierModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-file-medical me-2"></i>Nouveau Dossier Médical Complet</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.medecin.dossier.create') }}" method="POST">
                @csrf
                <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
                    
                    <!-- Section 1: Informations Patient -->
                    <div class="form-section">
                        <h6 class="section-title"><i class="fas fa-user me-2"></i>1. Informations Patient</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Patient <span class="text-danger">*</span></label>
                                <select name="patient_id" id="patient_id" class="form-select" required>
                                    <option value="">-- Sélectionner un patient --</option>
                                    @foreach($patients ?? [] as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->nom }} - {{ $patient->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date de Consultation <span class="text-danger">*</span></label>
                                <input type="date" name="date_consultation" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Antécédents -->
                    <div class="form-section">
                        <h6 class="section-title"><i class="fas fa-history me-2"></i>2. Antécédents</h6>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Antécédents Médicaux Personnels</label>
                                <textarea name="antecedents_medicaux" class="form-control" rows="3" placeholder="Maladies passées, opérations, hospitalisations...&#10;Ex: Diabète type 2 (2018), Appendicectomie (2015)"></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Antécédents Familiaux</label>
                                <textarea name="antecedents_familiaux" class="form-control" rows="2" placeholder="Maladies héréditaires dans la famille...&#10;Ex: Père diabétique, Mère hypertendue"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Allergies Connues</label>
                                <textarea name="allergies" class="form-control" rows="2" placeholder="Médicaments, aliments, autres...&#10;Ex: Pénicilline, Arachides"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Traitements en Cours</label>
                                <textarea name="traitements_en_cours" class="form-control" rows="2" placeholder="Médicaments actuellement pris...&#10;Ex: Metformine 500mg 2x/jour"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Signes Vitaux et Mesures -->
                    <div class="form-section">
                        <h6 class="section-title"><i class="fas fa-heartbeat me-2"></i>3. Signes Vitaux et Mesures</h6>
                        <div class="signes-vitaux-grid">
                            <div>
                                <label class="form-label">Poids (kg)</label>
                                <input type="number" step="0.1" name="poids" class="form-control" placeholder="Ex: 70.5">
                            </div>
                            <div>
                                <label class="form-label">Taille (cm)</label>
                                <input type="number" step="0.1" name="taille" class="form-control" placeholder="Ex: 175">
                            </div>
                            <div>
                                <label class="form-label">IMC</label>
                                <input type="text" id="imc" class="form-control" readonly placeholder="Auto">
                            </div>
                            <div>
                                <label class="form-label">Température (°C)</label>
                                <input type="number" step="0.1" name="temperature" class="form-control" placeholder="Ex: 37.5">
                            </div>
                            <div>
                                <label class="form-label">TA Systolique</label>
                                <input type="number" name="tension_systolique" class="form-control" placeholder="Ex: 120">
                            </div>
                            <div>
                                <label class="form-label">TA Diastolique</label>
                                <input type="number" name="tension_diastolique" class="form-control" placeholder="Ex: 80">
                            </div>
                            <div>
                                <label class="form-label">Pouls (bpm)</label>
                                <input type="number" name="pouls" class="form-control" placeholder="Ex: 72">
                            </div>
                            <div>
                                <label class="form-label">Fréq. Resp.</label>
                                <input type="number" name="frequence_respiratoire" class="form-control" placeholder="Ex: 16">
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Anamnèse (Histoire de la Maladie) -->
                    <div class="form-section">
                        <h6 class="section-title"><i class="fas fa-clipboard-list me-2"></i>4. Anamnèse (Histoire de la Maladie)</h6>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Motif de Consultation <span class="text-danger">*</span></label>
                                <textarea name="motif_consultation" class="form-control" rows="2" required placeholder="Pourquoi le patient consulte-t-il aujourd'hui ?&#10;Ex: Douleurs abdominales depuis 3 jours"></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Histoire de la Maladie Actuelle</label>
                                <textarea name="histoire_maladie" class="form-control" rows="3" placeholder="Depuis quand ? Comment ça a commencé ? Évolution ?&#10;Ex: Douleurs apparues il y a 3 jours, progressivement aggravées, localisées au quadrant inférieur droit"></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Symptômes Présentés</label>
                                <textarea name="symptomes" class="form-control" rows="3" placeholder="Liste des symptômes rapportés par le patient...&#10;Ex: Fièvre, nausées, vomissements, perte d'appétit"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Examen Clinique -->
                    <div class="form-section">
                        <h6 class="section-title"><i class="fas fa-stethoscope me-2"></i>5. Examen Clinique</h6>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Examen Général</label>
                                <textarea name="examen_general" class="form-control" rows="2" placeholder="État général du patient, conscience, coloration...&#10;Ex: Patient conscient, orienté, bon état général"></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Examen Physique Détaillé</label>
                                <textarea name="examen_physique" class="form-control" rows="4" placeholder="Inspection, palpation, percussion, auscultation par système...&#10;Ex: Abdomen: Sensibilité au quadrant inférieur droit, défense musculaire, signe de Blumberg positif"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 6: Diagnostic Initial (Hypothèse) -->
                    <div class="form-section">
                        <h6 class="section-title"><i class="fas fa-diagnoses me-2"></i>6. Diagnostic Initial / Hypothèse Diagnostique</h6>
                        <div class="alert alert-warning">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Note :</strong> Il s'agit de votre hypothèse diagnostique basée sur l'examen clinique. Le diagnostic final sera confirmé après réception des résultats d'examens.
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Hypothèse Diagnostique Principale <span class="text-danger">*</span></label>
                                <textarea name="diagnostic" class="form-control" rows="2" required placeholder="Votre hypothèse basée sur l'examen clinique...&#10;Ex: Suspicion d'appendicite aiguë"></textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Code CIM-10 (Provisoire)</label>
                                <input type="text" name="code_cim10" class="form-control" placeholder="Ex: K35.8">
                                <small class="text-muted">Classification internationale</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Hypothèses Secondaires</label>
                                <textarea name="diagnostics_secondaires" class="form-control" rows="2" placeholder="Autres pathologies possibles...&#10;Ex: Possible déshydratation associée"></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Diagnostic Différentiel</label>
                                <textarea name="diagnostic_differentiel" class="form-control" rows="2" placeholder="Autres hypothèses à écarter par les examens...&#10;Ex: Gastro-entérite, colique néphrétique, salpingite"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 7: Notes Initiales -->
                    <div class="form-section">
                        <h6 class="section-title"><i class="fas fa-notes-medical me-2"></i>7. Notes et Observations Initiales</h6>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Observations Médicales</label>
                                <textarea name="observations" class="form-control" rows="3" placeholder="Notes personnelles du médecin, points d'attention...&#10;Ex: Patient anxieux, à surveiller de près"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Prochain Rendez-vous</label>
                                <input type="date" name="date_prochain_rdv" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Niveau d'Urgence</label>
                                <select name="urgence" class="form-select">
                                    <option value="normale">Normale</option>
                                    <option value="urgente">Urgente</option>
                                    <option value="tres_urgente">Très Urgente</option>
                                </select>
                            </div>
                        </div>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note :</strong> Après création du dossier, vous pourrez prescrire des examens. Le traitement sera ajouté après réception des résultats.
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Créer le Dossier Médical Complet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Calcul automatique de l'IMC
document.addEventListener('DOMContentLoaded', function() {
    const poidsInput = document.querySelector('input[name="poids"]');
    const tailleInput = document.querySelector('input[name="taille"]');
    
    if (poidsInput && tailleInput) {
        poidsInput.addEventListener('input', calculateIMC);
        tailleInput.addEventListener('input', calculateIMC);
    }
});

function calculateIMC() {
    const poids = parseFloat(document.querySelector('input[name="poids"]').value);
    const taille = parseFloat(document.querySelector('input[name="taille"]').value) / 100; // Convertir cm en m
    
    if (poids && taille) {
        const imc = (poids / (taille * taille)).toFixed(1);
        document.getElementById('imc').value = imc;
    }
}
</script>
@endsection

