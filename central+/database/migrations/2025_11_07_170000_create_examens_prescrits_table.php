<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examens_prescrits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dossier_medical_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('medecin_id');
            $table->unsignedBigInteger('hopital_id');
            $table->unsignedBigInteger('laborantin_id')->nullable();
            $table->string('numero_examen')->unique();
            $table->string('type_examen'); // biologique, imagerie, fonctionnel
            $table->string('nom_examen'); // Ex: NFS, Radio thorax, ECG
            $table->text('indication'); // Raison de l'examen
            $table->date('date_prescription');
            $table->date('date_realisation')->nullable();
            $table->decimal('prix', 10, 2)->default(0);
            $table->enum('statut_paiement', ['en_attente', 'paye', 'annule'])->default('en_attente');
            $table->date('date_paiement')->nullable();
            $table->unsignedBigInteger('valide_par')->nullable(); // ID du caissier
            $table->enum('statut_examen', ['prescrit', 'paye', 'en_cours', 'termine'])->default('prescrit');
            $table->text('resultats')->nullable();
            $table->text('interpretation')->nullable();
            $table->string('fichier_resultat')->nullable(); // Chemin vers le PDF/image
            $table->timestamps();
            
            $table->index(['dossier_medical_id', 'statut_examen']);
            $table->index(['hopital_id', 'statut_paiement']);
            $table->index(['laborantin_id', 'statut_examen']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examens_prescrits');
    }
};

