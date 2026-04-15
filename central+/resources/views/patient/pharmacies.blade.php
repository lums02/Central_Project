@extends('layouts.admin')
@section('title', 'Trouver une Pharmacie')
@section('page-title', 'Trouver une Pharmacie')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-pills text-success me-2"></i>Trouver une Pharmacie
        </h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-search me-2"></i>Rechercher des Médicaments
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('patient.pharmacies') }}" method="GET" id="searchForm" onsubmit="console.log('Form submitted')">
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Recherchez un ou plusieurs médicaments pour trouver les pharmacies proches qui les ont en stock.
                </div>

                <div id="medicamentsContainer">
                    @if(!empty($searchTerms) && is_array($searchTerms) && count($searchTerms) > 0)
                        @foreach($searchTerms as $index => $term)
                        <div class="mb-3 medicament-row">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-pills"></i></span>
                                <input type="text" name="medicaments[]" class="form-control medicament-search" 
                                       placeholder="Nom du médicament (ex: Paracétamol...)" 
                                       value="{{ $term }}" autocomplete="off">
                                @if($index > 0)
                                <button type="button" class="btn btn-danger remove-medicament">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </div>
                            <div class="autocomplete-suggestions" style="display: none;"></div>
                        </div>
                        @endforeach
                    @else
                    <div class="mb-3 medicament-row">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-pills"></i></span>
                            <input type="text" name="medicaments[]" class="form-control medicament-search" 
                                   placeholder="Nom du médicament (ex: Paracétamol...)" 
                                   autocomplete="off">
                        </div>
                        <div class="autocomplete-suggestions" style="display: none;"></div>
                    </div>
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-secondary" id="addMedicament">
                        <i class="fas fa-plus me-2"></i>Ajouter un médicament
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(!empty($searchTerms) && count(array_filter($searchTerms)) > 0)
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-map-marker-alt me-2"></i>Pharmacies Disponibles ({{ $pharmacies->count() }})
            </h6>
        </div>
        <div class="card-body">
            @forelse($pharmacies as $pharmacie)
            <div class="card border-left-success shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h5 class="text-dark mb-2">
                                <i class="fas fa-store text-success me-2"></i>{{ $pharmacie->nom }}
                            </h5>
                            <p class="text-muted mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $pharmacie->adresse }}
                            </p>
                            @if($pharmacie->telephone)
                            <p class="text-muted mb-2">
                                <i class="fas fa-phone me-1"></i>{{ $pharmacie->telephone }}
                            </p>
                            @endif
                            
                            <div class="mt-3">
                                <h6 class="text-success mb-2">
                                    <i class="fas fa-check-circle me-1"></i>Médicaments disponibles :
                                </h6>
                                <div class="row">
                                    @foreach($medicamentsDisponibles[$pharmacie->id] ?? [] as $medicament)
                                    <div class="col-md-6 mb-2">
                                        <div class="border rounded p-2 bg-light">
                                            <strong>{{ $medicament->nom }}</strong>
                                            @if($medicament->forme)
                                            <small class="text-muted d-block">{{ $medicament->forme }}</small>
                                            @endif
                                            <small class="text-success d-block">
                                                <i class="fas fa-box me-1"></i>Stock: {{ $medicament->stock_actuel }}
                                            </small>
                                            @if($medicament->prix_vente)
                                            <small class="text-primary d-block">
                                                {{ number_format($medicament->prix_vente, 0, ',', ' ') }} FCFA
                                            </small>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <div class="ms-3">
                            <a href="tel:{{ $pharmacie->telephone }}" class="btn btn-success btn-sm mb-2 d-block">
                                <i class="fas fa-phone me-1"></i>Appeler
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x mb-3" style="opacity: 0.3; color: #28a745;"></i>
                <h5 class="text-gray-800">Aucune pharmacie trouvée</h5>
                <p class="text-muted">Aucune pharmacie ne dispose de ces médicaments pour le moment.</p>
            </div>
            @endforelse
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addMedicament').addEventListener('click', function() {
        const container = document.getElementById('medicamentsContainer');
        const newRow = document.createElement('div');
        newRow.className = 'mb-3 medicament-row';
        newRow.innerHTML = '<div class="input-group"><span class="input-group-text"><i class="fas fa-pills"></i></span><input type="text" name="medicaments[]" class="form-control medicament-search" placeholder="Nom du médicament" autocomplete="off"><button type="button" class="btn btn-danger remove-medicament"><i class="fas fa-times"></i></button></div><div class="autocomplete-suggestions" style="display: none;"></div>';
        container.appendChild(newRow);
        attachAutocomplete(newRow.querySelector('.medicament-search'));
        newRow.querySelector('.remove-medicament').addEventListener('click', function() {
            newRow.remove();
        });
    });
    
    document.querySelectorAll('.remove-medicament').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.medicament-row').remove();
        });
    });
    
    document.querySelectorAll('.medicament-search').forEach(input => {
        attachAutocomplete(input);
    });
    
    function attachAutocomplete(input) {
        let timeout = null;
        const suggestionsDiv = input.closest('.medicament-row').querySelector('.autocomplete-suggestions');
        
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            const term = this.value;
            
            if (term.length < 2) {
                suggestionsDiv.style.display = 'none';
                return;
            }
            
            timeout = setTimeout(() => {
                fetch('{{ route('patient.search.medicaments') }}?term=' + encodeURIComponent(term))
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            suggestionsDiv.innerHTML = data.map(item => '<div class="suggestion-item p-2 border-bottom" style="cursor: pointer;">' + item.label + '</div>').join('');
                            suggestionsDiv.style.display = 'block';
                            suggestionsDiv.style.position = 'absolute';
                            suggestionsDiv.style.background = 'white';
                            suggestionsDiv.style.border = '1px solid #ddd';
                            suggestionsDiv.style.borderRadius = '4px';
                            suggestionsDiv.style.width = input.offsetWidth + 'px';
                            suggestionsDiv.style.zIndex = '1000';
                            suggestionsDiv.style.maxHeight = '200px';
                            suggestionsDiv.style.overflowY = 'auto';
                            
                            suggestionsDiv.querySelectorAll('.suggestion-item').forEach((item, index) => {
                                item.addEventListener('click', function() {
                                    input.value = data[index].value;
                                    suggestionsDiv.style.display = 'none';
                                });
                                item.addEventListener('mouseenter', function() {
                                    this.style.backgroundColor = '#f0f0f0';
                                });
                                item.addEventListener('mouseleave', function() {
                                    this.style.backgroundColor = 'white';
                                });
                            });
                        } else {
                            suggestionsDiv.style.display = 'none';
                        }
                    });
            }, 300);
        });
        
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.style.display = 'none';
            }
        });
    }
});
</script>
@endsection

