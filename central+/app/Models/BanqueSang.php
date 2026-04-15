<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BanqueSang extends Model
{
    protected $table = 'banque_sangs';

    protected $fillable = [
        'nom',
        'email',
        'adresse',
        'telephone',
        'logo',
    ];
    
    /**
     * Relation avec les réserves de sang
     */
    public function reserves()
    {
        return $this->hasMany(ReserveSang::class, 'banque_sang_id');
    }
    
    /**
     * Relation avec les donneurs
     */
    public function donneurs()
    {
        return $this->hasMany(Donneur::class, 'banque_sang_id');
    }
    
    /**
     * Relation avec les dons
     */
    public function dons()
    {
        return $this->hasMany(Don::class, 'banque_sang_id');
    }
}
