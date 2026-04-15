<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [];

        // Déterminer le type de dashboard selon le rôle et le type d'utilisateur
        if ($user->isSuperAdmin()) {
            // Dashboard Super Admin - Statistiques globales
            $data = $this->getSuperAdminStats();
        } elseif ($user->role === 'admin') {
            // Dashboard Admin d'entité - Statistiques spécifiques à l'entité
            $data = $this->getEntityAdminStats($user);
        } else {
            // Dashboard utilisateur normal
            $data = $this->getUserStats($user);
        }

        // Permissions de l'utilisateur pour l'affichage
        $data['user_permissions'] = $user->getAllPermissions()->pluck('name')->toArray();
        $data['user_role'] = $user->role;
        $data['user_type'] = $user->type_utilisateur;

        return view('admin.dashboard', compact('data'));
    }

    private function getSuperAdminStats()
    {
        return [
            'dashboard_type' => 'superadmin',
            'total_users' => Utilisateur::count(),
            'pending_users' => Utilisateur::where('status', 'pending')->count(),
            'approved_users' => Utilisateur::where('status', 'approved')->count(),
            'total_roles' => \Spatie\Permission\Models\Role::count(),
            'entity_stats' => [
                'hopitaux' => Utilisateur::where('type_utilisateur', 'hopital')->count(),
                'pharmacies' => Utilisateur::where('type_utilisateur', 'pharmacie')->count(),
                'banques_sang' => Utilisateur::where('type_utilisateur', 'banque_sang')->count(),
                'centres' => Utilisateur::where('type_utilisateur', 'centre')->count(),
            ]
        ];
    }

    private function getEntityAdminStats($user)
    {
        $entityType = $user->type_utilisateur;
        
        $entiteId = $user->entite_id;
        
        $stats = [
            'dashboard_type' => 'entity_admin',
            'entity_type' => $entityType,
            'entity_name' => ucfirst(str_replace('_', ' ', $entityType)),
            'total_users' => \App\Models\Utilisateur::where('entite_id', $entiteId)->count(),
            'pending_users' => \App\Models\Utilisateur::where('entite_id', $entiteId)->where('status', 'pending')->count(),
            'approved_users' => \App\Models\Utilisateur::where('entite_id', $entiteId)->where('status', 'approved')->count(),
        ];

        // Statistiques selon le type d'entité et les permissions
        switch ($user->type_utilisateur) {
            case 'hopital':
                $stats['total_patients'] = \App\Models\Utilisateur::where('type_utilisateur', 'patient')
                    ->where('entite_id', $entiteId)
                    ->count();
                $stats['total_appointments'] = \App\Models\RendezVous::where('hopital_id', $entiteId)->count();
                $stats['today_appointments'] = \App\Models\RendezVous::where('hopital_id', $entiteId)
                    ->whereDate('date_rendezvous', today())
                    ->count();
                $stats['total_consultations'] = \App\Models\DossierMedical::where('hopital_id', $entiteId)->count();
                $stats['total_medecins'] = \App\Models\Utilisateur::where('type_utilisateur', 'hopital')
                    ->where('entite_id', $entiteId)
                    ->where('role', 'medecin')
                    ->count();
                break;

            case 'pharmacie':
                $stats['total_medicaments'] = \App\Models\Medicament::where('pharmacie_id', $entiteId)
                    ->where('actif', true)
                    ->count();
                $stats['stock_faible'] = \App\Models\Medicament::where('pharmacie_id', $entiteId)
                    ->where('actif', true)
                    ->whereRaw('stock_actuel <= stock_minimum')
                    ->count();
                $stats['total_commandes'] = \App\Models\Commande::where('pharmacie_id', $entiteId)->count();
                $stats['commandes_en_attente'] = \App\Models\Commande::where('pharmacie_id', $entiteId)
                    ->where('statut', 'en_attente')
                    ->count();
                break;

            case 'banque_sang':
                $stats['total_donneurs'] = \App\Models\Donneur::where('banque_sang_id', $entiteId)->count();
                $stats['dons_7_jours'] = \App\Models\Don::where('banque_sang_id', $entiteId)
                    ->where('created_at', '>=', now()->subDays(7))
                    ->count();
                $stats['total_personnel'] = \App\Models\Utilisateur::where('type_utilisateur', 'banque_sang')
                    ->where('entite_id', $entiteId)
                    ->count();
                $stats['groupes_sanguins'] = 8; // Toujours 8 groupes
                break;

            case 'centre':
                $stats['total_patients'] = 0; // Patient::where('centre_id', $user->centre_id)->count();
                $stats['total_consultations'] = 0; // Consultation::where('centre_id', $user->centre_id)->count();
                $stats['today_consultations'] = 0; // Consultation::where('centre_id', $user->centre_id)->whereDate('date', today())->count();
                $stats['total_prescriptions'] = 0; // Prescription::where('centre_id', $user->centre_id)->count();
                break;
        }

        return $stats;
    }

    private function getUserStats($user)
    {
        return [
            'dashboard_type' => 'user',
            'user_type' => $user->type_utilisateur,
            'welcome_message' => 'Bienvenue dans votre espace personnel',
        ];
    }
}
