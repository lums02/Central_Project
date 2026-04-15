<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Medicament extends Model
{
    protected $fillable = [
        'pharmacie_id',
        'code',
        'nom',
        'nom_generique',
        'categorie',
        'forme',
        'dosage',
        'prix_unitaire',
        'prix_achat',
        'stock_actuel',
        'stock_minimum',
        'prescription_requise',
        'description',
        'indication',
        'contre_indication',
        'effets_secondaires',
        'posologie',
        'fabricant',
        'numero_lot',
        'date_fabrication',
        'date_expiration',
        'emplacement',
        'actif',
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'prix_achat' => 'decimal:2',
        'stock_actuel' => 'integer',
        'stock_minimum' => 'integer',
        'prescription_requise' => 'boolean',
        'actif' => 'boolean',
        'date_fabrication' => 'date',
        'date_expiration' => 'date',
    ];

    /**
     * Relation avec la pharmacie
     */
    public function pharmacie()
    {
        return $this->belongsTo(Pharmacie::class);
    }

    /**
     * Scope pour filtrer par pharmacie
     */
    public function scopeOfPharmacie($query, $pharmacieId)
    {
        return $query->where('pharmacie_id', $pharmacieId);
    }

    /**
     * Scope pour les médicaments actifs
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope pour les médicaments en stock faible
     */
    public function scopeStockFaible($query)
    {
        return $query->whereColumn('stock_actuel', '<=', 'stock_minimum');
    }

    /**
     * Scope pour les médicaments bientôt périmés (dans les 3 mois)
     */
    public function scopeBientotPerime($query)
    {
        return $query->where('date_expiration', '<=', Carbon::now()->addMonths(3))
                     ->where('date_expiration', '>=', Carbon::now());
    }

    /**
     * Scope pour les médicaments périmés
     */
    public function scopePerime($query)
    {
        return $query->where('date_expiration', '<', Carbon::now());
    }

    /**
     * Vérifier si le stock est faible
     */
    public function isStockFaible()
    {
        return $this->stock_actuel <= $this->stock_minimum;
    }

    /**
     * Vérifier si le médicament est périmé
     */
    public function isPerime()
    {
        return $this->date_expiration && $this->date_expiration->isPast();
    }

    /**
     * Vérifier si le médicament est bientôt périmé (dans les 3 mois)
     */
    public function isBientotPerime()
    {
        return $this->date_expiration && 
               $this->date_expiration->isFuture() && 
               $this->date_expiration->diffInMonths(Carbon::now()) <= 3;
    }

    /**
     * Calculer la marge bénéficiaire
     */
    public function getMarge()
    {
        if ($this->prix_achat && $this->prix_achat > 0) {
            return (($this->prix_unitaire - $this->prix_achat) / $this->prix_achat) * 100;
        }
        return 0;
    }

    /**
     * Obtenir le statut du stock
     */
    public function getStockStatus()
    {
        if ($this->stock_actuel == 0) {
            return ['label' => 'Rupture', 'class' => 'danger'];
        } elseif ($this->isStockFaible()) {
            return ['label' => 'Stock Faible', 'class' => 'warning'];
        } else {
            return ['label' => 'Disponible', 'class' => 'success'];
        }
    }
}

