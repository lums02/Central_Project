<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeSang extends Model
{
    protected $table = 'demandes_sang';
    
    protected $fillable = [
        'banque_sang_id', 'hopital_id', 'patient_id', 'numero_demande', 'groupe_sanguin',
        'quantite_demandee', 'quantite_fournie', 'urgence', 'statut', 'date_demande',
        'date_besoin', 'date_livraison', 'nom_patient', 'medecin_demandeur',
        'indication_medicale', 'notes', 'traitee_par', 'traitee_at',
    ];

    protected $casts = [
        'date_demande' => 'date',
        'date_besoin' => 'date',
        'date_livraison' => 'date',
        'quantite_demandee' => 'decimal:2',
        'quantite_fournie' => 'decimal:2',
        'traitee_at' => 'datetime',
    ];

    public function banqueSang() { return $this->belongsTo(BanqueSang::class); }
    public function hopital() { return $this->belongsTo(Hopital::class); }
    
    public function scopeOfBanque($query, $banqueId) { return $query->where('banque_sang_id', $banqueId); }
    public function scopeUrgente($query) { return $query->whereIn('urgence', ['urgente', 'critique']); }
    
    public function getUrgenceClass() {
        return match($this->urgence) {
            'critique' => 'danger',
            'urgente' => 'warning',
            default => 'info',
        };
    }
}

