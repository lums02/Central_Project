@extends('layouts.admin')

@section('title', 'Dashboard Médecin')
@section('page-title', 'Dashboard Médecin')

@section('content')
<style>
.stats-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    height: 100%;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

/* Cartes modernes */
.modern-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow: hidden;
}

.modern-card-header {
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modern-card-body {
    padding: 20px;
}

/* Items patients */
.patient-item, .dossier-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 10px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.patient-item:hover, .dossier-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.patient-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e9ecef;
    color: #495057;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: 600;
}

.dossier-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e9ecef;
    color: #495057;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.patient-info, .dossier-info {
    flex: 1;
}

.patient-info h6, .dossier-info h6 {
    margin: 0;
    font-weight: 600;
    color: #2c3e50;
}

.patient-meta, .dossier-meta {
    text-align: right;
}
</style>

<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card" style="border-left: 5px solid #003366;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0" style="color: #003366; font-weight: 600;">{{ $stats['total_patients'] }}</h3>
                    <p class="mb-0" style="color: #666; font-size: 0.9rem;">Total Patients</p>
                </div>
                <div style="background: linear-gradient(135deg, #003366 0%, #002244 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-users fa-2x text-white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card" style="border-left: 5px solid #28a745;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0" style="color: #28a745; font-weight: 600;">{{ $stats['total_dossiers'] }}</h3>
                    <p class="mb-0" style="color: #666; font-size: 0.9rem;">Dossiers Médicaux</p>
                </div>
                <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-file-medical fa-2x text-white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card" style="border-left: 5px solid #00a8e8;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0" style="color: #00a8e8; font-weight: 600;">{{ $stats['dossiers_actifs'] }}</h3>
                    <p class="mb-0" style="color: #666; font-size: 0.9rem;">Dossiers Actifs</p>
                </div>
                <div style="background: linear-gradient(135deg, #00a8e8 0%, #007bff 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-circle fa-2x text-white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card" style="border-left: 5px solid #ffc107;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0" style="color: #ffc107; font-weight: 600;">{{ $stats['consultations_aujourd_hui'] }}</h3>
                    <p class="mb-0" style="color: #666; font-size: 0.9rem;">Consultations Aujourd'hui</p>
                </div>
                <div style="background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-calendar-day fa-2x text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Patients récents -->
    <div class="col-xl-6 mb-4">
        <div class="modern-card">
            <div class="modern-card-header">
                <div>
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Mes Patients Récents</h5>
                    <small class="text-muted">{{ $patients->take(5)->count() }} patients</small>
                </div>
                <a href="{{ route('admin.medecin.patients') }}" class="btn btn-sm btn-outline-primary">
                    Voir Tout <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="modern-card-body">
                @forelse($patients->take(5) as $patient)
                    <div class="patient-item">
                        <div class="patient-avatar">
                            {{ substr($patient->nom, 0, 1) }}
                        </div>
                        <div class="patient-info">
                            <h6 class="mb-0">{{ $patient->nom }}</h6>
                            <small class="text-muted">
                                <i class="fas fa-envelope me-1"></i>{{ $patient->email }}
                            </small>
                        </div>
                        <div class="patient-meta">
                            @php
                                $dernierDossier = $patient->dossiers()->where('medecin_id', auth()->id())->latest()->first();
                            @endphp
                            <small class="text-muted d-block">Dernière consultation</small>
                            <strong>{{ $dernierDossier ? $dernierDossier->date_consultation->format('d/m/Y') : 'Aucune' }}</strong>
                        </div>
                        <div class="patient-actions">
                            <a href="{{ route('admin.medecin.dossiers') }}?patient={{ $patient->id }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-folder-open"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-user-slash fa-3x mb-3 opacity-25"></i>
                        <p>Aucun patient trouvé</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Dossiers récents -->
    <div class="col-xl-6 mb-4">
        <div class="modern-card">
            <div class="modern-card-header">
                <div>
                    <h5 class="mb-0"><i class="fas fa-file-medical me-2"></i>Dossiers Récents</h5>
                    <small class="text-muted">{{ $dossiers->take(5)->count() }} dossiers</small>
                </div>
                <a href="{{ route('admin.medecin.dossiers') }}" class="btn btn-sm btn-outline-primary">
                    Voir Tout <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="modern-card-body">
                @forelse($dossiers->take(5) as $dossier)
                    <div class="dossier-item">
                        <div class="dossier-icon">
                            <i class="fas fa-file-medical-alt"></i>
                        </div>
                        <div class="dossier-info">
                            <h6 class="mb-0">{{ $dossier->patient->nom }}</h6>
                            <small class="text-muted">
                                <i class="fas fa-hashtag me-1"></i>{{ $dossier->numero_dossier }}
                            </small>
                        </div>
                        <div class="dossier-meta">
                            <span class="badge bg-success">{{ $dossier->statut }}</span>
                            <small class="text-muted d-block mt-1">{{ $dossier->date_consultation->format('d/m/Y') }}</small>
                        </div>
                        <div class="dossier-actions">
                            <a href="{{ route('admin.medecin.dossier.show', $dossier->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                        <p>Aucun dossier trouvé</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Animation au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Animer les cartes de stats
    const statsCards = document.querySelectorAll('.stats-card');
    statsCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        }, index * 100);
    });
});
</script>
@endsection
