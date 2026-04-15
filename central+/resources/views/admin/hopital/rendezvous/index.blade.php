@extends('layouts.admin')

@section('title', 'Gestion des Rendez-vous')
@section('page-title', 'Gestion des Rendez-vous')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-end align-items-center mb-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nouveauRendezVousModal">
                    <i class="fas fa-plus"></i> Nouveau Rendez-vous
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon bg-primary">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>Total Rendez-vous</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon bg-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $stats['en_attente'] }}</h3>
                    <p>En Attente</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $stats['confirme'] }}</h3>
                    <p>Confirmés</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon bg-info">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $stats['aujourdhui'] }}</h3>
                    <p>Aujourd'hui</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" placeholder="Rechercher patient..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="statut">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En Attente</option>
                        <option value="confirme" {{ request('statut') == 'confirme' ? 'selected' : '' }}>Confirmé</option>
                        <option value="annule" {{ request('statut') == 'annule' ? 'selected' : '' }}>Annulé</option>
                        <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="medecin_id">
                        <option value="">Tous les médecins</option>
                        @foreach($medecins as $medecin)
                            <option value="{{ $medecin->id }}" {{ request('medecin_id') == $medecin->id ? 'selected' : '' }}>
                                Dr. {{ $medecin->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Liste des rendez-vous -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Rendez-vous <span class="badge bg-primary">{{ $rendezvous->total() }}</span></h5>
        </div>
        <div class="card-body p-0">
            @if($rendezvous->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date & Heure</th>
                                <th>Patient</th>
                                <th>Médecin</th>
                                <th>Type</th>
                                <th>Motif</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rendezvous as $rdv)
                                <tr>
                                    <td>
                                        <strong>{{ \Carbon\Carbon::parse($rdv->date_rendezvous)->format('d/m/Y') }}</strong><br>
                                        <small class="text-muted">{{ substr($rdv->heure_rendezvous, 0, 5) }}</small>
                                    </td>
                                    <td>{{ $rdv->patient->nom ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-info">Dr. {{ $rdv->medecin->nom ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $rdv->type_consultation)) }}</td>
                                    <td>{{ \Str::limit($rdv->motif, 40) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $rdv->statut_color }}">
                                            {{ $rdv->statut_format }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.hopital.rendezvous.show', $rdv->id) }}" class="btn btn-sm btn-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#updateStatutModal{{ $rdv->id }}" title="Modifier statut">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal statut -->
                                <div class="modal fade" id="updateStatutModal{{ $rdv->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.hopital.rendezvous.update-statut', $rdv->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Modifier le Statut</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <select name="statut" class="form-select" required>
                                                        <option value="en_attente" {{ $rdv->statut == 'en_attente' ? 'selected' : '' }}>En Attente</option>
                                                        <option value="confirme" {{ $rdv->statut == 'confirme' ? 'selected' : '' }}>Confirmé</option>
                                                        <option value="annule" {{ $rdv->statut == 'annule' ? 'selected' : '' }}>Annulé</option>
                                                        <option value="termine" {{ $rdv->statut == 'termine' ? 'selected' : '' }}>Terminé</option>
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white">
                    {{ $rendezvous->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun rendez-vous trouvé.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.stats-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 15px;
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stats-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    color: #003366;
}

.stats-content p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}
</style>

<!-- Modal Nouveau Rendez-vous -->
<div class="modal fade" id="nouveauRendezVousModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.hopital.rendezvous.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-calendar-plus"></i> Nouveau Rendez-vous</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Patient *</label>
                            <select name="patient_id" class="form-select" required>
                                <option value="">-- Sélectionner un patient --</option>
                                @foreach($patients ?? [] as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->nom }} - {{ $patient->email }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Médecin *</label>
                            <select name="medecin_id" class="form-select" required>
                                <option value="">-- Sélectionner un médecin --</option>
                                @foreach($medecins ?? [] as $medecin)
                                    <option value="{{ $medecin->id }}">Dr. {{ $medecin->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date *</label>
                            <input type="date" name="date_rendezvous" class="form-control" min="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Heure *</label>
                            <input type="time" name="heure_rendezvous" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Type *</label>
                            <select name="type_consultation" class="form-select" required>
                                <option value="consultation_generale">Consultation Générale</option>
                                <option value="consultation_specialisee">Consultation Spécialisée</option>
                                <option value="suivi">Suivi</option>
                                <option value="urgence">Urgence</option>
                                <option value="controle">Contrôle</option>
                            </select>
                        </div>

                        <div class="col-md-8 mb-3">
                            <label class="form-label">Motif *</label>
                            <textarea name="motif" class="form-control" rows="2" required placeholder="Motif de la consultation..."></textarea>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Prix (USD)</label>
                            <input type="number" name="prix" class="form-control" step="0.01" min="0" value="0">
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Notes additionnelles..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Créer le Rendez-vous
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

