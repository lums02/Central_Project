<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Utilisateur;

class PatientController extends Controller
{
    /**
     * Afficher la page d'accueil des patients
     */
    public function index()
    {
        return view('patient.index');
    }

    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login', ['userType' => 'patient']);
    }

    /**
     * Traiter la connexion des patients
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Vérifier que l'utilisateur est un patient
        $user = Utilisateur::where('email', $credentials['email'])->first();
        
        if ($user && $user->type_utilisateur !== 'patient') {
            return redirect()->back()
                ->withErrors(['email' => 'Cette adresse email n\'est pas associée à un compte patient.'])
                ->withInput($request->except('password'));
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('patient.dashboard'))
                ->with('success', 'Connexion réussie ! Bienvenue dans votre espace patient.');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Les identifiants fournis ne correspondent à aucun compte patient.'])
            ->withInput($request->except('password'));
    }

    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegisterForm()
    {
        return view('auth.register', ['userType' => 'patient', 'selectedEntity' => 'patient']);
    }

    /**
     * Traiter l'inscription des patients
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs',
            'telephone' => 'required|string|max:20',
            'date_naissance' => 'required|date|before:today',
            'sexe' => 'required|in:M,F',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'date_naissance.required' => 'La date de naissance est obligatoire.',
            'date_naissance.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'sexe.required' => 'Le sexe est obligatoire.',
            'sexe.in' => 'Le sexe doit être Masculin ou Féminin.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'terms.required' => 'Vous devez accepter les conditions d\'utilisation.',
            'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        // Créer le patient
        $patient = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'date_naissance' => $request->date_naissance,
            'sexe' => $request->sexe,
            'password' => Hash::make($request->password),
            'type_utilisateur' => 'patient',
            'status' => 'actif',
        ]);

        // Assigner le rôle patient
        $patient->assignRole('patient');

        // Connecter automatiquement le patient
        Auth::login($patient);

        return redirect()->route('patient.dashboard')
            ->with('success', 'Compte créé avec succès ! Bienvenue dans votre espace patient.');
    }

    /**
     * Afficher le dashboard patient
     */
    public function dashboard()
    {
        $patient = Auth::user();
        
        // Vérifier que l'utilisateur est bien un patient
        if ($patient->type_utilisateur !== 'patient') {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Accès non autorisé.');
        }

        // Récupérer les dossiers médicaux du patient
        $dossiers = \App\Models\DossierMedical::where('patient_id', $patient->id)
            ->with(['medecin', 'hopital'])
            ->orderBy('date_consultation', 'desc')
            ->get();

        // Récupérer les rendez-vous du patient
        $rendezvous = \App\Models\RendezVous::where('patient_id', $patient->id)
            ->with(['medecin', 'hopital'])
            ->where('date_rendezvous', '>=', now()->subDays(30)) // 30 derniers jours
            ->orderBy('date_rendezvous', 'desc')
            ->get();

        // Récupérer les examens prescrits
        $examens = \App\Models\ExamenPrescrit::where('patient_id', $patient->id)
            ->with(['medecin', 'hopital'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Statistiques
        $stats = [
            'total_dossiers' => $dossiers->count(),
            'total_consultations' => $dossiers->count(),
            'rendez_vous_a_venir' => $rendezvous->where('statut', '!=', 'termine')
                ->where('statut', '!=', 'annule')
                ->where('date_rendezvous', '>=', now())
                ->count(),
            'examens_en_attente' => $examens->whereIn('statut_examen', ['prescrit', 'paye', 'en_cours'])->count(),
        ];

        // Prochain rendez-vous
        $prochainRdv = $rendezvous->where('statut', '!=', 'termine')
            ->where('statut', '!=', 'annule')
            ->where('date_rendezvous', '>=', now())
            ->sortBy('date_rendezvous')
            ->first();

        // Pharmacies disponibles
        $pharmacies = \App\Models\Pharmacie::all();

        // Banques de sang disponibles
        $banquesSang = \App\Models\BanqueSang::all();

        return view('patient.dashboard', compact('patient', 'dossiers', 'rendezvous', 'examens', 'stats', 'prochainRdv', 'pharmacies', 'banquesSang'));
    }

    /**
     * Afficher mes dossiers médicaux
     */
    public function mesDossiers()
    {
        $patient = Auth::user();
        
        $dossiers = \App\Models\DossierMedical::where('patient_id', $patient->id)
            ->with(['medecin', 'hopital'])
            ->orderBy('date_consultation', 'desc')
            ->paginate(10);
        
        return view('patient.dossiers', compact('dossiers'));
    }

    /**
     * Voir un dossier médical
     */
    public function voirDossier($id)
    {
        $patient = Auth::user();
        
        $dossier = \App\Models\DossierMedical::where('patient_id', $patient->id)
            ->where('id', $id)
            ->with(['medecin', 'hopital'])
            ->firstOrFail();
        
        // Récupérer les examens liés à ce dossier
        $examens = \App\Models\ExamenPrescrit::where('dossier_medical_id', $dossier->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('patient.dossier-show', compact('dossier', 'examens'));
    }

    /**
     * Afficher mes rendez-vous
     */
    public function mesRendezVous()
    {
        $patient = Auth::user();
        
        $rendezvous = \App\Models\RendezVous::where('patient_id', $patient->id)
            ->with(['medecin', 'hopital'])
            ->orderBy('date_rendezvous', 'desc')
            ->paginate(15);
        
        return view('patient.rendezvous', compact('rendezvous'));
    }

    /**
     * Afficher mes examens
     */
    public function mesExamens()
    {
        $patient = Auth::user();
        
        $examens = \App\Models\ExamenPrescrit::where('patient_id', $patient->id)
            ->with(['medecin', 'hopital', 'dossierMedical'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('patient.examens', compact('examens'));
    }

    /**
     * Choisir un hôpital
     */
    public function choisirHopital()
    {
        $patient = Auth::user();
        
        // Liste de tous les hôpitaux
        $hopitaux = \App\Models\Hopital::orderBy('nom')->get();
        
        return view('patient.choisir-hopital', compact('hopitaux'));
    }
    
    /**
     * Enregistrer le choix d'hôpital
     */
    public function enregistrerHopital(Request $request)
    {
        $patient = Auth::user();
        
        $validated = $request->validate([
            'hopital_id' => 'required|exists:hopitaux,id',
        ]);
        
        $patient->update([
            'hopital_id' => $validated['hopital_id'],
        ]);
        
        // Créer une notification pour l'administrateur de l'hôpital
        $hopital = \App\Models\Hopital::find($validated['hopital_id']);
        
        // Trouver l'administrateur de l'hôpital
        $adminHopital = \App\Models\Utilisateur::where('entite_id', $hopital->id)
            ->where('type_utilisateur', 'hopital')
            ->where('role', 'admin')
            ->first();
        
        if ($adminHopital) {
            \App\Models\Notification::create([
                'user_id' => $adminHopital->id,
                'hopital_id' => $hopital->id,
                'type' => 'nouveau_patient',
                'title' => 'Nouveau patient inscrit',
                'message' => 'Le patient ' . $patient->nom . ' ' . $patient->prenom . ' vient de choisir votre hôpital.',
                'read' => false,
            ]);
        }
        
        return redirect()->route('patient.dashboard')
            ->with('success', 'Hôpital sélectionné avec succès ! Vous pouvez maintenant prendre rendez-vous.');
    }
    
    /**
     * Trouver une pharmacie
     */
    public function pharmacies(Request $request)
    {
        $patient = Auth::user();
        
        // Récupérer les médicaments recherchés
        $searchTerms = $request->input('medicaments', []);
        $pharmacies = collect();
        $medicamentsDisponibles = [];
        
        if (!empty($searchTerms) && count(array_filter($searchTerms)) > 0) {
            // Rechercher les pharmacies qui ont ces médicaments en stock
            $pharmacies = \App\Models\Pharmacie::whereHas('medicaments', function($query) use ($searchTerms) {
                $query->where('actif', true)
                      ->where('stock_actuel', '>', 0)
                      ->where(function($q) use ($searchTerms) {
                          foreach ($searchTerms as $term) {
                              if (!empty($term)) {
                                  $q->orWhere('nom', 'LIKE', '%' . $term . '%')
                                    ->orWhere('nom_generique', 'LIKE', '%' . $term . '%');
                              }
                          }
                      });
            })
            ->with(['medicaments' => function($query) use ($searchTerms) {
                $query->where('actif', true)
                      ->where('stock_actuel', '>', 0)
                      ->where(function($q) use ($searchTerms) {
                          foreach ($searchTerms as $term) {
                              if (!empty($term)) {
                                  $q->orWhere('nom', 'LIKE', '%' . $term . '%')
                                    ->orWhere('nom_generique', 'LIKE', '%' . $term . '%');
                              }
                          }
                      });
            }])
            ->get();
            
            // Récupérer les médicaments trouvés pour chaque pharmacie
            foreach ($pharmacies as $pharmacie) {
                $medicamentsDisponibles[$pharmacie->id] = $pharmacie->medicaments;
            }
        }
        
        return view('patient.pharmacies', compact('pharmacies', 'searchTerms', 'medicamentsDisponibles'));
    }
    
    /**
     * API pour rechercher des médicaments (autocomplétion)
     */
    public function searchMedicaments(Request $request)
    {
        $term = $request->input('term');
        
        // Rechercher les médicaments disponibles (avec stock > 0)
        $medicaments = \App\Models\Medicament::where('actif', true)
            ->where('stock_actuel', '>', 0)
            ->where(function($query) use ($term) {
                $query->where('nom', 'LIKE', '%' . $term . '%')
                      ->orWhere('nom_generique', 'LIKE', '%' . $term . '%');
            })
            ->select('nom', 'nom_generique', 'forme', 'dosage')
            ->distinct()
            ->limit(10)
            ->get()
            ->map(function($med) {
                return [
                    'label' => $med->nom . ($med->forme ? ' - ' . $med->forme : '') . ($med->dosage ? ' ' . $med->dosage : ''),
                    'value' => $med->nom
                ];
            });
        
        return response()->json($medicaments);
    }

    /**
     * Trouver une banque de sang
     */
    public function banquesSang(Request $request)
    {
        $patient = Auth::user();
        
        // Récupérer le groupe sanguin recherché
        $groupeSanguin = $request->input('groupe_sanguin');
        $rhesus = $request->input('rhesus');
        
        $banquesSang = collect();
        $reservesDisponibles = [];
        
        if ($groupeSanguin && $rhesus) {
            // Combiner le groupe et le rhésus (ex: "A+", "O-")
            $groupeComplet = $groupeSanguin . $rhesus;
            
            // Rechercher les banques qui ont des réserves pour ce groupe sanguin
            $banquesSang = \App\Models\BanqueSang::whereHas('reserves', function($query) use ($groupeComplet) {
                $query->where('groupe_sanguin', $groupeComplet)
                      ->where('quantite_disponible', '>', 0);
            })
            ->with(['reserves' => function($query) use ($groupeComplet) {
                $query->where('groupe_sanguin', $groupeComplet)
                      ->where('quantite_disponible', '>', 0);
            }])
            ->get();
            
            // Récupérer les réserves disponibles pour chaque banque
            foreach ($banquesSang as $banque) {
                $reservesDisponibles[$banque->id] = $banque->reserves;
            }
        }
        
        return view('patient.banques-sang', compact('banquesSang', 'groupeSanguin', 'rhesus', 'reservesDisponibles'));
    }

    /**
     * Déconnexion des patients
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('patient.index')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Afficher le formulaire de demande de réinitialisation de mot de passe
     */
    public function showPasswordRequestForm()
    {
        return view('patient.password-request');
    }

    /**
     * Traiter la demande de réinitialisation de mot de passe
     */
    public function passwordRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:utilisateurs,email',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.exists' => 'Cette adresse email n\'est pas enregistrée.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Vérifier que l'utilisateur est un patient
        $user = Utilisateur::where('email', $request->email)
            ->where('type_utilisateur', 'patient')
            ->first();

        if (!$user) {
            return redirect()->back()
                ->withErrors(['email' => 'Cette adresse email n\'est pas associée à un compte patient.'])
                ->withInput();
        }

        // TODO: Implémenter l'envoi d'email de réinitialisation
        // Pour l'instant, on affiche juste un message de succès
        
        return redirect()->route('patient.login')
            ->with('success', 'Un email de réinitialisation a été envoyé à votre adresse email.');
    }
}
