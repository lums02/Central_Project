<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes_sang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banque_sang_id');
            $table->unsignedBigInteger('hopital_id');
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->string('numero_demande')->unique(); // DEM-20251109-0001
            $table->enum('groupe_sanguin', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->decimal('quantite_demandee', 5, 2); // En litres
            $table->decimal('quantite_fournie', 5, 2)->default(0);
            $table->enum('urgence', ['normale', 'urgente', 'critique'])->default('normale');
            $table->enum('statut', ['en_attente', 'en_preparation', 'prete', 'livree', 'annulee'])->default('en_attente');
            $table->date('date_demande');
            $table->date('date_besoin'); // Date à laquelle le sang est nécessaire
            $table->date('date_livraison')->nullable();
            $table->string('nom_patient')->nullable();
            $table->string('medecin_demandeur')->nullable();
            $table->text('indication_medicale'); // Raison de la demande
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('traitee_par')->nullable();
            $table->timestamp('traitee_at')->nullable();
            $table->timestamps();
            
            $table->index('banque_sang_id');
            $table->index('hopital_id');
            $table->index('groupe_sanguin');
            $table->index('statut');
            $table->index('urgence');
            $table->index(['banque_sang_id', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandes_sang');
    }
};

