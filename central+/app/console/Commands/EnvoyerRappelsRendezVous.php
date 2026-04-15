<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RendezVous;
use App\Models\Notification;
use Carbon\Carbon;

class EnvoyerRappelsRendezVous extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rendezvous:rappels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer des rappels de rendez-vous aux médecins et patients (24h et 2h avant)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔔 Vérification des rendez-vous à rappeler...');
        
        $now = Carbon::now();
        $in24Hours = $now->copy()->addHours(24);
        $in2Hours = $now->copy()->addHours(2);
        
        // Récupérer les rendez-vous confirmés ou en attente
        $rendezvous = RendezVous::whereIn('statut', ['en_attente', 'confirme'])
            ->with(['patient', 'medecin', 'hopital'])
            ->get();
        
        $rappels24h = 0;
        $rappels2h = 0;
        
        foreach ($rendezvous as $rdv) {
            // Construire la date/heure du rendez-vous
            $dateRdv = Carbon::parse($rdv->date_rendezvous)->format('Y-m-d');
            $heureRdv = $rdv->heure_rendezvous;
            $dateTimeRdv = Carbon::parse($dateRdv . ' ' . $heureRdv);
            
            // Vérifier si le RDV est dans 24 heures (avec marge de 1 heure)
            if ($dateTimeRdv->between($in24Hours->copy()->subHour(), $in24Hours->copy()->addHour())) {
                // Vérifier si un rappel 24h n'a pas déjà été envoyé
                $rappelExiste = Notification::where('type', 'rappel_rdv_24h')
                    ->where('data', 'like', '%"rdv_id":' . $rdv->id . '%')
                    ->exists();
                
                if (!$rappelExiste) {
                    $this->envoyerRappel24h($rdv);
                    $rappels24h++;
                }
            }
            
            // Vérifier si le RDV est dans 2 heures (avec marge de 30 minutes)
            if ($dateTimeRdv->between($in2Hours->copy()->subMinutes(30), $in2Hours->copy()->addMinutes(30))) {
                // Vérifier si un rappel 2h n'a pas déjà été envoyé
                $rappelExiste = Notification::where('type', 'rappel_rdv_2h')
                    ->where('data', 'like', '%"rdv_id":' . $rdv->id . '%')
                    ->exists();
                
                if (!$rappelExiste) {
                    $this->envoyerRappel2h($rdv);
                    $rappels2h++;
                }
            }
        }
        
        $this->info("✅ Rappels 24h envoyés: {$rappels24h}");
        $this->info("✅ Rappels 2h envoyés: {$rappels2h}");
        $this->info('✨ Terminé !');
        
        return Command::SUCCESS;
    }
    
    /**
     * Envoyer un rappel 24 heures avant
     */
    private function envoyerRappel24h($rdv)
    {
        $dateRdv = Carbon::parse($rdv->date_rendezvous)->format('d/m/Y');
        $heureRdv = substr($rdv->heure_rendezvous, 0, 5);
        
        // Notification au médecin
        Notification::create([
            'user_id' => $rdv->medecin_id,
            'hopital_id' => $rdv->hopital_id,
            'type' => 'rappel_rdv_24h',
            'title' => 'Rappel : Rendez-vous demain',
            'message' => "Rendez-vous avec {$rdv->patient->nom} demain à {$heureRdv}. Motif: {$rdv->motif}",
            'data' => json_encode([
                'rdv_id' => $rdv->id,
                'patient_id' => $rdv->patient_id,
                'date' => $dateRdv,
                'heure' => $heureRdv
            ]),
            'read' => false,
        ]);
        
        // Notification au patient
        Notification::create([
            'user_id' => $rdv->patient_id,
            'hopital_id' => $rdv->hopital_id,
            'type' => 'rappel_rdv_24h',
            'title' => 'Rappel : Rendez-vous demain',
            'message' => "Vous avez un rendez-vous avec Dr. {$rdv->medecin->nom} demain à {$heureRdv} à {$rdv->hopital->nom}.",
            'data' => json_encode([
                'rdv_id' => $rdv->id,
                'medecin_id' => $rdv->medecin_id,
                'date' => $dateRdv,
                'heure' => $heureRdv
            ]),
            'read' => false,
        ]);
        
        $this->line("  📧 Rappel 24h envoyé pour RDV #{$rdv->id} ({$rdv->patient->nom})");
    }
    
    /**
     * Envoyer un rappel 2 heures avant
     */
    private function envoyerRappel2h($rdv)
    {
        $dateRdv = Carbon::parse($rdv->date_rendezvous)->format('d/m/Y');
        $heureRdv = substr($rdv->heure_rendezvous, 0, 5);
        
        // Notification au médecin
        Notification::create([
            'user_id' => $rdv->medecin_id,
            'hopital_id' => $rdv->hopital_id,
            'type' => 'rappel_rdv_2h',
            'title' => '⚠️ Rendez-vous dans 2 heures',
            'message' => "Rendez-vous avec {$rdv->patient->nom} aujourd'hui à {$heureRdv}. Motif: {$rdv->motif}",
            'data' => json_encode([
                'rdv_id' => $rdv->id,
                'patient_id' => $rdv->patient_id,
                'date' => $dateRdv,
                'heure' => $heureRdv
            ]),
            'read' => false,
        ]);
        
        // Notification au patient
        Notification::create([
            'user_id' => $rdv->patient_id,
            'hopital_id' => $rdv->hopital_id,
            'type' => 'rappel_rdv_2h',
            'title' => '⚠️ Rendez-vous dans 2 heures',
            'message' => "N'oubliez pas votre rendez-vous avec Dr. {$rdv->medecin->nom} aujourd'hui à {$heureRdv} à {$rdv->hopital->nom}.",
            'data' => json_encode([
                'rdv_id' => $rdv->id,
                'medecin_id' => $rdv->medecin_id,
                'date' => $dateRdv,
                'heure' => $heureRdv
            ]),
            'read' => false,
        ]);
        
        $this->line("  📧 Rappel 2h envoyé pour RDV #{$rdv->id} ({$rdv->patient->nom})");
    }
}
