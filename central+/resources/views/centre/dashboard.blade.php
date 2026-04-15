@extends('layouts.app')

@section('title', 'Dashboard Centre - CENTRAL+')

@section('content')
<div class="container-fluid">
    <div class="page-header mb-4" style="background: linear-gradient(135deg, #003366 0%, #002244 100%); padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h1 style="color: white; margin: 0; font-size: 2.2rem; font-weight: 600;">
            <i class="fas fa-clinic-medical me-3" style="color: #00a8e8;"></i>Dashboard Centre
        </h1>
        <p style="color: #b3d9ff; margin: 0.5rem 0 0 0; font-size: 1.1rem;">
            Bienvenue, {{ auth()->user()->nom }} - Gestion de votre centre
        </p>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Dashboard Centre en développement</strong><br>
        Les fonctionnalités complètes seront bientôt disponibles.
    </div>
</div>
@endsection
