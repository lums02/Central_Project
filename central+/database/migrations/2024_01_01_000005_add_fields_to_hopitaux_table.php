<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hopitaux', function (Blueprint $table) {
            $table->string('type_hopital')->nullable()->after('nom');
            $table->integer('nombre_lits')->nullable()->after('type_hopital');
        });
    }

    public function down(): void
    {
        Schema::table('hopitaux', function (Blueprint $table) {
            $table->dropColumn(['type_hopital', 'nombre_lits']);
        });
    }
};

