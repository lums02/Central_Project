<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MouvementStock extends Model
{
    protected $table = 'mouvements_stock';
    
    protected $fillable = [
        'medicament_id',
        'pharmacie_id',
        'user_id',
        'type',
        'quantite',
        'stock_avant',
        'stock_apres',
        'prix_unitaire',
        'reference',
        'motif',
        'notes',
    ];

    protected $casts = [
        'quantite' => 'integer',
        'stock_avant' => 'integer',
        'stock_apres' => 'integer',
        'prix_unitaire' => 'decimal:2',
    ];

    /**
     * Relation avec le médicament
     */
    public function medicament()
    {
        return $this->belongsTo(Medicament::class);
    }

    /**
     * Relation avec la pharmacie
     */
    public function pharmacie()
    {
        return $this->belongsTo(Pharmacie::class);
    }

    /**
     * Relation avec l'utilisateur qui a fait le mouvement
     */
    public function user()
    {
        return $this->belongsTo(Utilisateur::class, 'user_id');
    }

    /**
     * Scope pour filtrer par pharmacie
     */
    public function scopeOfPharmacie($query, $pharmacieId)
    {
        return $query->where('pharmacie_id', $pharmacieId);
    }

    /**
     * Scope pour filtrer par médicament
     */
    public function scopeOfMedicament($query, $medicamentId)
    {
        return $query->where('medicament_id', $medicamentId);
    }

    /**
     * Scope pour filtrer par type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Obtenir la classe CSS selon le type de mouvement
     */
    public function getTypeClass()
    {
        return match($this->type) {
            'entree' => 'success',
            'sortie' => 'danger',
            'ajustement' => 'warning',
            'vente' => 'info',
            'retour' => 'primary',
            'perime' => 'dark',
            default => 'secondary',
        };
    }

    /**
     * Obtenir l'icône selon le type de mouvement
     */
    public function getTypeIcon()
    {
        return match($this->type) {
            'entree' => 'arrow-down',
            'sortie' => 'arrow-up',
            'ajustement' => 'edit',
            'vente' => 'shopping-cart',
            'retour' => 'undo',
            'perime' => 'times-circle',
            default => 'exchange-alt',
        };
    }

    /**
     * Obtenir le libellé du type
     */
    public function getTypeLabel()
    {
        return match($this->type) {
            'entree' => 'Entrée',
            'sortie' => 'Sortie',
            'ajustement' => 'Ajustement',
            'vente' => 'Vente',
            'retour' => 'Retour',
            'perime' => 'Périmé',
            default => 'Autre',
        };
    }
}

