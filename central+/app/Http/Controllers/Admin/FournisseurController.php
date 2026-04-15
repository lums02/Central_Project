<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FournisseurController extends Controller
{
    /**
     * Afficher la liste des fournisseurs
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $pharmacieId = $user->entite_id;
        
        $query = Fournisseur::ofPharmacie($pharmacieId);
        
        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('actif')) {
            $query->where('actif', $request->actif === '1');
        }
        
        $fournisseurs = $query->orderBy('nom')->paginate(20);
        
        // Statistiques
        $stats = [
            'total' => Fournisseur::ofPharmacie($pharmacieId)->count(),
            'actifs' => Fournisseur::ofPharmacie($pharmacieId)->actif()->count(),
            'inactifs' => Fournisseur::ofPharmacie($pharmacieId)->where('actif', false)->count(),
        ];
        
        return view('admin.pharmacie.fournisseurs.index', compact('fournisseurs', 'stats'));
    }

    /**
     * Enregistrer un nouveau fournisseur
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:fournisseurs,code',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'telephone_2' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'pays' => 'nullable|string|max:100',
            'contact_nom' => 'nullable|string|max:255',
            'contact_fonction' => 'nullable|string|max:100',
            'numero_registre' => 'nullable|string|max:100',
            'numero_fiscal' => 'nullable|string|max:100',
            'specialites' => 'nullable|string',
            'delai_livraison_jours' => 'nullable|integer|min:1',
            'montant_minimum_commande' => 'nullable|numeric|min:0',
            'conditions_paiement' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        $validated['pharmacie_id'] = $user->entite_id;
        $validated['actif'] = true;
        
        $fournisseur = Fournisseur::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Fournisseur ajouté avec succès',
            'fournisseur' => $fournisseur
        ]);
    }

    /**
     * Afficher les détails d'un fournisseur
     */
    public function show($id)
    {
        $user = Auth::user();
        $fournisseur = Fournisseur::ofPharmacie($user->entite_id)
            ->with(['commandes' => function($q) {
                $q->orderBy('date_commande', 'desc')->limit(10);
            }])
            ->findOrFail($id);
        
        return view('admin.pharmacie.fournisseurs.show', compact('fournisseur'));
    }

    /**
     * Mettre à jour un fournisseur
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $fournisseur = Fournisseur::ofPharmacie($user->entite_id)->findOrFail($id);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:fournisseurs,code,' . $id,
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'telephone_2' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'pays' => 'nullable|string|max:100',
            'contact_nom' => 'nullable|string|max:255',
            'contact_fonction' => 'nullable|string|max:100',
            'numero_registre' => 'nullable|string|max:100',
            'numero_fiscal' => 'nullable|string|max:100',
            'specialites' => 'nullable|string',
            'delai_livraison_jours' => 'nullable|integer|min:1',
            'montant_minimum_commande' => 'nullable|numeric|min:0',
            'conditions_paiement' => 'nullable|string',
            'notes' => 'nullable|string',
            'actif' => 'nullable|boolean',
        ]);
        
        $fournisseur->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Fournisseur mis à jour avec succès'
        ]);
    }

    /**
     * Désactiver un fournisseur
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $fournisseur = Fournisseur::ofPharmacie($user->entite_id)->findOrFail($id);
        
        $fournisseur->update(['actif' => false]);
        
        return response()->json([
            'success' => true,
            'message' => 'Fournisseur désactivé avec succès'
        ]);
    }

    /**
     * Liste des fournisseurs pour select (API)
     */
    public function liste()
    {
        $user = Auth::user();
        $fournisseurs = Fournisseur::ofPharmacie($user->entite_id)
            ->actif()
            ->select('id', 'nom', 'telephone', 'delai_livraison_jours')
            ->orderBy('nom')
            ->get();
        
        return response()->json($fournisseurs);
    }
}

