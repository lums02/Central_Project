<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeTransfertDossier extends Model
{
    use HasFactory;

    protected $table = 'demandes_transfert_dossier';

    protected $fillable = [
        'patient_id',
        'hopital_demandeur_id',
        'hopital_detenteur_id',
        'dossier_medical_id',
        'statut',
        'motif_demande',
        'notes_demandeur',
        'notes_detenteur',
        'reponse_patient',
        'date_demande',
        'date_consentement_patient',
        'date_transfert',
    ];

    protected $casts = [
        'date_demande' => 'datetime',
        'date_consentement_patient' => 'datetime',
        'date_transfert' => 'datetime',
    ];

    /**
     * Relation avec le patient
     */
    public function patient()
    {
        return $this->belongsTo(Utilisateur::class, 'patient_id');
    }

    /**
     * Relation avec l'hôpital demandeur (Hôpital B)
     */
    public function hopitalDemandeur()
    {
        return $this->belongsTo(Hopital::class, 'hopital_demandeur_id');
    }

    /**
     * Relation avec l'hôpital détenteur (Hôpital A)
     */
    public function hopitalDetenteur()
    {
        return $this->belongsTo(Hopital::class, 'hopital_detenteur_id');
    }

    /**
     * Relation avec le dossier médical
     */
    public function dossierMedical()
    {
        return $this->belongsTo(DossierMedical::class, 'dossier_medical_id');
    }

    /**
     * Scopes
     */
    public function scopeEnAttentePatient($query)
    {
        return $query->where('statut', 'en_attente_patient');
    }

    public function scopeAcceptePatient($query)
    {
        return $query->where('statut', 'accepte_patient');
    }

    public function scopeTransfere($query)
    {
        return $query->where('statut', 'transfere');
    }

    /**
     * Obtenir le statut formaté
     */
    public function getStatutFormatAttribute()
    {
        $statuts = [
            'en_attente_patient' => 'En Attente du Patient',
            'accepte_patient' => 'Accepté par le Patient',
            'refuse_patient' => 'Refusé par le Patient',
            'transfere' => 'Transféré',
            'refuse_hopital' => 'Refusé par l\'Hôpital',
            'annule' => 'Annulé',
        ];

        return $statuts[$this->statut] ?? $this->statut;
    }

    /**
     * Obtenir la couleur du badge selon le statut
     */
    public function getStatutColorAttribute()
    {
        $colors = [
            'en_attente_patient' => 'warning',
            'accepte_patient' => 'info',
            'refuse_patient' => 'danger',
            'transfere' => 'success',
            'refuse_hopital' => 'danger',
            'annule' => 'secondary',
        ];

        return $colors[$this->statut] ?? 'primary';
    }
}

