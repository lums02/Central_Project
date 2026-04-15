<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\DossierMedical;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HopitalPatientController extends Controller
{
    /**
     * Afficher la liste des patients de l'hôpital
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est admin d'un hôpital
        if ($user->type_utilisateur !== 'hopital' || $user->role !== 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Accès non autorisé.');
        }
        
        // Récupérer les patients de cet hôpital
        $query = Utilisateur::where('type_utilisateur', 'patient')
            ->where(function($q) use ($user) {
                $q->where('entite_id', $user->entite_id)
                  ->orWhere('hopital_id', $user->entite_id);
            })
            ->with(['dossiersMedicaux' => function($q) {
                $q->orderBy('date_consultation', 'desc');
            }]);
        
        // Recherche
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%");
            });
        }
        
        // Filtres
        if ($request->has('sexe') && $request->sexe != '') {
            $query->where('sexe', $request->sexe);
        }
        
        if ($request->has('statut') && $request->statut != '') {
            $query->where('status', $request->statut);
        }
        
        $patients = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistiques
        $stats = [
            'total_patients' => Utilisateur::where('type_utilisateur', 'patient')
                ->where(function($q) use ($user) {
                    $q->where('entite_id', $user->entite_id)
                      ->orWhere('hopital_id', $user->entite_id);
                })->count(),
            'patients_actifs' => Utilisateur::where('type_utilisateur', 'patient')
                ->where(function($q) use ($user) {
                    $q->where('entite_id', $user->entite_id)
                      ->orWhere('hopital_id', $user->entite_id);
                })
                ->where('status', 'actif')->count(),
            'nouveaux_patients' => Utilisateur::where('type_utilisateur', 'patient')
                ->where(function($q) use ($user) {
                    $q->where('entite_id', $user->entite_id)
                      ->orWhere('hopital_id', $user->entite_id);
                })
                ->whereDate('created_at', '>=', now()->subDays(7))->count(),
            'total_dossiers' => DossierMedical::whereIn('patient_id', 
                Utilisateur::where('type_utilisateur', 'patient')
                    ->where(function($q) use ($user) {
                        $q->where('entite_id', $user->entite_id)
                          ->orWhere('hopital_id', $user->entite_id);
                    })
                    ->pluck('id')
            )->count(),
        ];
        
        return view('admin.hopital.patients.index', compact('patients', 'stats'));
    }
    
    /**
     * Créer un nouveau patient
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'mot_de_passe' => 'required|string|min:6',
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
            'sexe' => 'nullable|in:M,F,masculin,feminin',
            'groupe_sanguin' => 'nullable|string',
            'adresse' => 'nullable|string',
            'antecedents' => 'nullable|string',
            'prenom' => 'nullable|string|max:255',
            'medecin_id' => 'nullable|exists:utilisateurs,id',
        ]);
        
        // Normaliser le sexe
        $sexe = $request->sexe;
        if ($sexe === 'M') $sexe = 'masculin';
        if ($sexe === 'F') $sexe = 'feminin';
        
        // Créer le patient
        $patient = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'date_naissance' => $request->date_naissance,
            'sexe' => $sexe,
            'groupe_sanguin' => $request->groupe_sanguin,
            'adresse' => $request->adresse,
            'type_utilisateur' => 'patient',
            'entite_id' => $user->entite_id,
            'mot_de_passe' => bcrypt($request->mot_de_passe),
            'status' => 'approved', // Approuvé automatiquement
            'role' => 'patient',
        ]);
        
        // Si un médecin traitant est sélectionné, créer une notification pour le médecin
        if ($request->medecin_id) {
            \App\Models\Notification::create([
                'user_id' => $request->medecin_id,
                'hopital_id' => $user->entite_id,
                'type' => 'nouveau_patient',
                'title' => 'Nouveau patient assigné',
                'message' => "Le patient {$patient->nom} vous a été assigné par l'administration.",
                'data' => json_encode(['patient_id' => $patient->id]),
                'read' => false,
            ]);
        }
        
        // Si la requête attend du JSON (AJAX)
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Patient créé avec succès',
                'patient' => $patient
            ]);
        }
        
        // Redirection selon le type d'utilisateur
        if ($user->role === 'medecin') {
            return redirect()->route('admin.medecin.patients')
                ->with('success', 'Patient créé avec succès.');
        }
        
        // Sinon redirection normale pour l'admin
        return redirect()->route('admin.hopital.patients.index')
            ->with('success', 'Patient créé avec succès.');
    }
    
    /**
     * Afficher le dossier médical d'un patient
     */
    public function show($id)
    {
        $user = Auth::user();
        
        // Récupérer le patient
        $patient = Utilisateur::where('id', $id)
            ->where('type_utilisateur', 'patient')
            ->where('entite_id', $user->entite_id)
            ->with(['dossiersMedicaux' => function($q) {
                $q->with(['medecin', 'hopital'])
                  ->orderBy('date_consultation', 'desc');
            }])
            ->firstOrFail();
        
        // Récupérer les médecins de l'hôpital pour l'envoi de dossier
        $medecins = Utilisateur::where('type_utilisateur', 'medecin')
            ->where('entite_id', $user->entite_id)
            ->where('status', 'actif')
            ->get();
        
        return view('admin.hopital.patients.show', compact('patient', 'medecins'));
    }
    
    /**
     * Créer un nouveau dossier médical pour un patient
     */
    public function createDossier(Request $request, $patientId)
    {
        $user = Auth::user();
        
        $request->validate([
            'medecin_id' => 'required|exists:utilisateurs,id',
            'motif_consultation' => 'required|string',
            'diagnostic' => 'nullable|string',
            'traitement' => 'nullable|string',
            'observations' => 'nullable|string',
        ], [
            'medecin_id.required' => 'Veuillez sélectionner un médecin.',
            'motif_consultation.required' => 'Le motif de consultation est obligatoire.',
        ]);
        
        // Vérifier que le patient appartient à cet hôpital
        $patient = Utilisateur::where('id', $patientId)
            ->where('type_utilisateur', 'patient')
            ->where('entite_id', $user->entite_id)
            ->firstOrFail();
        
        // Vérifier que le médecin appartient à cet hôpital
        $medecin = Utilisateur::where('id', $request->medecin_id)
            ->where('type_utilisateur', 'medecin')
            ->where('entite_id', $user->entite_id)
            ->firstOrFail();
        
        // Créer le dossier médical
        $dossier = DossierMedical::create([
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'hopital_id' => $user->entite_id,
            'numero_dossier' => 'DM-' . now()->format('Ymd') . '-' . str_pad(DossierMedical::count() + 1, 5, '0', STR_PAD_LEFT),
            'date_consultation' => now(),
            'motif_consultation' => $request->motif_consultation,
            'diagnostic' => $request->diagnostic,
            'traitement' => $request->traitement,
            'observations' => $request->observations,
            'statut' => 'actif',
        ]);
        
        return redirect()->route('admin.hopital.patients.show', $patient->id)
            ->with('success', 'Dossier médical créé avec succès et assigné au Dr. ' . $medecin->nom);
    }
    
    /**
     * Envoyer le dossier médical à un médecin
     */
    public function assignDossierToMedecin(Request $request, $patientId, $dossierId)
    {
        $user = Auth::user();
        
        $request->validate([
            'medecin_id' => 'required|exists:utilisateurs,id',
            'notes' => 'nullable|string',
        ], [
            'medecin_id.required' => 'Veuillez sélectionner un médecin.',
        ]);
        
        // Vérifier que le patient appartient à cet hôpital
        $patient = Utilisateur::where('id', $patientId)
            ->where('type_utilisateur', 'patient')
            ->where('entite_id', $user->entite_id)
            ->firstOrFail();
        
        // Vérifier que le dossier existe et appartient à ce patient
        $dossier = DossierMedical::where('id', $dossierId)
            ->where('patient_id', $patient->id)
            ->firstOrFail();
        
        // Vérifier que le médecin appartient à cet hôpital
        $medecin = Utilisateur::where('id', $request->medecin_id)
            ->where('type_utilisateur', 'medecin')
            ->where('entite_id', $user->entite_id)
            ->firstOrFail();
        
        // Mettre à jour le dossier
        $dossier->update([
            'medecin_id' => $medecin->id,
            'observations' => $dossier->observations . "\n\n[Assigné par " . $user->nom . " le " . now()->format('d/m/Y H:i') . "]\n" . ($request->notes ?? ''),
        ]);
        
        // Créer une notification pour le médecin
        \App\Models\Notification::create([
            'user_id' => $medecin->id,
            'hopital_id' => null,
            'type' => 'dossier_assigne',
            'title' => 'Nouveau dossier assigné',
            'message' => 'Le dossier du patient ' . $patient->nom . ' vous a été assigné.',
            'data' => json_encode(['dossier_id' => $dossier->id, 'patient_id' => $patient->id]),
            'read' => false,
        ]);
        
        return redirect()->route('admin.hopital.patients.show', $patient->id)
            ->with('success', 'Dossier médical envoyé au Dr. ' . $medecin->nom);
    }
    
    /**
     * Afficher un dossier médical spécifique
     */
    public function showDossier($patientId, $dossierId)
    {
        $user = Auth::user();
        
        // Vérifier que le patient appartient à cet hôpital
        $patient = Utilisateur::where('id', $patientId)
            ->where('type_utilisateur', 'patient')
            ->where('entite_id', $user->entite_id)
            ->firstOrFail();
        
        // Récupérer le dossier
        $dossier = DossierMedical::where('id', $dossierId)
            ->where('patient_id', $patient->id)
            ->with(['medecin', 'hopital', 'patient'])
            ->firstOrFail();
        
        return view('admin.hopital.patients.dossier', compact('patient', 'dossier'));
    }
    
    /**
     * Mettre à jour le statut d'un patient
     */
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        
        $request->validate([
            'status' => 'required|in:actif,inactif,disabled',
        ]);
        
        $patient = Utilisateur::where('id', $id)
            ->where('type_utilisateur', 'patient')
            ->where('entite_id', $user->entite_id)
            ->firstOrFail();
        
        $patient->update([
            'status' => $request->status,
        ]);
        
        return redirect()->back()
            ->with('success', 'Statut du patient mis à jour avec succès.');
    }
}

