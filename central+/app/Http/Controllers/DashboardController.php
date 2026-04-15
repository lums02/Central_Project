<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function hopitalDashboard()
    {
        if (Auth::user()->type_utilisateur !== 'hopital') {
            abort(403, 'Accès interdit pour ce type d\'utilisateur.');
        }
        
        $user = Auth::user();
        $data = [];
        
        // Déterminer le type de dashboard selon le rôle
        if ($user->isSuperAdmin()) {
            $data = $this->getSuperAdminStats();
        } elseif ($user->role === 'admin_hopital') {
            $data = $this->getEntityAdminStats($user);
        } else {
            $data = $this->getUserStats($user);
        }
        
        // Permissions de l'utilisateur pour l'affichage
        $data['user_permissions'] = $user->getAllPermissions()->pluck('name')->toArray();
        $data['user_role'] = $user->role;
        $data['user_type'] = $user->type_utilisateur;
        
        return view('entities.hopital.dashboard', compact('data'));
    }

    public function pharmacieDashboard()
    {
        if (Auth::user()->type_utilisateur !== 'pharmacie') {
            abort(403, 'Accès interdit pour ce type d\'utilisateur.');
        }
        return view('pharmacie.dashboard');
    }

    public function banqueSangDashboard()
    {
        if (Auth::user()->type_utilisateur !== 'banque_sang') {
            abort(403, 'Accès interdit pour ce type d\'utilisateur.');
        }
        return view('banque.dashboard');
    }

    public function centreDashboard()
    {
        if (Auth::user()->type_utilisateur !== 'centre') {
            abort(403, 'Accès interdit pour ce type d\'utilisateur.');
        }
        return view('centre.dashboard');
    }

    public function patientDashboard()
    {
        if (Auth::user()->type_utilisateur !== 'patient') {
            abort(403, 'Accès interdit pour ce type d\'utilisateur.');
        }
        return view('patient.dashboard');
    }
    
    private function getSuperAdminStats()
    {
        return [
            'dashboard_type' => 'superadmin',
            'total_users' => \App\Models\Utilisateur::count(),
            'pending_users' => \App\Models\Utilisateur::where('status', 'pending')->count(),
            'approved_users' => \App\Models\Utilisateur::where('status', 'approved')->count(),
            'total_roles' => \Spatie\Permission\Models\Role::count(),
            'entity_stats' => [
                'hopitaux' => \App\Models\Utilisateur::where('type_utilisateur', 'hopital')->count(),
                'pharmacies' => \App\Models\Utilisateur::where('type_utilisateur', 'pharmacie')->count(),
                'banques_sang' => \App\Models\Utilisateur::where('type_utilisateur', 'banque_sang')->count(),
                'centres' => \App\Models\Utilisateur::where('type_utilisateur', 'centre')->count(),
            ]
        ];
    }
    
    private function getEntityAdminStats($user)
    {
        $entityType = $user->type_utilisateur;
        
        $entiteId = $user->entite_id;
        
        return [
            'dashboard_type' => 'entity_admin',
            'entity_type' => $entityType,
            'entity_name' => ucfirst(str_replace('_', ' ', $entityType)),
            'welcome_message' => "Bienvenue dans votre espace {$entityType}",
            'total_users' => \App\Models\Utilisateur::where('entite_id', $entiteId)->count(),
            'pending_users' => \App\Models\Utilisateur::where('entite_id', $entiteId)->where('status', 'pending')->count(),
            'approved_users' => \App\Models\Utilisateur::where('entite_id', $entiteId)->where('status', 'approved')->count(),
            'total_patients' => 0, // À implémenter selon les modèles
            'total_appointments' => 0, // À implémenter selon les modèles
            'total_medical_records' => 0, // À implémenter selon les modèles
            'total_prescriptions' => 0, // À implémenter selon les modèles
        ];
    }
    
    private function getUserStats($user)
    {
        return [
            'dashboard_type' => 'user',
            'welcome_message' => "Bienvenue dans votre espace personnel",
            'user_name' => $user->nom,
            'user_role' => $user->role,
            'user_type' => $user->type_utilisateur,
        ];
    }
}