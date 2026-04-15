<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donneur;
use App\Models\Don;
use App\Models\ReserveSang;
use App\Models\DemandeSang;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BanqueSangController extends Controller
{
    // ========== DONNEURS ==========
    
    public function donneurs(Request $request)
    {
        $user = Auth::user();
        $query = Donneur::ofBanque($user->entite_id);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('numero_donneur', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('groupe_sanguin')) {
            $query->where('groupe_sanguin', $request->groupe_sanguin);
        }
        
        $donneurs = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $stats = [
            'total' => Donneur::ofBanque($user->entite_id)->count(),
            'eligibles' => Donneur::ofBanque($user->entite_id)->eligible()->count(),
            'dons_mois' => Don::ofBanque($user->entite_id)->whereMonth('date_don', date('m'))->count(),
        ];
        
        return view('admin.banque-sang.donneurs.index', compact('donneurs', 'stats'));
    }
    
    public function storeDonneur(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'date_naissance' => 'required|date',
            'groupe_sanguin' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'telephone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'adresse' => 'required|string',
            'poids' => 'nullable|numeric|min:50',
        ]);
        
        $numeroDonneur = 'DON-' . str_pad(Donneur::ofBanque($user->entite_id)->count() + 1, 4, '0', STR_PAD_LEFT);
        
        $validated['banque_sang_id'] = $user->entite_id;
        $validated['numero_donneur'] = $numeroDonneur;
        
        $donneur = Donneur::create($validated);
        
        return response()->json(['success' => true, 'message' => 'Donneur enregistré avec succès']);
    }
    
    // ========== DONS ==========
    
    public function dons(Request $request)
    {
        $user = Auth::user();
        $query = Don::ofBanque($user->entite_id)->with(['donneur', 'technicien']);
        
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        $dons = $query->orderBy('date_don', 'desc')->paginate(20);
        $donneurs = Donneur::ofBanque($user->entite_id)->eligible()->get();
        
        return view('admin.banque-sang.dons.index', compact('dons', 'donneurs'));
    }
    
    public function storeDon(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'donneur_id' => 'required|exists:donneurs,id',
            'date_don' => 'required|date',
            'volume_preleve' => 'required|numeric|min:0.1|max:0.5',
            'type_don' => 'required|in:sang_total,plasma,plaquettes,globules_rouges',
        ]);
        
        DB::beginTransaction();
        try {
            $donneur = Donneur::findOrFail($validated['donneur_id']);
            
            $numeroDon = 'DON-' . date('Ymd') . '-' . str_pad(
                Don::ofBanque($user->entite_id)->whereDate('date_don', today())->count() + 1,
                4, '0', STR_PAD_LEFT
            );
            
            $don = Don::create([
                'banque_sang_id' => $user->entite_id,
                'donneur_id' => $donneur->id,
                'technicien_id' => $user->id,
                'numero_don' => $numeroDon,
                'date_don' => $validated['date_don'],
                'heure_don' => now()->format('H:i:s'),
                'groupe_sanguin' => $donneur->groupe_sanguin,
                'volume_preleve' => $validated['volume_preleve'],
                'type_don' => $validated['type_don'],
                'statut' => 'en_attente_analyse',
            ]);
            
            $donneur->update([
                'derniere_date_don' => $validated['date_don'],
                'nombre_dons' => $donneur->nombre_dons + 1,
            ]);
            
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Don enregistré avec succès']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    
    // ========== RÉSERVES ==========
    
    public function reserves()
    {
        $user = Auth::user();
        $reserves = ReserveSang::ofBanque($user->entite_id)->orderBy('groupe_sanguin')->get();
        
        // Créer les réserves manquantes
        $groupes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        foreach ($groupes as $groupe) {
            if (!$reserves->where('groupe_sanguin', $groupe)->first()) {
                ReserveSang::create([
                    'banque_sang_id' => $user->entite_id,
                    'groupe_sanguin' => $groupe,
                    'quantite_disponible' => 0,
                ]);
            }
        }
        
        $reserves = ReserveSang::ofBanque($user->entite_id)->orderBy('groupe_sanguin')->get();
        
        return view('admin.banque-sang.reserves.index', compact('reserves'));
    }
    
    // ========== DEMANDES ==========
    
    public function demandes(Request $request)
    {
        $user = Auth::user();
        $query = DemandeSang::ofBanque($user->entite_id)->with('hopital');
        
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        $demandes = $query->orderBy('urgence', 'desc')->orderBy('date_besoin', 'asc')->paginate(20);
        
        return view('admin.banque-sang.demandes.index', compact('demandes'));
    }
    
    public function traiterDemande(Request $request, $id)
    {
        $user = Auth::user();
        $demande = DemandeSang::ofBanque($user->entite_id)->findOrFail($id);
        
        $validated = $request->validate([
            'quantite_fournie' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        try {
            // Mettre à jour la réserve
            $reserve = ReserveSang::ofBanque($user->entite_id)
                ->where('groupe_sanguin', $demande->groupe_sanguin)
                ->first();
            
            if ($reserve->quantite_disponible < $validated['quantite_fournie']) {
                throw new \Exception('Quantité insuffisante en réserve');
            }
            
            $reserve->update([
                'quantite_disponible' => $reserve->quantite_disponible - $validated['quantite_fournie'],
                'derniere_mise_a_jour' => now(),
            ]);
            
            $demande->update([
                'quantite_fournie' => $validated['quantite_fournie'],
                'statut' => 'livree',
                'date_livraison' => now(),
                'traitee_par' => $user->id,
                'traitee_at' => now(),
                'notes' => $validated['notes'] ?? $demande->notes,
            ]);
            
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Demande traitée avec succès']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}

