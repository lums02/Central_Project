<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'hopital_id',
        'patient_id',
        'medecin_id',
        'receptionniste_id',
        'caissier_id',
        'dossier_medical_id',
        'poids',
        'taille',
        'temperature',
        'tension_arterielle',
        'frequence_cardiaque',
        'motif_consultation',
        'frais_consultation',
        'statut_paiement',
        'mode_paiement',
        'montant_paye',
        'date_paiement',
        'numero_facture',
        'statut_consultation',
        'date_consultation',
        'date_fin_consultation',
        'notes_receptionniste',
        'notes_caissier',
    ];

    protected $casts = [
        'date_paiement' => 'datetime',
        'date_consultation' => 'datetime',
        'date_fin_consultation' => 'datetime',
        'frais_consultation' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'poids' => 'decimal:2',
        'taille' => 'decimal:2',
        'temperature' => 'decimal:1',
        'frequence_cardiaque' => 'integer',
    ];

    /**
     * Relations
     */
    
    public function hopital()
    {
        return $this->belongsTo(Hopital::class, 'hopital_id');
    }

    public function patient()
    {
        return $this->belongsTo(Utilisateur::class, 'patient_id');
    }

    public function medecin()
    {
        return $this->belongsTo(Utilisateur::class, 'medecin_id');
    }

    public function receptionniste()
    {
        return $this->belongsTo(Utilisateur::class, 'receptionniste_id');
    }

    public function caissier()
    {
        return $this->belongsTo(Utilisateur::class, 'caissier_id');
    }

    public function dossierMedical()
    {
        return $this->belongsTo(DossierMedical::class, 'dossier_medical_id');
    }

    /**
     * Scopes
     */
    
    public function scopeOfSameHospital($query)
    {
        $user = auth()->user();
        
        if ($user && $user->entite_id) {
            return $query->where('hopital_id', $user->entite_id);
        }
        
        return $query;
    }

    public function scopeEnAttentePaiement($query)
    {
        return $query->where('statut_paiement', 'en_attente')
                     ->where('statut_consultation', 'en_attente_paiement');
    }

    public function scopePayeEnAttente($query)
    {
        return $query->where('statut_paiement', 'paye')
                     ->where('statut_consultation', 'paye_en_attente');
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut_consultation', 'en_cours');
    }

    public function scopeTermine($query)
    {
        return $query->where('statut_consultation', 'termine');
    }

    /**
     * Méthodes utiles
     */
    
    public function genererNumeroFacture()
    {
        return 'FACT-' . $this->hopital_id . '-' . date('Ymd') . '-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function estPayee()
    {
        return $this->statut_paiement === 'paye';
    }

    public function estEnAttentePaiement()
    {
        return $this->statut_paiement === 'en_attente';
    }

    public function peutEtreConsultee()
    {
        return $this->estPayee() && in_array($this->statut_consultation, ['paye_en_attente', 'en_cours']);
    }

    public function marquerCommePayee($caissier_id, $mode_paiement, $montant)
    {
        $this->update([
            'caissier_id' => $caissier_id,
            'statut_paiement' => 'paye',
            'mode_paiement' => $mode_paiement,
            'montant_paye' => $montant,
            'date_paiement' => now(),
            'statut_consultation' => 'paye_en_attente',
            'numero_facture' => $this->genererNumeroFacture(),
        ]);
    }

    public function demarrerConsultation()
    {
        $this->update([
            'statut_consultation' => 'en_cours',
            'date_consultation' => now(),
        ]);
    }

    public function terminerConsultation($dossier_medical_id = null)
    {
        $this->update([
            'statut_consultation' => 'termine',
            'date_fin_consultation' => now(),
            'dossier_medical_id' => $dossier_medical_id,
        ]);
    }

    /**
     * Accesseurs
     */
    
    public function getStatutPaiementBadgeAttribute()
    {
        return match($this->statut_paiement) {
            'en_attente' => '<span class="badge bg-warning">En attente</span>',
            'paye' => '<span class="badge bg-success">Payé</span>',
            'rembourse' => '<span class="badge bg-secondary">Remboursé</span>',
            default => '<span class="badge bg-secondary">Inconnu</span>',
        };
    }

    public function getStatutConsultationBadgeAttribute()
    {
        return match($this->statut_consultation) {
            'en_attente_paiement' => '<span class="badge bg-warning">En attente de paiement</span>',
            'paye_en_attente' => '<span class="badge bg-info">Payé - En attente</span>',
            'en_cours' => '<span class="badge bg-primary">En cours</span>',
            'termine' => '<span class="badge bg-success">Terminé</span>',
            'annule' => '<span class="badge bg-danger">Annulé</span>',
            default => '<span class="badge bg-secondary">Inconnu</span>',
        };
    }
}

