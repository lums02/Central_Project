<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'utilisateurs'; // Nom de la table

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'date_naissance',
        'sexe',
        'adresse',
        'mot_de_passe',
        'role',
        'type_utilisateur',
        'entite_id',
        'hopital_id',
        'groupe_sanguin',
        'status',
        'rejection_reason',
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    public $timestamps = true; // Activé pour la compatibilité avec Spatie

    /**
     * Pour que Laravel sache que le mot de passe est "mot_de_passe"
     */
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    /**
     * Relation avec l'entité (hôpital, pharmacie, banque de sang, etc.)
     */
    public function entite()
    {
        switch ($this->type_utilisateur) {
            case 'hopital':
                return $this->belongsTo(Hopital::class, 'entite_id');
            case 'pharmacie':
                return $this->belongsTo(Pharmacie::class, 'entite_id');
            case 'banque_sang':
                return $this->belongsTo(BanqueSang::class, 'entite_id');
            default:
                return $this->belongsTo(Hopital::class, 'entite_id'); // Par défaut
        }
    }
    
    /**
     * Obtenir le nom de l'entité
     */
    /**
     * Scope pour filtrer par entité de l'utilisateur connecté
     */
    public function scopeOfSameEntity($query)
    {
        $user = auth()->user();
        
        if (!$user || $user->isSuperAdmin()) {
            return $query; // Superadmin voit tout
        }
        
        return $query->where('entite_id', $user->entite_id)
                     ->where('type_utilisateur', $user->type_utilisateur);
    }
    
    /**
     * Scope pour filtrer uniquement par entite_id
     */
    public function scopeOfEntity($query, $entiteId)
    {
        return $query->where('entite_id', $entiteId);
    }
    
    public function getEntiteName()
    {
        if ($this->isSuperAdmin()) {
            return 'CENTRAL+';
        }
        
        // Pour les patients, afficher l'hôpital s'il est choisi
        if ($this->type_utilisateur === 'patient') {
            if ($this->hopital_id) {
                $hopital = \App\Models\Hopital::find($this->hopital_id);
                return $hopital ? $hopital->nom : 'Patient';
            }
            return 'Patient';
        }
        
        $entite = $this->entite;
        return $entite ? $entite->nom : 'Entité inconnue';
    }
    

    /**
     * Exemple de scopes (optionnel mais pratique)
     */
    public function scopeHopital($query)
    {
        return $query->where('role', 'hopital');
    }

    public function scopePharmacie($query)
    {
        return $query->where('role', 'pharmacie');
    }

    public function scopePatient($query)
    {
        return $query->where('role', 'patient');
    }

    /**
     * Vérifier si l'utilisateur a un rôle spécifique
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Vérifier si l'utilisateur a un type spécifique
     */
    public function hasType($type)
    {
        return $this->type_utilisateur === $type;
    }

    // Constantes pour les statuts
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /**
     * Vérifier si l'utilisateur est en attente d'approbation
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Vérifier si l'utilisateur est approuvé
     */
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Vérifier si l'utilisateur est rejeté
     */
    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Approuver l'utilisateur
     */
    public function approve()
    {
        $this->update(['status' => self::STATUS_APPROVED]);
    }

    /**
     * Rejeter l'utilisateur
     */
    public function reject($reason = null)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejection_reason' => $reason
        ]);
    }

    /**
     * Vérifier si l'utilisateur est un superadmin
     */
    public function isSuperAdmin()
    {
        return $this->role === 'superadmin' || $this->email === 'admin@central.com';
    }

    /**
     * Vérifier si l'utilisateur peut être supprimé
     */
    public function canBeDeleted()
    {
        return !$this->isSuperAdmin();
    }

    /**
     * Vérifier si l'utilisateur peut être modifié
     */
    public function canBeModified()
    {
        // Le superadmin peut être modifié mais pas supprimé
        return true;
    }

    /**
     * Attribuer toutes les permissions au superadmin
     */
    public function assignAllPermissions()
    {
        if ($this->isSuperAdmin()) {
            // Permissions spécifiques du superadmin
            $superAdminPermissions = [
                // CRUD sur les entités
                'view_hopital', 'create_hopital', 'edit_hopital', 'delete_hopital',
                'view_pharmacie', 'create_pharmacie', 'edit_pharmacie', 'delete_pharmacie',
                'view_banque_sang', 'create_banque_sang', 'edit_banque_sang', 'delete_banque_sang',
                'view_centre', 'create_centre', 'edit_centre', 'delete_centre',
                
                // CRUD sur les utilisateurs
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'manage_users', 'gérer_les_utilisateurs',
                
                // CRUD sur les rôles et permissions
                'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
                'manage_permissions',
                
                // Permissions de gestion générale
                'view_dashboard', 'view_reports', 'create_reports', 'edit_reports', 'delete_reports'
            ];

            // Créer les permissions si elles n'existent pas
            foreach ($superAdminPermissions as $permissionName) {
                \Spatie\Permission\Models\Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web'
                ]);
            }

            // Récupérer seulement les permissions spécifiques
            $permissions = \Spatie\Permission\Models\Permission::whereIn('name', $superAdminPermissions)->get();
            
            // Supprimer toutes les permissions actuelles et attribuer seulement les spécifiques
            $this->syncPermissions($permissions);
            
            // S'assurer que le rôle superadmin existe
            $superAdminRole = \Spatie\Permission\Models\Role::firstOrCreate([
                'name' => 'superadmin',
                'guard_name' => 'web'
            ]);
            
            $this->assignRole($superAdminRole);
        }
    }

    /**
     * Boot method pour automatiquement attribuer toutes les permissions au superadmin
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($utilisateur) {
            if ($utilisateur->isSuperAdmin()) {
                $utilisateur->assignAllPermissions();
            }
        });

        static::updated(function ($utilisateur) {
            // Ne déclencher que si le rôle a changé vers superadmin
            if ($utilisateur->isSuperAdmin() && $utilisateur->wasChanged('role')) {
                $utilisateur->assignAllPermissions();
            }
        });
    }

    /**
     * Vérifier si l'utilisateur connecté est le superadmin
     */
    public static function isCurrentUserSuperAdmin()
    {
        return auth()->check() && auth()->user()->isSuperAdmin();
    }

    /**
     * Obtenir le superadmin
     */
    public static function getSuperAdmin()
    {
        return static::where('email', 'admin@central.com')->first();
    }

    /**
     * Vérifier si l'utilisateur est le premier de son type d'entité
     */
    public function isFirstOfEntityType()
    {
        return !static::where('type_utilisateur', $this->type_utilisateur)
            ->where('status', 'approved')
            ->where('id', '!=', $this->id)
            ->exists();
    }
    
    /**
     * Vérifier si l'utilisateur est le premier de sa même entité spécifique
     */
    public function isFirstOfEntity()
    {
        return !static::where('entite_id', $this->entite_id)
            ->where('status', 'approved')
            ->where('id', '!=', $this->id)
            ->exists();
    }
    
    // Relation avec les dossiers médicaux (en tant que patient)
    public function dossiers()
    {
        return $this->hasMany(DossierMedical::class, 'patient_id');
    }
    
    // Alias pour dossiers
    public function dossiersMedicaux()
    {
        return $this->hasMany(DossierMedical::class, 'patient_id');
    }
    
    // Relation avec les dossiers médicaux (en tant que médecin)
    public function dossiersMedecin()
    {
        return $this->hasMany(DossierMedical::class, 'medecin_id');
    }

    /**
     * Vérifier si l'utilisateur peut être promu admin
     */
    public function canBePromotedToAdmin()
    {
        // Seulement si c'est le premier de son type d'entité
        return $this->isFirstOfEntityType();
    }
}
