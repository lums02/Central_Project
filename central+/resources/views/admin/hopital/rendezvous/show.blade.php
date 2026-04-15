@extends('layouts.admin')

@section('title', 'Détails du Rendez-vous')
@section('page-title', 'Détails du Rendez-vous')

@section('content')
<div class="container-fluid py-4">
    <a href="{{ route('admin.hopital.rendezvous.index') }}" class="btn btn-outline-primary mb-3">
        <i class="fas fa-arrow-left"></i> Retour
    </a>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Détails du Rendez-vous</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label><strong>Date:</strong></label>
                            <p>{{ \Carbon\Carbon::parse($rendezvous->date_rendezvous)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label><strong>Heure:</strong></label>
                            <p>{{ substr($rendezvous->heure_rendezvous, 0, 5) }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label><strong>Type de Consultation:</strong></label>
                            <p>{{ ucfirst(str_replace('_', ' ', $rendezvous->type_consultation)) }}</p>
                        </div>
                        <div class="col-md-6">
                            <label><strong>Prix:</strong></label>
                            <p>{{ $rendezvous->prix }} USD</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label><strong>Motif:</strong></label>
                        <p>{{ $rendezvous->motif }}</p>
                    </div>
                    @if($rendezvous->notes)
                        <div class="mb-3">
                            <label><strong>Notes:</strong></label>
                            <p>{{ $rendezvous->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Patient</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nom:</strong> {{ $rendezvous->patient->nom }}</p>
                    <p><strong>Email:</strong> {{ $rendezvous->patient->email }}</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-user-md"></i> Médecin</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nom:</strong> Dr. {{ $rendezvous->medecin->nom }}</p>
                    <p><strong>Email:</strong> {{ $rendezvous->medecin->email }}</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Statut</h5>
                </div>
                <div class="card-body text-center">
                    <span class="badge bg-{{ $rendezvous->statut_color }} fs-5">
                        {{ $rendezvous->statut_format }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

