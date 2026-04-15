<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Donneur extends Model
{
    protected $table = 'donneurs';
    
    protected $fillable = [
        'banque_sang_id', 'numero_donneur', 'nom', 'prenom', 'sexe', 'date_naissance',
        'groupe_sanguin', 'telephone', 'email', 'adresse', 'ville', 'profession',
        'poids', 'numero_carte_identite', 'eligible', 'raison_ineligibilite',
        'derniere_date_don', 'nombre_dons', 'antecedents_medicaux', 'notes', 'actif',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'derniere_date_don' => 'date',
        'poids' => 'decimal:2',
        'eligible' => 'boolean',
        'actif' => 'boolean',
        'nombre_dons' => 'integer',
    ];

    public function banqueSang() { return $this->belongsTo(BanqueSang::class); }
    public function dons() { return $this->hasMany(Don::class); }
    
    public function scopeOfBanque($query, $banqueId) { return $query->where('banque_sang_id', $banqueId); }
    public function scopeEligible($query) { return $query->where('eligible', true); }
    
    public function getAge() { return $this->date_naissance ? Carbon::now()->diffInYears($this->date_naissance) : null; }
    
    public function peutDonner() {
        if (!$this->eligible) return false;
        if (!$this->derniere_date_don) return true;
        return Carbon::now()->diffInDays($this->derniere_date_don) >= 56; // 8 semaines minimum
    }
}

