<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\DossierMedical;
use App\Models\RendezVous;
use App\Models\ExamenPrescrit;
use App\Models\Notification;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedecinController extends Controller
{
    public function dashboard()
    {
        $medecin = Auth::user();
        
        // Récupérer UNIQUEMENT les patients de SON hôpital
        $patients = Utilisateur::where('type_utilisateur', 'patient')
            ->where(function($query) use ($medecin) {
                $query->where('entite_id', $medecin->entite_id)
                      ->orWhere('hopital_id', $medecin->entite_id);
            })
            ->get();
        
        // Récupérer UNIQUEMENT ses dossiers médicaux
        $dossiers = DossierMedical::where('medecin_id', $medecin->id)
            ->where('hopital_id', $medecin->entite_id)
            ->with(['patient', 'hopital'])
            ->orderBy('date_consultation', 'desc')
            ->get();
        
        $stats = [
            'total_patients' => $patients->count(),
            'total_dossiers' => $dossiers->count(),
            'dossiers_actifs' => $dossiers->where('statut', 'actif')->count(),
            'consultations_aujourd_hui' => $dossiers->where('date_consultation', today())->count(),
        ];
        
        return view('medecin.dashboard', compact('patients', 'dossiers', 'stats'));
    }
    
    public function patients()
    {
        $medecin = Auth::user();
        
        // Récupérer les patients avec consultations payées pour ce médecin
        $consultationsPatientsIds = Consultation::where('hopital_id', $medecin->entite_id)
            ->where('medecin_id', $medecin->id)
            ->where('statut_paiement', 'paye')
            ->pluck('patient_id')
            ->unique();
        
        // Récupérer aussi les patients avec dossiers existants
        $dossiersPatientsIds = DossierMedical::where('medecin_id', $medecin->id)
            ->pluck('patient_id')
            ->unique();
        
        // Fusionner les deux listes
        $allPatientsIds = $consultationsPatientsIds->merge($dossiersPatientsIds)->unique();
        
        $patients = Utilisateur::whereIn('id', $allPatientsIds)
            ->where('type_utilisateur', 'patient')
            ->with([
                'dossiers' => function($query) use ($medecin) {
                    $query->where('medecin_id', $medecin->id);
                }
            ])
            ->get();
        
        // Ajouter les consultations en attente pour chaque patient
        foreach ($patients as $patient) {
            $patient->consultations_en_attente = Consultation::where('patient_id', $patient->id)
                ->where('medecin_id', $medecin->id)
                ->where('statut_paiement', 'paye')
                ->whereIn('statut_consultation', ['paye_en_attente', 'en_cours'])
                ->count();
        }
        
        return view('medecin.patients', compact('patients'));
    }
    
    public function rendezvous()
    {
        $medecin = Auth::user();
        
        // Récupérer les patients pour le formulaire
        $patients = Utilisateur::where('type_utilisateur', 'patient')
            ->where(function($query) use ($medecin) {
                $query->where('entite_id', $medecin->entite_id)
                      ->orWhere('hopital_id', $medecin->entite_id);
            })
            ->get();
        
        // Récupérer les rendez-vous du médecin
        $rendezvous = RendezVous::where('medecin_id', $medecin->id)
            ->with(['patient', 'hopital'])
            ->orderBy('date_rendezvous', 'desc')
            ->orderBy('heure_rendezvous', 'desc')
            ->get();
        
        return view('medecin.rendezvous', compact('patients', 'rendezvous'));
    }
    
    public function dossiers()
    {
        $medecin = Auth::user();
        
        $dossiers = DossierMedical::where('medecin_id', $medecin->id)
            ->with(['patient', 'hopital'])
            ->orderBy('date_consultation', 'desc')
            ->paginate(10);
        
        // Récupérer les patients pour le formulaire
        $patients = Utilisateur::where('type_utilisateur', 'patient')
            ->where('entite_id', $medecin->entite_id)
            ->get();
        
        return view('medecin.dossiers', compact('dossiers', 'patients'));
    }
    
    public function showDossier($id)
    {
        $medecin = Auth::user();
        
        $dossier = DossierMedical::where('medecin_id', $medecin->id)
            ->where('id', $id)
            ->with(['patient', 'hopital'])
            ->firstOrFail();
        
        return view('medecin.dossier-show', compact('dossier'));
    }
    
    public function createDossier(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:utilisateurs,id',
            'date_consultation' => 'required|date',
            'motif_consultation' => 'required|string',
            'diagnostic' => 'required|string',
            // Tous les autres champs sont optionnels
            'antecedents_medicaux' => 'nullable|string',
            'antecedents_familiaux' => 'nullable|string',
            'allergies' => 'nullable|string',
            'traitements_en_cours' => 'nullable|string',
            'poids' => 'nullable|numeric',
            'taille' => 'nullable|numeric',
            'temperature' => 'nullable|numeric',
            'tension_systolique' => 'nullable|integer',
            'tension_diastolique' => 'nullable|integer',
            'pouls' => 'nullable|integer',
            'frequence_respiratoire' => 'nullable|integer',
            'histoire_maladie' => 'nullable|string',
            'symptomes' => 'nullable|string',
            'examen_general' => 'nullable|string',
            'examen_physique' => 'nullable|string',
            'code_cim10' => 'nullable|string',
            'diagnostics_secondaires' => 'nullable|string',
            'diagnostic_differentiel' => 'nullable|string',
            'traitement' => 'nullable|string',
            'observations' => 'nullable|string',
            'date_prochain_rdv' => 'nullable|date',
            'urgence' => 'nullable|string',
        ]);
        
        $medecin = Auth::user();
        
        // Générer un numéro de dossier unique
        $numeroDossier = 'DM-' . date('Ymd') . '-' . str_pad(DossierMedical::count() + 1, 5, '0', STR_PAD_LEFT);
        
        // Construire les signes vitaux
        $signesVitaux = [];
        if ($request->temperature) $signesVitaux[] = "Température: {$request->temperature}°C";
        if ($request->tension_systolique && $request->tension_diastolique) {
            $signesVitaux[] = "TA: {$request->tension_systolique}/{$request->tension_diastolique} mmHg";
        }
        if ($request->pouls) $signesVitaux[] = "Pouls: {$request->pouls} bpm";
        if ($request->frequence_respiratoire) $signesVitaux[] = "FR: {$request->frequence_respiratoire}/min";
        if ($request->poids) $signesVitaux[] = "Poids: {$request->poids} kg";
        if ($request->taille) $signesVitaux[] = "Taille: {$request->taille} cm";
        
        // Calculer l'IMC si poids et taille sont fournis
        $imc = null;
        if ($request->poids && $request->taille) {
            $taille_m = $request->taille / 100;
            $imc = round($request->poids / ($taille_m * $taille_m), 1);
            $signesVitaux[] = "IMC: {$imc}";
        }
        
        $signesVitauxText = !empty($signesVitaux) ? implode("\n", $signesVitaux) : null;
        
        // Construire l'examen clinique complet
        $examenClinique = [];
        if ($request->examen_general) $examenClinique[] = "EXAMEN GÉNÉRAL:\n{$request->examen_general}";
        if ($signesVitauxText) $examenClinique[] = "SIGNES VITAUX:\n{$signesVitauxText}";
        if ($request->examen_physique) $examenClinique[] = "EXAMEN PHYSIQUE:\n{$request->examen_physique}";
        
        $examenCliniqueText = !empty($examenClinique) ? implode("\n\n", $examenClinique) : null;
        
        // Construire le diagnostic complet
        $diagnosticComplet = $request->diagnostic;
        if ($request->code_cim10) $diagnosticComplet .= " (CIM-10: {$request->code_cim10})";
        if ($request->diagnostics_secondaires) {
            $diagnosticComplet .= "\n\nDIAGNOSTICS SECONDAIRES:\n{$request->diagnostics_secondaires}";
        }
        if ($request->diagnostic_differentiel) {
            $diagnosticComplet .= "\n\nDIAGNOSTIC DIFFÉRENTIEL:\n{$request->diagnostic_differentiel}";
        }
        
        // Construire les antécédents
        $antecedents = [];
        if ($request->antecedents_medicaux) $antecedents[] = "ANTÉCÉDENTS MÉDICAUX:\n{$request->antecedents_medicaux}";
        if ($request->antecedents_familiaux) $antecedents[] = "ANTÉCÉDENTS FAMILIAUX:\n{$request->antecedents_familiaux}";
        if ($request->allergies) $antecedents[] = "ALLERGIES:\n{$request->allergies}";
        if ($request->traitements_en_cours) $antecedents[] = "TRAITEMENTS EN COURS:\n{$request->traitements_en_cours}";
        
        $antecedentsText = !empty($antecedents) ? implode("\n\n", $antecedents) : null;
        
        // Construire l'anamnèse
        $anamneseText = $request->motif_consultation;
        if ($request->histoire_maladie) $anamneseText .= "\n\nHISTOIRE DE LA MALADIE:\n{$request->histoire_maladie}";
        if ($request->symptomes) $anamneseText .= "\n\nSYMPTÔMES:\n{$request->symptomes}";
        
        $dossier = DossierMedical::create([
            'patient_id' => $request->patient_id,
            'medecin_id' => $medecin->id,
            'hopital_id' => $medecin->entite_id,
            'numero_dossier' => $numeroDossier,
            'motif_consultation' => $anamneseText,
            'antecedents' => $antecedentsText,
            'examen_clinique' => $examenCliniqueText,
            'diagnostic' => $diagnosticComplet,
            'traitement' => $request->traitement ?? 'En attente des résultats d\'examens',
            'observations' => $request->observations,
            'date_consultation' => $request->date_consultation,
            'date_prochain_rdv' => $request->date_prochain_rdv,
            'urgence' => $request->urgence ?? 'normale',
            'statut' => 'actif',
        ]);
        
        // Créer automatiquement un rendez-vous si date_prochain_rdv est définie
        if ($request->date_prochain_rdv) {
            RendezVous::create([
                'patient_id' => $request->patient_id,
                'medecin_id' => $medecin->id,
                'hopital_id' => $medecin->entite_id,
                'date_rendezvous' => $request->date_prochain_rdv,
                'heure_rendezvous' => '09:00', // Heure par défaut
                'type_consultation' => 'suivi',
                'motif' => 'Rendez-vous de suivi suite à consultation du ' . $request->date_consultation,
                'notes' => 'Créé automatiquement depuis le dossier médical ' . $numeroDossier,
                'statut' => 'en_attente',
                'prix' => 0,
            ]);
        }
        
        return redirect()->route('admin.medecin.dossier.show', $dossier->id)
            ->with('success', 'Dossier médical créé avec succès !' . ($request->date_prochain_rdv ? ' Un rendez-vous de suivi a été automatiquement créé.' : ''));
    }
    
    public function createRendezVous(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:utilisateurs,id',
            'date_rendezvous' => 'required|date|after_or_equal:today',
            'heure_rendezvous' => 'required',
            'type_rendezvous' => 'required|string',
            'motif' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        
        $medecin = Auth::user();
        
        RendezVous::create([
            'patient_id' => $request->patient_id,
            'medecin_id' => $medecin->id,
            'hopital_id' => $medecin->entite_id,
            'date_rendezvous' => $request->date_rendezvous,
            'heure_rendezvous' => $request->heure_rendezvous,
            'type_consultation' => $request->type_rendezvous,
            'motif' => $request->motif,
            'notes' => $request->notes,
            'statut' => 'en_attente',
            'prix' => 0,
        ]);
        
        return redirect()->route('admin.medecin.rendezvous')
            ->with('success', 'Rendez-vous créé avec succès !');
    }
    
    /**
     * Mettre à jour le statut d'un rendez-vous
     */
    public function updateRendezVousStatut(Request $request, $id)
    {
        $medecin = Auth::user();
        
        $rendezvous = RendezVous::where('id', $id)
            ->where('medecin_id', $medecin->id)
            ->firstOrFail();
        
        $rendezvous->update(['statut' => $request->statut]);
        
        $message = match($request->statut) {
            'confirme' => 'Rendez-vous confirmé avec succès !',
            'termine' => 'Rendez-vous marqué comme terminé !',
            'annule' => 'Rendez-vous annulé.',
            default => 'Statut mis à jour.'
        };
        
        return redirect()->route('admin.medecin.rendezvous')
            ->with('success', $message);
    }
    
    /**
     * Mettre à jour un dossier médical
     */
    public function updateDossier(Request $request, $id)
    {
        $medecin = Auth::user();
        
        $dossier = DossierMedical::where('id', $id)
            ->where('medecin_id', $medecin->id)
            ->firstOrFail();
        
        // Déterminer le type de mise à jour
        if ($request->has('traitement') && $request->has('diagnostic_final')) {
            // Ajout d'un traitement après résultats d'examens
            $updateData = [];
            
            // Mettre à jour le diagnostic si un diagnostic final est fourni
            if ($request->diagnostic_final) {
                $diagnosticFinal = "DIAGNOSTIC FINAL (Confirmé):\n" . $request->diagnostic_final;
                $diagnosticFinal .= "\n\n" . "DIAGNOSTIC INITIAL:\n" . $dossier->diagnostic;
                $updateData['diagnostic'] = $diagnosticFinal;
            }
            
            // Ajouter le traitement
            $traitementComplet = "=== TRAITEMENT PRESCRIT LE " . now()->format('d/m/Y') . " ===\n\n";
            $traitementComplet .= $request->traitement;
            
            if ($request->soins) {
                $traitementComplet .= "\n\nSOINS ET PROCÉDURES:\n" . $request->soins;
            }
            
            if ($request->recommandations) {
                $traitementComplet .= "\n\nRECOMMANDATIONS:\n" . $request->recommandations;
            }
            
            // Ajouter au traitement existant ou remplacer
            if ($dossier->traitement && $dossier->traitement !== 'En attente des résultats d\'examens') {
                $updateData['traitement'] = $dossier->traitement . "\n\n" . $traitementComplet;
            } else {
                $updateData['traitement'] = $traitementComplet;
            }
            
            $dossier->update($updateData);
            
            return response()->json([
                'success' => true,
                'message' => 'Traitement ajouté avec succès'
            ]);
            
        } elseif ($request->has('evolution')) {
            // Ajout d'une consultation de suivi
            $consultation = "\n\n=== CONSULTATION DU " . now()->format('d/m/Y') . " ===\n";
            $consultation .= "Type: " . ($request->type_consultation ?? 'Suivi') . "\n";
            $consultation .= "Motif: " . $request->motif . "\n\n";
            $consultation .= "ÉVOLUTION:\n" . $request->evolution . "\n";
            
            if ($request->nouveaux_symptomes) {
                $consultation .= "\nNOUVEAUX SYMPTÔMES:\n" . $request->nouveaux_symptomes . "\n";
            }
            
            if ($request->examen_clinique) {
                $consultation .= "\nEXAMEN CLINIQUE:\n" . $request->examen_clinique . "\n";
            }
            
            if ($request->ajustement_traitement) {
                $consultation .= "\nAJUSTEMENT DU TRAITEMENT:\n" . $request->ajustement_traitement . "\n";
            }
            
            if ($request->notes) {
                $consultation .= "\nNOTES:\n" . $request->notes . "\n";
            }
            
            // Ajouter aux observations
            $dossier->update([
                'observations' => ($dossier->observations ?? '') . $consultation
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Consultation ajoutée avec succès'
            ]);
            
        } else {
            // Modification simple du dossier
            $updateData = [];
            
            if ($request->has('diagnostic')) $updateData['diagnostic'] = $request->diagnostic;
            if ($request->has('traitement')) $updateData['traitement'] = $request->traitement;
            if ($request->has('observations')) $updateData['observations'] = $request->observations;
            if ($request->has('statut')) $updateData['statut'] = $request->statut;
            
            $dossier->update($updateData);
            
            return response()->json([
                'success' => true,
                'message' => 'Dossier mis à jour avec succès'
            ]);
        }
    }
    
    /**
     * Prescrire des examens médicaux
     */
    public function prescrireExamens(Request $request, $id)
    {
        $medecin = Auth::user();
        
        $dossier = DossierMedical::where('id', $id)
            ->where('medecin_id', $medecin->id)
            ->firstOrFail();
        
        $examens = $request->examens;
        $examensCreated = [];
        
        foreach ($examens as $examenData) {
            $numeroExamen = 'EX-' . date('Ymd') . '-' . str_pad(ExamenPrescrit::count() + 1, 5, '0', STR_PAD_LEFT);
            
            $examen = ExamenPrescrit::create([
                'dossier_medical_id' => $dossier->id,
                'patient_id' => $dossier->patient_id,
                'medecin_id' => $medecin->id,
                'hopital_id' => $medecin->entite_id,
                'numero_examen' => $numeroExamen,
                'type_examen' => $examenData['type'] ?? $examenData['type_examen'] ?? 'Autre',
                'nom_examen' => $examenData['nom'] ?? $examenData['nom_examen'] ?? '',
                'indication' => $examenData['indication'] ?? '',
                'date_prescription' => now(),
                'prix' => 0, // Le caissier fixera le prix
                'statut_paiement' => 'en_attente',
                'statut_examen' => 'prescrit',
            ]);
            
            $examensCreated[] = $examen;
        }
        
        // Créer notification pour le caissier
        $caissiers = Utilisateur::where('type_utilisateur', 'hopital')
            ->where('entite_id', $medecin->entite_id)
            ->where('role', 'caissier')
            ->get();
        
        foreach ($caissiers as $caissier) {
            Notification::create([
                'user_id' => $caissier->id,
                'hopital_id' => null,
                'type' => 'examens_a_payer',
                'title' => 'Examens à valider',
                'message' => 'Le Dr. ' . $medecin->nom . ' a prescrit ' . count($examensCreated) . ' examen(s) pour ' . $dossier->patient->nom,
                'data' => json_encode(['dossier_id' => $dossier->id, 'examens' => array_column($examensCreated, 'id')]),
                'read' => false,
            ]);
        }
        
        return redirect()->route('admin.medecin.dossier.show', $dossier->id)
            ->with('success', count($examensCreated) . ' examen(s) prescrit(s) avec succès ! Le caissier a été notifié.');
    }
    
    /**
     * Afficher une consultation pour le médecin
     */
    public function showConsultation($id)
    {
        $medecin = Auth::user();
        
        $consultation = Consultation::where('id', $id)
            ->where('medecin_id', $medecin->id)
            ->where('hopital_id', $medecin->entite_id)
            ->with(['patient', 'receptionniste', 'caissier', 'dossierMedical'])
            ->firstOrFail();
        
        return view('medecin.consultation-show', compact('consultation'));
    }
    
    /**
     * Démarrer une consultation (marquer comme "en cours")
     */
    public function demarrerConsultation($id)
    {
        $medecin = Auth::user();
        
        $consultation = Consultation::where('id', $id)
            ->where('medecin_id', $medecin->id)
            ->where('hopital_id', $medecin->entite_id)
            ->firstOrFail();
        
        $consultation->demarrerConsultation();
        
        return redirect()->back()->with('success', 'Consultation démarrée');
    }
    
    /**
     * Créer un dossier médical à partir d'une consultation
     */
    public function creerDossierDepuisConsultation(Request $request, $id)
    {
        $medecin = Auth::user();
        
        $consultation = Consultation::where('id', $id)
            ->where('medecin_id', $medecin->id)
            ->where('hopital_id', $medecin->entite_id)
            ->firstOrFail();
        
        $validated = $request->validate([
            'anamnese' => 'required|string',
            'examen_clinique' => 'required|string',
            'diagnostic' => 'required|string',
            'antecedents_medicaux' => 'nullable|string',
            'antecedents_familiaux' => 'nullable|string',
            'allergies' => 'nullable|string',
            'traitement_actuel' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        // Créer le dossier médical
        $dossier = DossierMedical::create([
            'patient_id' => $consultation->patient_id,
            'medecin_id' => $medecin->id,
            'hopital_id' => $medecin->entite_id,
            'numero_dossier' => 'DOS-' . strtoupper(uniqid()),
            'date_consultation' => $consultation->date_consultation ?? now(),
            'motif_consultation' => $consultation->motif_consultation,
            
            // Signes vitaux depuis la consultation
            'poids' => $consultation->poids,
            'taille' => $consultation->taille,
            'temperature' => $consultation->temperature,
            'tension_arterielle' => $consultation->tension_arterielle,
            'frequence_cardiaque' => $consultation->frequence_cardiaque,
            
            // Informations remplies par le médecin
            'anamnese' => $validated['anamnese'],
            'examen_clinique' => $validated['examen_clinique'],
            'diagnostic' => $validated['diagnostic'],
            'traitement' => 'En attente des résultats d\'examens',
            'antecedents_medicaux' => $validated['antecedents_medicaux'] ?? null,
            'antecedents_familiaux' => $validated['antecedents_familiaux'] ?? null,
            'allergies' => $validated['allergies'] ?? null,
            'traitement_actuel' => $validated['traitement_actuel'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'statut' => 'actif',
        ]);
        
        // Lier le dossier à la consultation et marquer la consultation comme terminée
        $consultation->update([
            'dossier_medical_id' => $dossier->id,
            'statut_consultation' => 'termine'
        ]);
        
        return redirect()->route('admin.medecin.dossier.show', $dossier->id)
            ->with('success', 'Dossier médical créé avec succès');
    }
    
    /**
     * Prescrire des examens depuis une consultation
     */
    public function prescrireExamensConsultation(Request $request, $id)
    {
        $medecin = Auth::user();
        
        $consultation = Consultation::where('id', $id)
            ->where('medecin_id', $medecin->id)
            ->where('hopital_id', $medecin->entite_id)
            ->firstOrFail();
        
        // Si pas encore de dossier, créer d'abord le dossier
        if (!$consultation->dossier_medical_id) {
            return redirect()->back()->with('error', 'Veuillez d\'abord créer le dossier médical avant de prescrire des examens');
        }
        
        $validated = $request->validate([
            'examens' => 'required|array|min:1',
            'examens.*.type_examen' => 'required|string',
            'examens.*.nom_examen' => 'required|string',
            'examens.*.indication' => 'required|string',
        ]);
        
        $examensCreated = [];
        
        foreach ($validated['examens'] as $index => $examenData) {
            $numeroExamen = 'EX-' . $medecin->entite_id . '-' . date('Ymd') . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            
            $examen = ExamenPrescrit::create([
                'dossier_medical_id' => $consultation->dossier_medical_id,
                'patient_id' => $consultation->patient_id,
                'medecin_id' => $medecin->id,
                'hopital_id' => $medecin->entite_id,
                'numero_examen' => $numeroExamen,
                'type_examen' => $examenData['type_examen'],
                'nom_examen' => $examenData['nom_examen'],
                'indication' => $examenData['indication'],
                'date_prescription' => now(),
                'prix' => 0,
                'statut_paiement' => 'en_attente',
                'statut_examen' => 'prescrit',
            ]);
            
            $examensCreated[] = $examen;
        }
        
        // Terminer la consultation
        $consultation->terminerConsultation($consultation->dossier_medical_id);
        
        // Notifier les caissiers
        $caissiers = Utilisateur::where('type_utilisateur', 'hopital')
            ->where('entite_id', $medecin->entite_id)
            ->where('role', 'caissier')
            ->get();
        
        foreach ($caissiers as $caissier) {
            Notification::create([
                'user_id' => $caissier->id,
                'type' => 'examens_a_payer',
                'title' => 'Examens à encaisser',
                'message' => "Le Dr. {$medecin->nom} a prescrit " . count($examensCreated) . " examen(s) pour {$consultation->patient->nom} {$consultation->patient->prenom}",
                'data' => json_encode(['dossier_id' => $consultation->dossier_medical_id]),
                'read' => false,
            ]);
        }
        
        return redirect()->route('admin.medecin.patients')
            ->with('success', 'Examens prescrits avec succès. Le patient doit retourner à la caisse pour payer.');
    }
}