<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'hopital_id',
        'pharmacie_id',
        'banque_sang_id',
        'type',
        'title',
        'message',
        'data',
        'read',
    ];

    protected $casts = [
        'data' => 'array',
        'read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(Utilisateur::class, 'user_id');
    }

    public function hopital()
    {
        return $this->belongsTo(Hopital::class, 'hopital_id');
    }

    public function pharmacie()
    {
        return $this->belongsTo(Pharmacie::class, 'pharmacie_id');
    }

    public function banqueSang()
    {
        return $this->belongsTo(BanqueSang::class, 'banque_sang_id');
    }
}

