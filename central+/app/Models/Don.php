<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Don extends Model
{
    protected $table = 'dons';
    
    protected $fillable = [
        'banque_sang_id', 'donneur_id', 'technicien_id', 'numero_don', 'date_don', 'heure_don',
        'groupe_sanguin', 'volume_preleve', 'type_don', 'statut', 'observations_prelevement',
        'tension_arterielle_systolique', 'tension_arterielle_diastolique', 'hemoglobine',
        'temperature', 'resultats_analyses', 'date_analyse', 'date_expiration',
        'numero_poche', 'emplacement_stockage',
    ];

    protected $casts = [
        'date_don' => 'date',
        'date_analyse' => 'date',
        'date_expiration' => 'date',
        'volume_preleve' => 'decimal:2',
        'tension_arterielle_systolique' => 'decimal:2',
        'tension_arterielle_diastolique' => 'decimal:2',
        'hemoglobine' => 'decimal:2',
        'temperature' => 'decimal:1',
    ];

    public function banqueSang() { return $this->belongsTo(BanqueSang::class); }
    public function donneur() { return $this->belongsTo(Donneur::class); }
    public function technicien() { return $this->belongsTo(Utilisateur::class, 'technicien_id'); }
    
    public function scopeOfBanque($query, $banqueId) { return $query->where('banque_sang_id', $banqueId); }
    public function scopeConforme($query) { return $query->where('statut', 'conforme'); }
}

