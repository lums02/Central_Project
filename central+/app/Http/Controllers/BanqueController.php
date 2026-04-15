<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BanqueController extends Controller
{
    /**
     * Afficher la page d'accueil des banques de sang
     */
    public function index()
    {
        return view('banque.index');
    }

    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login', ['userType' => 'banque_sang']);
    }

    /**
     * Traiter la connexion des banques de sang
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Vérifier que l'utilisateur est bien une banque de sang
            if ($user->type_utilisateur === 'banque_sang') {
                $request->session()->regenerate();
                return redirect()->intended(route('banque.dashboard'));
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Vous n\'êtes pas autorisé à accéder à l\'espace banque de sang.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ]);
    }

    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegisterForm()
    {
        return view('auth.register', ['userType' => 'banque_sang', 'selectedEntity' => 'banque_sang']);
    }

    /**
     * Traiter l'inscription des banques de sang
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs',
            'adresse' => 'required|string|max:500',
            'password' => 'required|string|min:8|confirmed',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nom.required' => 'Le nom de la banque de sang est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'adresse.required' => 'L\'adresse de la banque de sang est obligatoire.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Ici, tu peux ajouter la logique d'inscription spécifique aux banques de sang
        // Pour l'instant, on redirige vers le formulaire général
        return redirect()->route('register.form', ['type' => 'banque_sang'])
            ->with('info', 'Veuillez utiliser le formulaire d\'inscription général pour créer votre compte banque de sang.');
    }

    /**
     * Afficher le tableau de bord des banques de sang
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        return view('banque.dashboard', compact('user'));
    }

    /**
     * Déconnexion des banques de sang
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('banque.index')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
