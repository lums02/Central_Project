<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckDatabaseStructure extends Command
{
    protected $signature = 'db:check-structure';
    protected $description = 'Vérifie la structure des tables principales de la base de données';

    public function handle()
    {
        $tables = [
            'pharmacies' => [
                'id', 'nom', 'email', 'adresse', 'logo', 'created_at', 'updated_at'
            ],
            'banque_sangs' => [
                'id', 'nom', 'email', 'adresse', 'logo', 'created_at', 'updated_at'
            ],
            'utilisateurs' => [
                'id', 'nom', 'email', 'mot_de_passe', 'role', 'type_utilisateur', 'entite_id', 'created_at', 'updated_at'
            ],
            'hopitaux' => [
                'id', 'nom', 'email', 'adresse', 'type_hopital', 'nombre_lits', 'logo', 'created_at', 'updated_at'
            ],
            'centres' => [
                'id', 'nom', 'email', 'adresse', 'logo', 'created_at', 'updated_at'
            ],
            'patients' => [
                'id', 'nom', 'email', 'date_naissance', 'sexe', 'created_at', 'updated_at'
            ],
        ];

        $ok = true;
        foreach ($tables as $table => $columns) {
            if (!DB::getSchemaBuilder()->hasTable($table)) {
                $this->error("Table manquante : $table");
                $ok = false;
                continue;
            }
            $missing = [];
            foreach ($columns as $col) {
                if (!DB::getSchemaBuilder()->hasColumn($table, $col)) {
                    $missing[] = $col;
                }
            }
            if ($missing) {
                $this->error("Colonnes manquantes dans $table : " . implode(', ', $missing));
                $ok = false;
            } else {
                $this->info("$table : OK");
            }
        }
        if ($ok) {
            $this->info('Structure de la base conforme !');
        } else {
            $this->warn('Des erreurs ont été détectées.');
        }
        return $ok ? 0 : 1;
    }
} 