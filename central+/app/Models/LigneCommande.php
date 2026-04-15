<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneCommande extends Model
{
    protected $table = 'lignes_commande';
    
    protected $fillable = [
        'commande_id',
        'medicament_id',
        'quantite_commandee',
        'quantite_recue',
        'prix_unitaire',
        'montant_ligne',
        'notes',
    ];

    protected $casts = [
        'quantite_commandee' => 'integer',
        'quantite_recue' => 'integer',
        'prix_unitaire' => 'decimal:2',
        'montant_ligne' => 'decimal:2',
    ];

    /**
     * Relation avec la commande
     */
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    /**
     * Relation avec le médicament
     */
    public function medicament()
    {
        return $this->belongsTo(Medicament::class);
    }

    /**
     * Vérifier si la ligne est complètement reçue
     */
    public function estComplete()
    {
        return $this->quantite_recue >= $this->quantite_commandee;
    }

    /**
     * Obtenir la quantité restante à recevoir
     */
    public function getQuantiteRestante()
    {
        return $this->quantite_commandee - $this->quantite_recue;
    }

    /**
     * Obtenir le pourcentage de réception
     */
    public function getPourcentageReception()
    {
        if ($this->quantite_commandee == 0) {
            return 0;
        }
        return ($this->quantite_recue / $this->quantite_commandee) * 100;
    }
}

