<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PharmacieController;
use App\Http\Controllers\BanqueController;
use App\Http\Controllers\MedecinController;
use App\Http\Controllers\ReceptionnisteController;

// Page d'accueil
Route::get('/', function () {
    return view('home');
});


// Routes publiques pour les patients
Route::prefix('patient')->name('patient.')->group(function () {
    // Page d'accueil des patients
    Route::get('/', [PatientController::class, 'index'])->name('index');
    
    // Connexion des patients (supprimé - utilise les routes générales)
    // Route::get('/login', [PatientController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [PatientController::class, 'login'])->name('login.submit');
    
    // Inscription des patients (supprimé - utilise les routes générales)
    // Route::get('/register', [PatientController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [PatientController::class, 'register'])->name('register.submit');
    
    // Mot de passe oublié
    Route::get('/password/request', [PatientController::class, 'showPasswordRequestForm'])->name('password.request');
    Route::post('/password/request', [PatientController::class, 'passwordRequest'])->name('password.request.submit');
    
    // Routes protégées pour les patients connectés
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [PatientController::class, 'logout'])->name('logout');
        
        // Mon dossier médical
        Route::get('/dossiers', [PatientController::class, 'mesDossiers'])->name('dossiers');
        Route::get('/dossiers/{id}', [PatientController::class, 'voirDossier'])->name('dossier.show');
        
        // Mes rendez-vous
        Route::get('/rendezvous', [PatientController::class, 'mesRendezVous'])->name('rendezvous');
        
        // Mes examens
        Route::get('/examens', [PatientController::class, 'mesExamens'])->name('examens');
        
        // Choisir un hôpital
        Route::get('/choisir-hopital', [PatientController::class, 'choisirHopital'])->name('choisir-hopital');
        Route::post('/choisir-hopital', [PatientController::class, 'enregistrerHopital'])->name('enregistrer-hopital');
        
        // Trouver une pharmacie
        Route::get('/pharmacies', [PatientController::class, 'pharmacies'])->name('pharmacies');
        Route::get('/search-medicaments', [PatientController::class, 'searchMedicaments'])->name('search.medicaments');
        
        // Trouver une banque de sang
        Route::get('/banques-sang', [PatientController::class, 'banquesSang'])->name('banques-sang');
        
        // Routes pour les consentements de transfert
        Route::get('/consentements', [\App\Http\Controllers\Admin\TransfertDossierController::class, 'mesConsentements'])->name('consentements');
        Route::post('/consentements/{id}/accepter', [\App\Http\Controllers\Admin\TransfertDossierController::class, 'accepterConsentement'])->name('consentement.accepter');
        Route::post('/consentements/{id}/refuser', [\App\Http\Controllers\Admin\TransfertDossierController::class, 'refuserConsentement'])->name('consentement.refuser');
    });
});

// Routes publiques pour les pharmacies
Route::prefix('pharmacie')->name('pharmacie.')->group(function () {
    // Page d'accueil des pharmacies
    Route::get('/', [PharmacieController::class, 'index'])->name('index');
    
    // Connexion des pharmacies (supprimé - utilise les routes générales)
    // Route::get('/login', [PharmacieController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [PharmacieController::class, 'login'])->name('login.submit');
    
    // Inscription des pharmacies (supprimé - utilise les routes générales)
    // Route::get('/register', [PharmacieController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [PharmacieController::class, 'register'])->name('register.submit');
    
    // Routes protégées pour les pharmacies connectées
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [PharmacieController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [PharmacieController::class, 'logout'])->name('logout');
    });
});

// Routes publiques pour les banques de sang
Route::prefix('banque')->name('banque.')->group(function () {
    // Page d'accueil des banques de sang
    Route::get('/', [BanqueController::class, 'index'])->name('index');
    
    // Connexion des banques de sang (supprimé - utilise les routes générales)
    // Route::get('/login', [BanqueController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [BanqueController::class, 'login'])->name('login.submit');
    
    // Inscription des banques de sang (supprimé - utilise les routes générales)
    // Route::get('/register', [BanqueController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [BanqueController::class, 'register'])->name('register.submit');
    
    // Routes protégées pour les banques de sang connectées
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [BanqueController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [BanqueController::class, 'logout'])->name('logout');
    });
});

// Enregistrement (Inscription)
// Routes d'inscription avec adaptation automatique
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::get('/register/{entity}', [RegisterController::class, 'showRegistrationForm'])->name('register.entity');
Route::post('/register', [RegisterController::class, 'submit'])->name('register.submit');

// Pages d'accueil des entités (exemples)
Route::get('/hopital', function() {
    return view('entities.hopital.home');
})->name('entity.hopital');

// Route de test pour le layout admin
Route::get('/admin/test-layout', function () {
    return view('admin.test-layout');
})->name('admin.test-layout');

// Connexion
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Déconnexion
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirection admin vers login si pas connecté
Route::get('/admin', function () {
    return redirect()->route('login');
})->name('admin.redirect');



// 🔐 Dashboards protégés (pour les entités non-admin)
Route::middleware(['auth'])->group(function () {
    Route::get('/hopital/dashboard', [DashboardController::class, 'hopitalDashboard'])->name('hopital.dashboard');
    Route::get('/pharmacie/dashboard', [DashboardController::class, 'pharmacieDashboard'])->name('pharmacie.dashboard');
    Route::get('/banque/dashboard', [DashboardController::class, 'banqueSangDashboard'])->name('banque.dashboard');
    Route::get('/centre/dashboard', [DashboardController::class, 'centreDashboard'])->name('centre.dashboard');
    // Note: patient.dashboard est défini dans le groupe patient.* plus haut
});

// Gestion des permissions
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Route principale admin - redirige vers le dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    
    // Dashboard admin
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des utilisateurs
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/pending', [\App\Http\Controllers\Admin\UserController::class, 'pendingUsers'])->name('users.pending');
    Route::get('/users/stats', [\App\Http\Controllers\Admin\UserController::class, 'stats'])->name('users.stats');
    Route::get('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::post('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{id}/approve', [\App\Http\Controllers\Admin\UserController::class, 'approveUser'])->name('users.approve');
    Route::post('/users/{id}/reject', [\App\Http\Controllers\Admin\UserController::class, 'rejectUser'])->name('users.reject');
    Route::post('/users/{id}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    Route::post('/users/permissions', [\App\Http\Controllers\Admin\UserController::class, 'updatePermissions'])->name('users.updatePermissions');
    Route::get('/users/{id}/permissions', [\App\Http\Controllers\Admin\UserController::class, 'showPermissions'])->name('users.permissions');
    
    // Gestion des permissions
    Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class);
    
    // Gestion des entités
    Route::get('/entities', function () {
        return view('admin.entities');
    })->name('entities');
    
    // Gestion des paramètres
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
    
    // Routes pour les réceptionnistes
    Route::prefix('receptionniste')->name('receptionniste.')->group(function () {
        Route::get('/dashboard', [ReceptionnisteController::class, 'dashboard'])->name('dashboard');
        Route::get('/patients', [ReceptionnisteController::class, 'patients'])->name('patients');
        Route::post('/patients', [ReceptionnisteController::class, 'storePatient'])->name('patients.store');
        Route::put('/patients/{id}', [ReceptionnisteController::class, 'updatePatient'])->name('patients.update');
        Route::get('/rendezvous', [ReceptionnisteController::class, 'rendezvous'])->name('rendezvous');
        Route::post('/rendezvous', [ReceptionnisteController::class, 'storeRendezVous'])->name('rendezvous.store');
        Route::post('/rendezvous/{id}/confirmer', [ReceptionnisteController::class, 'confirmerRendezVous'])->name('rendezvous.confirmer');
        Route::post('/rendezvous/{id}/annuler', [ReceptionnisteController::class, 'annulerRendezVous'])->name('rendezvous.annuler');
    });

    // Routes pour les médecins
    Route::prefix('medecin')->name('medecin.')->group(function () {
        Route::get('/dashboard', [MedecinController::class, 'dashboard'])->name('dashboard');
        
        // Consultations (nouveau système)
        Route::get('/consultations', [MedecinController::class, 'consultations'])->name('consultations');
        Route::get('/consultations/{id}', [MedecinController::class, 'showConsultation'])->name('consultations.show');
        Route::post('/consultations/{id}/demarrer', [MedecinController::class, 'demarrerConsultation'])->name('consultations.demarrer');
        Route::post('/consultations/{id}/creer-dossier', [MedecinController::class, 'creerDossierDepuisConsultation'])->name('consultations.creer-dossier');
        
        Route::get('/patients', [MedecinController::class, 'patients'])->name('patients');
        Route::get('/dossiers', [MedecinController::class, 'dossiers'])->name('dossiers');
        Route::get('/dossiers/{id}', [MedecinController::class, 'showDossier'])->name('dossier.show');
        Route::post('/dossiers', [MedecinController::class, 'createDossier'])->name('dossier.create');
        Route::put('/dossiers/{id}', [MedecinController::class, 'updateDossier'])->name('dossier.update');
        Route::get('/rendezvous', [MedecinController::class, 'rendezvous'])->name('rendezvous');
        Route::post('/rendezvous', [MedecinController::class, 'createRendezVous'])->name('rendezvous.create');
        Route::post('/rendezvous/{id}/statut', [MedecinController::class, 'updateRendezVousStatut'])->name('rendezvous.update-statut');
        
        // Gestion des examens
        Route::post('/dossiers/{id}/prescrire-examens', [MedecinController::class, 'prescrireExamens'])->name('examens.prescrire');
        
        // Notifications pour médecins
        Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'getMedecinNotifications'])->name('notifications.get');
        Route::post('/notifications/{id}/mark-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markMedecinAsRead'])->name('notifications.mark-read');
    });
    
    // Routes pour les caissiers
    Route::prefix('caissier')->name('caissier.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\CaissierController::class, 'dashboard'])->name('dashboard');
        
        // Consultations
        Route::get('/consultations', [\App\Http\Controllers\CaissierController::class, 'consultations'])->name('consultations');
        Route::get('/consultations/{id}', [\App\Http\Controllers\CaissierController::class, 'showConsultation'])->name('consultations.show');
        Route::post('/consultations/{id}/encaisser', [\App\Http\Controllers\CaissierController::class, 'encaisser'])->name('consultations.encaisser');
        Route::get('/consultations/{id}/facture', [\App\Http\Controllers\CaissierController::class, 'facture'])->name('facture');
        Route::get('/consultations/{id}/facture/pdf', [\App\Http\Controllers\CaissierController::class, 'telechargerFacture'])->name('facture.pdf');
        Route::get('/rechercher-patient', [\App\Http\Controllers\CaissierController::class, 'rechercherPatient'])->name('rechercher-patient');
        Route::get('/historique', [\App\Http\Controllers\CaissierController::class, 'historique'])->name('historique');
        
        // Examens
        Route::get('/examens', [\App\Http\Controllers\CaissierController::class, 'examensEnAttente'])->name('examens');
        Route::post('/examens/{id}/valider-paiement', [\App\Http\Controllers\CaissierController::class, 'validerPaiement'])->name('examens.valider');
        Route::get('/historique-examens', [\App\Http\Controllers\CaissierController::class, 'historiqueExamens'])->name('historique-examens');
    });
    
    // Routes pour les laborantins
    Route::prefix('laborantin')->name('laborantin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\LaborantinController::class, 'dashboard'])->name('dashboard');
        Route::get('/examens', [\App\Http\Controllers\LaborantinController::class, 'examensARealiser'])->name('examens');
        Route::post('/examens/{id}/marquer-en-cours', [\App\Http\Controllers\LaborantinController::class, 'marquerEnCours'])->name('examens.en-cours');
        Route::post('/examens/{id}/uploader-resultats', [\App\Http\Controllers\LaborantinController::class, 'uploaderResultats'])->name('examens.upload');
        Route::get('/historique', [\App\Http\Controllers\LaborantinController::class, 'historique'])->name('historique');
    });
    
    // Routes pour la gestion des patients de l'hôpital
    Route::prefix('hopital')->name('hopital.')->group(function () {
        Route::prefix('patients')->name('patients.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\HopitalPatientController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\HopitalPatientController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Admin\HopitalPatientController::class, 'show'])->name('show');
            Route::post('/{id}/create-dossier', [\App\Http\Controllers\Admin\HopitalPatientController::class, 'createDossier'])->name('create-dossier');
            Route::post('/{patientId}/assign-dossier/{dossierId}', [\App\Http\Controllers\Admin\HopitalPatientController::class, 'assignDossierToMedecin'])->name('assign-dossier');
            Route::get('/{patientId}/dossier/{dossierId}', [\App\Http\Controllers\Admin\HopitalPatientController::class, 'showDossier'])->name('dossier');
            Route::post('/{id}/update-status', [\App\Http\Controllers\Admin\HopitalPatientController::class, 'updateStatus'])->name('update-status');
        });
        
        // Routes pour la gestion des rendez-vous
        Route::prefix('rendezvous')->name('rendezvous.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\HopitalRendezVousController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\HopitalRendezVousController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\HopitalRendezVousController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Admin\HopitalRendezVousController::class, 'show'])->name('show');
            Route::put('/{id}/statut', [\App\Http\Controllers\Admin\HopitalRendezVousController::class, 'updateStatut'])->name('update-statut');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\HopitalRendezVousController::class, 'destroy'])->name('destroy');
        });
        
        // Routes pour les transferts de dossiers médicaux
        Route::prefix('transferts')->name('transferts.')->group(function () {
            Route::get('/rechercher', [\App\Http\Controllers\Admin\TransfertDossierController::class, 'rechercherPatient'])->name('rechercher');
            Route::get('/rechercher-ajax', [\App\Http\Controllers\Admin\TransfertDossierController::class, 'rechercherPatientAjax'])->name('rechercher-ajax');
            Route::post('/creer-demande', [\App\Http\Controllers\Admin\TransfertDossierController::class, 'creerDemande'])->name('creer-demande');
            Route::get('/demandes-envoyees', [\App\Http\Controllers\Admin\TransfertDossierController::class, 'demandesEnvoyees'])->name('demandes-envoyees');
            Route::get('/demandes-recues', [\App\Http\Controllers\Admin\TransfertDossierController::class, 'demandesRecues'])->name('demandes-recues');
            Route::post('/{id}/transferer', [\App\Http\Controllers\Admin\TransfertDossierController::class, 'transfererDossier'])->name('transferer');
            Route::post('/{id}/refuser', [\App\Http\Controllers\Admin\TransfertDossierController::class, 'refuserDemande'])->name('refuser');
        });
    });
    
    // Route pour les notifications (en dehors du groupe hopital, dans le groupe admin)
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'getNotifications'])->name('notifications.get');
    Route::post('/notifications/{id}/mark-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    
    // ========== ROUTES PHARMACIE ==========
    Route::prefix('pharmacie')->name('pharmacie.')->group(function () {
        // Route de test pour créer une notification
        Route::get('/test-notification', function() {
            $user = auth()->user();
            if ($user->type_utilisateur === 'pharmacie') {
                \App\Helpers\NotificationHelper::notifyStockFaible(
                    $user->entite_id,
                    'Paracétamol 500mg',
                    5
                );
                return redirect()->route('admin.dashboard')->with('success', 'Notification de test créée !');
            }
            return back()->with('error', 'Vous devez être connecté en tant que pharmacie');
        })->name('test-notification');
        
        // Gestion des médicaments
        Route::prefix('medicaments')->name('medicaments.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MedicamentController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\MedicamentController::class, 'store'])->name('store');
            Route::get('/categories', [\App\Http\Controllers\Admin\MedicamentController::class, 'getCategories'])->name('categories');
            Route::get('/formes', [\App\Http\Controllers\Admin\MedicamentController::class, 'getFormes'])->name('formes');
            Route::get('/{id}', [\App\Http\Controllers\Admin\MedicamentController::class, 'show'])->name('show');
            Route::put('/{id}', [\App\Http\Controllers\Admin\MedicamentController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\MedicamentController::class, 'destroy'])->name('destroy');
        });
        
        // Gestion des stocks
        Route::prefix('stocks')->name('stocks.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\StockController::class, 'index'])->name('index');
            Route::post('/ajuster', [\App\Http\Controllers\Admin\StockController::class, 'ajuster'])->name('ajuster');
            Route::get('/inventaire', [\App\Http\Controllers\Admin\StockController::class, 'inventaire'])->name('inventaire');
            Route::post('/inventaire', [\App\Http\Controllers\Admin\StockController::class, 'enregistrerInventaire'])->name('inventaire.enregistrer');
            Route::get('/{id}/historique', [\App\Http\Controllers\Admin\StockController::class, 'historique'])->name('historique');
        });
        
        // Gestion des commandes
        Route::prefix('commandes')->name('commandes.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\CommandeController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\CommandeController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Admin\CommandeController::class, 'show'])->name('show');
            Route::post('/{id}/valider', [\App\Http\Controllers\Admin\CommandeController::class, 'valider'])->name('valider');
            Route::post('/{id}/receptionner', [\App\Http\Controllers\Admin\CommandeController::class, 'receptionner'])->name('receptionner');
            Route::post('/{id}/annuler', [\App\Http\Controllers\Admin\CommandeController::class, 'annuler'])->name('annuler');
        });
        
        // Gestion des fournisseurs
        Route::prefix('fournisseurs')->name('fournisseurs.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\FournisseurController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\FournisseurController::class, 'store'])->name('store');
            Route::get('/liste', [\App\Http\Controllers\Admin\FournisseurController::class, 'liste'])->name('liste');
            Route::get('/{id}', [\App\Http\Controllers\Admin\FournisseurController::class, 'show'])->name('show');
            Route::put('/{id}', [\App\Http\Controllers\Admin\FournisseurController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\FournisseurController::class, 'destroy'])->name('destroy');
        });
        
        // Gestion des ventes
        Route::prefix('ventes')->name('ventes.')->group(function () {
            Route::get('/', function() { return view('admin.pharmacie.ventes.index'); })->name('index');
            Route::post('/', function() { return back(); })->name('store');
            Route::get('/{id}', function() { return view('admin.pharmacie.ventes.show'); })->name('show');
        });
    });
    
    // ========== ROUTES BANQUE DE SANG ==========
    Route::prefix('banque-sang')->name('banque-sang.')->group(function () {
        // Gestion des donneurs
        Route::prefix('donneurs')->name('donneurs.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\BanqueSangController::class, 'donneurs'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\BanqueSangController::class, 'storeDonneur'])->name('store');
        });
        
        // Gestion des dons
        Route::prefix('dons')->name('dons.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\BanqueSangController::class, 'dons'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\BanqueSangController::class, 'storeDon'])->name('store');
        });
        
        // Gestion des réserves
        Route::prefix('reserves')->name('reserves.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\BanqueSangController::class, 'reserves'])->name('index');
        });
        
        // Gestion des demandes
        Route::prefix('demandes')->name('demandes.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\BanqueSangController::class, 'demandes'])->name('index');
            Route::post('/{id}/traiter', [\App\Http\Controllers\Admin\BanqueSangController::class, 'traiterDemande'])->name('traiter');
        });
        
        // Gestion des analyses
        Route::prefix('analyses')->name('analyses.')->group(function () {
            Route::get('/', function() { return view('admin.banque-sang.analyses.index'); })->name('index');
        });
    });
    
    // Routes API pour charger les entités (pour superadmin)
    Route::prefix('api')->group(function () {
        Route::get('/hopitaux', function() {
            return \App\Models\Hopital::select('id', 'nom')->get();
        });
        Route::get('/pharmacies', function() {
            return \App\Models\Pharmacie::select('id', 'nom')->get();
        });
        Route::get('/banque-sangs', function() {
            return \App\Models\BanqueSang::select('id', 'nom')->get();
        });
    });
});

// Routes pour les transferts de dossiers - PATIENTS
Route::prefix('patient')->name('patient.')->middleware(['auth'])->group(function () {
        Route::get('/consentements-transfert', [\App\Http\Controllers\Admin\TransfertDossierController::class, 'mesConsentements'])->name('consentements-transfert');
        Route::post('/consentements-transfert/{id}/accepter', [\App\Http\Controllers\Admin\TransfertDossierController::class, 'accepterConsentement'])->name('consentement-transfert.accepter');
        Route::post('/consentements-transfert/{id}/refuser', [\App\Http\Controllers\Admin\TransfertDossierController::class, 'refuserConsentement'])->name('consentement-transfert.refuser');
    });
    
    // Page d'accueil admin
    Route::get('/index', function () {
        return view('admin.index');
    })->name('index');
    
    Route::resource('permissions', PermissionController::class);
    
    // Route pour récupérer les permissions d'un rôle
    Route::get('/permissions/{id}/permissions', [PermissionController::class, 'getRolePermissions'])->name('permissions.getRolePermissions');
    
    // Route pour vérifier les permissions d'un rôle
    Route::get('/permissions/{id}/verify', [PermissionController::class, 'verifyRolePermissions'])->name('permissions.verify');
    
    // Route explicite pour la mise à jour des rôles
    Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    
    // Gestion des utilisateurs - Routes spécifiques en premier
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/pending', [UserController::class, 'pendingUsers'])->name('users.pending');
    Route::get('/users/stats', [UserController::class, 'stats'])->name('users.stats');
    Route::post('/users/permissions', [UserController::class, 'updatePermissions'])->name('users.updatePermissions');
    
    // Routes avec paramètres en dernier
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::post('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('superadmin.protection');
    Route::get('/users/{id}/permissions', [UserController::class, 'showPermissions'])->name('users.permissions');
    Route::post('/users/{id}/approve', [UserController::class, 'approveUser'])->name('users.approve');
    Route::post('/users/{id}/reject', [UserController::class, 'rejectUser'])->name('users.reject');
    Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    
    // Route en français pour la compatibilité
    Route::get('/utilisateurs', [UserController::class, 'index'])->name('utilisateurs.index');
    Route::get('/utilisateurs/en-attente', [UserController::class, 'pendingUsers'])->name('utilisateurs.pending');
    
    // Gestion des entités
    Route::get('/entities', function () {
        return view('admin.entities');
    })->name('entities');
    
    // Gestion des settings
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
    
    // Routes temporaires pour les modules (à remplacer par de vraies routes plus tard)
    Route::get('/patients', function () {
        return view('admin.modules.coming-soon', ['module' => 'Patients']);
    })->name('patients.index');
    
    Route::get('/appointments', function () {
        return view('admin.modules.coming-soon', ['module' => 'Rendez-vous']);
    })->name('appointments.index');
    
    Route::get('/medical-records', function () {
        return view('admin.modules.coming-soon', ['module' => 'Dossiers Médicaux']);
    })->name('medical-records.index');
    
    Route::get('/prescriptions', function () {
        return view('admin.modules.coming-soon', ['module' => 'Prescriptions']);
    })->name('prescriptions.index');
    
    Route::get('/invoices', function () {
        return view('admin.modules.coming-soon', ['module' => 'Factures']);
    })->name('invoices.index');
    
    Route::get('/reports', function () {
        return view('admin.modules.coming-soon', ['module' => 'Rapports']);
    })->name('reports.index');
    
    Route::get('/medicines', function () {
        return view('admin.modules.coming-soon', ['module' => 'Médicaments']);
    })->name('medicines.index');
    
    Route::get('/stocks', function () {
        return view('admin.modules.coming-soon', ['module' => 'Stocks']);
    })->name('stocks.index');
    
    Route::get('/donors', function () {
        return view('admin.modules.coming-soon', ['module' => 'Donneurs']);
    })->name('donors.index');
    
    Route::get('/blood-reserves', function () {
        return view('admin.modules.coming-soon', ['module' => 'Réserves de Sang']);
    })->name('blood-reserves.index');
    
    Route::get('/services', function () {
        return view('admin.modules.coming-soon', ['module' => 'Services']);
    })->name('services.index');
    
    Route::get('/consultations', function () {
        return view('admin.modules.coming-soon', ['module' => 'Consultations']);
    })->name('consultations.index');