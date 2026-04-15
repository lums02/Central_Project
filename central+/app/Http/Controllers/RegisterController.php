<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Hopital;
use App\Models\Utilisateur;
use App\Models\Pharmacie;
use App\Models\BanqueSang;
use App\Models\Centre;

class RegisterController extends Controller
{
    public function showRegistrationForm(Request $request, $entity = null)
    {
        // Déterminer l'entité à partir du paramètre ou de la requête
        $entityTypes = ['hopital', 'pharmacie', 'banque_sang', 'centre', 'patient'];
        
        // Vérifier le paramètre 'type' dans la requête
        $userType = $request->get('type');
        
        // Détection automatique si l'utilisateur vient d'une page spécifique
        if (!$userType) {
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
        
        // Récupérer le plan sélectionné et les paramètres de paiement
        $selectedPlan = $request->get('plan');
        $selectedPeriod = $request->get('period');
        $paymentMethod = $request->get('payment_method');
        $amount = $request->get('amount');
        
        // Debug: Log pour vérifier
        \Log::info('RegisterController - userType: ' . ($userType ?? 'null') . ', plan: ' . ($selectedPlan ?? 'null') . ', period: ' . ($selectedPeriod ?? 'null') . ', payment: ' . ($paymentMethod ?? 'null') . ', amount: ' . ($amount ?? 'null') . ', referer: ' . ($referer ?? 'null'));
        
        if ($userType && in_array($userType, $entityTypes)) {
            $selectedEntity = $userType;
        } elseif ($entity && in_array($entity, $entityTypes)) {
            $selectedEntity = $entity;
        } else {
            $selectedEntity = null;
        }
        
        return view('auth.register', compact('selectedEntity', 'userType', 'selectedPlan', 'selectedPeriod', 'paymentMethod', 'amount'));
    }
public function submit(Request $request)
{
    // 1. Validation dynamique avec règles strictes
    $rules = [
        'type_utilisateur' => 'required|in:hopital,pharmacie,banque_sang,centre,patient',
        'nom' => 'required|string|max:255|min:3',
        'email' => [
            'required',
            'email:rfc,dns',
            'unique:utilisateurs,email',
            'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
        ],
        'password' => [
            'required',
            'string',
            'min:8',
            'confirmed',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/'
        ],
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];

    if (in_array($request->type_utilisateur , ['hopital', 'pharmacie', 'banque_sang', 'centre'])) {
        $rules['adresse'] = 'required|string';
        if ($request->type_utilisateur === 'hopital') {
            $rules['type_hopital'] = 'required|string';
        }
    } elseif ($request->type_utilisateur === 'patient') {
        $rules['date_naissance'] = 'required|date';
        $rules['sexe'] = 'required|in:masculin,feminin';
        $rules['telephone'] = 'nullable|string|max:20';
        $rules['hopital_id'] = 'nullable|exists:hopitaux,id';
        $rules['groupe_sanguin'] = 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-';
    }

    // Messages d'erreur personnalisés en français
    $messages = [
        'nom.required' => 'Le nom est obligatoire.',
        'nom.min' => 'Le nom doit contenir au moins 3 caractères.',
        'email.required' => 'L\'adresse email est obligatoire.',
        'email.email' => 'L\'adresse email doit être valide (ex: exemple@gmail.com).',
        'email.unique' => 'Cette adresse email est déjà utilisée.',
        'email.regex' => 'L\'adresse email doit être au format correct (ex: votremail@gmail.com).',
        'password.required' => 'Le mot de passe est obligatoire.',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        'password.regex' => 'Le mot de passe doit contenir au moins : 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial (@$!%*?&#).',
        'adresse.required' => 'L\'adresse est obligatoire.',
        'date_naissance.required' => 'La date de naissance est obligatoire.',
        'sexe.required' => 'Le sexe est obligatoire.',
    ];

    $validated = $request->validate($rules, $messages);

    DB::beginTransaction();
    try {
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $entite_id = null;
        $type = $validated['type_utilisateur'];

        switch ($type) {
            case 'hopital':
                $entite = Hopital::create([
                    'nom' => $validated['nom'],
                    'email' => $validated['email'],
                    'adresse' => $validated['adresse'],
                    'type_hopital' => $validated['type_hopital'],
                    'nombre_lits' => 200,
                    'logo' => $logoPath,
                ]);
                break;

            case 'pharmacie':
                $entite = Pharmacie::create([
                    'nom' => $validated['nom'],
                    'email' => $validated['email'],
                    'adresse' => $validated['adresse'],
                    'logo' => $logoPath,
                ]);
                break;

            case 'banque_sang':
                $entite = BanqueSang::create([
                    'nom' => $validated['nom'],
                    'email' => $validated['email'],
                    'adresse' => $validated['adresse'],
                    'logo' => $logoPath,
                ]);
                break;

            case 'centre':
                $entite = Centre::create([
                    'nom' => $validated['nom'],
                    'email' => $validated['email'],
                    'adresse' => $validated['adresse'],
                    'logo' => $logoPath,
                ]);
                break;

            case 'patient':
                // Pour les patients, on crée directement l'utilisateur sans entité séparée
                $utilisateur = Utilisateur::create([
                    'nom' => $validated['nom'],
                    'email' => $validated['email'],
                    'mot_de_passe' => Hash::make($validated['password']),
                    'role' => 'patient',
                    'type_utilisateur' => 'patient',
                    'date_naissance' => $validated['date_naissance'],
                    'sexe' => $validated['sexe'],
                    'telephone' => $validated['telephone'] ?? null,
                    'hopital_id' => $validated['hopital_id'] ?? null,
                    'groupe_sanguin' => $validated['groupe_sanguin'] ?? null,
                    'status' => 'approved', // Les patients sont automatiquement approuvés
                ]);
                
                // Assigner le rôle patient
                $utilisateur->assignRole('patient');
                
                DB::commit();
                
                return redirect()->route('login')
                    ->with('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter avec vos identifiants.');

            default:
                throw new \Exception('Type d\'entité non reconnu');
        }

        $entite_id = $entite->id;

        // Vérifier si c'est le premier utilisateur de cette entité spécifique
        $isFirstUserOfEntity = !Utilisateur::where('entite_id', $entite_id)
            ->exists();

        // Déterminer le rôle : admin si premier utilisateur de cette entité, sinon user
        $userRole = $isFirstUserOfEntity ? 'admin' : 'user';

        Utilisateur::create([
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'mot_de_passe' => Hash::make($validated['password']),
            'role' => $userRole,
            'type_utilisateur' => $type,
            'entite_id' => $entite_id,
            'status' => 'pending', // Nouvel utilisateur en attente d'approbation
        ]);

        DB::commit();

        $roleMessage = $isFirstUserOfEntity 
            ? 'Inscription réussie ! Vous êtes le premier utilisateur de cette entité et serez administrateur. Votre compte est en attente d\'approbation.'
            : 'Inscription réussie ! Votre compte est en attente d\'approbation par l\'administrateur.';

        return redirect()->route('login')->with('success', $roleMessage);
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Erreur : ' . $e->getMessage()])->withInput();
    }
}


}
