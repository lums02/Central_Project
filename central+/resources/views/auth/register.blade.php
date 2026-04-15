{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>
        @if($userType === 'patient')
            Mon Espace Personnel - Inscription Patient
        @elseif($selectedEntity)
            Inscription {{ ucfirst($selectedEntity) }} - Plateforme Santé
        @else
            Inscription - Plateforme Santé
        @endif
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="{{ asset('css/register-validation.css') }}" rel="stylesheet" />
    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .register-container {
            max-width: 600px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
        
        .register-header {
            background: #003366;
            color: white;
            padding: 2rem;
            border-radius: 12px 12px 0 0;
            text-align: center;
        }
        
        .register-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 1;
        }
        
        .register-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0.5rem 0 0 0;
            position: relative;
            z-index: 1;
        }
        
        .register-body {
            padding: 2.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #003366;
            box-shadow: 0 0 0 0.2rem rgba(0, 51, 102, 0.25);
            background: white;
        }
        
        .form-control-plaintext {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: #003366;
        }
        
        .btn-primary {
            background: #003366;
            border: none;
            border-radius: 8px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: #004080;
            transform: translateY(-1px);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.5rem;
        }
        
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border-left: 4px solid #17a2b8;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .login-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }
        
        .login-link a {
            color: #003366;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .login-link a:hover {
            color: #004080;
            text-decoration: underline;
        }
        
        .entity-badge {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .icon-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #003366 0%, #004080 100%);
            color: white;
            border-radius: 50%;
            margin-right: 1rem;
        }
        
        @media (max-width: 768px) {
            .register-container {
                margin: 1rem;
                max-width: none;
            }
            
            .register-header h1 {
                font-size: 2rem;
            }
            
            .register-body {
                padding: 1.5rem;
            }
        }
        
        /* Effet de focus simple */
        .form-control:focus, .form-select:focus {
            border-color: #003366;
            box-shadow: 0 0 0 0.2rem rgba(0, 51, 102, 0.25);
        }
    </style>
</head>
<body>
<div class="register-container mx-auto">
    <div class="register-header">
        <h1>
            @if($userType === 'patient')
                <i class="fas fa-heartbeat me-2"></i>Mon Espace Personnel
            @elseif($userType === 'pharmacie')
                <i class="fas fa-store me-2"></i>Mon Espace Pharmacie
            @elseif($userType === 'banque_sang')
                <i class="fas fa-tint me-2"></i>Mon Espace Banque de Sang
            @elseif($selectedEntity)
                Inscription {{ ucfirst($selectedEntity) }}
            @else
                Inscription
            @endif
        </h1>
        <p>
            @if($userType === 'patient')
                Créez votre compte patient pour accéder à vos informations médicales
            @elseif($userType === 'pharmacie')
                Créez votre compte pharmacie pour gérer votre établissement
            @else
                Rejoignez la plateforme CENTRAL+ et commencez votre expérience
            @endif
        </p>
    </div>
    
    <div class="register-body">
            @if($selectedEntity)
            <div class="alert alert-info text-center mb-4">
                Vous vous inscrivez en tant que 
                <span class="entity-badge">
                    @switch($selectedEntity)
                        @case('hopital')
                            Hôpital
                            @break
                        @case('pharmacie')
                            Pharmacie
                            @break
                        @case('banque_sang')
                            Banque de sang
                            @break
                        @case('centre')
                            Centre médical
                            @break
                        @case('patient')
                            Patient
                            @break
                    @endswitch
                </span>
            </div>
        @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="registerForm" action="{{ route('register.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Champs cachés pour le plan et paiement --}}
        @if(isset($selectedPlan))
            <input type="hidden" name="selected_plan" value="{{ $selectedPlan }}">
            <input type="hidden" name="selected_period" value="{{ $selectedPeriod ?? 'monthly' }}">
            <input type="hidden" name="payment_method" value="{{ $paymentMethod ?? '' }}">
            <input type="hidden" name="amount" value="{{ $amount ?? '' }}">
            
            <div class="alert alert-success mb-4">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Paiement confirmé !</strong> 
                <div class="mt-2">
                    <strong>Plan :</strong> {{ ucfirst($selectedPlan) }} 
                    @if(isset($selectedPeriod))
                        ({{ $selectedPeriod === 'yearly' ? 'Annuel' : 'Mensuel' }})
                    @endif
                    <br>
                    @if(isset($paymentMethod))
                        <strong>Mode de paiement :</strong> 
                        @switch($paymentMethod)
                            @case('orange-money')
                                Orange Money
                                @break
                            @case('airtel-money')
                                Airtel Money
                                @break
                            @case('mpesa')
                                M-Pesa
                                @break
                            @case('visa')
                                Visa
                                @break
                            @case('mastercard')
                                Mastercard
                                @break
                            @default
                                {{ ucfirst($paymentMethod) }}
                        @endswitch
                    @endif
                    <br>
                    @if(isset($amount))
                        <strong>Montant :</strong> €{{ $amount }}
                    @endif
                </div>
            </div>
        @endif

        @if(!$selectedEntity && $userType !== 'patient')
            {{-- Afficher le sélecteur seulement si aucune entité n'est présélectionnée et ce n'est pas un patient --}}
            <div class="form-group">
                <label for="type_utilisateur" class="form-label">Type d'entité</label>
                <select name="type_utilisateur" id="type_utilisateur" class="form-select" required onchange="toggleFields()">
                    <option value="" disabled selected>-- Sélectionnez une entité --</option>
                    <option value="hopital" {{ old('type_utilisateur') == 'hopital' ? 'selected' : '' }}>Hôpital</option>
                    <option value="pharmacie" {{ old('type_utilisateur') == 'pharmacie' ? 'selected' : '' }}>Pharmacie</option>
                    <option value="banque_sang" {{ old('type_utilisateur') == 'banque_sang' ? 'selected' : '' }}>Banque de sang</option>
                    <option value="centre" {{ old('type_utilisateur') == 'centre' ? 'selected' : '' }}>Centre médical</option>
                    <option value="patient" {{ old('type_utilisateur') == 'patient' ? 'selected' : '' }}>Patient</option>
                </select>
            </div>
        @else
            {{-- Entité présélectionnée - champ caché --}}
            <input type="hidden" name="type_utilisateur" id="type_utilisateur" value="{{ $userType === 'patient' ? 'patient' : $selectedEntity }}">
            
            {{-- Afficher l'entité sélectionnée --}}
            <div class="form-group">
                <label class="form-label">Type d'entité</label>
                <div class="form-control-plaintext">
                    <strong>
                        @switch($userType === 'patient' ? 'patient' : $selectedEntity)
                            @case('hopital')
                                Hôpital
                                @break
                            @case('pharmacie')
                                Pharmacie
                                @break
                            @case('banque_sang')
                                Banque de sang
                                @break
                            @case('centre')
                                Centre médical
                                @break
                            @case('patient')
                                Patient
                                @break
                        @endswitch
                    </strong>
                </div>
            </div>
        @endif

        {{-- Champs communs à toutes les entités --}}
        <div class="form-group">
            <label for="nom" class="form-label">
                @if($selectedEntity && $selectedEntity != 'patient')
                    Raison sociale
                @else
                    Nom complet
                @endif
            </label>
            <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}" 
                   placeholder="@if($selectedEntity && $selectedEntity != 'patient')Nom de votre établissement @else Votre nom complet @endif" required />
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Adresse email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" 
                   placeholder="votre.email@exemple.com" required />
        </div>



        <div class="form-group" id="adresse-group">
            <label for="adresse" class="form-label">Adresse</label>
            <textarea name="adresse" id="adresse" rows="3" class="form-control" 
                      placeholder="Adresse complète de votre établissement">{{ old('adresse') }}</textarea>
        </div>

        {{-- Champs spécifiques hôpital, pharmacie, banque_sang, centre --}}
        <div class="form-group" id="logo-group">
            <label for="logo" class="form-label">Logo de l'établissement</label>
            <input type="file" name="logo" id="logo" class="form-control" accept="image/*" />
            <small class="form-text text-muted">Format : JPG, PNG, GIF (max 2MB)</small>
        </div>

        <div class="form-group" id="type_hopital-group">
            <label for="type_hopital" class="form-label">Type d'hôpital</label>
            <select name="type_hopital" id="type_hopital" class="form-select">
                <option value="" disabled selected>-- Sélectionnez un type --</option>
                <option value="Général" {{ old('type_hopital') == 'Général' ? 'selected' : '' }}>Hôpital Général</option>
                <option value="Spécialisé" {{ old('type_hopital') == 'Spécialisé' ? 'selected' : '' }}>Hôpital Spécialisé</option>
                <option value="Clinique" {{ old('type_hopital') == 'Clinique' ? 'selected' : '' }}>Clinique</option>
                <option value="Centre Médical" {{ old('type_hopital') == 'Centre Médical' ? 'selected' : '' }}>Centre Médical</option>
            </select>
        </div>

        {{-- Champs spécifiques patients --}}
        <div class="form-group" id="date_naissance-group" style="display:none;">
            <label for="date_naissance" class="form-label">Date de naissance</label>
            <input type="date" name="date_naissance" id="date_naissance" class="form-control" value="{{ old('date_naissance') }}" />
        </div>

        <div class="form-group" id="sexe-group" style="display:none;">
            <label for="sexe" class="form-label">Sexe</label>
            <select name="sexe" id="sexe" class="form-select">
                <option value="" disabled selected>-- Sélectionnez --</option>
                <option value="masculin" {{ old('sexe') == 'masculin' ? 'selected' : '' }}>Masculin</option>
                <option value="feminin" {{ old('sexe') == 'feminin' ? 'selected' : '' }}>Féminin</option>
            </select>
        </div>

        <div class="form-group" id="hopital-group" style="display:none;">
            <label for="hopital_id" class="form-label">Hôpital (optionnel)</label>
            <select name="hopital_id" id="hopital_id" class="form-select">
                <option value="">-- Aucun hôpital sélectionné --</option>
                @php
                    $hopitaux = \App\Models\Hopital::orderBy('nom')->get();
                @endphp
                @foreach($hopitaux as $hopital)
                <option value="{{ $hopital->id }}" {{ old('hopital_id') == $hopital->id ? 'selected' : '' }}>
                    {{ $hopital->nom }} - {{ $hopital->adresse }}
                </option>
                @endforeach
            </select>
            <small class="text-muted">Vous pouvez choisir un hôpital ou le faire plus tard</small>
        </div>

        <div class="form-group" id="telephone-group" style="display:none;">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="tel" name="telephone" id="telephone" class="form-control" value="{{ old('telephone') }}" 
                   placeholder="Ex: +243 123 456 789" />
        </div>

        <div class="form-group" id="groupe-sanguin-group" style="display:none;">
            <label for="groupe_sanguin" class="form-label">Groupe Sanguin (optionnel)</label>
            <select name="groupe_sanguin" id="groupe_sanguin" class="form-select">
                <option value="">-- Non renseigné --</option>
                <option value="A+" {{ old('groupe_sanguin') == 'A+' ? 'selected' : '' }}>A+</option>
                <option value="A-" {{ old('groupe_sanguin') == 'A-' ? 'selected' : '' }}>A-</option>
                <option value="B+" {{ old('groupe_sanguin') == 'B+' ? 'selected' : '' }}>B+</option>
                <option value="B-" {{ old('groupe_sanguin') == 'B-' ? 'selected' : '' }}>B-</option>
                <option value="AB+" {{ old('groupe_sanguin') == 'AB+' ? 'selected' : '' }}>AB+</option>
                <option value="AB-" {{ old('groupe_sanguin') == 'AB-' ? 'selected' : '' }}>AB-</option>
                <option value="O+" {{ old('groupe_sanguin') == 'O+' ? 'selected' : '' }}>O+</option>
                <option value="O-" {{ old('groupe_sanguin') == 'O-' ? 'selected' : '' }}>O-</option>
            </select>
        </div>

        {{-- Champs communs mot de passe --}}
        <div class="form-group">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" name="password" id="password" class="form-control" 
                   placeholder="Minimum 8 caractères" required />
            <div id="password-strength"></div>
            <div class="password-criteria">
                <h6><i class="fas fa-shield-alt"></i> Critères du mot de passe sécurisé :</h6>
                <ul>
                    <li><i class="fas fa-circle"></i> Au moins 8 caractères</li>
                    <li><i class="fas fa-circle"></i> Une lettre majuscule (A-Z)</li>
                    <li><i class="fas fa-circle"></i> Une lettre minuscule (a-z)</li>
                    <li><i class="fas fa-circle"></i> Un chiffre (0-9)</li>
                    <li><i class="fas fa-circle"></i> Un caractère spécial (@$!%*?&#)</li>
                </ul>
            </div>
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" 
                   placeholder="Répétez votre mot de passe" required />
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary w-100">
                Créer mon compte
            </button>
        </div>
    </form>

        <div class="login-link">
            <p class="mb-0">
                Déjà inscrit ? 
            @if($userType === 'patient')
                <a href="{{ route('login') }}?type=patient" class="fw-bold">
                    Accéder à mon espace
                </a>
            @elseif($userType === 'pharmacie')
                <a href="{{ route('login') }}?type=pharmacie" class="fw-bold">
                    Accéder à mon espace
                </a>
            @elseif($userType === 'banque_sang')
                <a href="{{ route('login') }}?type=banque_sang" class="fw-bold">
                    Accéder à mon espace
                </a>
            @else
                <a href="{{ route('login') }}" class="fw-bold">
                    Se connecter
                </a>
            @endif
            </p>
            @if($userType === 'patient')
                <p class="mb-0 mt-2">
                    <a href="{{ route('patient.index') }}" class="fw-bold">
                        ← Retour à l'accueil patient
                    </a>
                </p>
            @elseif($userType === 'pharmacie')
                <p class="mb-0 mt-2">
                    <a href="{{ route('pharmacie.index') }}" class="fw-bold">
                        ← Retour à l'accueil pharmacie
                    </a>
                </p>
            @elseif($userType === 'banque_sang')
                <p class="mb-0 mt-2">
                    <a href="{{ route('banque.index') }}" class="fw-bold">
                        ← Retour à l'accueil banque de sang
                    </a>
                </p>
            @endif
        </div>
    </div>
</div>

<script>
    function toggleFields() {
        const type = document.getElementById('type_utilisateur').value;
        // Champs spécifiques
        const logoGroup = document.getElementById('logo-group');
        const typeHopitalGroup = document.getElementById('type_hopital-group');
        const dateNaissanceGroup = document.getElementById('date_naissance-group');
        const sexeGroup = document.getElementById('sexe-group');
        const adresseGroup = document.getElementById('adresse-group');

        // Cacher tout par défaut
        logoGroup.style.display = 'none';
        typeHopitalGroup.style.display = 'none';
        dateNaissanceGroup.style.display = 'none';
        sexeGroup.style.display = 'none';
        adresseGroup.style.display = 'none';

        // Montrer selon entité
        if (type === 'hopital' || type === 'pharmacie' || type === 'banque_sang' || type === 'centre') {
            logoGroup.style.display = 'block';
            adresseGroup.style.display = 'block';
            if(type === 'hopital'){
                typeHopitalGroup.style.display = 'block';
            }
        } else if(type === 'patient') {
            dateNaissanceGroup.style.display = 'block';
            sexeGroup.style.display = 'block';
            const hopitalGroup = document.getElementById('hopital-group');
            const telephoneGroup = document.getElementById('telephone-group');
            const groupeSanguinGroup = document.getElementById('groupe-sanguin-group');
            if (hopitalGroup) hopitalGroup.style.display = 'block';
            if (telephoneGroup) telephoneGroup.style.display = 'block';
            if (groupeSanguinGroup) groupeSanguinGroup.style.display = 'block';
        }
    }

    // Appeler au chargement pour gérer la sélection précédente
    document.addEventListener('DOMContentLoaded', function() {
        // Si une entité est présélectionnée, afficher les champs appropriés
        @if($selectedEntity)
            toggleFieldsForEntity('{{ $selectedEntity }}');
        @elseif($userType === 'patient')
            toggleFieldsForEntity('patient');
        @elseif($userType === 'pharmacie')
            toggleFieldsForEntity('pharmacie');
        @elseif($userType === 'banque_sang')
            toggleFieldsForEntity('banque_sang');
        @else
            toggleFields();
        @endif
    });
    
    // Fonction pour afficher les champs selon l'entité présélectionnée
    function toggleFieldsForEntity(entityType) {
        const logoGroup = document.getElementById('logo-group');
        const typeHopitalGroup = document.getElementById('type_hopital-group');
        const dateNaissanceGroup = document.getElementById('date_naissance-group');
        const sexeGroup = document.getElementById('sexe-group');
        const adresseGroup = document.getElementById('adresse-group');

        // Cacher tout par défaut
        logoGroup.style.display = 'none';
        typeHopitalGroup.style.display = 'none';
        dateNaissanceGroup.style.display = 'none';
        sexeGroup.style.display = 'none';
        adresseGroup.style.display = 'none';

        // Montrer selon entité
        if (entityType === 'hopital' || entityType === 'pharmacie' || entityType === 'banque_sang' || entityType === 'centre') {
            logoGroup.style.display = 'block';
            adresseGroup.style.display = 'block';
            if(entityType === 'hopital'){
                typeHopitalGroup.style.display = 'block';
            }
        } else if(entityType === 'patient') {
            dateNaissanceGroup.style.display = 'block';
            sexeGroup.style.display = 'block';
            const hopitalGroup = document.getElementById('hopital-group');
            const telephoneGroup = document.getElementById('telephone-group');
            const groupeSanguinGroup = document.getElementById('groupe-sanguin-group');
            if (hopitalGroup) hopitalGroup.style.display = 'block';
            if (telephoneGroup) telephoneGroup.style.display = 'block';
            if (groupeSanguinGroup) groupeSanguinGroup.style.display = 'block';
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/register-validation.js') }}"></script>
</body>
</html>
