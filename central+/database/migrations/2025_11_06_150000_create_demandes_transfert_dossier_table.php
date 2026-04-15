<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('demandes_transfert_dossier', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('hopital_demandeur_id'); // Hôpital B qui demande
            $table->unsignedBigInteger('hopital_detenteur_id'); // Hôpital A qui détient
            $table->unsignedBigInteger('dossier_medical_id')->nullable();
            $table->enum('statut', [
                'en_attente_patient',      // En attente du consentement patient
                'accepte_patient',          // Patient a accepté
                'refuse_patient',           // Patient a refusé
                'transfere',                // Dossier transféré
                'refuse_hopital',           // Hôpital A a refusé
                'annule'                    // Demande annulée
            ])->default('en_attente_patient');
            $table->text('motif_demande');
            $table->text('notes_demandeur')->nullable();
            $table->text('notes_detenteur')->nullable();
            $table->text('reponse_patient')->nullable();
            $table->timestamp('date_demande');
            $table->timestamp('date_consentement_patient')->nullable();
            $table->timestamp('date_transfert')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('patient_id')->references('id')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('hopital_demandeur_id')->references('id')->on('hopitaux')->onDelete('cascade');
            $table->foreign('hopital_detenteur_id')->references('id')->on('hopitaux')->onDelete('cascade');
            $table->foreign('dossier_medical_id')->references('id')->on('dossier_medicals')->onDelete('set null');

            // Indexes
            $table->index('patient_id');
            $table->index('hopital_demandeur_id');
            $table->index('hopital_detenteur_id');
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes_transfert_dossier');
    }
};

