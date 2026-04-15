<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemandeTransfertDossier;
use App\Models\Utilisateur;
use App\Models\DossierMedical;
use App\Models\Hopital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransfertDossierController extends Controller
{
    /**
     * HÔPITAL DEMANDEUR (B) - Rechercher un patient via AJAX
     */
    public function rechercherPatientAjax(Request $request)
    {
        $user = Auth::user();
        
        if ($user->type_utilisateur !== 'hopital' || $user->role !== 'admin') {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }
        
        $search = $request->get('search');
        
        if (!$search || strlen($search) < 3) {
            return response()->json(['patients' => []]);
        }
        
        // Rechercher des patients qui ne sont PAS dans cet hôpital
        $patients = Utilisateur::where('type_utilisateur', 'patient')
            ->where('entite_id', '!=', $user->entite_id)
            ->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->with(['dossiersMedicaux' => function($q) {
                $q->with('hopital')->latest()->take(1);
            }])
            ->limit(10)
            ->get()
            ->map(function($patient) {
                $dernierDossier = $patient->dossiersMedicaux->first();
                return [
                    'id' => $patient->id,
                    'nom' => $patient->nom,
                    'email' => $patient->email,
                    'hopital_id' => $dernierDossier ? $dernierDossier->hopital_id : null,
                    'hopital_nom' => $dernierDossier ? $dernierDossier->hopital->nom : 'Inconnu',
                    'nb_dossiers' => $patient->dossiersMedicaux->count(),
                ];
            });
        
        return response()->json(['patients' => $patients]);
    }
    
    /**
     * HÔPITAL DEMANDEUR (B) - Rechercher un patient
     */
    public function rechercherPatient(Request $request)
    {
        $user = Auth::user();
        
        if ($user->type_utilisateur !== 'hopital' || $user->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Accès non autorisé.');
        }
        
        $patients = [];
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            // Rechercher des patients qui ne sont PAS dans cet hôpital
            $patients = Utilisateur::where('type_utilisateur', 'patient')
                ->where('entite_id', '!=', $user->entite_id)
                ->where(function($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->with(['dossiersMedicaux' => function($q) {
                    $q->with('hopital');
                }])
                ->limit(20)
                ->get();
        }
        
        return view('admin.hopital.transferts.rechercher', compact('patients'));
    }
    
    /**
     * HÔPITAL DEMANDEUR (B) - Créer une demande de transfert
     */
    public function creerDemande(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'patient_id' => 'required|exists:utilisateurs,id',
            'hopital_detenteur_id' => 'required|exists:hopitaux,id',
            'motif_demande' => 'required|string',
        ], [
            'patient_id.required' => 'Patient requis.',
            'hopital_detenteur_id.required' => 'Hôpital détenteur requis.',
            'motif_demande.required' => 'Le motif est obligatoire.',
        ]);
        
        // Vérifier qu'il n'existe pas déjà une demande en attente
        $demandeExistante = DemandeTransfertDossier::where('patient_id', $request->patient_id)
            ->where('hopital_demandeur_id', $user->entite_id)
            ->where('hopital_detenteur_id', $request->hopital_detenteur_id)
            ->whereIn('statut', ['en_attente_patient', 'accepte_patient'])
            ->first();
        
        if ($demandeExistante) {
            return back()->with('error', 'Une demande est déjà en cours pour ce patient.');
        }
        
        $demande = DemandeTransfertDossier::create([
            'patient_id' => $request->patient_id,
            'hopital_demandeur_id' => $user->entite_id,
            'hopital_detenteur_id' => $request->hopital_detenteur_id,
            'motif_demande' => $request->motif_demande,
            'notes_demandeur' => $request->notes_demandeur,
            'statut' => 'en_attente_patient',
            'date_demande' => now(),
        ]);
        
        // Créer une notification pour l'hôpital détenteur
        \App\Models\Notification::create([
            'user_id' => null, // Pour tous les admins de l'hôpital
            'hopital_id' => $request->hopital_detenteur_id,
            'type' => 'demande_transfert_recue',
            'title' => 'Nouvelle demande de transfert',
            'message' => 'L\'hôpital ' . $user->getEntiteName() . ' demande le dossier d\'un patient.',
            'data' => json_encode(['demande_id' => $demande->id]),
            'read' => false,
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Demande de transfert envoyée avec succès.'
            ]);
        }
        
        return redirect()->route('admin.hopital.transferts.demandes-envoyees')
            ->with('success', 'Demande de transfert envoyée avec succès.');
    }
    
    /**
     * HÔPITAL DEMANDEUR (B) - Voir mes demandes envoyées
     */
    public function demandesEnvoyees()
    {
        $user = Auth::user();
        
        $demandes = DemandeTransfertDossier::where('hopital_demandeur_id', $user->entite_id)
            ->with(['patient', 'hopitalDetenteur'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.hopital.transferts.demandes-envoyees', compact('demandes'));
    }
    
    /**
     * HÔPITAL DÉTENTEUR (A) - Voir les demandes reçues
     */
    public function demandesRecues()
    {
        $user = Auth::user();
        
        $demandes = DemandeTransfertDossier::where('hopital_detenteur_id', $user->entite_id)
            ->with(['patient', 'hopitalDemandeur'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        $stats = [
            'en_attente' => DemandeTransfertDossier::where('hopital_detenteur_id', $user->entite_id)
                ->where('statut', 'en_attente_patient')->count(),
            'accepte' => DemandeTransfertDossier::where('hopital_detenteur_id', $user->entite_id)
                ->where('statut', 'accepte_patient')->count(),
        ];
        
        return view('admin.hopital.transferts.demandes-recues', compact('demandes', 'stats'));
    }
    
    /**
     * HÔPITAL DÉTENTEUR (A) - Transférer le dossier (après acceptation patient)
     */
    public function transfererDossier(Request $request, $id)
    {
        $user = Auth::user();
        
        $demande = DemandeTransfertDossier::where('id', $id)
            ->where('hopital_detenteur_id', $user->entite_id)
            ->where('statut', 'accepte_patient')
            ->firstOrFail();
        
        DB::beginTransaction();
        try {
            // Copier le dossier médical pour l'hôpital demandeur
            $dossierOriginal = DossierMedical::where('patient_id', $demande->patient_id)
                ->where('hopital_id', $user->entite_id)
                ->latest()
                ->first();
            
            if ($dossierOriginal) {
                $nouveauDossier = $dossierOriginal->replicate();
                $nouveauDossier->hopital_id = $demande->hopital_demandeur_id;
                $nouveauDossier->numero_dossier = 'DM-TRANSFERT-' . now()->format('Ymd') . '-' . $demande->id;
                $nouveauDossier->observations = "Dossier transféré depuis " . $user->getEntiteName() . "\n\n" . $dossierOriginal->observations;
                $nouveauDossier->save();
                
                $demande->dossier_medical_id = $nouveauDossier->id;
            }
            
            $demande->update([
                'statut' => 'transfere',
                'date_transfert' => now(),
                'notes_detenteur' => $request->notes_detenteur,
            ]);
            
            // Créer une notification pour l'hôpital demandeur
            \App\Models\Notification::create([
                'user_id' => null,
                'hopital_id' => $demande->hopital_demandeur_id,
                'type' => 'transfert_complete',
                'title' => 'Dossier transféré',
                'message' => 'Le dossier médical du patient ' . $demande->patient->nom . ' a été transféré avec succès.',
                'data' => json_encode(['demande_id' => $demande->id, 'patient_id' => $demande->patient_id]),
                'read' => false,
            ]);
            
            DB::commit();
            
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Dossier transféré avec succès.']);
            }
            
            return redirect()->back()->with('success', 'Dossier transféré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            
            return back()->with('error', 'Erreur lors du transfert : ' . $e->getMessage());
        }
    }
    
    /**
     * HÔPITAL DÉTENTEUR (A) - Refuser la demande
     */
    public function refuserDemande(Request $request, $id)
    {
        $user = Auth::user();
        
        $demande = DemandeTransfertDossier::where('id', $id)
            ->where('hopital_detenteur_id', $user->entite_id)
            ->firstOrFail();
        
        $demande->update([
            'statut' => 'refuse_hopital',
            'notes_detenteur' => $request->notes_detenteur,
        ]);
        
        return redirect()->back()->with('success', 'Demande refusée.');
    }
    
    /**
     * PATIENT - Voir mes demandes de consentement
     */
    public function mesConsentements()
    {
        $user = Auth::user();
        
        if ($user->type_utilisateur !== 'patient') {
            return redirect()->route('admin.dashboard')->with('error', 'Accès patient uniquement.');
        }
        
        $demandes = DemandeTransfertDossier::where('patient_id', $user->id)
            ->with(['hopitalDemandeur', 'hopitalDetenteur'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('patient.consentements', compact('demandes'));
    }
    
    /**
     * PATIENT - Donner son consentement (accepter)
     */
    public function accepterConsentement(Request $request, $id)
    {
        $user = Auth::user();
        
        $demande = DemandeTransfertDossier::where('id', $id)
            ->where('patient_id', $user->id)
            ->where('statut', 'en_attente_patient')
            ->firstOrFail();
        
        $demande->update([
            'statut' => 'accepte_patient',
            'date_consentement_patient' => now(),
            'reponse_patient' => $request->reponse_patient ?? 'Consentement accordé',
        ]);
        
        return redirect()->back()->with('success', 'Consentement accordé. Le dossier sera transféré.');
    }
    
    /**
     * PATIENT - Refuser le consentement
     */
    public function refuserConsentement(Request $request, $id)
    {
        $user = Auth::user();
        
        $demande = DemandeTransfertDossier::where('id', $id)
            ->where('patient_id', $user->id)
            ->where('statut', 'en_attente_patient')
            ->firstOrFail();
        
        $demande->update([
            'statut' => 'refuse_patient',
            'date_consentement_patient' => now(),
            'reponse_patient' => $request->reponse_patient ?? 'Consentement refusé',
        ]);
        
        return redirect()->back()->with('success', 'Consentement refusé.');
    }
}

