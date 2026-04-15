<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamenPrescrit extends Model
{
    protected $table = 'examens_prescrits';

    protected $fillable = [
        'dossier_medical_id',
        'patient_id',
        'medecin_id',
        'hopital_id',
        'laborantin_id',
        'numero_examen',
        'type_examen',
        'nom_examen',
        'indication',
        'date_prescription',
        'date_realisation',
        'prix',
        'statut_paiement',
        'date_paiement',
        'valide_par',
        'statut_examen',
        'resultats',
        'interpretation',
        'fichier_resultat',
    ];

    protected $casts = [
        'date_prescription' => 'date',
        'date_realisation' => 'date',
        'date_paiement' => 'date',
        'prix' => 'decimal:2',
    ];

    public function dossierMedical()
    {
        return $this->belongsTo(DossierMedical::class, 'dossier_medical_id');
    }

    public function patient()
    {
        return $this->belongsTo(Utilisateur::class, 'patient_id');
    }

    public function medecin()
    {
        return $this->belongsTo(Utilisateur::class, 'medecin_id');
    }

    public function laborantin()
    {
        return $this->belongsTo(Utilisateur::class, 'laborantin_id');
    }

    public function caissier()
    {
        return $this->belongsTo(Utilisateur::class, 'valide_par');
    }

    public function hopital()
    {
        return $this->belongsTo(Hopital::class, 'hopital_id');
    }
}

