<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reserves_sang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banque_sang_id');
            $table->enum('groupe_sanguin', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->decimal('quantite_disponible', 8, 2)->default(0); // En litres
            $table->decimal('quantite_minimum', 8, 2)->default(5); // Seuil d'alerte
            $table->decimal('quantite_critique', 8, 2)->default(2); // Seuil critique
            $table->integer('nombre_poches')->default(0);
            $table->date('derniere_mise_a_jour')->nullable();
            $table->timestamps();
            
            $table->index('banque_sang_id');
            $table->index('groupe_sanguin');
            $table->unique(['banque_sang_id', 'groupe_sanguin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reserves_sang');
    }
};

