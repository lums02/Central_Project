<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

// Configuration de la base de données
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'central+',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    echo "=== CONFIGURATION DU SYSTÈME DE RENDEZ-VOUS ===\n\n";

    // 1. Créer la table medecins si elle n'existe pas
    if (!Capsule::schema()->hasTable('medecins')) {
        echo "Création de la table medecins...\n";
        Capsule::schema()->create('medecins', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('specialite');
            $table->unsignedBigInteger('hopital_id');
            $table->string('email')->unique();
            $table->string('telephone');
            $table->text('adresse')->nullable();
            $table->decimal('tarif_consultation', 8, 2)->default(0);
            $table->json('disponibilites')->nullable();
            $table->enum('statut', ['actif', 'inactif'])->default('actif');
            $table->timestamps();

            $table->foreign('hopital_id')->references('id')->on('hopitaux')->onDelete('cascade');
            $table->index('specialite');
            $table->index('hopital_id');
            $table->index('statut');
        });
        echo "✅ Table medecins créée\n";
    } else {
        echo "✅ Table medecins existe déjà\n";
    }

    // 2. Créer la table rendezvous si elle n'existe pas
    if (!Capsule::schema()->hasTable('rendezvous')) {
        echo "Création de la table rendezvous...\n";
        Capsule::schema()->create('rendezvous', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('medecin_id');
            $table->unsignedBigInteger('hopital_id');
            $table->date('date_rendezvous');
            $table->time('heure_rendezvous');
            $table->string('type_consultation');
            $table->text('motif');
            $table->enum('statut', ['en_attente', 'confirme', 'annule', 'termine'])->default('en_attente');
            $table->text('notes')->nullable();
            $table->decimal('prix', 8, 2)->default(0);
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('medecin_id')->references('id')->on('medecins')->onDelete('cascade');
            $table->foreign('hopital_id')->references('id')->on('hopitaux')->onDelete('cascade');

            $table->index(['patient_id', 'date_rendezvous']);
            $table->index(['medecin_id', 'date_rendezvous']);
            $table->index(['hopital_id', 'date_rendezvous']);
            $table->index('statut');
        });
        echo "✅ Table rendezvous créée\n";
    } else {
        echo "✅ Table rendezvous existe déjà\n";
    }

    // 3. Vérifier et créer des données de test
    echo "\n=== CRÉATION DES DONNÉES DE TEST ===\n";

    // Vérifier si l'utilisateur patient existe
    $patient = Capsule::table('utilisateurs')->where('type_utilisateur', 'patient')->first();
    
    if (!$patient) {
        echo "Création d'un patient de test...\n";
        $patientId = Capsule::table('utilisateurs')->insertGetId([
            'nom' => 'Dupont',
            'prenom' => 'Marie',
            'email' => 'marie.dupont@example.com',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'type_utilisateur' => 'patient',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        echo "✅ Patient créé (ID: $patientId)\n";
    } else {
        $patientId = $patient->id;
        echo "✅ Patient existe déjà (ID: $patientId)\n";
    }

    // Vérifier si des hôpitaux existent
    $hopital = Capsule::table('hopitaux')->first();
    
    if (!$hopital) {
        echo "Création d'un hôpital de test...\n";
        $hopitalId = Capsule::table('hopitaux')->insertGetId([
            'nom' => 'Hôpital Central',
            'adresse' => '123 Rue de la Santé, Paris',
            'telephone' => '01 23 45 67 89',
            'email' => 'contact@hopital-central.fr',
            'statut' => 'actif',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        echo "✅ Hôpital créé (ID: $hopitalId)\n";
    } else {
        $hopitalId = $hopital->id;
        echo "✅ Hôpital existe déjà (ID: $hopitalId)\n";
    }

    // Vérifier si des médecins existent
    $medecins = Capsule::table('medecins')->get();
    
    if ($medecins->count() == 0) {
        echo "Création des médecins de test...\n";
        $medecinsData = [
            [
                'nom' => 'Martin',
                'prenom' => 'Jean',
                'specialite' => 'cardiologie',
                'hopital_id' => $hopitalId,
                'email' => 'jean.martin@hopital-central.fr',
                'telephone' => '01 23 45 67 90',
                'adresse' => 'Cabinet 101, Hôpital Central',
                'tarif_consultation' => 50.00,
                'statut' => 'actif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nom' => 'Dubois',
                'prenom' => 'Sophie',
                'specialite' => 'dermatologie',
                'hopital_id' => $hopitalId,
                'email' => 'sophie.dubois@hopital-central.fr',
                'telephone' => '01 23 45 67 91',
                'adresse' => 'Cabinet 102, Hôpital Central',
                'tarif_consultation' => 45.00,
                'statut' => 'actif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nom' => 'Bernard',
                'prenom' => 'Pierre',
                'specialite' => 'generaliste',
                'hopital_id' => $hopitalId,
                'email' => 'pierre.bernard@hopital-central.fr',
                'telephone' => '01 23 45 67 92',
                'adresse' => 'Cabinet 103, Hôpital Central',
                'tarif_consultation' => 40.00,
                'statut' => 'actif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        foreach ($medecinsData as $medecinData) {
            Capsule::table('medecins')->insert($medecinData);
        }
        echo "✅ 3 médecins créés\n";
    } else {
        echo "✅ Médecins existent déjà (" . $medecins->count() . " médecins)\n";
    }

    // Vérifier si des rendez-vous existent
    $rendezvous = Capsule::table('rendezvous')->get();
    
    if ($rendezvous->count() == 0) {
        echo "Création des rendez-vous de test...\n";
        $medecins = Capsule::table('medecins')->get();
        
        $rendezvousData = [
            [
                'patient_id' => $patientId,
                'medecin_id' => $medecins[0]->id,
                'hopital_id' => $hopitalId,
                'date_rendezvous' => date('Y-m-d', strtotime('+1 day')),
                'heure_rendezvous' => '09:00:00',
                'type_consultation' => 'consultation_specialisee',
                'motif' => 'Consultation de suivi cardiaque',
                'statut' => 'confirme',
                'notes' => 'Patient à jeun pour les examens',
                'prix' => $medecins[0]->tarif_consultation,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'patient_id' => $patientId,
                'medecin_id' => $medecins[1]->id,
                'hopital_id' => $hopitalId,
                'date_rendezvous' => date('Y-m-d', strtotime('+3 days')),
                'heure_rendezvous' => '14:30:00',
                'type_consultation' => 'consultation_generale',
                'motif' => 'Examen de la peau',
                'statut' => 'en_attente',
                'notes' => 'Première consultation dermatologique',
                'prix' => $medecins[1]->tarif_consultation,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'patient_id' => $patientId,
                'medecin_id' => $medecins[2]->id,
                'hopital_id' => $hopitalId,
                'date_rendezvous' => date('Y-m-d'),
                'heure_rendezvous' => '10:00:00',
                'type_consultation' => 'consultation_generale',
                'motif' => 'Consultation de routine',
                'statut' => 'confirme',
                'notes' => 'Bilan de santé annuel',
                'prix' => $medecins[2]->tarif_consultation,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        foreach ($rendezvousData as $rdv) {
            Capsule::table('rendezvous')->insert($rdv);
        }
        echo "✅ 3 rendez-vous créés\n";
    } else {
        echo "✅ Rendez-vous existent déjà (" . $rendezvous->count() . " rendez-vous)\n";
    }

    echo "\n=== CONFIGURATION TERMINÉE ===\n";
    echo "✅ Système de rendez-vous prêt !\n";
    echo "✅ Données de test créées\n";
    echo "✅ Vous pouvez maintenant tester le système\n\n";
    
    echo "INFORMATIONS DE CONNEXION PATIENT :\n";
    echo "Email: marie.dupont@example.com\n";
    echo "Mot de passe: password\n\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

