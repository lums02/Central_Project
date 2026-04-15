<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pharmacie_id');
            $table->string('nom');
            $table->string('code')->nullable()->unique();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('telephone_2')->nullable();
            $table->text('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('pays')->default('RDC');
            $table->string('contact_nom')->nullable(); // Nom du contact principal
            $table->string('contact_fonction')->nullable();
            $table->string('numero_registre')->nullable(); // N° registre commerce
            $table->string('numero_fiscal')->nullable(); // N° identification fiscale
            $table->text('specialites')->nullable(); // Spécialités du fournisseur
            $table->integer('delai_livraison_jours')->default(7); // Délai moyen de livraison
            $table->decimal('montant_minimum_commande', 10, 2)->nullable();
            $table->text('conditions_paiement')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
            $table->index('pharmacie_id');
            $table->index(['pharmacie_id', 'actif']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fournisseurs');
    }
};

