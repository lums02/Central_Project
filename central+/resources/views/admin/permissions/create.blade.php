@extends('layouts.admin')

@section('title', 'Créer une Permission - CENTRAL+')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Entête simple -->
            <div class="page-header mb-4" style="background: #003366; padding: 1.5rem; border-radius: 8px;">
                <h1 style="color: white; margin: 0; font-size: 1.8rem; font-weight: 500;">Créer une Permission</h1>
            </div>

            <!-- Formulaire simple -->
            <div class="row justify-content-center">
                <div class="col-lg-5 col-xl-4">
                    <div class="permission-form" style="background: white; padding: 2rem; border-radius: 8px; border: 1px solid #e9ecef;">
                        
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.permissions.store') }}" method="POST">
                            @csrf
                            
                            <!-- Nom de la permission -->
                            <div class="form-group mb-4">
                                <label for="name" class="form-label" style="color: #003366; font-weight: 600; margin-bottom: 0.5rem;">
                                    Nom de la permission
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       style="border: 1px solid #ced4da; border-radius: 4px; padding: 0.75rem;"
                                       placeholder="Ex: gérer_les_utilisateurs" 
                                       value="{{ old('name') }}"
                                       required
                                       maxlength="100">
                                
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Boutons d'action -->
                            <div class="d-flex justify-content-between align-items-center pt-3" style="border-top: 1px solid #e9ecef;">
                                <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                                    Retour
                                </a>
                                
                                <button type="submit" class="btn" style="background: #003366; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 4px;">
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-control:focus {
    border-color: #17a2b8;
    box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
}

.btn:hover {
    opacity: 0.9;
}
</style>
@endsection