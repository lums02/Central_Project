<?php

namespace App\Http\Controllers;

use App\Models\ExamenPrescrit;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaborantinController extends Controller
{
    /**
     * Dashboard du laborantin
     */
    public function dashboard()
    {
        $laborantin = Auth::user();
        
        $stats = [
            'examens_en_attente' => ExamenPrescrit::where('hopital_id', $laborantin->entite_id)
                ->where('statut_examen', 'paye')->count(),
            'examens_en_cours' => ExamenPrescrit::where('hopital_id', $laborantin->entite_id)
                ->where('statut_examen', 'en_cours')
                ->where('laborantin_id', $laborantin->id)->count(),
            'examens_termines_aujourd_hui' => ExamenPrescrit::where('hopital_id', $laborantin->entite_id)
                ->where('statut_examen', 'termine')
                ->whereDate('date_realisation', today())->count(),
            'total_examens' => ExamenPrescrit::where('hopital_id', $laborantin->entite_id)
                ->where('statut_examen', 'termine')->count(),
        ];
        
        return view('laborantin.dashboard', compact('stats'));
    }
    
    /**
     * Liste des examens à réaliser
     */
    public function examensARealiser()
    {
        $laborantin = Auth::user();
        
        $examens = ExamenPrescrit::where('hopital_id', $laborantin->entite_id)
            ->whereIn('statut_examen', ['paye', 'en_cours'])
            ->with(['patient', 'medecin', 'dossierMedical'])
            ->orderBy('date_prescription', 'desc')
            ->paginate(20);
        
        return view('laborantin.examens', compact('examens'));
    }
    
    /**
     * Uploader les résultats d'un examen
     */
    public function uploaderResultats(Request $request, $id)
    {
        $laborantin = Auth::user();
        
        $request->validate([
            'resultats' => 'required|string',
            'interpretation' => 'nullable|string',
            'fichier' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
        
        $examen = ExamenPrescrit::where('id', $id)
            ->where('hopital_id', $laborantin->entite_id)
            ->firstOrFail();
        
        $fichierPath = null;
        if ($request->hasFile('fichier')) {
            $fichierPath = $request->file('fichier')->store('examens_resultats', 'public');
        }
        
        $examen->update([
            'statut_examen' => 'termine',
            'laborantin_id' => $laborantin->id,
            'date_realisation' => now(),
            'resultats' => $request->resultats,
            'interpretation' => $request->interpretation,
            'fichier_resultat' => $fichierPath,
        ]);
        
        // Ajouter les résultats au diagnostic du dossier médical
        $dossier = $examen->dossierMedical;
        if ($dossier) {
            $resultatExamen = "\n\n=== RÉSULTAT EXAMEN: " . $examen->nom_examen . " ===";
            $resultatExamen .= "\nDate: " . now()->format('d/m/Y H:i');
            $resultatExamen .= "\nRésultats: " . $request->resultats;
            if ($request->interpretation) {
                $resultatExamen .= "\nInterprétation: " . $request->interpretation;
            }
            
            // Ajouter au diagnostic existant
            $dossier->update([
                'diagnostic' => $dossier->diagnostic . $resultatExamen
            ]);
        }
        
        // Notifier le médecin
        Notification::create([
            'user_id' => $examen->medecin_id,
            'hopital_id' => null,
            'type' => 'resultats_examen',
            'title' => 'Résultats d\'examen disponibles',
            'message' => 'Résultats de ' . $examen->nom_examen . ' pour ' . $examen->patient->nom . ' sont disponibles.',
            'data' => json_encode(['examen_id' => $examen->id, 'dossier_id' => $examen->dossier_medical_id]),
            'read' => false,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Résultats uploadés avec succès. Le médecin a été notifié.'
        ]);
    }
    
    /**
     * Marquer un examen comme en cours
     */
    public function marquerEnCours($id)
    {
        $laborantin = Auth::user();
        
        $examen = ExamenPrescrit::where('id', $id)
            ->where('hopital_id', $laborantin->entite_id)
            ->firstOrFail();
        
        $examen->update([
            'statut_examen' => 'en_cours',
            'laborantin_id' => $laborantin->id,
        ]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Historique de tous les examens réalisés
     */
    public function historique()
    {
        $laborantin = Auth::user();
        
        $examens = ExamenPrescrit::where('hopital_id', $laborantin->entite_id)
            ->where('statut_examen', 'termine')
            ->with(['patient', 'medecin', 'dossierMedical', 'laborantin'])
            ->orderBy('date_realisation', 'desc')
            ->paginate(20);
        
        return view('laborantin.historique', compact('examens'));
    }
}

