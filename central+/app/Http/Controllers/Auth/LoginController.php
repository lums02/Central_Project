<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $userType = $request->get('type', 'default');
        
        // Détection automatique si l'utilisateur vient d'une page spécifique
        if ($userType === 'default') {
            $referer = $request->headers->get('referer');
            if ($referer) {
                if (str_contains($referer, '/patient')) {
                    $userType = 'patient';
                } elseif (str_contains($referer, '/pharmacie')) {
                    $userType = 'pharmacie';
                } elseif (str_contains($referer, '/banque')) {
                    $userType = 'banque_sang';
                }
            }
        }
        
        // Debug: Log pour vérifier
        \Log::info('LoginController - userType: ' . $userType . ', referer: ' . ($referer ?? 'null'));
        
        return view('auth.login', compact('userType'));
    }

    public function login(Request $request)
    {
        // Valider les champs reçus
        $credentials = $request->validate([
            'email'            => ['required', 'email'],
            'mot_de_passe'     => ['required'],
            'user_type'        => ['sometimes', 'string'],
        ]);

        // On récupère l'utilisateur par email uniquement
        $utilisateur = Utilisateur::where('email', $request->email)->first();

        // Vérification du mot de passe
        if ($utilisateur && Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
            // Vérifier le statut de l'utilisateur
            if ($utilisateur->status === 'pending') {
                return back()->withErrors([
                    'email' => 'Votre compte est en attente d\'approbation par l\'administrateur.',
                ])->withInput();
            }

            if ($utilisateur->status === 'rejected') {
                $reason = $utilisateur->rejection_reason ? " Raison : {$utilisateur->rejection_reason}" : '';
                return back()->withErrors([
                    'email' => 'Votre compte a été rejeté.' . $reason,
                ])->withInput();
            }

            // Si l'utilisateur est approuvé, procéder à la connexion
            Auth::login($utilisateur);

            // Vérifier si c'est une connexion patient spécifique
            if ($request->user_type === 'patient') {
                // Vérifier que l'utilisateur est bien un patient
                if ($utilisateur->type_utilisateur === 'patient') {
                    return redirect()->intended(route('patient.dashboard'));
                } else {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Ce compte n\'est pas un compte patient.',
                    ])->withInput();
                }
            }

            // Rediriger selon le rôle et le type_utilisateur
            if ($utilisateur->role === 'admin' || $utilisateur->role === 'superadmin') {
                // Les admins (superadmin, hopital_admin, pharmacie_admin, banque_sang_admin) 
                // vont toujours vers le dashboard admin
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($utilisateur->role === 'medecin') {
                // Les médecins vont vers leur dashboard spécifique
                return redirect()->intended(route('admin.medecin.dashboard'));
            } elseif ($utilisateur->role === 'caissier') {
                // Les caissiers vont vers leur dashboard
                return redirect()->intended(route('admin.caissier.dashboard'));
            } elseif ($utilisateur->role === 'laborantin') {
                // Les laborantins vont vers leur page d'examens
                return redirect()->intended(route('admin.laborantin.examens'));
            } elseif ($utilisateur->role === 'receptionniste') {
                // Les réceptionnistes vont vers leur dashboard
                return redirect()->intended(route('admin.receptionniste.dashboard'));
            } elseif ($utilisateur->type_utilisateur === 'patient') {
                // Les patients vont vers leur dashboard
                return redirect()->intended(route('patient.dashboard'));
            } else {
                // Tous les autres utilisateurs (personnel non-admin) vont vers le dashboard admin
                return redirect()->intended(route('admin.dashboard'));
            }
        }

        // Si les identifiants sont incorrects
        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect.',
        ])->withInput();
    }

    /**
     * Rediriger l'utilisateur après une connexion réussie
     */
    protected function authenticated($request, $user)
    {
        // Forcer la mise à jour des permissions du superadmin
        if ($user->isSuperAdmin()) {
            $user->assignAllPermissions();
        }

        // Redirection selon le rôle
        if ($user->role === 'superadmin' || $user->role === 'admin') {
            // Tous les admins (superadmin, hopital_admin, pharmacie_admin, banque_sang_admin)
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'medecin') {
            return redirect()->route('admin.medecin.dashboard');
        } elseif ($user->role === 'caissier') {
            return redirect()->route('admin.caissier.dashboard');
        } elseif ($user->role === 'laborantin') {
            return redirect()->route('admin.laborantin.examens');
        } elseif ($user->role === 'receptionniste') {
            return redirect()->route('admin.receptionniste.dashboard');
        } elseif ($user->type_utilisateur === 'patient') {
            return redirect()->route('patient.dashboard');
        } else {
            // Tous les autres utilisateurs vont vers le dashboard admin
            return redirect()->route('admin.dashboard');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}
