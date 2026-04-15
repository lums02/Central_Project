@extends('layouts.admin')
@section('title', 'Rechercher Patient')
@section('page-title', 'Rechercher un Patient')
@section('content')
<div class="container-fluid py-4">
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Nom ou email du patient..." value="{{ request('search') }}">
                    <button class="btn btn-primary"><i class="fas fa-search"></i> Rechercher</button>
                </div>
            </form>
        </div>
    </div>
    @if(count($patients) > 0)
        @foreach($patients as $patient)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>{{ $patient->nom }}</h5>
                    <p>Email: {{ $patient->email }}</p>
                    @if($patient->dossiersMedicaux->count() > 0)
                        <p><strong>Dossiers médicaux:</strong> {{ $patient->dossiersMedicaux->count() }} dossier(s)</p>
                        <p><strong>Hôpital actuel:</strong> {{ $patient->dossiersMedicaux->first()->hopital->nom ?? 'N/A' }}</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#demandeModal{{ $patient->id }}">
                            <i class="fas fa-file-export"></i> Demander le Dossier
                        </button>
                        
                        <div class="modal fade" id="demandeModal{{ $patient->id }}">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.hopital.transferts.creer-demande') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                    <input type="hidden" name="hopital_detenteur_id" value="{{ $patient->dossiersMedicaux->first()->hopital_id }}">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5>Demander le Dossier Médical</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Motif de la demande *</label>
                                                <textarea name="motif_demande" class="form-control" rows="3" required placeholder="Ex: Patient transféré dans notre hôpital..."></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label>Notes</label>
                                                <textarea name="notes_demandeur" class="form-control" rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Envoyer la Demande</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">Aucun dossier médical disponible</p>
                    @endif
                </div>
            </div>
        @endforeach
    @elseif(request('search'))
        <p class="text-center text-muted">Aucun patient trouvé</p>
    @endif
</div>
@endsection

