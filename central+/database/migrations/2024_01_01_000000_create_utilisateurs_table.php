<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom')->nullable();
            $table->string('email')->unique();
            $table->string('type_utilisateur'); // hopital, pharmacie, banque_sang, patient, medecin
            $table->unsignedBigInteger('entite_id')->nullable(); // ID de l'hôpital, pharmacie, etc.
            $table->string('telephone')->nullable();
            $table->date('date_naissance')->nullable();
            $table->enum('sexe', ['masculin', 'feminin'])->nullable();
            $table->text('adresse')->nullable();
            $table->string('mot_de_passe');
            $table->string('role')->default('user'); // user, admin, medecin, superadmin
            $table->enum('status', ['pending', 'approved', 'rejected', 'actif'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};

