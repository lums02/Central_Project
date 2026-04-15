<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dossier_medicals', function (Blueprint $table) {
            // Anamnèse
            $table->text('motif_consultation')->nullable()->after('numero_dossier');
            $table->text('antecedents')->nullable()->after('motif_consultation');
            
            // Examen clinique
            $table->text('examen_clinique')->nullable()->after('antecedents');
            
            // Suivi
            $table->date('date_prochain_rdv')->nullable()->after('date_consultation');
            $table->string('urgence')->default('normale')->after('date_prochain_rdv');
        });
    }

    public function down(): void
    {
        Schema::table('dossier_medicals', function (Blueprint $table) {
            $table->dropColumn([
                'motif_consultation',
                'antecedents',
                'examen_clinique',
                'date_prochain_rdv',
                'urgence'
            ]);
        });
    }
};

