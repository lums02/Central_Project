@extends('layouts.admin')

@section('title', 'Gestion des Ventes')
@section('page-title', 'Gestion des Ventes')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-cash-register me-2"></i>Gestion des Ventes</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nouvelleVenteModal">
            <i class="fas fa-plus me-2"></i>Nouvelle Vente
        </button>
    </div>

    <!-- Statistiques du jour -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success">$0.00</h3>
                    <p class="mb-0">Ventes du Jour</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary">0</h3>
                    <p class="mb-0">Transactions</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info">$0.00</h3>
                    <p class="mb-0">Ventes du Mois</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des ventes -->
    <div class="card">
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Aucune vente enregistrée pour le moment.
            </div>
        </div>
    </div>
</div>
@endsection

