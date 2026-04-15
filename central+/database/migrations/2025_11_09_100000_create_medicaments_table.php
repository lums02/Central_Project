<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicaments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pharmacie_id');
            $table->string('code')->nullable()->unique(); // Code DCI ou code interne
            $table->string('nom');
            $table->string('nom_generique')->nullable();
            $table->string('categorie'); // Antibiotiques, Antalgiques, etc.
            $table->string('forme'); // Comprimé, Sirop, Injection, etc.
            $table->string('dosage')->nullable(); // 500mg, 10ml, etc.
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('prix_achat', 10, 2)->nullable(); // Prix d'achat pour calculer la marge
            $table->integer('stock_actuel')->default(0);
            $table->integer('stock_minimum')->default(10);
            $table->boolean('prescription_requise')->default(false);
            $table->text('description')->nullable();
            $table->text('indication')->nullable(); // Indications thérapeutiques
            $table->text('contre_indication')->nullable();
            $table->text('effets_secondaires')->nullable();
            $table->text('posologie')->nullable();
            $table->string('fabricant')->nullable();
            $table->string('numero_lot')->nullable();
            $table->date('date_fabrication')->nullable();
            $table->date('date_expiration')->nullable();
            $table->string('emplacement')->nullable(); // Emplacement dans la pharmacie
            $table->boolean('actif')->default(true);
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index('pharmacie_id');
            $table->index('categorie');
            $table->index('nom');
            $table->index(['pharmacie_id', 'actif']);
            $table->index(['pharmacie_id', 'stock_actuel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicaments');
    }
};

