<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pharmacie_id');
            $table->unsignedBigInteger('fournisseur_id');
            $table->unsignedBigInteger('user_id'); // Qui a créé la commande
            $table->string('numero_commande')->unique(); // CMD-20251109-0001
            $table->enum('statut', ['brouillon', 'en_attente', 'validee', 'en_cours', 'livree_partielle', 'livree', 'annulee'])->default('brouillon');
            $table->date('date_commande');
            $table->date('date_livraison_prevue')->nullable();
            $table->date('date_livraison_reelle')->nullable();
            $table->decimal('montant_total', 12, 2)->default(0);
            $table->decimal('montant_tva', 10, 2)->default(0);
            $table->decimal('frais_livraison', 10, 2)->default(0);
            $table->decimal('remise', 10, 2)->default(0);
            $table->decimal('montant_final', 12, 2)->default(0);
            $table->string('reference_fournisseur')->nullable(); // N° commande du fournisseur
            $table->string('numero_facture')->nullable();
            $table->text('notes')->nullable();
            $table->text('notes_reception')->nullable();
            $table->unsignedBigInteger('validee_par')->nullable(); // Qui a validé
            $table->timestamp('validee_at')->nullable();
            $table->unsignedBigInteger('receptionnee_par')->nullable(); // Qui a réceptionné
            $table->timestamp('receptionnee_at')->nullable();
            $table->timestamps();
            
            $table->index('pharmacie_id');
            $table->index('fournisseur_id');
            $table->index('statut');
            $table->index(['pharmacie_id', 'statut']);
            $table->index('date_commande');
            
            $table->foreign('fournisseur_id')->references('id')->on('fournisseurs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('utilisateurs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};

