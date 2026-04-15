<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePharmaciesAndBanqueSangsTables extends Migration
{
    public function up(): void
    {
        Schema::table('pharmacies', function (Blueprint $table) {
            if (!Schema::hasColumn('pharmacies', 'nom')) {
                $table->string('nom')->after('id');
            }
            if (!Schema::hasColumn('pharmacies', 'email')) {
                $table->string('email')->unique()->after('nom');
            }
            if (!Schema::hasColumn('pharmacies', 'adresse')) {
                $table->string('adresse')->nullable();
            }
            if (!Schema::hasColumn('pharmacies', 'logo')) {
                $table->string('logo')->nullable();
            }
        });

        Schema::table('banque_sangs', function (Blueprint $table) {
            if (!Schema::hasColumn('banque_sangs', 'nom')) {
                $table->string('nom')->after('id');
            }
            if (!Schema::hasColumn('banque_sangs', 'email')) {
                $table->string('email')->unique()->after('nom');
            }
            if (!Schema::hasColumn('banque_sangs', 'adresse')) {
                $table->string('adresse')->nullable();
            }
            if (!Schema::hasColumn('banque_sangs', 'logo')) {
                $table->string('logo')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->dropColumn(['nom', 'email', 'adresse', 'logo']);
        });

        Schema::table('banque_sangs', function (Blueprint $table) {
            $table->dropColumn(['nom', 'email', 'adresse', 'logo']);
        });
    }
}
