<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntitiesSeeder extends Seeder
{
    public function run(): void
    {
        // Créer des hôpitaux
        DB::table('hopitaux')->insert([
            [
                'nom' => 'Hôpital Saint-Joseph',
                'adresse' => 'Kinshasa, RDC',
                'telephone' => '+243 123 456 789',
                'email' => 'contact@saintjoseph.cd',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Hôpital Général de Référence',
                'adresse' => 'Lubumbashi, RDC',
                'telephone' => '+243 987 654 321',
                'email' => 'contact@hgr.cd',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Créer des pharmacies
        DB::table('pharmacies')->insert([
            [
                'nom' => 'Pharmacie Centrale',
                'adresse' => 'Kinshasa, RDC',
                'telephone' => '+243 111 222 333',
                'email' => 'contact@pharmaciecentrale.cd',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Pharmacie du Peuple',
                'adresse' => 'Goma, RDC',
                'telephone' => '+243 444 555 666',
                'email' => 'contact@pharmaciedupeuple.cd',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Créer des banques de sang
        DB::table('banque_sangs')->insert([
            [
                'nom' => 'Banque de Sang Nationale',
                'adresse' => 'Kinshasa, RDC',
                'telephone' => '+243 777 888 999',
                'email' => 'contact@banquesang.cd',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Centre de Transfusion Sanguine',
                'adresse' => 'Lubumbashi, RDC',
                'telephone' => '+243 666 777 888',
                'email' => 'contact@transfusion.cd',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        echo "✅ Entités créées avec succès !\n";
        echo "   - 2 Hôpitaux\n";
        echo "   - 2 Pharmacies\n";
        echo "   - 2 Banques de Sang\n";
    }
}

