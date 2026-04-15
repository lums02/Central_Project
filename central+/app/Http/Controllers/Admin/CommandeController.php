<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Fournisseur;
use App\Models\Medicament;
use App\Models\MouvementStock;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    /**
     * Afficher la liste des commandes
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $pharmacieId = $user->entite_id;
        
        $query = Commande::ofPharmacie($pharmacieId)->with(['fournisseur', 'user']);
        
        // Filtres
        if ($request->filled('statut')) {
            $query->ofStatut($request->statut);
        }
        
        if ($request->filled('fournisseur_id')) {
            $query->where('fournisseur_id', $request->fournisseur_id);
        }
        
        $commandes = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Statistiques
        $stats = [
            'en_attente' => Commande::ofPharmacie($pharmacieId)->ofStatut('en_attente')->count(),
            'en_cours' => Commande::ofPharmacie($pharmacieId)->whereIn('statut', ['validee', 'en_cours', 'livree_partielle'])->count(),
            'livrees' => Commande::ofPharmacie($pharmacieId)->ofStatut('livree')->count(),
            'total_mois' => Commande::ofPharmacie($pharmacieId)
                ->whereMonth('date_commande', date('m'))
                ->whereYear('date_commande', date('Y'))
                ->sum('montant_final'),
        ];
        
        $fournisseurs = Fournisseur::ofPharmacie($pharmacieId)->actif()->get();
        
        return view('admin.pharmacie.commandes.index', compact('commandes', 'stats', 'fournisseurs'));
    }

    /**
     * Créer une nouvelle commande
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'date_commande' => 'required|date',
            'date_livraison_prevue' => 'nullable|date|after:date_commande',
            'notes' => 'nullable|string',
            'lignes' => 'required|array|min:1',
            'lignes.*.medicament_id' => 'required|exists:medicaments,id',
            'lignes.*.quantite' => 'required|integer|min:1',
            'lignes.*.prix_unitaire' => 'required|numeric|min:0',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Générer le numéro de commande
            $numeroCommande = 'CMD-' . date('Ymd') . '-' . str_pad(
                Commande::ofPharmacie($user->entite_id)->whereDate('created_at', today())->count() + 1,
                4,
                '0',
                STR_PAD_LEFT
            );
            
            // Calculer le montant total
            $montantTotal = 0;
            foreach ($validated['lignes'] as $ligne) {
                $montantTotal += $ligne['quantite'] * $ligne['prix_unitaire'];
            }
            
            // Créer la commande
            $commande = Commande::create([
                'pharmacie_id' => $user->entite_id,
                'fournisseur_id' => $validated['fournisseur_id'],
                'user_id' => $user->id,
                'numero_commande' => $numeroCommande,
                'statut' => 'en_attente',
                'date_commande' => $validated['date_commande'],
                'date_livraison_prevue' => $validated['date_livraison_prevue'] ?? null,
                'montant_total' => $montantTotal,
                'montant_final' => $montantTotal,
                'notes' => $validated['notes'] ?? null,
            ]);
            
            // Créer les lignes de commande
            foreach ($validated['lignes'] as $ligne) {
                LigneCommande::create([
                    'commande_id' => $commande->id,
                    'medicament_id' => $ligne['medicament_id'],
                    'quantite_commandee' => $ligne['quantite'],
                    'quantite_recue' => 0,
                    'prix_unitaire' => $ligne['prix_unitaire'],
                    'montant_ligne' => $ligne['quantite'] * $ligne['prix_unitaire'],
                ]);
            }
            
            // Notification
            NotificationHelper::createPharmacieNotification(
                $user->entite_id,
                'nouvelle_commande',
                'Nouvelle Commande',
                "Commande {$numeroCommande} créée pour un montant de \${$montantTotal}",
                ['commande_id' => $commande->id]
            );
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Commande créée avec succès',
                'commande' => $commande
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Afficher les détails d'une commande
     */
    public function show($id)
    {
        $user = Auth::user();
        $commande = Commande::ofPharmacie($user->entite_id)
            ->with(['fournisseur', 'lignes.medicament', 'user'])
            ->findOrFail($id);
        
        return view('admin.pharmacie.commandes.show', compact('commande'));
    }

    /**
     * Valider une commande
     */
    public function valider($id)
    {
        $user = Auth::user();
        $commande = Commande::ofPharmacie($user->entite_id)->findOrFail($id);
        
        if (!$commande->peutEtreValidee()) {
            return response()->json([
                'success' => false,
                'message' => 'Cette commande ne peut pas être validée'
            ], 400);
        }
        
        $commande->update([
            'statut' => 'validee',
            'validee_par' => $user->id,
            'validee_at' => now(),
        ]);
        
        NotificationHelper::createPharmacieNotification(
            $user->entite_id,
            'commande_validee',
            'Commande Validée',
            "La commande {$commande->numero_commande} a été validée",
            ['commande_id' => $commande->id]
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Commande validée avec succès'
        ]);
    }

    /**
     * Réceptionner une commande (totale ou partielle)
     */
    public function receptionner(Request $request, $id)
    {
        $user = Auth::user();
        $commande = Commande::ofPharmacie($user->entite_id)->findOrFail($id);
        
        if (!$commande->peutEtreReceptionnee()) {
            return response()->json([
                'success' => false,
                'message' => 'Cette commande ne peut pas être réceptionnée'
            ], 400);
        }
        
        $validated = $request->validate([
            'lignes' => 'required|array',
            'lignes.*.ligne_id' => 'required|exists:lignes_commande,id',
            'lignes.*.quantite_recue' => 'required|integer|min:0',
            'notes_reception' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        
        try {
            $toutRecu = true;
            
            foreach ($validated['lignes'] as $ligneData) {
                $ligne = LigneCommande::findOrFail($ligneData['ligne_id']);
                $nouvelleQuantiteRecue = $ligne->quantite_recue + $ligneData['quantite_recue'];
                
                // Mettre à jour la ligne
                $ligne->update([
                    'quantite_recue' => $nouvelleQuantiteRecue
                ]);
                
                // Ajouter au stock si quantité reçue > 0
                if ($ligneData['quantite_recue'] > 0) {
                    $medicament = $ligne->medicament;
                    $stockAvant = $medicament->stock_actuel;
                    $stockApres = $stockAvant + $ligneData['quantite_recue'];
                    
                    $medicament->update(['stock_actuel' => $stockApres]);
                    
                    // Créer le mouvement de stock
                    MouvementStock::create([
                        'medicament_id' => $medicament->id,
                        'pharmacie_id' => $user->entite_id,
                        'user_id' => $user->id,
                        'type' => 'entree',
                        'quantite' => $ligneData['quantite_recue'],
                        'stock_avant' => $stockAvant,
                        'stock_apres' => $stockApres,
                        'prix_unitaire' => $ligne->prix_unitaire,
                        'reference' => $commande->numero_commande,
                        'motif' => 'Réception commande',
                    ]);
                }
                
                // Vérifier si tout est reçu
                if (!$ligne->estComplete()) {
                    $toutRecu = false;
                }
            }
            
            // Mettre à jour le statut de la commande
            $nouveauStatut = $toutRecu ? 'livree' : 'livree_partielle';
            $commande->update([
                'statut' => $nouveauStatut,
                'date_livraison_reelle' => now(),
                'notes_reception' => $validated['notes_reception'] ?? null,
                'receptionnee_par' => $user->id,
                'receptionnee_at' => now(),
            ]);
            
            // Notification
            NotificationHelper::createPharmacieNotification(
                $user->entite_id,
                'commande_livree',
                $toutRecu ? 'Commande Livrée' : 'Livraison Partielle',
                "La commande {$commande->numero_commande} a été " . ($toutRecu ? 'livrée complètement' : 'partiellement livrée'),
                ['commande_id' => $commande->id]
            );
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Réception enregistrée avec succès'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Annuler une commande
     */
    public function annuler(Request $request, $id)
    {
        $user = Auth::user();
        $commande = Commande::ofPharmacie($user->entite_id)->findOrFail($id);
        
        if (!$commande->peutEtreModifiee()) {
            return response()->json([
                'success' => false,
                'message' => 'Cette commande ne peut plus être annulée'
            ], 400);
        }
        
        $commande->update([
            'statut' => 'annulee',
            'notes' => ($commande->notes ?? '') . "\n[Annulée le " . now()->format('d/m/Y H:i') . " par " . $user->nom . "]"
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Commande annulée avec succès'
        ]);
    }
}

