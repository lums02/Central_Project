<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RendezVous;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HopitalRendezVousController extends Controller
{
    /**
     * Afficher la liste des rendez-vous de l'hôpital
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est admin d'un hôpital
        if ($user->type_utilisateur !== 'hopital' || $user->role !== 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Accès non autorisé.');
        }
        
        // Requête de base
        $query = RendezVous::where('hopital_id', $user->entite_id)
            ->with(['patient', 'medecin']);
        
        // Filtres
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('statut') && $request->statut != '') {
            $query->where('statut', $request->statut);
        }
        
        if ($request->has('medecin_id') && $request->medecin_id != '') {
            $query->where('medecin_id', $request->medecin_id);
        }
        
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('date_rendezvous', $request->date);
        }
        
        // Par défaut, trier par date décroissante
        $rendezvous = $query->orderBy('date_rendezvous', 'desc')
            ->orderBy('heure_rendezvous', 'desc')
            ->paginate(15);
        
        // Statistiques
        $stats = [
            'total' => RendezVous::where('hopital_id', $user->entite_id)->count(),
            'en_attente' => RendezVous::where('hopital_id', $user->entite_id)->where('statut', 'en_attente')->count(),
            'confirme' => RendezVous::where('hopital_id', $user->entite_id)->where('statut', 'confirme')->count(),
            'aujourdhui' => RendezVous::where('hopital_id', $user->entite_id)
                ->whereDate('date_rendezvous', today())->count(),
        ];
        
        // Liste des médecins pour le filtre et le modal
        $medecins = Utilisateur::where('type_utilisateur', 'medecin')
            ->where('entite_id', $user->entite_id)
            ->where('status', 'actif')
            ->get();
        
        // Liste des patients pour le modal
        $patients = Utilisateur::where('type_utilisateur', 'patient')
            ->where(function($query) use ($user) {
                $query->where('entite_id', $user->entite_id)
                      ->orWhere('hopital_id', $user->entite_id);
            })
            ->where('status', 'actif')
            ->get();
        
        return view('admin.hopital.rendezvous.index', compact('rendezvous', 'stats', 'medecins', 'patients'));
    }
    
    /**
     * Afficher le formulaire de création de rendez-vous
     */
    public function create()
    {
        $user = Auth::user();
        
        // Récupérer les patients et médecins de l'hôpital
        $patients = Utilisateur::where('type_utilisateur', 'patient')
            ->where(function($query) use ($user) {
                $query->where('entite_id', $user->entite_id)
                      ->orWhere('hopital_id', $user->entite_id);
            })
            ->where('status', 'actif')
            ->get();
        
        $medecins = Utilisateur::where('type_utilisateur', 'medecin')
            ->where('entite_id', $user->entite_id)
            ->where('status', 'actif')
            ->get();
        
        return view('admin.hopital.rendezvous.create', compact('patients', 'medecins'));
    }
    
    /**
     * Enregistrer un nouveau rendez-vous
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'patient_id' => 'required|exists:utilisateurs,id',
            'medecin_id' => 'required|exists:utilisateurs,id',
            'date_rendezvous' => 'required|date|after_or_equal:today',
            'heure_rendezvous' => 'required',
            'type_consultation' => 'required|string',
            'motif' => 'required|string',
            'prix' => 'nullable|numeric|min:0',
        ], [
            'patient_id.required' => 'Veuillez sélectionner un patient.',
            'medecin_id.required' => 'Veuillez sélectionner un médecin.',
            'date_rendezvous.required' => 'La date du rendez-vous est obligatoire.',
            'date_rendezvous.after_or_equal' => 'La date ne peut pas être dans le passé.',
            'heure_rendezvous.required' => 'L\'heure du rendez-vous est obligatoire.',
            'type_consultation.required' => 'Le type de consultation est obligatoire.',
            'motif.required' => 'Le motif est obligatoire.',
        ]);
        
        // Vérifier que le patient et le médecin appartiennent à cet hôpital
        $patient = Utilisateur::where('id', $request->patient_id)
            ->where('entite_id', $user->entite_id)
            ->firstOrFail();
        
        $medecin = Utilisateur::where('id', $request->medecin_id)
            ->where('entite_id', $user->entite_id)
            ->firstOrFail();
        
        // Créer le rendez-vous
        RendezVous::create([
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'hopital_id' => $user->entite_id,
            'date_rendezvous' => $request->date_rendezvous,
            'heure_rendezvous' => $request->heure_rendezvous,
            'type_consultation' => $request->type_consultation,
            'motif' => $request->motif,
            'statut' => 'en_attente',
            'notes' => $request->notes,
            'prix' => $request->prix ?? 0,
        ]);
        
        return redirect()->route('admin.hopital.rendezvous.index')
            ->with('success', 'Rendez-vous créé avec succès.');
    }
    
    /**
     * Afficher les détails d'un rendez-vous
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $rendezvous = RendezVous::where('id', $id)
            ->where('hopital_id', $user->entite_id)
            ->with(['patient', 'medecin', 'hopital'])
            ->firstOrFail();
        
        return view('admin.hopital.rendezvous.show', compact('rendezvous'));
    }
    
    /**
     * Mettre à jour le statut d'un rendez-vous
     */
    public function updateStatut(Request $request, $id)
    {
        $user = Auth::user();
        
        $request->validate([
            'statut' => 'required|in:en_attente,confirme,annule,termine',
        ]);
        
        $rendezvous = RendezVous::where('id', $id)
            ->where('hopital_id', $user->entite_id)
            ->firstOrFail();
        
        $rendezvous->update([
            'statut' => $request->statut,
        ]);
        
        return redirect()->back()
            ->with('success', 'Statut du rendez-vous mis à jour.');
    }
    
    /**
     * Supprimer un rendez-vous
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        $rendezvous = RendezVous::where('id', $id)
            ->where('hopital_id', $user->entite_id)
            ->firstOrFail();
        
        $rendezvous->delete();
        
        return redirect()->route('admin.hopital.rendezvous.index')
            ->with('success', 'Rendez-vous supprimé avec succès.');
    }
}

