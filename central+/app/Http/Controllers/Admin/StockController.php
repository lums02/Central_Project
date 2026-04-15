<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicament;
use App\Models\MouvementStock;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Afficher la liste des stocks
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $pharmacieId = $user->entite_id;
        
        $query = Medicament::ofPharmacie($pharmacieId)->actif();
        
        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('statut')) {
            if ($request->statut === 'stock_faible') {
                $query->stockFaible();
            } elseif ($request->statut === 'rupture') {
                $query->where('stock_actuel', 0);
            } elseif ($request->statut === 'perime') {
                $query->perime();
            }
        }
        
        $medicaments = $query->orderBy('stock_actuel', 'asc')->paginate(20);
        
        // Statistiques
        $stats = [
            'total_medicaments' => Medicament::ofPharmacie($pharmacieId)->actif()->count(),
            'stock_faible' => Medicament::ofPharmacie($pharmacieId)->actif()->stockFaible()->count(),
            'rupture' => Medicament::ofPharmacie($pharmacieId)->actif()->where('stock_actuel', 0)->count(),
            'valeur_stock' => Medicament::ofPharmacie($pharmacieId)->actif()
                ->selectRaw('SUM(stock_actuel * prix_unitaire) as total')
                ->value('total') ?? 0,
        ];
        
        // Mouvements récents
        $mouvementsRecents = MouvementStock::ofPharmacie($pharmacieId)
            ->with(['medicament', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.pharmacie.stocks.index', compact('medicaments', 'stats', 'mouvementsRecents'));
    }

    /**
     * Ajuster le stock d'un médicament
     */
    public function ajuster(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'medicament_id' => 'required|exists:medicaments,id',
            'type' => 'required|in:entree,sortie,ajustement,vente,retour,perime',
            'quantite' => 'required|integer|min:1',
            'prix_unitaire' => 'nullable|numeric|min:0',
            'reference' => 'nullable|string|max:100',
            'motif' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        $medicament = Medicament::ofPharmacie($user->entite_id)->findOrFail($validated['medicament_id']);
        
        DB::beginTransaction();
        
        try {
            $stockAvant = $medicament->stock_actuel;
            
            // Calculer le nouveau stock
            if (in_array($validated['type'], ['entree', 'retour'])) {
                $nouveauStock = $stockAvant + $validated['quantite'];
                $quantiteMouvement = $validated['quantite'];
            } else {
                // sortie, ajustement, vente, perime
                $nouveauStock = $stockAvant - $validated['quantite'];
                $quantiteMouvement = -$validated['quantite'];
            }
            
            // Vérifier que le stock ne devient pas négatif
            if ($nouveauStock < 0) {
                throw new \Exception('Stock insuffisant. Stock actuel: ' . $stockAvant);
            }
            
            // Mettre à jour le stock du médicament
            $medicament->update(['stock_actuel' => $nouveauStock]);
            
            // Enregistrer le mouvement
            MouvementStock::create([
                'medicament_id' => $medicament->id,
                'pharmacie_id' => $user->entite_id,
                'user_id' => $user->id,
                'type' => $validated['type'],
                'quantite' => $quantiteMouvement,
                'stock_avant' => $stockAvant,
                'stock_apres' => $nouveauStock,
                'prix_unitaire' => $validated['prix_unitaire'] ?? $medicament->prix_unitaire,
                'reference' => $validated['reference'] ?? null,
                'motif' => $validated['motif'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);
            
            // Vérifier si le stock est devenu faible
            if ($medicament->isStockFaible() && $nouveauStock > 0) {
                NotificationHelper::notifyStockFaible(
                    $user->entite_id,
                    $medicament->nom,
                    $nouveauStock
                );
            }
            
            // Notification si rupture de stock
            if ($nouveauStock == 0) {
                NotificationHelper::createPharmacieNotification(
                    $user->entite_id,
                    'stock_critique',
                    'Rupture de Stock',
                    "Le médicament {$medicament->nom} est en rupture de stock",
                    ['medicament_id' => $medicament->id]
                );
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Stock ajusté avec succès',
                'nouveau_stock' => $nouveauStock
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
     * Obtenir l'historique des mouvements d'un médicament
     */
    public function historique($medicamentId)
    {
        $user = Auth::user();
        $medicament = Medicament::ofPharmacie($user->entite_id)->findOrFail($medicamentId);
        
        $mouvements = MouvementStock::ofMedicament($medicamentId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        return view('admin.pharmacie.stocks.historique', compact('medicament', 'mouvements'));
    }

    /**
     * Inventaire - Vérifier et ajuster tous les stocks
     */
    public function inventaire()
    {
        $user = Auth::user();
        $medicaments = Medicament::ofPharmacie($user->entite_id)
            ->actif()
            ->orderBy('nom')
            ->get();
        
        return view('admin.pharmacie.stocks.inventaire', compact('medicaments'));
    }

    /**
     * Enregistrer l'inventaire
     */
    public function enregistrerInventaire(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'stocks' => 'required|array',
            'stocks.*.medicament_id' => 'required|exists:medicaments,id',
            'stocks.*.quantite_reelle' => 'required|integer|min:0',
            'stocks.*.notes' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        
        try {
            $ajustements = 0;
            
            foreach ($validated['stocks'] as $stock) {
                $medicament = Medicament::ofPharmacie($user->entite_id)
                    ->findOrFail($stock['medicament_id']);
                
                $stockAvant = $medicament->stock_actuel;
                $quantiteReelle = $stock['quantite_reelle'];
                
                // Si différence, créer un ajustement
                if ($stockAvant != $quantiteReelle) {
                    $difference = $quantiteReelle - $stockAvant;
                    
                    MouvementStock::create([
                        'medicament_id' => $medicament->id,
                        'pharmacie_id' => $user->entite_id,
                        'user_id' => $user->id,
                        'type' => 'ajustement',
                        'quantite' => $difference,
                        'stock_avant' => $stockAvant,
                        'stock_apres' => $quantiteReelle,
                        'prix_unitaire' => $medicament->prix_unitaire,
                        'reference' => 'INV-' . date('Ymd-His'),
                        'motif' => 'Inventaire physique',
                        'notes' => $stock['notes'] ?? null,
                    ]);
                    
                    $medicament->update(['stock_actuel' => $quantiteReelle]);
                    $ajustements++;
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Inventaire enregistré avec succès. {$ajustements} ajustement(s) effectué(s)."
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}

