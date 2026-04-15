<?php

namespace App\Helpers;

use App\Models\Notification;

class NotificationHelper
{
    /**
     * Créer une notification pour une pharmacie
     */
    public static function createPharmacieNotification($pharmacieId, $type, $title, $message, $data = null, $userId = null)
    {
        return Notification::create([
            'pharmacie_id' => $pharmacieId,
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'read' => false,
        ]);
    }

    /**
     * Créer une notification pour une banque de sang
     */
    public static function createBanqueSangNotification($banqueSangId, $type, $title, $message, $data = null, $userId = null)
    {
        return Notification::create([
            'banque_sang_id' => $banqueSangId,
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'read' => false,
        ]);
    }

    /**
     * Créer une notification pour un hôpital
     */
    public static function createHopitalNotification($hopitalId, $type, $title, $message, $data = null, $userId = null)
    {
        return Notification::create([
            'hopital_id' => $hopitalId,
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'read' => false,
        ]);
    }

    /**
     * Notifier un stock faible dans une pharmacie
     */
    public static function notifyStockFaible($pharmacieId, $medicamentNom, $quantite)
    {
        return self::createPharmacieNotification(
            $pharmacieId,
            'stock_faible',
            'Stock Faible',
            "Le médicament {$medicamentNom} est en stock faible ({$quantite} unités restantes)",
            ['medicament' => $medicamentNom, 'quantite' => $quantite]
        );
    }

    /**
     * Notifier une nouvelle commande dans une pharmacie
     */
    public static function notifyNouvelleCommande($pharmacieId, $commandeId, $montant)
    {
        return self::createPharmacieNotification(
            $pharmacieId,
            'nouvelle_commande',
            'Nouvelle Commande',
            "Nouvelle commande reçue d'un montant de \${$montant}",
            ['commande_id' => $commandeId, 'montant' => $montant]
        );
    }

    /**
     * Notifier une réserve de sang faible
     */
    public static function notifyReserveFaible($banqueSangId, $groupeSanguin, $quantite)
    {
        return self::createBanqueSangNotification(
            $banqueSangId,
            'reserve_faible',
            'Réserve de Sang Faible',
            "La réserve du groupe {$groupeSanguin} est faible ({$quantite}L restants)",
            ['groupe_sanguin' => $groupeSanguin, 'quantite' => $quantite]
        );
    }

    /**
     * Notifier une demande de sang urgente
     */
    public static function notifyDemandeUrgente($banqueSangId, $demandeId, $groupeSanguin, $quantite)
    {
        return self::createBanqueSangNotification(
            $banqueSangId,
            'demande_urgente',
            'Demande Urgente',
            "Demande urgente de {$quantite}L de sang {$groupeSanguin}",
            ['demande_id' => $demandeId, 'groupe_sanguin' => $groupeSanguin, 'quantite' => $quantite]
        );
    }
}

