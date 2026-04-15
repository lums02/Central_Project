@extends('layouts.admin')

@section('title', 'Gestion des Analyses')
@section('page-title', 'Gestion des Analyses')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-microscope me-2"></i>Analyses Sanguines</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nouvelleAnalyseModal">
            <i class="fas fa-plus me-2"></i>Nouvelle Analyse
        </button>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-warning">0</h3>
                    <p class="mb-0">En Cours</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success">0</h3>
                    <p class="mb-0">Conformes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-danger">0</h3>
                    <p class="mb-0">Non Conformes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info">0</h3>
                    <p class="mb-0">Total</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des analyses -->
    <div class="card">
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Aucune analyse enregistrée pour le moment.
            </div>
        </div>
    </div>
</div>
@endsection

