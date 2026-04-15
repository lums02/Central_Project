<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mouvements_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medicament_id');
            $table->unsignedBigInteger('pharmacie_id');
            $table->unsignedBigInteger('user_id'); // Qui a fait le mouvement
            $table->enum('type', ['entree', 'sortie', 'ajustement', 'vente', 'retour', 'perime']); // Type de mouvement
            $table->integer('quantite'); // Quantité (positive pour entrée, négative pour sortie)
            $table->integer('stock_avant'); // Stock avant le mouvement
            $table->integer('stock_apres'); // Stock après le mouvement
            $table->decimal('prix_unitaire', 10, 2)->nullable(); // Prix au moment du mouvement
            $table->string('reference')->nullable(); // Référence (N° facture, N° commande, etc.)
            $table->text('motif')->nullable(); // Motif de l'ajustement
            $table->text('notes')->nullable(); // Notes additionnelles
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index('medicament_id');
            $table->index('pharmacie_id');
            $table->index('type');
            $table->index(['medicament_id', 'created_at']);
            $table->index(['pharmacie_id', 'created_at']);
            
            // Clés étrangères
            $table->foreign('medicament_id')->references('id')->on('medicaments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('utilisateurs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements_stock');
    }
};

