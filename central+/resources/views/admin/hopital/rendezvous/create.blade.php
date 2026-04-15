@extends('layouts.admin')

@section('title', 'Nouveau Rendez-vous')
@section('page-title', 'Nouveau Rendez-vous')

@section('content')
<div class="container-fluid py-4">
    <a href="{{ route('admin.hopital.rendezvous.index') }}" class="btn btn-outline-primary mb-3">
        <i class="fas fa-arrow-left"></i> Retour
    </a>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-plus"></i> Créer un Nouveau Rendez-vous</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.hopital.rendezvous.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Patient *</label>
                        <select name="patient_id" class="form-select" required>
                            <option value="">-- Sélectionner un patient --</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->nom }} - {{ $patient->email }}</option>
                            @endforeach
                        </select>
                        @error('patient_id')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Médecin *</label>
                        <select name="medecin_id" class="form-select" required>
                            <option value="">-- Sélectionner un médecin --</option>
                            @foreach($medecins as $medecin)
                                <option value="{{ $medecin->id }}">Dr. {{ $medecin->nom }}</option>
                            @endforeach
                        </select>
                        @error('medecin_id')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Date *</label>
                        <input type="date" name="date_rendezvous" class="form-control" min="{{ date('Y-m-d') }}" required>
                        @error('date_rendezvous')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Heure *</label>
                        <input type="time" name="heure_rendezvous" class="form-control" required>
                        @error('heure_rendezvous')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Type de Consultation *</label>
                        <select name="type_consultation" class="form-select" required>
                            <option value="consultation_generale">Consultation Générale</option>
                            <option value="consultation_specialisee">Consultation Spécialisée</option>
                            <option value="suivi">Suivi</option>
                            <option value="urgence">Urgence</option>
                            <option value="controle">Contrôle</option>
                        </select>
                        @error('type_consultation')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label">Motif *</label>
                        <textarea name="motif" class="form-control" rows="3" required placeholder="Décrivez le motif de la consultation..."></textarea>
                        @error('motif')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Prix (USD)</label>
                        <input type="number" name="prix" class="form-control" step="0.01" min="0" placeholder="0.00">
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Notes additionnelles..."></textarea>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Créer le Rendez-vous
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

