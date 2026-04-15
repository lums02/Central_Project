<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Consultation;
use App\Models\Utilisateur;
use App\Models\Notification;
use App\Models\ExamenPrescrit;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class CaissierController extends Controller
{
    /**
     * Dashboard du caissier
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Statistiques du jour
        $today = Carbon::today();
        
        $stats = [
            'consultations_en_attente' => Consultation::where('hopital_id', $user->entite_id)
                ->where('statut_paiement', 'en_attente')
                ->count(),
            'consultations_payees_aujourd_hui' => Consultation::where('hopital_id', $user->entite_id)
                ->where('statut_paiement', 'paye')
                ->whereDate('date_paiement', $today)
                ->count(),
            'montant_encaisse_aujourd_hui' => Consultation::where('hopital_id', $user->entite_id)
                ->where('statut_paiement', 'paye')
                ->whereDate('date_paiement', $today)
                ->sum('montant_paye'),
            'montant_en_attente' => Consultation::where('hopital_id', $user->entite_id)
                ->where('statut_paiement', 'en_attente')
                ->sum('frais_consultation'),
        ];
        
        // Consultations en attente de paiement
        $consultationsEnAttente = Consultation::where('hopital_id', $user->entite_id)
            ->where('statut_paiement', 'en_attente')
            ->with(['patient', 'medecin', 'receptionniste'])
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();
        
        // Paiements récents
        $paiementsRecents = Consultation::where('hopital_id', $user->entite_id)
            ->where('statut_paiement', 'paye')
            ->with(['patient', 'medecin', 'caissier'])
            ->orderBy('date_paiement', 'desc')
            ->limit(10)
            ->get();
        
        return view('caissier.dashboard', compact('stats', 'consultationsEnAttente', 'paiementsRecents'));
    }
    
    /**
     * Liste des consultations en attente de paiement
     */
    public function consultations()
    {
        $user = Auth::user();
        
        $consultations = Consultation::where('hopital_id', $user->entite_id)
            ->where('statut_paiement', 'en_attente')
            ->with(['patient', 'medecin', 'receptionniste'])
            ->orderBy('created_at', 'asc')
            ->paginate(20);
        
        return view('caissier.consultations', compact('consultations'));
    }
    
    /**
     * Afficher le détail d'une consultation pour paiement
     */
    public function showConsultation($id)
    {
        $user = Auth::user();
        
        $consultation = Consultation::where('id', $id)
            ->where('hopital_id', $user->entite_id)
            ->with(['patient', 'medecin', 'receptionniste'])
            ->firstOrFail();
        
        return view('caissier.paiement', compact('consultation'));
    }
    
    /**
     * Encaisser le paiement d'une consultation
     */
    public function encaisser(Request $request, $id)
    {
        $user = Auth::user();
        
        $consultation = Consultation::where('id', $id)
            ->where('hopital_id', $user->entite_id)
            ->where('statut_paiement', 'en_attente')
            ->firstOrFail();
        
        $validated = $request->validate([
            'mode_paiement' => 'required|in:especes,carte,mobile_money,cheque,virement',
            'montant_paye' => 'required|numeric|min:0',
            'notes_caissier' => 'nullable|string',
        ]);
        
        // Marquer comme payée
        $consultation->marquerCommePayee(
            $user->id,
            $validated['mode_paiement'],
            $validated['montant_paye']
        );
        
        // Ajouter les notes si présentes
        if (isset($validated['notes_caissier'])) {
            $consultation->update(['notes_caissier' => $validated['notes_caissier']]);
        }
        
        // Notifier le médecin
        Notification::create([
            'user_id' => $consultation->medecin_id,
            'type' => 'consultation_payee',
            'title' => 'Consultation payée - Patient en attente',
            'message' => "Le patient {$consultation->patient->nom} {$consultation->patient->prenom} a payé et vous attend.",
            'data' => json_encode(['consultation_id' => $consultation->id]),
            'read' => false,
        ]);
        
        return redirect()->route('admin.caissier.facture', $consultation->id)
            ->with('success', 'Paiement enregistré avec succès');
    }
    
    /**
     * Générer et afficher la facture
     */
    public function facture($id)
    {
        $user = Auth::user();
        
        $consultation = Consultation::where('id', $id)
            ->where('hopital_id', $user->entite_id)
            ->where('statut_paiement', 'paye')
            ->with(['patient', 'medecin', 'receptionniste', 'caissier', 'hopital'])
            ->firstOrFail();
        
        return view('caissier.facture', compact('consultation'));
    }
    
    /**
     * Télécharger la facture en PDF
     */
    public function telechargerFacture($id)
    {
        $user = Auth::user();
        
        $consultation = Consultation::where('id', $id)
            ->where('hopital_id', $user->entite_id)
            ->where('statut_paiement', 'paye')
            ->with(['patient', 'medecin', 'receptionniste', 'caissier', 'hopital'])
            ->firstOrFail();
        
        $pdf = Pdf::loadView('caissier.facture-pdf', compact('consultation'));
        
        return $pdf->download("facture-{$consultation->numero_facture}.pdf");
    }
    
    /**
     * Rechercher un patient par nom
     */
    public function rechercherPatient(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('q', '');
        
        // Si "all", afficher toutes les consultations en attente
        if ($search === 'all') {
            $consultations = Consultation::where('hopital_id', $user->entite_id)
                ->where('statut_paiement', 'en_attente')
                ->with(['patient', 'medecin'])
                ->orderBy('created_at', 'asc')
                ->get();
        } elseif (strlen($search) < 2) {
            return response()->json([]);
        } else {
            // Rechercher les patients ayant des consultations en attente dans cet hôpital
            $consultations = Consultation::where('hopital_id', $user->entite_id)
                ->where('statut_paiement', 'en_attente')
                ->whereHas('patient', function($query) use ($search) {
                    $query->where('nom', 'like', "%{$search}%")
                          ->orWhere('prenom', 'like', "%{$search}%")
                          ->orWhere('telephone', 'like', "%{$search}%");
                })
                ->with(['patient', 'medecin'])
                ->orderBy('created_at', 'asc')
                ->get();
        }
        
        return response()->json($consultations->map(function($consultation) {
            return [
                'id' => $consultation->id,
                'patient_nom' => $consultation->patient->nom . ' ' . $consultation->patient->prenom,
                'patient_telephone' => $consultation->patient->telephone,
                'medecin_nom' => $consultation->medecin->nom . ' ' . $consultation->medecin->prenom,
                'motif' => $consultation->motif_consultation,
                'montant' => $consultation->frais_consultation,
                'poids' => $consultation->poids,
                'taille' => $consultation->taille,
                'date_creation' => $consultation->created_at->format('d/m/Y H:i'),
            ];
        }));
    }
    
    /**
     * Historique des paiements consultations
     */
    public function historique()
    {
        $user = Auth::user();
        
        $paiements = Consultation::where('hopital_id', $user->entite_id)
            ->where('statut_paiement', 'paye')
            ->with(['patient', 'medecin', 'caissier'])
            ->orderBy('date_paiement', 'desc')
            ->paginate(20);
        
        return view('caissier.historique', compact('paiements'));
    }
    
    /**
     * Examens en attente de paiement
     */
    public function examensEnAttente()
    {
        $user = Auth::user();
        
        $examens = ExamenPrescrit::where('hopital_id', $user->entite_id)
            ->where('statut_paiement', 'en_attente')
            ->with(['patient', 'medecin', 'dossierMedical'])
            ->orderBy('date_prescription', 'asc')
            ->paginate(20);
        
        return view('caissier.examens', compact('examens'));
    }
    
    /**
     * Valider le paiement d'un examen
     */
    public function validerPaiement(Request $request, $id)
    {
        $user = Auth::user();
        
        $examen = ExamenPrescrit::where('id', $id)
            ->where('hopital_id', $user->entite_id)
            ->firstOrFail();
        
        $validated = $request->validate([
            'prix' => 'required|numeric|min:0',
            'mode_paiement' => 'nullable|string',
        ]);
        
        $examen->update([
            'prix' => $validated['prix'],
            'statut_paiement' => 'paye',
            'statut_examen' => 'paye',
            'date_paiement' => now(),
            'caissier_id' => $user->id,
        ]);
        
        // Notifier le laborantin
        $laborantins = Utilisateur::where('type_utilisateur', 'hopital')
            ->where('entite_id', $user->entite_id)
            ->where('role', 'laborantin')
            ->get();
        
        foreach ($laborantins as $laborantin) {
            Notification::create([
                'user_id' => $laborantin->id,
                'type' => 'examen_a_realiser',
                'title' => 'Nouvel examen à réaliser',
                'message' => "Examen payé pour {$examen->patient->nom} {$examen->patient->prenom} : {$examen->nom_examen}",
                'data' => json_encode(['examen_id' => $examen->id]),
                'read' => false,
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Paiement validé avec succès. Le laborantin a été notifié.'
        ]);
    }
    
    /**
     * Historique de TOUS les paiements (consultations + examens)
     */
    public function historiqueExamens()
    {
        $user = Auth::user();
        
        // Récupérer les examens payés
        $examens = ExamenPrescrit::where('hopital_id', $user->entite_id)
            ->where('statut_paiement', 'paye')
            ->with(['patient', 'medecin', 'dossierMedical'])
            ->orderBy('date_paiement', 'desc')
            ->get();
        
        // Récupérer les consultations payées
        $consultations = Consultation::where('hopital_id', $user->entite_id)
            ->where('statut_paiement', 'paye')
            ->with(['patient', 'medecin', 'caissier'])
            ->orderBy('date_paiement', 'desc')
            ->get();
        
        return view('caissier.historique-examens', compact('examens', 'consultations'));
    }
}
