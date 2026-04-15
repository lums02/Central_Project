<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lignes_commande', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('commande_id');
            $table->unsignedBigInteger('medicament_id');
            $table->integer('quantite_commandee');
            $table->integer('quantite_recue')->default(0);
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('montant_ligne', 10, 2); // quantite * prix_unitaire
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('commande_id');
            $table->index('medicament_id');
            
            $table->foreign('commande_id')->references('id')->on('commandes')->onDelete('cascade');
            $table->foreign('medicament_id')->references('id')->on('medicaments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lignes_commande');
    }
};

