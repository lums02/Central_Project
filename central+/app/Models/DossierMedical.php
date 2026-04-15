<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DossierMedical extends Model
{
    use HasFactory;
    
    /**
     * Scope pour filtrer par hôpital de l'utilisateur connecté
     */
    public function scopeOfSameHospital($query)
    {
        $user = auth()->user();
        
        if (!$user || $user->isSuperAdmin()) {
            return $query; // Superadmin voit tout
        }
        
        if ($user->type_utilisateur === 'hopital') {
            return $query->where('hopital_id', $user->entite_id);
        }
        
        // Si c'est un médecin, voir uniquement ses dossiers
        if ($user->role === 'medecin') {
            return $query->where('medecin_id', $user->id);
        }
        
        return $query->where('hopital_id', $user->entite_id);
    }

    protected $fillable = [
        'patient_id',
        'medecin_id',
        'hopital_id',
        'numero_dossier',
        'motif_consultation',
        'antecedents',
        'examen_clinique',
        'diagnostic',
        'traitement',
        'observations',
        'date_consultation',
        'date_prochain_rdv',
        'urgence',
        'statut'
    ];
    
    protected $casts = [
        'date_consultation' => 'date',
        'date_prochain_rdv' => 'date',
    ];
    
    public function patient()
    {
        return $this->belongsTo(Utilisateur::class, 'patient_id');
    }
    
    public function medecin()
    {
        return $this->belongsTo(Utilisateur::class, 'medecin_id');
    }
    
    public function hopital()
    {
        return $this->belongsTo(Hopital::class, 'hopital_id');
    }
}