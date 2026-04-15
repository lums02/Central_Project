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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            
            // Références
            $table->unsignedBigInteger('hopital_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('medecin_id');
            $table->unsignedBigInteger('receptionniste_id');
            $table->unsignedBigInteger('caissier_id')->nullable();
            $table->unsignedBigInteger('dossier_medical_id')->nullable();
            
            // Informations de base du patient (remplies par réceptionniste)
            $table->decimal('poids', 5, 2)->nullable()->comment('En kg');
            $table->decimal('taille', 5, 2)->nullable()->comment('En cm');
            $table->decimal('temperature', 4, 1)->nullable()->comment('En °C');
            $table->string('tension_arterielle', 20)->nullable()->comment('Ex: 120/80');
            $table->integer('frequence_cardiaque')->nullable()->comment('Pouls en bpm');
            
            // Motif de consultation
            $table->text('motif_consultation');
            
            // Paiement
            $table->decimal('frais_consultation', 10, 2)->default(0);
            $table->enum('statut_paiement', ['en_attente', 'paye', 'rembourse'])->default('en_attente');
            $table->string('mode_paiement', 50)->nullable()->comment('Espèces, Carte, Mobile Money, etc.');
            $table->decimal('montant_paye', 10, 2)->nullable();
            $table->dateTime('date_paiement')->nullable();
            $table->string('numero_facture', 50)->nullable();
            
            // Statut de la consultation
            $table->enum('statut_consultation', [
                'en_attente_paiement',
                'paye_en_attente',
                'en_cours',
                'termine',
                'annule'
            ])->default('en_attente_paiement');
            
            // Dates
            $table->dateTime('date_consultation')->nullable()->comment('Date/heure de début de la consultation');
            $table->dateTime('date_fin_consultation')->nullable();
            
            // Notes
            $table->text('notes_receptionniste')->nullable();
            $table->text('notes_caissier')->nullable();
            
            $table->timestamps();
            
            // Index et clés étrangères
            $table->foreign('hopital_id')->references('id')->on('hopitaux')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('medecin_id')->references('id')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('receptionniste_id')->references('id')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('caissier_id')->references('id')->on('utilisateurs')->onDelete('set null');
            $table->foreign('dossier_medical_id')->references('id')->on('dossier_medicals')->onDelete('set null');
            
            // Index pour recherche rapide
            $table->index('hopital_id');
            $table->index('statut_paiement');
            $table->index('statut_consultation');
            $table->index('date_consultation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};

