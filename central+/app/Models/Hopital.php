<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hopital extends Model
{
    protected $table = 'hopitaux';

    protected $fillable = [
        'nom',
        'email',
        'adresse',
        'telephone',
        'type_hopital',
        'nombre_lits',
        'logo',
        'statut',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'nombre_lits' => 'integer'
    ];

    // Relations
    public function medecins(): HasMany
    {
        return $this->hasMany(Medecin::class, 'hopital_id');
    }

    public function rendezvous(): HasMany
    {
        return $this->hasMany(RendezVous::class, 'hopital_id');
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }
}
