@extends('layouts.admin')

@section('title', 'Dashboard - Hôpital')
@section('page-title', 'Tableau de Bord Hôpital')

@section('content')
<div class="container-fluid">
    <!-- En-tête du tableau de bord -->
    <div class="page-header mb-4" style="background: linear-gradient(135deg, #003366 0%, #002244 100%); padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h1 style="color: white; margin: 0; font-size: 2.2rem; font-weight: 600;">
            @if($data['dashboard_type'] === 'superadmin')
                <i class="fas fa-tachometer-alt me-3" style="color: #00a8e8;"></i>Tableau de Bord Super Admin
            @elseif($data['dashboard_type'] === 'entity_admin')
                <i class="fas fa-hospital me-3" style="color: #00a8e8;"></i>Tableau de Bord {{ $data['entity_name'] }}
            @else
                <i class="fas fa-user me-3" style="color: #00a8e8;"></i>Tableau de Bord Personnel
            @endif
        </h1>
        <p style="color: #b3d9ff; margin: 0.5rem 0 0 0; font-size: 1.1rem;">
            @if($data['dashboard_type'] === 'superadmin')
                Gestion centralisée du système Central+
            @elseif($data['dashboard_type'] === 'entity_admin')
                Gestion de votre hôpital - {{ auth()->user()->nom }}
            @else
                {{ $data['welcome_message'] ?? 'Bienvenue dans votre espace personnel' }}
            @endif
        </p>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        @if($data['dashboard_type'] === 'superadmin')
            <!-- Statistiques Super Admin -->
            <div class="col-md-3">
                <div class="stats-card" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 5px solid #003366;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0" style="color: #003366; font-weight: 600;">{{ $data['total_users'] }}</h3>
                            <p class="mb-0" style="color: #666; font-size: 0.9rem;">Utilisateurs Totaux</p>
                        </div>
                        <div style="background: linear-gradient(135deg, #003366 0%, #002244 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-users fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stats-card" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 5px solid #ffc107;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0" style="color: #ffc107; font-weight: 600;">{{ $data['pending_users'] }}</h3>
                            <p class="mb-0" style="color: #666; font-size: 0.9rem;">En Attente</p>
                        </div>
                        <div style="background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-clock fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stats-card" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 5px solid #28a745;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0" style="color: #28a745; font-weight: 600;">{{ $data['approved_users'] }}</h3>
                            <p class="mb-0" style="color: #666; font-size: 0.9rem;">Approuvés</p>
                        </div>
                        <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-check-circle fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stats-card" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 5px solid #dc3545;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0" style="color: #dc3545; font-weight: 600;">{{ $data['total_roles'] }}</h3>
                            <p class="mb-0" style="color: #666; font-size: 0.9rem;">Rôles</p>
                        </div>
                        <div style="background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-tag fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($data['dashboard_type'] === 'entity_admin')
            <!-- Statistiques Admin Hôpital -->
            @if(in_array('view_users', $data['user_permissions']))
            <div class="col-md-3">
                <div class="stats-card" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 5px solid #003366;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0" style="color: #003366; font-weight: 600;">{{ $data['total_users'] }}</h3>
                            <p class="mb-0" style="color: #666; font-size: 0.9rem;">Utilisateurs {{ $data['entity_name'] }}</p>
                        </div>
                        <div style="background: linear-gradient(135deg, #003366 0%, #002244 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-users fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            @if(in_array('view_users', $data['user_permissions']))
            <div class="col-md-3">
                <div class="stats-card" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 5px solid #ffc107;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0" style="color: #ffc107; font-weight: 600;">{{ $data['pending_users'] }}</h3>
                            <p class="mb-0" style="color: #666; font-size: 0.9rem;">En Attente</p>
                        </div>
                        <div style="background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-clock fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            @if(in_array('view_users', $data['user_permissions']))
            <div class="col-md-3">
                <div class="stats-card" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 5px solid #28a745;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0" style="color: #28a745; font-weight: 600;">{{ $data['approved_users'] }}</h3>
                            <p class="mb-0" style="color: #666; font-size: 0.9rem;">Approuvés</p>
                        </div>
                        <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-check-circle fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            @if(in_array('view_patients', $data['user_permissions']))
            <div class="col-md-3">
                <div class="stats-card" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 5px solid #dc3545;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0" style="color: #dc3545; font-weight: 600;">{{ $data['total_patients'] }}</h3>
                            <p class="mb-0" style="color: #666; font-size: 0.9rem;">Patients</p>
                        </div>
                        <div style="background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-injured fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            @if(in_array('view_appointments', $data['user_permissions']))
            <div class="col-md-3">
                <div class="stats-card" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 5px solid #ffc107;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0" style="color: #ffc107; font-weight: 600;">{{ $data['total_appointments'] }}</h3>
                            <p class="mb-0" style="color: #666; font-size: 0.9rem;">Rendez-vous</p>
                        </div>
                        <div style="background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-calendar-check fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            @if(in_array('view_medical_records', $data['user_permissions']))
            <div class="col-md-3">
                <div class="stats-card" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 5px solid #28a745;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0" style="color: #28a745; font-weight: 600;">{{ $data['total_medical_records'] }}</h3>
                            <p class="mb-0" style="color: #666; font-size: 0.9rem;">Dossiers Médicaux</p>
                        </div>
                        <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-file-medical fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            @if(in_array('view_prescriptions', $data['user_permissions']))
            <div class="col-md-3">
                <div class="stats-card" style="background: white; border-radius: 15px; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; border-left: 5px solid #dc3545;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0" style="color: #dc3545; font-weight: 600;">{{ $data['total_prescriptions'] }}</h3>
                            <p class="mb-0" style="color: #666; font-size: 0.9rem;">Prescriptions</p>
                        </div>
                        <div style="background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-prescription-bottle-alt fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @else
            <!-- Statistiques Utilisateur Normal -->
            <div class="col-md-12">
                <div class="stats-card" style="background: white; border-radius: 15px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
                    <h3 style="color: #003366; margin-bottom: 1rem;">Bienvenue, {{ $data['user_name'] }}!</h3>
                    <p style="color: #666; font-size: 1.1rem;">Vous êtes connecté en tant que <strong>{{ $data['user_role'] }}</strong> dans le système {{ $data['user_type'] }}.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Actions rapides selon les permissions -->
    @if($data['dashboard_type'] === 'entity_admin')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="card-header" style="background: linear-gradient(135deg, #003366 0%, #002244 100%); color: white; border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Actions Rapides</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if(in_array('create_users', $data['user_permissions']))
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-outline-primary w-100" style="border-radius: 10px; padding: 1rem;">
                                <i class="fas fa-user-plus fa-2x mb-2"></i><br>
                                Nouvel Utilisateur
                            </a>
                        </div>
                        @endif
                        
                        @if(in_array('create_patients', $data['user_permissions']))
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-outline-info w-100" style="border-radius: 10px; padding: 1rem;">
                                <i class="fas fa-user-injured fa-2x mb-2"></i><br>
                                Nouveau Patient
                            </a>
                        </div>
                        @endif
                        
                        @if(in_array('create_appointments', $data['user_permissions']))
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-outline-success w-100" style="border-radius: 10px; padding: 1rem;">
                                <i class="fas fa-calendar-plus fa-2x mb-2"></i><br>
                                Nouveau Rendez-vous
                            </a>
                        </div>
                        @endif
                        
                        @if(in_array('create_prescriptions', $data['user_permissions']))
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-outline-warning w-100" style="border-radius: 10px; padding: 1rem;">
                                <i class="fas fa-prescription-bottle-alt fa-2x mb-2"></i><br>
                                Nouvelle Prescription
                            </a>
                        </div>
                        @endif
                        
                        @if(in_array('view_reports', $data['user_permissions']))
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-outline-info w-100" style="border-radius: 10px; padding: 1rem;">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i><br>
                                Rapports
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Informations sur les permissions -->
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="card-header" style="background: #f8f9fa; border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Vos Permissions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($data['user_permissions'] as $permission)
                        <div class="col-md-4 mb-2">
                            <span class="badge bg-success">{{ $permission }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
