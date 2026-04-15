@extends('layouts.admin')

@section('title', 'Historique des Examens Réalisés')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-history me-2"></i>Historique des Examens Réalisés</h1>
        <div>
            <a href="{{ route('admin.laborantin.examens') }}" class="btn btn-primary me-2">
                <i class="fas fa-microscope me-2"></i>Examens à Réaliser
            </a>
            <a href="{{ route('admin.laborantin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-home me-2"></i>Dashboard
            </a>
        </div>
    </div>

    <!-- Tableau de l'historique -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>Tous les Examens Terminés
        </div>
        <div class="card-body p-0">
            @if($examens->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="10%">N° Examen</th>
                                <th width="15%">Patient</th>
                                <th width="12%">Médecin</th>
                                <th width="15%">Examen</th>
                                <th width="10%">Prescrit le</th>
                                <th width="10%">Réalisé le</th>
                                <th width="10%">Réalisé par</th>
                                <th width="10%">Actions</th>
                                <!--th width="8%">Actions</th!-->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($examens as $examen)
                            <tr>
                                <td>
                                    <span class="badge bg-success">{{ $examen->numero_examen }}</span>
                                </td>
                                <td>
                                    <strong>{{ $examen->patient->nom }} {{ $examen->patient->prenom }}</strong><br>
                                    <small class="text-muted">{{ $examen->patient->telephone }}</small>
                                </td>
                                <td>
                                    <small>Dr. {{ $examen->medecin->nom }} {{ $examen->medecin->prenom }}</small>
                                </td>
                                <td>
                                    <strong>{{ $examen->nom_examen }}</strong><br>
                                    <small class="text-muted">{{ ucfirst($examen->type_examen) }}</small><br>
                                    @if($examen->indication)
                                        <small class="text-info"><i class="fas fa-info-circle me-1"></i>{{ \Str::limit($examen->indication, 30) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $examen->date_prescription->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    <small>
                                        <strong class="text-success">{{ $examen->date_realisation->format('d/m/Y') }}</strong><br>
                                        {{ $examen->date_realisation->format('H:i') }}
                                    </small>
                                </td>
                                <td>
                                    @if($examen->laborantin)
                                        <small>{{ $examen->laborantin->nom }} {{ $examen->laborantin->prenom }}</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td>
                                    @if($examen->resultats)
                                        <button type="button" class="btn btn-sm btn-info" 
                                                onclick="voirResultats({{ $examen->id }}, '{{ addslashes($examen->nom_examen) }}', '{{ addslashes($examen->resultats) }}', '{{ addslashes($examen->interpretation ?? '') }}', '{{ $examen->fichier_resultat }}')">
                                            <i class="fas fa-eye"></i> Voir
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <!--td>
                                    @if($examen->dossierMedical)
                                        <a href="{{ route('admin.medecin.dossier.show', $examen->dossierMedical->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Voir le dossier">
                                            <i class="fas fa-folder-open"></i>
                                        </a>
                                    @endif
                                </td!-->
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Total : {{ $examens->total() }} examen(s) réalisé(s)
                        </div>
                        <div>
                            {{ $examens->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 text-muted">Aucun examen dans l'historique</h4>
                    <p class="text-muted">Les examens réalisés apparaîtront ici</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Résultats -->
<div class="modal fade" id="resultatModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-file-medical-alt me-2"></i>Résultats de l'Examen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6 class="mb-3" id="modalExamenNom"></h6>
                
                <div class="mb-3">
                    <label class="fw-bold text-muted d-block mb-2">Résultats :</label>
                    <div class="p-3 bg-light rounded" id="modalResultats"></div>
                </div>

                <div class="mb-3" id="modalInterpretationDiv" style="display: none;">
                    <label class="fw-bold text-muted d-block mb-2">Interprétation :</label>
                    <div class="p-3 bg-info bg-opacity-10 rounded" id="modalInterpretation"></div>
                </div>

                <div id="modalFichierDiv" style="display: none;">
                    <label class="fw-bold text-muted d-block mb-2">Fichier joint :</label>
                    <a href="#" id="modalFichierLink" class="btn btn-primary" target="_blank">
                        <i class="fas fa-download me-2"></i>Télécharger le fichier
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
function voirResultats(examenId, nomExamen, resultats, interpretation, fichier) {
    document.getElementById('modalExamenNom').textContent = nomExamen;
    document.getElementById('modalResultats').textContent = resultats;
    
    if (interpretation) {
        document.getElementById('modalInterpretation').textContent = interpretation;
        document.getElementById('modalInterpretationDiv').style.display = 'block';
    } else {
        document.getElementById('modalInterpretationDiv').style.display = 'none';
    }
    
    if (fichier) {
        document.getElementById('modalFichierLink').href = '/storage/' + fichier;
        document.getElementById('modalFichierDiv').style.display = 'block';
    } else {
        document.getElementById('modalFichierDiv').style.display = 'none';
    }
    
    new bootstrap.Modal(document.getElementById('resultatModal')).show();
}
</script>

<style>
.table tbody tr {
    transition: all 0.2s;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}
</style>
@endsection

