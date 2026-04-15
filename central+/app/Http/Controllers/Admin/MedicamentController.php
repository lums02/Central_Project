<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicament;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicamentController extends Controller
{
    /**
     * Afficher la liste des médicaments
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $pharmacieId = $user->entite_id;
        
        $query = Medicament::ofPharmacie($pharmacieId);
        
        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('nom_generique', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }
        
        if ($request->filled('forme')) {
            $query->where('forme', $request->forme);
        }
        
        if ($request->filled('statut')) {
            if ($request->statut === 'stock_faible') {
                $query->stockFaible();
            } elseif ($request->statut === 'perime') {
                $query->perime();
            } elseif ($request->statut === 'bientot_perime') {
                $query->bientotPerime();
            }
        }
        
        $medicaments = $query->orderBy('nom')->paginate(20);
        
        // Statistiques
        $stats = [
            'total' => Medicament::ofPharmacie($pharmacieId)->actif()->count(),
            'stock_faible' => Medicament::ofPharmacie($pharmacieId)->actif()->stockFaible()->count(),
            'perimes' => Medicament::ofPharmacie($pharmacieId)->perime()->count(),
            'bientot_perimes' => Medicament::ofPharmacie($pharmacieId)->bientotPerime()->count(),
        ];
        
        // Catégories et formes pour les filtres
        $categories = Medicament::ofPharmacie($pharmacieId)->distinct()->pluck('categorie')->sort();
        $formes = Medicament::ofPharmacie($pharmacieId)->distinct()->pluck('forme')->sort();
        
        return view('admin.pharmacie.medicaments.index', compact('medicaments', 'stats', 'categories', 'formes'));
    }

    /**
     * Enregistrer un nouveau médicament
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:medicaments,code',
            'nom_generique' => 'nullable|string|max:255',
            'categorie' => 'required|string|max:100',
            'forme' => 'required|string|max:100',
            'dosage' => 'nullable|string|max:50',
            'prix_unitaire' => 'required|numeric|min:0',
            'prix_achat' => 'nullable|numeric|min:0',
            'stock_actuel' => 'nullable|integer|min:0',
            'stock_minimum' => 'nullable|integer|min:0',
            'prescription_requise' => 'nullable|boolean',
            'description' => 'nullable|string',
            'indication' => 'nullable|string',
            'contre_indication' => 'nullable|string',
            'effets_secondaires' => 'nullable|string',
            'posologie' => 'nullable|string',
            'fabricant' => 'nullable|string|max:255',
            'numero_lot' => 'nullable|string|max:100',
            'date_fabrication' => 'nullable|date',
            'date_expiration' => 'nullable|date|after:date_fabrication',
            'emplacement' => 'nullable|string|max:100',
        ]);
        
        $validated['pharmacie_id'] = $user->entite_id;
        $validated['prescription_requise'] = $request->has('prescription_requise');
        $validated['stock_actuel'] = $validated['stock_actuel'] ?? 0;
        $validated['stock_minimum'] = $validated['stock_minimum'] ?? 10;
        
        $medicament = Medicament::create($validated);
        
        // Vérifier si le stock est faible dès la création
        if ($medicament->isStockFaible() && $medicament->stock_actuel > 0) {
            NotificationHelper::notifyStockFaible(
                $user->entite_id,
                $medicament->nom,
                $medicament->stock_actuel
            );
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Médicament ajouté avec succès',
            'medicament' => $medicament
        ]);
    }

    /**
     * Afficher les détails d'un médicament
     */
    public function show($id)
    {
        $user = Auth::user();
        $medicament = Medicament::ofPharmacie($user->entite_id)->findOrFail($id);
        
        return view('admin.pharmacie.medicaments.show', compact('medicament'));
    }

    /**
     * Mettre à jour un médicament
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $medicament = Medicament::ofPharmacie($user->entite_id)->findOrFail($id);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:medicaments,code,' . $id,
            'nom_generique' => 'nullable|string|max:255',
            'categorie' => 'required|string|max:100',
            'forme' => 'required|string|max:100',
            'dosage' => 'nullable|string|max:50',
            'prix_unitaire' => 'required|numeric|min:0',
            'prix_achat' => 'nullable|numeric|min:0',
            'stock_actuel' => 'nullable|integer|min:0',
            'stock_minimum' => 'nullable|integer|min:0',
            'prescription_requise' => 'nullable|boolean',
            'description' => 'nullable|string',
            'indication' => 'nullable|string',
            'contre_indication' => 'nullable|string',
            'effets_secondaires' => 'nullable|string',
            'posologie' => 'nullable|string',
            'fabricant' => 'nullable|string|max:255',
            'numero_lot' => 'nullable|string|max:100',
            'date_fabrication' => 'nullable|date',
            'date_expiration' => 'nullable|date|after:date_fabrication',
            'emplacement' => 'nullable|string|max:100',
            'actif' => 'nullable|boolean',
        ]);
        
        $validated['prescription_requise'] = $request->has('prescription_requise');
        $validated['actif'] = $request->has('actif');
        
        $ancienStock = $medicament->stock_actuel;
        $medicament->update($validated);
        
        // Vérifier si le stock est devenu faible
        if (!$medicament->isStockFaible() && $ancienStock > $medicament->stock_minimum && $medicament->isStockFaible()) {
            NotificationHelper::notifyStockFaible(
                $user->entite_id,
                $medicament->nom,
                $medicament->stock_actuel
            );
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Médicament mis à jour avec succès',
            'medicament' => $medicament
        ]);
    }

    /**
     * Supprimer un médicament (soft delete - désactiver)
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $medicament = Medicament::ofPharmacie($user->entite_id)->findOrFail($id);
        
        $medicament->update(['actif' => false]);
        
        return response()->json([
            'success' => true,
            'message' => 'Médicament désactivé avec succès'
        ]);
    }

    /**
     * Obtenir les catégories disponibles
     */
    public function getCategories()
    {
        return response()->json([
            'Antibiotiques',
            'Antalgiques',
            'Anti-inflammatoires',
            'Antipaludéens',
            'Antihypertenseurs',
            'Antidiabétiques',
            'Antihistaminiques',
            'Antiacides',
            'Vitamines et Suppléments',
            'Antiseptiques',
            'Antifongiques',
            'Antiviraux',
        ]);
    }

    /**
     * Obtenir les formes disponibles
     */
    public function getFormes()
    {
        return response()->json([
            'Comprimé',
            'Gélule',
            'Sirop',
            'Suspension',
            'Injection',
            'Ampoule',
            'Pommade',
            'Crème',
            'Gel',
            'Suppositoire',
            'Collyre',
            'Gouttes',
            'Spray',
            'Patch',
        ]);
    }
}

