@extends('layouts.admin')

@section('title', 'Test Layout - CENTRAL+')
@section('page-title', 'Test Layout')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Test du Layout Admin</h5>
                    <p class="card-text">Cette page teste le positionnement des éléments dans la partie admin.</p>
                    <div class="alert alert-success">
                        ✅ Si vous voyez cette page avec une sidebar à gauche et un contenu principal à droite, le layout fonctionne correctement !
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6>Sidebar</h6>
                                    <p>La sidebar devrait être fixe à gauche avec une largeur de 250px.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6>Contenu Principal</h6>
                                    <p>Le contenu principal devrait être à droite de la sidebar.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
