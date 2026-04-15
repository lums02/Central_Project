<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donneurs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banque_sang_id');
            $table->string('numero_donneur')->unique(); // DON-0001
            $table->string('nom');
            $table->string('prenom');
            $table->string('sexe');
            $table->date('date_naissance');
            $table->enum('groupe_sanguin', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->string('telephone');
            $table->string('email')->nullable();
            $table->text('adresse');
            $table->string('ville')->nullable();
            $table->string('profession')->nullable();
            $table->decimal('poids', 5, 2)->nullable(); // En kg
            $table->string('numero_carte_identite')->nullable();
            $table->boolean('eligible')->default(true);
            $table->text('raison_ineligibilite')->nullable();
            $table->date('derniere_date_don')->nullable();
            $table->integer('nombre_dons')->default(0);
            $table->text('antecedents_medicaux')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
            $table->index('banque_sang_id');
            $table->index('groupe_sanguin');
            $table->index(['banque_sang_id', 'eligible']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donneurs');
    }
};

