@extends('layouts.admin')

@section('title', 'Accueil Admin')

@section('content')
<div class="container-fluid">
    <!-- En-tête de bienvenue -->
    <div class="page-header mb-4" style="background: #003366; padding: 2rem; border-radius: 8px;">
        <h1 style="color: white; margin: 0; font-size: 2.2rem; font-weight: 600;">
            <i class="fas fa-home me-3"></i>Bienvenue dans l'Administration
        </h1>
        <p style="color: #b3d9ff; margin: 0.5rem 0 0 0; font-size: 1.1rem;">
            Système Central+ - Gestion centralisée
        </p>
    </div>

    <!-- Message de redirection -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-arrow-right fa-3x text-primary mb-3"></i>
                    <h3 class="text-dark mb-3">Redirection automatique...</h3>
                    <p class="text-muted mb-4">
                        Vous allez être redirigé vers votre tableau de bord dans quelques secondes.
                    </p>
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Redirection automatique vers le dashboard après 2 secondes
setTimeout(function() {
    window.location.href = '{{ route("admin.dashboard") }}';
}, 2000);
</script>
@endsection
