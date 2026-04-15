<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Ajouter les colonnes pour pharmacie et banque de sang
            $table->unsignedBigInteger('pharmacie_id')->nullable()->after('hopital_id');
            $table->unsignedBigInteger('banque_sang_id')->nullable()->after('pharmacie_id');
            
            // Ajouter les index pour améliorer les performances
            $table->index(['pharmacie_id', 'read']);
            $table->index(['banque_sang_id', 'read']);
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['pharmacie_id', 'read']);
            $table->dropIndex(['banque_sang_id', 'read']);
            $table->dropColumn(['pharmacie_id', 'banque_sang_id']);
        });
    }
};

