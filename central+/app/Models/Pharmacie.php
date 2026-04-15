<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacie extends Model
{
    // Laravel utilisera la table 'pharmacies' automatiquement
    protected $fillable = [
        'nom',
        'email',
        'adresse',
        'telephone',
        'logo'
    ];
    
    /**
     * Relation avec les médicaments
     */
    public function medicaments()
    {
        return $this->hasMany(Medicament::class, 'pharmacie_id');
    }
}
