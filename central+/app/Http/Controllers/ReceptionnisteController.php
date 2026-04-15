<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Utilisateur;
use App\Models\RendezVous;
use App\Models\DossierMedical;
use App\Models\Hopital;
use App\Models\Consultation;
use App\Models\Notification;
use Carbon\Carbon;

class ReceptionnisteController extends Controller
{
    /**
     * Dashboard du réceptionniste
     */
    public function dashboard()
    {
        $user = Auth::user();
        $hopital = Hopital::find($user->entite_id);
        
        // Statistiques du jour
        $today = Carbon::today();
        
        // Patients ayant un RDV aujourd'hui
        $patientIdsDuJour = RendezVous::where('hopital_id', $user->entite_id)
            ->whereDate('date_rendezvous', $today)
            ->pluck('patient_id')
            ->unique();
        
        $stats = [
            'patients_du_jour' => $patientIdsDuJour->count(),
            'rdv_du_jour' => RendezVous::where('hopital_id', $user->entite_id)
                ->whereDate('date_rendezvous', $today)
                ->count(),
            'rdv_confirmes' => RendezVous::where('hopital_id', $user->entite_id)
                ->whereDate('date_rendezvous', $today)
                ->where('statut', 'confirme')
                ->count(),
            'rdv_en_attente' => RendezVous::where('hopital_id', $user->entite_id)
                ->whereDate('date_rendezvous', $today)
                ->where('statut', 'en_attente')
                ->count(),
        ];
        
        // Rendez-vous du jour
        $rendezVousDuJour = RendezVous::where('hopital_id', $user->entite_id)
            ->whereDate('date_rendezvous', $today)
            ->with(['patient', 'medecin'])
            ->orderBy('heure_rendezvous', 'asc')
            ->get();
        
        // Patients récents ayant des RDV dans cet hôpital
        $patientsRecentsIds = RendezVous::where('hopital_id', $user->entite_id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->pluck('patient_id')
            ->unique();
        
        $patientsRecents = Utilisateur::whereIn('id', $patientsRecentsIds)
            ->where('type_utilisateur', 'patient')
            ->limit(5)
            ->get();
        
        return view('receptionniste.dashboard', compact('stats', 'rendezVousDuJour', 'patientsRecents', 'hopital'));
    }
    
    /**
     * Liste des patients
     */
    public function patients()
    {
        $user = Auth::user();
        
        // Récupérer tous les patients ayant des consultations, rendez-vous ou dossiers dans cet hôpital
        $patientIds = Consultation::where('hopital_id', $user->entite_id)
            ->pluck('patient_id')
            ->merge(
                RendezVous::where('hopital_id', $user->entite_id)
                    ->pluck('patient_id')
            )
            ->merge(
                DossierMedical::where('hopital_id', $user->entite_id)
                    ->pluck('patient_id')
            )
            ->unique();
        
        $patients = Utilisateur::whereIn('id', $patientIds)
            ->where('type_utilisateur', 'patient')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('receptionniste.patients', compact('patients'));
    }
    
    /**
     * Créer un nouveau patient avec consultation
     */
    public function storePatient(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:masculin,feminin',
            'telephone' => 'required|string|max:20',
            'email' => 'nullable|email|unique:utilisateurs,email',
            'adresse' => 'required|string',
            'groupe_sanguin' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'mot_de_passe' => 'required|string|min:6',
            
            // Informations de consultation
            'medecin_id' => 'required|exists:utilisateurs,id',
            'motif_consultation' => 'required|string',
            'poids' => 'nullable|numeric|min:0',
            'taille' => 'nullable|numeric|min:0',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'tension_arterielle' => 'nullable|string|max:20',
            'frequence_cardiaque' => 'nullable|integer|min:0',
            'frais_consultation' => 'required|numeric|min:0',
            'notes_receptionniste' => 'nullable|string',
        ]);
        
        // Créer l'utilisateur patient
        $patient = Utilisateur::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'adresse' => $validated['adresse'],
            'mot_de_passe' => bcrypt($validated['mot_de_passe']),
            'type_utilisateur' => 'patient',
            'role' => 'patient',
            'status' => 'approved',
            'date_naissance' => $validated['date_naissance'],
            'sexe' => $validated['sexe'],
            'groupe_sanguin' => $validated['groupe_sanguin'],
            'hopital_id' => $user->entite_id,
        ]);
        
        // Assigner le rôle patient
        $patient->assignRole('patient');
        
        // Créer la consultation
        $consultation = Consultation::create([
            'hopital_id' => $user->entite_id,
            'patient_id' => $patient->id,
            'medecin_id' => $validated['medecin_id'],
            'receptionniste_id' => $user->id,
            'motif_consultation' => $validated['motif_consultation'],
            'poids' => $validated['poids'] ?? null,
            'taille' => $validated['taille'] ?? null,
            'temperature' => $validated['temperature'] ?? null,
            'tension_arterielle' => $validated['tension_arterielle'] ?? null,
            'frequence_cardiaque' => $validated['frequence_cardiaque'] ?? null,
            'frais_consultation' => $validated['frais_consultation'],
            'notes_receptionniste' => $validated['notes_receptionniste'] ?? null,
            'statut_paiement' => 'en_attente',
            'statut_consultation' => 'en_attente_paiement',
        ]);
        
        // Notifier les caissiers
        $caissiers = Utilisateur::where('entite_id', $user->entite_id)
            ->where('type_utilisateur', 'hopital')
            ->where('role', 'caissier')
            ->get();
        
        foreach ($caissiers as $caissier) {
            Notification::create([
                'user_id' => $caissier->id,
                'type' => 'nouvelle_consultation',
                'title' => 'Nouvelle consultation à encaisser',
                'message' => "Patient : {$patient->nom} {$patient->prenom} - Montant : {$validated['frais_consultation']} FC",
                'data' => json_encode(['consultation_id' => $consultation->id]),
                'read' => false,
            ]);
        }
        
        return redirect()->route('admin.receptionniste.dashboard')
            ->with('success', 'Patient et consultation créés avec succès. Le patient doit maintenant payer à la caisse.');
    }
    
    /**
     * Mettre à jour un patient
     */
    public function updatePatient(Request $request, $id)
    {
        $user = Auth::user();
        $patient = Utilisateur::where('id', $id)
            ->where('type_utilisateur', 'patient')
            ->firstOrFail();
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'groupe_sanguin' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        ]);
        
        $patient->update($validated);
        
        return redirect()->route('admin.receptionniste.patients')
            ->with('success', 'Patient mis à jour avec succès');
    }
    
    /**
     * Rendez-vous
     */
    public function rendezvous()
    {
        $user = Auth::user();
        
        $rendezvous = RendezVous::where('hopital_id', $user->entite_id)
            ->with(['patient', 'medecin'])
            ->orderBy('date_rendezvous', 'desc')
            ->orderBy('heure_rendezvous', 'desc')
            ->paginate(20);
        
        // Liste des médecins pour créer des RDV
        $medecins = Utilisateur::where('entite_id', $user->entite_id)
            ->where('type_utilisateur', 'hopital')
            ->where('role', 'medecin')
            ->get();
        
        // Liste des patients ayant des RDV ou dossiers dans cet hôpital
        $patientIds = RendezVous::where('hopital_id', $user->entite_id)
            ->pluck('patient_id')
            ->merge(
                DossierMedical::where('hopital_id', $user->entite_id)
                    ->pluck('patient_id')
            )
            ->unique();
        
        $patients = Utilisateur::whereIn('id', $patientIds)
            ->where('type_utilisateur', 'patient')
            ->get();
        
        return view('receptionniste.rendezvous', compact('rendezvous', 'medecins', 'patients'));
    }
    
    /**
     * Créer un rendez-vous
     */
    public function storeRendezVous(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'patient_id' => 'required|exists:utilisateurs,id',
            'medecin_id' => 'required|exists:utilisateurs,id',
            'date_rendezvous' => 'required|date',
            'heure_rendezvous' => 'required',
            'motif' => 'required|string',
        ]);
        
        RendezVous::create([
            'patient_id' => $validated['patient_id'],
            'medecin_id' => $validated['medecin_id'],
            'hopital_id' => $user->entite_id,
            'date_rendezvous' => $validated['date_rendezvous'],
            'heure_rendezvous' => $validated['heure_rendezvous'],
            'motif' => $validated['motif'],
            'statut' => 'en_attente',
        ]);
        
        return redirect()->route('admin.receptionniste.rendezvous')
            ->with('success', 'Rendez-vous créé avec succès');
    }
    
    /**
     * Confirmer un rendez-vous
     */
    public function confirmerRendezVous($id)
    {
        $user = Auth::user();
        
        $rdv = RendezVous::where('id', $id)
            ->where('hopital_id', $user->entite_id)
            ->firstOrFail();
        
        $rdv->update(['statut' => 'confirme']);
        
        return redirect()->back()->with('success', 'Rendez-vous confirmé');
    }
    
    /**
     * Annuler un rendez-vous
     */
    public function annulerRendezVous($id)
    {
        $user = Auth::user();
        
        $rdv = RendezVous::where('id', $id)
            ->where('hopital_id', $user->entite_id)
            ->firstOrFail();
        
        $rdv->update(['statut' => 'annule']);
        
        return redirect()->back()->with('success', 'Rendez-vous annulé');
    }
}

