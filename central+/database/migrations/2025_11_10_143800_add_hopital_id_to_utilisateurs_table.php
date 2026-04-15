<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->unsignedBigInteger('hopital_id')->nullable()->after('entite_id');
            $table->string('groupe_sanguin', 10)->nullable()->after('hopital_id');
            
            // Ajouter la clé étrangère pour hopital_id
            $table->foreign('hopital_id')->references('id')->on('hopitaux')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->dropForeign(['hopital_id']);
            $table->dropColumn(['hopital_id', 'groupe_sanguin']);
        });
    }
};
