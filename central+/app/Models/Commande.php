<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $fillable = [
        'pharmacie_id',
        'fournisseur_id',
        'user_id',
        'numero_commande',
        'statut',
        'date_commande',
        'date_livraison_prevue',
        'date_livraison_reelle',
        'montant_total',
        'montant_tva',
        'frais_livraison',
        'remise',
        'montant_final',
        'reference_fournisseur',
        'numero_facture',
        'notes',
        'notes_reception',
        'validee_par',
        'validee_at',
        'receptionnee_par',
        'receptionnee_at',
    ];

    protected $casts = [
        'date_commande' => 'date',
        'date_livraison_prevue' => 'date',
        'date_livraison_reelle' => 'date',
        'montant_total' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'frais_livraison' => 'decimal:2',
        'remise' => 'decimal:2',
        'montant_final' => 'decimal:2',
        'validee_at' => 'datetime',
        'receptionnee_at' => 'datetime',
    ];

    /**
     * Relation avec la pharmacie
     */
    public function pharmacie()
    {
        return $this->belongsTo(Pharmacie::class);
    }

    /**
     * Relation avec le fournisseur
     */
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    /**
     * Relation avec l'utilisateur créateur
     */
    public function user()
    {
        return $this->belongsTo(Utilisateur::class, 'user_id');
    }

    /**
     * Relation avec les lignes de commande
     */
    public function lignes()
    {
        return $this->hasMany(LigneCommande::class);
    }

    /**
     * Scope pour filtrer par pharmacie
     */
    public function scopeOfPharmacie($query, $pharmacieId)
    {
        return $query->where('pharmacie_id', $pharmacieId);
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeOfStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    /**
     * Obtenir la classe CSS selon le statut
     */
    public function getStatutClass()
    {
        return match($this->statut) {
            'brouillon' => 'secondary',
            'en_attente' => 'warning',
            'validee' => 'info',
            'en_cours' => 'primary',
            'livree_partielle' => 'warning',
            'livree' => 'success',
            'annulee' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Obtenir le libellé du statut
     */
    public function getStatutLabel()
    {
        return match($this->statut) {
            'brouillon' => 'Brouillon',
            'en_attente' => 'En Attente',
            'validee' => 'Validée',
            'en_cours' => 'En Cours',
            'livree_partielle' => 'Livrée Partiellement',
            'livree' => 'Livrée',
            'annulee' => 'Annulée',
            default => 'Inconnu',
        };
    }

    /**
     * Vérifier si la commande peut être modifiée
     */
    public function peutEtreModifiee()
    {
        return in_array($this->statut, ['brouillon', 'en_attente']);
    }

    /**
     * Vérifier si la commande peut être validée
     */
    public function peutEtreValidee()
    {
        return $this->statut === 'en_attente';
    }

    /**
     * Vérifier si la commande peut être réceptionnée
     */
    public function peutEtreReceptionnee()
    {
        return in_array($this->statut, ['validee', 'en_cours', 'livree_partielle']);
    }
}

