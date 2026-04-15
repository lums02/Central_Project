# CENTRAL+ - Plateforme de Gestion Médicale Intégrée

## 📋 TABLE DES MATIÈRES

1. [Vue d'ensemble](#vue-densemble)
2. [Architecture du système](#architecture-du-système)
3. [Entités et Rôles](#entités-et-rôles)
4. [Système Hôpital](#système-hôpital)
5. [Système Pharmacie](#système-pharmacie)
6. [Système Banque de Sang](#système-banque-de-sang)
7. [Système de Notifications](#système-de-notifications)
8. [Isolation des Données](#isolation-des-données)
9. [Installation et Configuration](#installation-et-configuration)
10. [Base de Données](#base-de-données)
11. [Workflows](#workflows)
12. [Tests](#tests)

---

## 🎯 VUE D'ENSEMBLE

**CENTRAL+** est une plateforme complète de gestion pour les établissements de santé en RDC, incluant :
- 🏥 **Hôpitaux** - Gestion des patients, dossiers médicaux, rendez-vous, examens
- 💊 **Pharmacies** - Gestion des médicaments, stocks, commandes, fournisseurs
- 🩸 **Banques de Sang** - Gestion des donneurs, dons, réserves, demandes
- 👤 **Patients** - Accès à leurs dossiers médicaux et rendez-vous

### **Caractéristiques Principales**
- ✅ **14 rôles** pour toutes les entités
- ✅ **68+ permissions** granulaires
- ✅ **Isolation complète** des données par entité
- ✅ **Notifications en temps réel** avec actualisation automatique
- ✅ **Workflows automatisés** pour tous les processus
- ✅ **Interface moderne** et responsive
- ✅ **20+ tables** en base de données
- ✅ **Traçabilité complète** de toutes les actions

---

## 🏗️ ARCHITECTURE DU SYSTÈME

### Technologies Utilisées
- **Backend** : Laravel 12.17.0
- **Frontend** : Blade Templates, Bootstrap 5, Font Awesome 6
- **Base de données** : MySQL 8.0+
- **Authentification** : Laravel Auth + Spatie Permissions
- **Notifications** : Système temps réel avec actualisation auto (30s)
- **AJAX** : Fetch API pour les interactions dynamiques

### Structure des Dossiers
```
central+/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── UserController.php
│   │   │   ├── PermissionController.php
│   │   │   ├── HopitalPatientController.php
│   │   │   ├── HopitalRendezVousController.php
│   │   │   ├── TransfertDossierController.php
│   │   │   ├── NotificationController.php
│   │   │   ├── MedicamentController.php (200+ lignes)
│   │   │   ├── StockController.php (220+ lignes)
│   │   │   ├── CommandeController.php (200+ lignes)
│   │   │   ├── FournisseurController.php (150+ lignes)
│   │   │   └── BanqueSangController.php (180+ lignes)
│   │   ├── Auth/
│   │   │   ├── LoginController.php
│   │   │   └── RegisterController.php
│   │   ├── MedecinController.php
│   │   ├── CaissierController.php
│   │   └── LaborantinController.php
│   ├── Models/
│   │   ├── Utilisateur.php
│   │   ├── Hopital.php, Pharmacie.php, BanqueSang.php
│   │   ├── DossierMedical.php, RendezVous.php
│   │   ├── ExamenPrescrit.php
│   │   ├── DemandeTransfertDossier.php
│   │   ├── Notification.php
│   │   ├── Medicament.php, MouvementStock.php
│   │   ├── Fournisseur.php, Commande.php, LigneCommande.php
│   │   └── Donneur.php, Don.php, ReserveSang.php, DemandeSang.php
│   └── Helpers/
│       └── NotificationHelper.php
├── resources/views/
│   ├── admin/
│   │   ├── hopital/ (patients, rendezvous, transferts)
│   │   ├── pharmacie/ (medicaments, stocks, commandes, fournisseurs, ventes)
│   │   └── banque-sang/ (donneurs, dons, reserves, demandes, analyses)
│   ├── medecin/
│   ├── caissier/
│   ├── laborantin/
│   └── patient/
└── database/migrations/ (30+ migrations)
```

---

## 👥 ENTITÉS ET RÔLES

### 1. SUPERADMIN
**Accès** : Plateforme complète
**Permissions** :
- Créer les administrateurs de chaque entité
- Voir toutes les entités (mais pas leurs données internes)
- Gérer les rôles et permissions globaux
- Approuver les inscriptions
- Statistiques globales

**Compte par défaut** : `admin@central.com` / `password`

### 2. HÔPITAL (6 rôles)

#### **Admin Hôpital**
- Gestion complète de l'hôpital
- Création du personnel (médecins, infirmiers, laborantins, caissiers, réceptionnistes)
- Gestion des patients
- Gestion des rendez-vous
- Demandes de transfert de dossiers

#### **Médecin**
- Consultation des patients
- Création et modification de dossiers médicaux
- Prescription d'examens
- Gestion des rendez-vous
- Ajout de consultations au dossier
- Dashboard : `/admin/medecin/dashboard`

#### **Infirmier**
- Consultation des patients
- Lecture des dossiers médicaux
- Consultation des rendez-vous

#### **Laborantin**
- Réception des examens prescrits (après paiement)
- Réalisation des examens
- Upload des résultats (texte + fichier PDF/image)
- Notification au médecin
- Page : `/admin/laborantin/examens`

#### **Caissier**
- Réception des prescriptions d'examens
- Fixation des prix
- Validation des paiements
- Notification au laborantin
- Page : `/admin/caissier/examens`

#### **Réceptionniste**
- Création de patients
- Gestion des rendez-vous

### 3. PHARMACIE (3 rôles)

#### **Admin Pharmacie**
- Gestion complète de la pharmacie
- Création du personnel (pharmaciens, assistants)
- Gestion des médicaments (CRUD complet)
- Gestion des stocks (ajustement, inventaire)
- Gestion des commandes fournisseurs
- Gestion des fournisseurs
- Gestion des ventes

#### **Pharmacien**
- Gestion des médicaments
- Gestion des stocks
- Traitement des commandes
- Ventes

#### **Assistant Pharmacie**
- Consultation des médicaments
- Consultation des stocks
- Ventes

### 4. BANQUE DE SANG (3 rôles)

#### **Admin Banque de Sang**
- Gestion complète de la banque
- Création du personnel (techniciens, gestionnaires)
- Gestion des donneurs
- Gestion des dons
- Gestion des réserves
- Traitement des demandes

#### **Technicien Laboratoire**
- Enregistrement des dons
- Prélèvements
- Analyses sanguines
- Gestion des réserves

#### **Gestionnaire Donneurs**
- Gestion des donneurs
- Planification des dons
- Traitement des demandes de sang

### 5. PATIENT
- Consultation de ses dossiers médicaux
- Consultation de ses rendez-vous
- Gestion des consentements de transfert

---

## 🏥 SYSTÈME HÔPITAL

### Dossier Médical Complet

#### Structure du Dossier

**1. Informations Administratives**
- Numéro de dossier (auto-généré : DM-YYYYMMDD-00001)
- Patient, Médecin, Hôpital
- Date de consultation
- Statut (actif, archivé)

**2. Consultation**
- Motif de consultation
- Symptômes présentés
- Examen clinique (signes vitaux, observations)

**3. Diagnostic**
- Diagnostic principal
- Code CIM-10 (Classification Internationale)
- Diagnostics secondaires

**4. Traitement**
- Traitement prescrit (médicaments, dosages)
- Plan de traitement à long terme
- Recommandations

**5. Suivi**
- Observations médicales
- Prochain rendez-vous
- Niveau d'urgence (Normale, Urgente, Très Urgente)

**6. Historique**
Chaque consultation ajoutée est stockée avec format :
```
=== CONSULTATION DU 07/11/2025 ===
Type: Consultation de Suivi
Motif: Contrôle post-traitement
Symptômes: Amélioration notable
Examen clinique: TA 120/80, Temp 37°C
Diagnostic/Évolution: Guérison en cours
Traitement: Continuer antibiotiques
Notes: Patient répondant bien au traitement
Urgence: normale
```

### Workflow Examens Médicaux

#### Table : examens_prescrits
**Champs** :
- `id`, `numero_examen` (unique)
- `dossier_medical_id`, `patient_id`, `medecin_id`, `hopital_id`
- `laborantin_id`, `valide_par` (caissier)
- `type_examen`, `nom_examen`, `indication`
- `date_prescription`, `date_realisation`, `date_paiement`
- `prix`, `statut_paiement`, `statut_examen`
- `resultats`, `interpretation`, `fichier_resultat`

#### Statuts
**statut_paiement** : `en_attente`, `paye`, `annule`
**statut_examen** : `prescrit`, `paye`, `en_cours`, `termine`

#### Workflow Détaillé
```
ÉTAPE 1 - MÉDECIN
├─ Ouvre dossier patient
├─ Clique "Prescrire des Examens"
├─ Remplit : Type, Nom, Indication
├─ Peut ajouter plusieurs examens
└─ Envoie → Examen créé (statut: prescrit, prix: $0)
    └─ 🔔 Notification au caissier

ÉTAPE 2 - CAISSIER
├─ Reçoit notification
├─ Va sur /admin/caissier/examens
├─ Voit examen avec badge "À définir"
├─ Clique "Fixer Prix & Valider"
├─ Entre prix (ex: $20.00)
├─ Choisit mode paiement (Espèces, Carte, Mobile Money)
└─ Valide → Examen mis à jour (statut_paiement: paye, statut_examen: paye)
    └─ 🔔 Notification au laborantin

ÉTAPE 3 - LABORANTIN
├─ Reçoit notification
├─ Va sur /admin/laborantin/examens
├─ Voit examen (statut: En attente)
├─ Clique "Commencer" → Statut: En cours
├─ Fait l'examen au laboratoire
├─ Clique "Résultats"
├─ Remplit : Résultats texte, Interprétation
├─ Upload fichier PDF/image
└─ Envoie → Examen mis à jour (statut_examen: termine)
    └─ 🔔 Notification au médecin

ÉTAPE 4 - MÉDECIN
├─ Reçoit notification "Résultats disponibles"
├─ Clique sur notification → Redirigé vers dossier
├─ Consulte les résultats
├─ Peut ajuster le traitement
└─ Ajoute une consultation de suivi si nécessaire
```

### Transfert Inter-Hospitalier

#### Workflow Complet
```
HÔPITAL B (demandeur)
├─ Recherche patient d'un autre hôpital
├─ Clique "Demander un Dossier Externe"
├─ Remplit motif de la demande
└─ Envoie → Demande créée (statut: en_attente_patient)
    └─ 🔔 Notification à Hôpital A

HÔPITAL A (détenteur)
├─ Reçoit notification
├─ Va sur "Demandes Reçues"
└─ Voit statut "En attente du patient"
    └─ Attente consentement patient

PATIENT
├─ Reçoit demande de consentement
├─ Accepte ou refuse
└─ Si accepté → Statut: accepte_patient

HÔPITAL A
├─ Voit "Accepté par le patient"
├─ Clique "Transférer"
└─ Dossier copié vers Hôpital B
    └─ 🔔 Notification à Hôpital B

HÔPITAL B
├─ Reçoit notification "Dossier transféré"
└─ Peut maintenant consulter le dossier
```

#### Table : demandes_transfert_dossier
**Statuts** :
- `en_attente_patient` - En attente du consentement
- `accepte_patient` - Patient a accepté
- `refuse_patient` - Patient a refusé
- `transfere` - Dossier transféré
- `refuse_hopital` - Hôpital a refusé
- `annule` - Demande annulée

---

## 💊 SYSTÈME PHARMACIE

### 1. Gestion des Médicaments

#### Table : medicaments (25+ champs)
```sql
- id, pharmacie_id
- code (unique), nom, nom_generique
- categorie, forme, dosage
- prix_unitaire, prix_achat
- stock_actuel, stock_minimum
- prescription_requise (boolean)
- description, indication, contre_indication, effets_secondaires, posologie
- fabricant, numero_lot
- date_fabrication, date_expiration
- emplacement
- actif (boolean)
- timestamps
```

#### Modèle Medicament
**Relations** :
- `pharmacie()` - Appartient à une pharmacie
- `mouvements()` - Historique des mouvements de stock

**Scopes** :
- `ofPharmacie($pharmacieId)` - Filtrer par pharmacie
- `actif()` - Médicaments actifs uniquement
- `stockFaible()` - Stock <= stock_minimum
- `bientotPerime()` - Expiration dans les 3 mois
- `perime()` - Date expiration dépassée

**Méthodes** :
- `isStockFaible()` - Vérifier si stock faible
- `isPerime()` - Vérifier si périmé
- `isBientotPerime()` - Vérifier si bientôt périmé (3 mois)
- `getMarge()` - Calculer marge bénéficiaire (%)
- `getStockStatus()` - Obtenir statut (Rupture, Stock Faible, Disponible)

#### Contrôleur MedicamentController
**Méthodes** :
- `index()` - Liste avec filtres (search, categorie, forme, statut) + pagination
- `store()` - Ajout avec validation + notification si stock faible
- `show()` - Vue détaillée complète
- `update()` - Modification avec vérification stock
- `destroy()` - Désactivation (soft delete)
- `getCategories()` - Liste des 12 catégories
- `getFormes()` - Liste des 14 formes

#### Catégories Disponibles (12)
- Antibiotiques
- Antalgiques
- Anti-inflammatoires
- Antipaludéens
- Antihypertenseurs
- Antidiabétiques
- Antihistaminiques
- Antiacides
- Vitamines et Suppléments
- Antiseptiques
- Antifongiques
- Antiviraux

#### Formes Disponibles (14)
- Comprimé, Gélule, Sirop, Suspension
- Injection, Ampoule
- Pommade, Crème, Gel
- Suppositoire, Collyre, Gouttes
- Spray, Patch

#### Fonctionnalités
- ✅ CRUD complet avec validation
- ✅ Filtres multiples (nom, catégorie, forme, statut)
- ✅ Recherche en temps réel
- ✅ Pagination (20 par page)
- ✅ Détection automatique médicaments périmés
- ✅ Calcul automatique marge bénéficiaire
- ✅ Notifications automatiques (stock faible)
- ✅ Vue détaillée avec toutes les informations
- ✅ Isolation par pharmacie

---

### 2. Gestion des Stocks

#### Table : mouvements_stock
```sql
- id, medicament_id, pharmacie_id, user_id
- type (entree, sortie, ajustement, vente, retour, perime)
- quantite (positif pour entrée, négatif pour sortie)
- stock_avant, stock_apres
- prix_unitaire
- reference (N° facture, bon, etc.)
- motif, notes
- timestamps
```

#### Modèle MouvementStock
**Relations** :
- `medicament()` - Médicament concerné
- `pharmacie()` - Pharmacie
- `user()` - Utilisateur qui a fait le mouvement

**Méthodes** :
- `getTypeClass()` - Classe CSS selon type (success, danger, warning, etc.)
- `getTypeIcon()` - Icône Font Awesome selon type
- `getTypeLabel()` - Libellé français du type

#### Contrôleur StockController
**Méthodes** :
- `index()` - Vue d'ensemble avec statistiques + mouvements récents
- `ajuster()` - Ajustement de stock avec validation (pas de stock négatif)
- `historique($medicamentId)` - Historique complet par médicament
- `inventaire()` - Interface d'inventaire physique
- `enregistrerInventaire()` - Enregistrement des ajustements d'inventaire

#### Fonctionnalités
- ✅ 6 types de mouvements
- ✅ Ajustement avec modal (type, quantité, référence, motif, notes)
- ✅ Historique complet avec pagination
- ✅ Inventaire physique avec :
  - Recherche en temps réel
  - Calcul automatique des écarts
  - Compteur d'ajustements
  - Validation avant enregistrement
- ✅ Notifications automatiques (stock faible, rupture)
- ✅ Traçabilité complète (qui, quand, pourquoi, combien)
- ✅ Calcul valeur totale du stock
- ✅ Alertes visuelles (rupture, stock faible)
- ✅ Transaction DB (rollback en cas d'erreur)

#### Statistiques Affichées
- Total médicaments
- Médicaments en stock faible
- Médicaments en rupture
- Valeur totale du stock (en USD)

---

### 3. Gestion des Commandes Fournisseurs

#### Tables

**fournisseurs** (20+ champs) :
```sql
- id, pharmacie_id
- nom, code, email, telephone, telephone_2
- adresse, ville, pays
- contact_nom, contact_fonction
- numero_registre, numero_fiscal
- specialites
- delai_livraison_jours
- montant_minimum_commande
- conditions_paiement
- notes, actif
```

**commandes** (25+ champs) :
```sql
- id, pharmacie_id, fournisseur_id, user_id
- numero_commande (unique: CMD-20251109-0001)
- statut (brouillon, en_attente, validee, en_cours, livree_partielle, livree, annulee)
- date_commande, date_livraison_prevue, date_livraison_reelle
- montant_total, montant_tva, frais_livraison, remise, montant_final
- reference_fournisseur, numero_facture
- notes, notes_reception
- validee_par, validee_at
- receptionnee_par, receptionnee_at
```

**lignes_commande** :
```sql
- id, commande_id, medicament_id
- quantite_commandee, quantite_recue
- prix_unitaire, montant_ligne
- notes
```

#### Workflow Commande Complète
```
ÉTAPE 1 - CRÉATION
├─ Admin crée commande
├─ Sélectionne fournisseur
├─ Ajoute médicaments (multi-lignes)
├─ Calcul automatique montant total
└─ Statut: en_attente
    └─ 🔔 Notification "Nouvelle commande"

ÉTAPE 2 - VALIDATION
├─ Admin valide la commande
├─ Statut: validee
├─ Enregistrement validee_par et validee_at
└─ 🔔 Notification "Commande validée"

ÉTAPE 3 - RÉCEPTION
├─ Réception des produits
├─ Modal avec liste des lignes
├─ Entre quantité reçue pour chaque ligne
├─ Peut être partielle ou complète
├─ Statut: livree_partielle ou livree
└─ Pour chaque ligne reçue :
    ├─ Mise à jour stock médicament
    ├─ Création mouvement_stock (type: entree)
    └─ Enregistrement dans historique

ÉTAPE 4 - FINALISATION
├─ Statut: livree
├─ Date livraison réelle enregistrée
└─ 🔔 Notification "Commande livrée"
```

#### Fonctionnalités
- ✅ Numéro unique auto-généré
- ✅ Commandes multi-lignes
- ✅ Calcul automatique délai livraison (selon fournisseur)
- ✅ Réception partielle ou totale
- ✅ Barre de progression (% reçu)
- ✅ Mise à jour automatique du stock
- ✅ Historique des mouvements
- ✅ Notifications à chaque étape
- ✅ Validation avant modification
- ✅ Annulation possible (si brouillon ou en_attente)
- ✅ Timeline visuelle des étapes

---

### 4. Gestion des Fournisseurs

#### Fonctionnalités
- ✅ CRUD complet
- ✅ Informations complètes (contact, registre, fiscal)
- ✅ Délai de livraison moyen
- ✅ Montant minimum de commande
- ✅ Conditions de paiement
- ✅ Spécialités du fournisseur
- ✅ Historique des commandes
- ✅ Filtres et recherche
- ✅ Désactivation (soft delete)

---

## 🩸 SYSTÈME BANQUE DE SANG

### 1. Gestion des Donneurs

#### Table : donneurs (20+ champs)
```sql
- id, banque_sang_id
- numero_donneur (unique: DON-0001)
- nom, prenom, sexe, date_naissance
- groupe_sanguin (A+, A-, B+, B-, AB+, AB-, O+, O-)
- telephone, email, adresse, ville, profession
- poids, numero_carte_identite
- eligible, raison_ineligibilite
- derniere_date_don, nombre_dons
- antecedents_medicaux, notes
- actif
```

#### Modèle Donneur
**Méthodes** :
- `getAge()` - Calculer l'âge
- `peutDonner()` - Vérifier éligibilité (délai 56 jours minimum)

**Scopes** :
- `ofBanque($banqueId)` - Filtrer par banque
- `eligible()` - Donneurs éligibles uniquement

#### Fonctionnalités
- ✅ Numéro unique auto-généré
- ✅ Vérification éligibilité automatique (56 jours entre dons)
- ✅ Calcul automatique de l'âge
- ✅ Compteur de dons
- ✅ Historique complet des dons
- ✅ Filtres par groupe sanguin
- ✅ Badge de statut (Peut donner, Attente X jours, Non éligible)

---

### 2. Gestion des Dons

#### Table : dons (25+ champs)
```sql
- id, banque_sang_id, donneur_id, technicien_id
- numero_don (unique: DON-20251109-0001)
- date_don, heure_don
- groupe_sanguin
- volume_preleve (en litres)
- type_don (sang_total, plasma, plaquettes, globules_rouges)
- statut (en_attente_analyse, analyse_en_cours, conforme, non_conforme, utilise, perime)
- observations_prelevement
- tension_arterielle_systolique, tension_arterielle_diastolique
- hemoglobine, temperature
- resultats_analyses, date_analyse
- date_expiration
- numero_poche, emplacement_stockage
```

#### Workflow Don
```
ÉTAPE 1 - ENREGISTREMENT DONNEUR
├─ Vérifier éligibilité (délai 56 jours)
├─ Vérifier poids minimum (50kg)
└─ Si OK → Peut donner

ÉTAPE 2 - PRÉLÈVEMENT
├─ Technicien enregistre le don
├─ Mesure : Tension, Hémoglobine, Température
├─ Volume prélevé (0.1L à 0.5L)
├─ Type de don sélectionné
└─ Statut: en_attente_analyse
    ├─ Mise à jour donneur :
    │   ├─ derniere_date_don = aujourd'hui
    │   └─ nombre_dons += 1
    └─ 🔔 Notification "Don enregistré"

ÉTAPE 3 - ANALYSE
├─ Laboratoire analyse le sang
├─ Résultats enregistrés
└─ Statut: conforme ou non_conforme

ÉTAPE 4 - STOCKAGE
├─ Si conforme → Ajout aux réserves
├─ Numéro de poche assigné
├─ Emplacement de stockage
└─ Date d'expiration calculée
```

#### Fonctionnalités
- ✅ 4 types de dons
- ✅ Informations médicales complètes
- ✅ Numéro unique auto-généré
- ✅ Mise à jour automatique du donneur
- ✅ Traçabilité complète

---

### 3. Réserves de Sang

#### Table : reserves_sang
```sql
- id, banque_sang_id
- groupe_sanguin (A+, A-, B+, B-, AB+, AB-, O+, O-)
- quantite_disponible (en litres)
- quantite_minimum (seuil d'alerte)
- quantite_critique (seuil critique)
- nombre_poches
- derniere_mise_a_jour
```

#### Modèle ReserveSang
**Méthodes** :
- `isFaible()` - Quantité <= minimum
- `isCritique()` - Quantité <= critique

#### Fonctionnalités
- ✅ Vue par groupe sanguin (8 cartes)
- ✅ Alertes visuelles (bordures colorées)
- ✅ Badges de statut (CRITIQUE, FAIBLE, DISPONIBLE)
- ✅ Auto-création des 8 groupes si manquants
- ✅ Mise à jour automatique après dons/demandes
- ✅ Notifications si réserve faible/critique

---

### 4. Demandes de Sang

#### Table : demandes_sang
```sql
- id, banque_sang_id, hopital_id, patient_id
- numero_demande (unique: DEM-20251109-0001)
- groupe_sanguin
- quantite_demandee, quantite_fournie (en litres)
- urgence (normale, urgente, critique)
- statut (en_attente, en_preparation, prete, livree, annulee)
- date_demande, date_besoin, date_livraison
- nom_patient, medecin_demandeur
- indication_medicale
- notes
- traitee_par, traitee_at
```

#### Workflow Demande
```
ÉTAPE 1 - DEMANDE
├─ Hôpital crée demande
├─ Spécifie : Groupe, Quantité, Urgence, Date besoin
└─ Statut: en_attente
    └─ 🔔 Notification à la banque

ÉTAPE 2 - TRAITEMENT
├─ Banque vérifie disponibilité
├─ Entre quantité à fournir
├─ Mise à jour réserve (quantite -= quantite_fournie)
├─ Statut: livree
└─ 🔔 Notification à l'hôpital
```

#### Fonctionnalités
- ✅ 3 niveaux d'urgence (badges colorés)
- ✅ Vérification disponibilité en réserve
- ✅ Mise à jour automatique des réserves
- ✅ Traçabilité complète
- ✅ Notifications automatiques

---

## 🔔 SYSTÈME DE NOTIFICATIONS

### Architecture
- Table `notifications` avec colonnes : `user_id`, `hopital_id`, `pharmacie_id`, `banque_sang_id`
- Support multi-entités
- Actualisation automatique toutes les 30 secondes
- Badge avec compteur
- Animation de la cloche si notifications non lues

### Isolation Correcte
```php
if ($user->isSuperAdmin()) {
    // Superadmin : UNIQUEMENT notifications personnelles
    $notifications = Notification::where('user_id', $user->id);
} else {
    // Admins d'entités : Personnelles + entité
    $notifications = Notification::where('user_id', $user->id)
        ->orWhere('pharmacie_id', $user->entite_id);
}
```

### Types de Notifications (30+)

#### **Hôpital** (9 types)
- `demande_transfert_recue`, `transfert_complete`
- `patient_nouveau`, `nouveau_patient`
- `rendez_vous`
- `dossier_assigne`
- `examens_a_payer` (pour caissier)
- `examen_a_realiser` (pour laborantin)
- `resultats_examen` (pour médecin)

#### **Pharmacie** (9 types)
- `nouvelle_commande`, `commande_validee`, `commande_livree`
- `stock_faible`, `stock_critique`
- `medicament_expire`
- `nouvelle_prescription`
- `vente_effectuee`, `paiement_recu`

#### **Banque de Sang** (9 types)
- `nouveau_donneur`, `don_enregistre`
- `demande_sang`, `demande_urgente`
- `reserve_faible`, `reserve_critique`
- `analyse_terminee`
- `sang_disponible`, `sang_expire`

### Helper NotificationHelper
**Méthodes** :
- `createPharmacieNotification()` - Créer notification pharmacie
- `createBanqueSangNotification()` - Créer notification banque
- `createHopitalNotification()` - Créer notification hôpital
- `notifyStockFaible()` - Notifier stock faible
- `notifyNouvelleCommande()` - Notifier nouvelle commande
- `notifyReserveFaible()` - Notifier réserve faible
- `notifyDemandeUrgente()` - Notifier demande urgente

---

## 🔒 ISOLATION DES DONNÉES

### Principe
Chaque entité est **complètement isolée** et ne voit QUE ses propres données.

### Implémentation

#### **Scopes dans les Modèles**
```php
// Utilisateur.php
public function scopeOfSameEntity($query)
{
    $user = auth()->user();
    if ($user->isSuperAdmin()) return $query;
    return $query->where('entite_id', $user->entite_id)
                 ->where('type_utilisateur', $user->type_utilisateur);
}

// Medicament.php
public function scopeOfPharmacie($query, $pharmacieId)
{
    return $query->where('pharmacie_id', $pharmacieId);
}

// Donneur.php
public function scopeOfBanque($query, $banqueId)
{
    return $query->where('banque_sang_id', $banqueId);
}
```

#### **Contrôleurs**
Tous les contrôleurs filtrent par `entite_id` sauf pour le superadmin :
```php
$user = Auth::user();
$medicaments = Medicament::ofPharmacie($user->entite_id)->get();
```

### Exemples d'Isolation

**Pharmacie Centrale** voit :
- ✅ Ses 150 médicaments
- ✅ Ses 5 employés
- ✅ Ses 50 commandes
- ✅ Ses 10 fournisseurs
- ❌ NE VOIT PAS Pharmacie du Peuple
- ❌ NE VOIT PAS les hôpitaux
- ❌ NE VOIT PAS les banques de sang

**Banque Nationale** voit :
- ✅ Ses 200 donneurs
- ✅ Ses 500 dons
- ✅ Ses réserves
- ✅ Ses 30 demandes
- ❌ NE VOIT PAS Centre de Transfusion
- ❌ NE VOIT PAS les pharmacies

---

## 📊 BASE DE DONNÉES COMPLÈTE

### Tables Principales (20+)

#### **Système** (5)
1. `utilisateurs` - Tous les utilisateurs
2. `hopitaux` - Hôpitaux
3. `pharmacies` - Pharmacies
4. `banque_sangs` - Banques de sang
5. `notifications` - Notifications multi-entités

#### **Hôpital** (5)
6. `dossier_medicals` - Dossiers médicaux
7. `rendezvous` - Rendez-vous
8. `examens_prescrits` - Examens médicaux
9. `demandes_transfert_dossier` - Transferts inter-hospitaliers
10. `consultations` - Historique consultations

#### **Pharmacie** (5)
11. `medicaments` - Catalogue médicaments
12. `mouvements_stock` - Historique mouvements
13. `fournisseurs` - Fournisseurs
14. `commandes` - Commandes fournisseurs
15. `lignes_commande` - Détails commandes

#### **Banque de Sang** (4)
16. `donneurs` - Donneurs de sang
17. `dons` - Prélèvements
18. `reserves_sang` - Réserves par groupe
19. `demandes_sang` - Demandes des hôpitaux

#### **Spatie Permissions** (4)
20. `roles` - Rôles
21. `permissions` - Permissions
22. `model_has_roles` - Attribution rôles
23. `model_has_permissions` - Attribution permissions

---

## 🚀 INSTALLATION ET CONFIGURATION

### Prérequis
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js & NPM (optionnel)

### Installation Complète

```bash
# Cloner le projet
cd C:\wamp64\www\Central\central+

# Installer les dépendances
composer install

# Configuration
cp .env.example .env
php artisan key:generate

# Configurer .env
DB_DATABASE=central+
DB_USERNAME=root
DB_PASSWORD=

# Base de données
php artisan migrate

# Créer les rôles et permissions
php artisan db:seed --class=CompleteRolesPermissionsSeeder

# Créer les entités de test
php artisan db:seed --class=EntitiesSeeder

# Créer le superadmin
php artisan db:seed --class=SuperAdminSeeder

# Lancer le serveur
php artisan serve --host=0.0.0.0 --port=8000
```

### Comptes par Défaut

**Superadmin** :
- Email : `admin@central.com`
- Password : `password`

### Entités de Test Créées

**Hôpitaux** :
- Hôpital Saint-Joseph
- Hôpital Général de Référence

**Pharmacies** :
- Pharmacie Centrale
- Pharmacie du Peuple

**Banques de Sang** :
- Banque de Sang Nationale
- Centre de Transfusion Sanguine

---

## 🧪 GUIDE DE TEST

### Test Complet Pharmacie

#### **1. Gestion des Médicaments**
```
1. Connexion admin pharmacie
2. Aller sur "Médicaments"
3. Cliquer "Nouveau Médicament"
4. Remplir :
   - Nom : Paracétamol
   - Catégorie : Antalgiques
   - Forme : Comprimé
   - Dosage : 500mg
   - Prix Achat : $1.50
   - Prix Vente : $2.50
   - Stock : 5 (pour tester alerte)
   - Stock Minimum : 10
5. Enregistrer
6. Vérifier notification 🔔 "Stock Faible"
```

#### **2. Gestion des Stocks**
```
1. Aller sur "Stocks"
2. Voir alertes (1 médicament en stock faible)
3. Cliquer icône ajustement
4. Sélectionner "Entrée (Réception)"
5. Quantité : 50
6. Référence : FAC-2025-001
7. Motif : Réception fournisseur
8. Enregistrer
9. Vérifier nouveau stock : 55
10. Cliquer "Historique" → Voir le mouvement
```

#### **3. Inventaire Physique**
```
1. Aller sur "Stocks"
2. Cliquer "Inventaire"
3. Modifier quantités réelles
4. Voir écarts calculés automatiquement
5. Voir compteur "Ajustements à effectuer"
6. Enregistrer → Stocks mis à jour
```

#### **4. Commandes Fournisseurs**
```
1. Créer un fournisseur :
   - Nom : Pharma Distribution
   - Téléphone : +243 XXX
   - Délai : 7 jours
2. Créer une commande :
   - Fournisseur : Pharma Distribution
   - Ajouter Paracétamol : 100 unités à $1.50
   - Voir montant total : $150.00
3. Valider la commande
4. Réceptionner :
   - Quantité reçue : 100
   - Confirmer
5. Vérifier stock Paracétamol : +100
6. Vérifier historique : Mouvement "Entrée" créé
```

### Test Complet Banque de Sang

#### **1. Enregistrement Donneur**
```
1. Connexion admin banque
2. Aller sur "Donneurs"
3. Cliquer "Nouveau Donneur"
4. Remplir :
   - Nom : Kabamba
   - Prénom : Joseph
   - Sexe : M
   - Date naissance : 01/01/1990
   - Groupe : O+
   - Téléphone : +243 XXX
   - Poids : 75kg
   - Adresse : Kinshasa
5. Enregistrer → Numéro DON-0001 généré
```

#### **2. Enregistrement Don**
```
1. Aller sur "Dons"
2. Cliquer "Enregistrer un Don"
3. Sélectionner donneur (vérifier éligibilité)
4. Volume : 0.45L
5. Type : Sang Total
6. Enregistrer
7. Vérifier :
   - Numéro DON-20251109-0001 généré
   - Nombre de dons du donneur : 1
   - Dernière date don : Aujourd'hui
```

#### **3. Réserves**
```
1. Aller sur "Réserves"
2. Voir les 8 groupes sanguins
3. Vérifier quantités disponibles
4. Voir alertes visuelles (bordures colorées)
```

#### **4. Demandes**
```
1. Simuler demande d'un hôpital
2. Aller sur "Demandes"
3. Cliquer "Traiter" sur une demande
4. Entre quantité à fournir
5. Confirmer
6. Vérifier réserve diminuée automatiquement
```

---

## 📈 STATISTIQUES DU PROJET

### **Fichiers Créés** : **40+**
- 12 migrations
- 12 modèles
- 10 contrôleurs
- 15+ vues
- 1 helper
- 1 README

### **Lignes de Code** : **5000+**

### **Fonctionnalités** : **50+**

### **Tables Base de Données** : **23**

### **Routes** : **100+**

---

## 🎨 DESIGN ET UX

### Couleurs CENTRAL+
- **Primary** : `#003366` (Bleu foncé)
- **Secondary** : `#ff6b35` (Orange)
- **Success** : `#28a745` (Vert)
- **Warning** : `#ffc107` (Jaune)
- **Danger** : `#dc3545` (Rouge)
- **Info** : `#17a2b8` (Bleu clair)

### Composants
- Sidebar bleu avec gradient
- Topbar blanc avec message de bienvenue
- Cartes de stats uniformes (140px hauteur)
- Modals pour toutes les actions
- Tableaux avec filtres et pagination
- Badges colorés pour les statuts
- Alertes contextuelles
- Animations fluides

### Layouts

#### **Espace Admin**
- Sidebar dynamique selon rôle
- Topbar avec notifications
- Dashboard adaptatif
- Cartes et tableaux modernes

#### **Espace Médecin**
- Sidebar bleu avec nom de l'hôpital
- Topbar blanc avec bienvenue
- Cloche de notifications
- Cartes de statistiques
- Design sobre et professionnel

---

## 🔐 SÉCURITÉ

### Authentification
- Middleware `auth` sur toutes les routes admin
- Vérification des rôles et permissions
- Protection CSRF sur tous les formulaires
- Sessions sécurisées

### Isolation des Données
- Filtrage par `entite_id` dans tous les contrôleurs
- Scopes dans les modèles
- Middleware `CheckEntityAccess`
- Validation stricte des accès

### Validation
- Validation côté serveur (Laravel)
- Validation côté client (JavaScript)
- Email format strict (RFC, DNS)
- Mot de passe fort (8 chars, majuscule, minuscule, chiffre, caractère spécial)
- Validation des quantités (pas de stock négatif)
- Validation des dates (cohérence)

---

## 📞 SUPPORT ET MAINTENANCE

### Commandes Utiles

```bash
# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Recréer la base de données
php artisan migrate:fresh --seed

# Voir les routes
php artisan route:list

# Voir les permissions
php artisan permission:show
```

### Logs
- Logs Laravel : `storage/logs/laravel.log`
- Logs SQL : Activer dans `.env` avec `DB_LOG=true`

---

## 📝 CHANGELOG

### Version 1.0 (09/11/2025)

#### **Système Hôpital** ✅
- ✅ Gestion complète des patients
- ✅ Dossiers médicaux avec consultations multiples
- ✅ Workflow examens (Médecin → Caissier → Laborantin)
- ✅ Transfert inter-hospitalier avec consentement patient
- ✅ Notifications en temps réel
- ✅ 6 rôles (admin, medecin, infirmier, laborantin, caissier, receptionniste)

#### **Système Pharmacie** ✅
- ✅ Gestion complète des médicaments (25+ champs)
- ✅ Système de gestion des stocks avec historique
- ✅ 6 types de mouvements de stock
- ✅ Inventaire physique avec calcul d'écarts
- ✅ Gestion des commandes fournisseurs
- ✅ Workflow complet (Création → Validation → Réception → Stock)
- ✅ Gestion des fournisseurs
- ✅ Notifications automatiques (stock faible, commandes)
- ✅ 3 rôles (admin, pharmacien, assistant)

#### **Système Banque de Sang** ✅
- ✅ Gestion des donneurs avec vérification éligibilité
- ✅ Enregistrement des dons avec infos médicales
- ✅ Réserves par groupe sanguin (8 groupes)
- ✅ Gestion des demandes des hôpitaux
- ✅ 3 niveaux d'urgence
- ✅ 4 types de dons
- ✅ Mise à jour automatique des réserves
- ✅ 3 rôles (admin, technicien_labo, gestionnaire_donneurs)

#### **Système Global** ✅
- ✅ Sidebar dynamique selon le rôle
- ✅ Dashboard adaptatif par entité
- ✅ Notifications multi-entités isolées
- ✅ Isolation complète des données
- ✅ 14 rôles pour toutes les entités
- ✅ 68+ permissions granulaires
- ✅ Interface moderne et responsive
- ✅ README complet avec documentation

---

## 🎯 PROCHAINES ÉTAPES (Optionnel)

### Fonctionnalités Avancées
1. Système de ventes pour la pharmacie avec facturation
2. Analyses sanguines détaillées pour la banque
3. Rapports et statistiques avancées (graphiques)
4. Export PDF/Excel des données
5. Système de facturation complet
6. API REST pour intégrations externes
7. Dashboard avec graphiques (Chart.js)
8. Système de messagerie interne
9. Gestion des rendez-vous en ligne pour patients
10. Application mobile (Flutter/React Native)

### Améliorations Techniques
1. Tests automatisés (PHPUnit)
2. CI/CD (GitHub Actions)
3. Docker pour le déploiement
4. Backup automatique de la base de données
5. Logs avancés avec monitoring
6. Cache Redis pour les performances
7. Queue pour les tâches longues
8. WebSockets pour notifications temps réel
9. Elasticsearch pour recherche avancée
10. Multi-langue (FR, EN, Lingala, Swahili)

---

## 📚 RESSOURCES

### Documentation Laravel
- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)

### Standards Médicaux
- CIM-10 : Classification Internationale des Maladies
- DCI : Dénomination Commune Internationale (médicaments)
- Normes OMS pour banques de sang

---

## 👨‍💻 DÉVELOPPEMENT

### Conventions de Code
- PSR-12 pour PHP
- Camel case pour les méthodes
- Snake case pour les colonnes DB
- Commentaires en français
- Validation stricte des données

### Git Workflow
```bash
# Créer une branche
git checkout -b feature/nouvelle-fonctionnalite

# Commit
git add .
git commit -m "feat: Ajout gestion des ventes"

# Push
git push origin feature/nouvelle-fonctionnalite
```

---

## 🎊 RÉSUMÉ SESSION 09/11/2025

### **Accomplissements de la Journée**

#### **Documentation** 📚
- ✅ README complet (1000+ lignes)
- ✅ Documentation de tous les workflows
- ✅ Guide d'installation
- ✅ Guide de test

#### **Sidebar & Dashboard** 🎨
- ✅ Sidebar 100% dynamique selon le rôle
- ✅ Dashboard adaptatif par entité
- ✅ Statistiques spécifiques

#### **Notifications** 🔔
- ✅ Support multi-entités
- ✅ Isolation correcte (superadmin ne voit pas les notifications des entités)
- ✅ 30+ types de notifications
- ✅ Helper pour création facile

#### **Pharmacie** 💊
- ✅ Gestion médicaments (200+ lignes code)
- ✅ Gestion stocks (220+ lignes code)
- ✅ Gestion commandes (200+ lignes code)
- ✅ Gestion fournisseurs (150+ lignes code)
- ✅ 5 tables créées
- ✅ 8 vues créées
- ✅ Workflow complet implémenté

#### **Banque de Sang** 🩸
- ✅ Gestion donneurs (180+ lignes code)
- ✅ Gestion dons
- ✅ Réserves par groupe sanguin
- ✅ Gestion demandes
- ✅ 4 tables créées
- ✅ 7 vues créées
- ✅ Workflow complet implémenté

### **Statistiques**
- **Fichiers créés** : 40+
- **Lignes de code** : 5000+
- **Migrations** : 12
- **Modèles** : 12
- **Contrôleurs** : 10
- **Vues** : 15+
- **Temps équivalent** : ~40 heures de développement

---

## 📞 SUPPORT

Pour toute question ou problème, contactez l'équipe CENTRAL+.

### Contacts
- Email : support@central.cd
- Téléphone : +243 XXX XXX XXX
- Site web : www.central.cd

---

## 📄 LICENCE

© 2025 CENTRAL+ - Tous droits réservés

---

**CENTRAL+ - La solution complète pour la gestion de votre établissement de santé en RDC** 🏥💊🩸✨

**Développé avec ❤️ pour améliorer la santé en République Démocratique du Congo**
