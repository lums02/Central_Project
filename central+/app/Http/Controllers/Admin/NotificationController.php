<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Récupérer les notifications de l'utilisateur
     */
    public function getNotifications()
    {
        $user = Auth::user();
        
        // Le superadmin ne voit QUE ses notifications personnelles, PAS celles des entités
        if ($user->isSuperAdmin()) {
            $notifications = Notification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->map(function($notif) {
                    return [
                        'id' => $notif->id,
                        'type' => $notif->type,
                        'title' => $notif->title,
                        'message' => $notif->message,
                        'icon' => $this->getIconForType($notif->type),
                        'time' => $notif->created_at->diffForHumans(),
                        'read' => $notif->read,
                        'data' => $notif->data,
                    ];
                });
            
            $unreadCount = Notification::where('user_id', $user->id)
                ->where('read', false)
                ->count();
        } else {
            // Récupérer les notifications pour cet utilisateur ou son entité
            $notifications = Notification::where(function($q) use ($user) {
                // Notifications personnelles
                $q->where('user_id', $user->id)
                  // Notifications pour toute l'entité (hôpital, pharmacie, banque de sang)
                  ->orWhere(function($q2) use ($user) {
                      // Utiliser le champ approprié selon le type d'entité
                      if ($user->type_utilisateur === 'hopital') {
                          $q2->where('hopital_id', $user->entite_id)->whereNull('user_id');
                      } elseif ($user->type_utilisateur === 'pharmacie') {
                          $q2->where('pharmacie_id', $user->entite_id)->whereNull('user_id');
                      } elseif ($user->type_utilisateur === 'banque_sang') {
                          $q2->where('banque_sang_id', $user->entite_id)->whereNull('user_id');
                      }
                  });
            })
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function($notif) {
                return [
                    'id' => $notif->id,
                    'type' => $notif->type,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'icon' => $this->getIconForType($notif->type),
                    'time' => $notif->created_at->diffForHumans(),
                    'read' => $notif->read,
                    'data' => $notif->data,
                ];
            });
            
            $unreadCount = Notification::where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere(function($q2) use ($user) {
                      if ($user->type_utilisateur === 'hopital') {
                          $q2->where('hopital_id', $user->entite_id)->whereNull('user_id');
                      } elseif ($user->type_utilisateur === 'pharmacie') {
                          $q2->where('pharmacie_id', $user->entite_id)->whereNull('user_id');
                      } elseif ($user->type_utilisateur === 'banque_sang') {
                          $q2->where('banque_sang_id', $user->entite_id)->whereNull('user_id');
                      }
                  });
            })
            ->where('read', false)
            ->count();
        }
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }
    
    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        $notification = Notification::where('id', $id)
            ->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere(function($q2) use ($user) {
                      $q2->where('hopital_id', $user->entite_id)
                         ->whereNull('user_id');
                  });
            })
            ->first();
        
        if ($notification) {
            $notification->update(['read' => true]);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }
    
    /**
     * Récupérer les notifications pour un médecin
     */
    public function getMedecinNotifications()
    {
        $user = Auth::user();
        
        // Récupérer les notifications pour ce médecin
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function($notif) {
                return [
                    'id' => $notif->id,
                    'type' => $notif->type,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'icon' => $this->getIconForType($notif->type),
                    'time' => $notif->created_at->diffForHumans(),
                    'read' => $notif->read,
                    'data' => $notif->data,
                ];
            });
        
        $unreadCount = Notification::where('user_id', $user->id)
            ->where('read', false)
            ->count();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }
    
    /**
     * Marquer une notification de médecin comme lue
     */
    public function markMedecinAsRead($id)
    {
        $user = Auth::user();
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        
        if ($notification) {
            $notification->update(['read' => true]);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }
    
    /**
     * Obtenir l'icône selon le type de notification
     */
    private function getIconForType($type)
    {
        $icons = [
            // Hôpital
            'demande_transfert_recue' => 'inbox',
            'transfert_complete' => 'check-circle',
            'patient_nouveau' => 'user-plus',
            'nouveau_patient' => 'user-plus',
            'nouvelle_consultation' => 'cash-register',
            'consultation_payee' => 'check-circle',
            'rendez_vous' => 'calendar-check',
            'rappel_rdv_24h' => 'calendar-day',
            'rappel_rdv_2h' => 'clock',
            'dossier_assigne' => 'file-medical',
            'examens_a_payer' => 'dollar-sign',
            'examen_a_realiser' => 'microscope',
            'resultats_examen' => 'file-medical-alt',
            
            // Pharmacie
            'nouvelle_commande' => 'shopping-cart',
            'commande_validee' => 'check-circle',
            'commande_livree' => 'truck',
            'stock_faible' => 'exclamation-triangle',
            'stock_critique' => 'exclamation-circle',
            'medicament_expire' => 'calendar-times',
            'nouvelle_prescription' => 'prescription',
            'vente_effectuee' => 'cash-register',
            'paiement_recu' => 'money-bill-wave',
            
            // Banque de Sang
            'nouveau_donneur' => 'user-plus',
            'don_enregistre' => 'hand-holding-heart',
            'demande_sang' => 'file-medical',
            'demande_urgente' => 'exclamation-triangle',
            'reserve_faible' => 'tint',
            'reserve_critique' => 'exclamation-circle',
            'analyse_terminee' => 'microscope',
            'sang_disponible' => 'check-circle',
            'sang_expire' => 'calendar-times',
        ];
        
        return $icons[$type] ?? 'bell';
    }
}

