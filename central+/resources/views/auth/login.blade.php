<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Connexion</title>
    @vite('resources/css/login.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            @if($userType === 'patient')
                <i class="fas fa-heartbeat"></i>
                <h1>Mon Espace Personnel</h1>
                <p>Connectez-vous pour accéder à vos informations médicales</p>
            @elseif($userType === 'pharmacie')
                <i class="fas fa-store"></i>
                <h1>Mon Espace Pharmacie</h1>
                <p>Connectez-vous pour gérer votre pharmacie</p>
            @elseif($userType === 'banque_sang')
                <i class="fas fa-tint"></i>
                <h1>Mon Espace Banque de Sang</h1>
                <p>Connectez-vous pour gérer votre banque de sang</p>
            @else
                <i class="fas fa-user-md"></i>
                <h1>Connexion à la plateforme</h1>
                <p>Choisissez votre entité pour accéder à l'espace de gestion</p>
            @endif
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="background:#f8d7da; color:#842029; padding:10px; border-radius:5px;">
                <ul class="mb-0" style="margin:0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            @if($userType === 'patient')
                <input type="hidden" name="user_type" value="patient">
            @endif



            

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus />
            </div>

            <div class="form-group">
                <label for="mot_de_passe"><i class="fas fa-lock"></i> Mot de passe</label>
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required />
            </div>

            <button type="submit" class="btn-login">
                @if($userType === 'patient')
                    <i class="fas fa-heartbeat"></i> Accéder à mon espace
                @else
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                @endif
            </button>
        </form>

        <div class="register-link">
            @if($userType === 'patient')
                <p>Pas encore inscrit ? <a href="{{ route('register.form') }}?type=patient">Créer un compte</a></p>
                <p><a href="{{ route('patient.index') }}">← Retour à l'accueil</a></p>
            @elseif($userType === 'pharmacie')
                <p>Pas encore inscrit ? <a href="{{ route('register.form') }}?type=pharmacie">Créer un compte</a></p>
                <p><a href="{{ route('pharmacie.index') }}">← Retour à l'accueil</a></p>
            @elseif($userType === 'banque_sang')
                <p>Pas encore inscrit ? <a href="{{ route('register.form') }}?type=banque_sang">Créer un compte</a></p>
                <p><a href="{{ route('banque.index') }}">← Retour à l'accueil</a></p>
            @else
                <p>Pas encore inscrit ? <a href="{{ route('register.form') }}">Créer un compte</a></p>
            @endif
        </div>
    </div>
</body>
</html>
