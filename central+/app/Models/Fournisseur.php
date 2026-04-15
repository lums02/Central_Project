<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $table = 'fournisseurs';
    
    protected $fillable = [
        'pharmacie_id',
        'nom',
        'code',
        'email',
        'telephone',
        'telephone_2',
        'adresse',
        'ville',
        'pays',
        'contact_nom',
        'contact_fonction',
        'numero_registre',
        'numero_fiscal',
        'specialites',
        'delai_livraison_jours',
        'montant_minimum_commande',
        'conditions_paiement',
        'notes',
        'actif',
    ];

    protected $casts = [
        'delai_livraison_jours' => 'integer',
        'montant_minimum_commande' => 'decimal:2',
        'actif' => 'boolean',
    ];

    /**
     * Relation avec la pharmacie
     */
    public function pharmacie()
    {
        return $this->belongsTo(Pharmacie::class);
    }

    /**
     * Relation avec les commandes
     */
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    /**
     * Scope pour filtrer par pharmacie
     */
    public function scopeOfPharmacie($query, $pharmacieId)
    {
        return $query->where('pharmacie_id', $pharmacieId);
    }

    /**
     * Scope pour les fournisseurs actifs
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}

