<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banque_sang_id');
            $table->unsignedBigInteger('donneur_id');
            $table->unsignedBigInteger('technicien_id'); // Qui a prélevé
            $table->string('numero_don')->unique(); // DON-20251109-0001
            $table->date('date_don');
            $table->time('heure_don');
            $table->enum('groupe_sanguin', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->decimal('volume_preleve', 5, 2); // En litres
            $table->enum('type_don', ['sang_total', 'plasma', 'plaquettes', 'globules_rouges']);
            $table->enum('statut', ['en_attente_analyse', 'analyse_en_cours', 'conforme', 'non_conforme', 'utilise', 'perime'])->default('en_attente_analyse');
            $table->text('observations_prelevement')->nullable();
            $table->decimal('tension_arterielle_systolique', 5, 2)->nullable();
            $table->decimal('tension_arterielle_diastolique', 5, 2)->nullable();
            $table->decimal('hemoglobine', 5, 2)->nullable();
            $table->decimal('temperature', 4, 1)->nullable();
            $table->text('resultats_analyses')->nullable();
            $table->date('date_analyse')->nullable();
            $table->date('date_expiration')->nullable();
            $table->string('numero_poche')->nullable();
            $table->string('emplacement_stockage')->nullable();
            $table->timestamps();
            
            $table->index('banque_sang_id');
            $table->index('donneur_id');
            $table->index('groupe_sanguin');
            $table->index('statut');
            $table->index(['banque_sang_id', 'statut']);
            
            $table->foreign('donneur_id')->references('id')->on('donneurs')->onDelete('cascade');
            $table->foreign('technicien_id')->references('id')->on('utilisateurs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dons');
    }
};

