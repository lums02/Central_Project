<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReserveSang extends Model
{
    protected $table = 'reserves_sang';
    
    protected $fillable = [
        'banque_sang_id', 'groupe_sanguin', 'quantite_disponible', 'quantite_minimum',
        'quantite_critique', 'nombre_poches', 'derniere_mise_a_jour',
    ];

    protected $casts = [
        'quantite_disponible' => 'decimal:2',
        'quantite_minimum' => 'decimal:2',
        'quantite_critique' => 'decimal:2',
        'nombre_poches' => 'integer',
        'derniere_mise_a_jour' => 'date',
    ];

    public function banqueSang() { return $this->belongsTo(BanqueSang::class); }
    
    public function scopeOfBanque($query, $banqueId) { return $query->where('banque_sang_id', $banqueId); }
    
    public function isFaible() { return $this->quantite_disponible <= $this->quantite_minimum; }
    public function isCritique() { return $this->quantite_disponible <= $this->quantite_critique; }
}

