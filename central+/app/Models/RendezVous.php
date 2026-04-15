<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model
{
    use HasFactory;

    protected $table = 'rendezvous';
    
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
        
        // Si c'est un médecin, voir uniquement ses RDV
        if ($user->role === 'medecin') {
            return $query->where('medecin_id', $user->id);
        }
        
        return $query->where('hopital_id', $user->entite_id);
    }

    protected $fillable = [
        'patient_id',
        'medecin_id',
        'hopital_id',
        'date_rendezvous',
        'heure_rendezvous',
        'type_consultation',
        'motif',
        'statut',
        'notes',
        'prix',
    ];

    protected $casts = [
        'date_rendezvous' => 'date',
        'prix' => 'decimal:2',
    ];

    /**
     * Relation avec le patient
     */
    public function patient()
    {
        return $this->belongsTo(Utilisateur::class, 'patient_id');
    }

    /**
     * Relation avec le médecin
     */
    public function medecin()
    {
        return $this->belongsTo(Utilisateur::class, 'medecin_id');
    }

    /**
     * Relation avec l'hôpital
     */
    public function hopital()
    {
        return $this->belongsTo(Hopital::class, 'hopital_id');
    }

    /**
     * Scopes
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeConfirme($query)
    {
        return $query->where('statut', 'confirme');
    }

    public function scopeAujourdhui($query)
    {
        return $query->whereDate('date_rendezvous', today());
    }

    public function scopeAVenir($query)
    {
        return $query->where('date_rendezvous', '>=', today());
    }

    /**
     * Obtenir le statut formaté
     */
    public function getStatutFormatAttribute()
    {
        $statuts = [
            'en_attente' => 'En Attente',
            'confirme' => 'Confirmé',
            'annule' => 'Annulé',
            'termine' => 'Terminé',
        ];

        return $statuts[$this->statut] ?? $this->statut;
    }

    /**
     * Obtenir la couleur du badge selon le statut
     */
    public function getStatutColorAttribute()
    {
        $colors = [
            'en_attente' => 'warning',
            'confirme' => 'success',
            'annule' => 'danger',
            'termine' => 'secondary',
        ];

        return $colors[$this->statut] ?? 'primary';
    }
}

