# 📘 GUIDE DE FONCTIONNEMENT DE CENTRAL+

**Pour l'équipe de développement**

---

## 🎯 QU'EST-CE QUE CENTRAL+ ?

**Central+** est une plateforme web qui connecte **3 types d'établissements de santé** :

1. 🏥 **Hôpitaux** - Gèrent les patients, dossiers médicaux, rendez-vous
2. 💊 **Pharmacies** - Gèrent les médicaments, stocks, commandes
3. 🩸 **Banques de Sang** - Gèrent les donneurs, dons, réserves

Chaque établissement fonctionne de manière **totalement isolée** : un hôpital ne voit pas ce que fait un autre hôpital, une pharmacie ne voit pas les données d'une autre pharmacie, etc.

---

## 👥 COMMENT ÇA FONCTIONNE ? (VUE D'ENSEMBLE)

### **1. LE SUPERADMIN**
- C'est le "patron" du système
- Il crée les **administrateurs** des hôpitaux, pharmacies et banques de sang
- Il ne voit PAS les données sensibles (dossiers médicaux, stocks, etc.)
- Il gère uniquement les rôles et permissions

### **2. LES ADMINISTRATEURS D'ENTITÉS**
Chaque hôpital, pharmacie ou banque de sang a son propre administrateur qui :
- Crée et gère son **personnel** (médecins, pharmaciens, techniciens, etc.)
- Voit uniquement **SES propres données**
- Gère les opérations quotidiennes de son établissement

### **3. LE PERSONNEL**
Chaque membre du personnel a un **rôle spécifique** avec des permissions limitées :
- Un **médecin** crée des dossiers et prescrit des examens
- Un **caissier** valide les paiements
- Un **laborantin** réalise les examens et upload les résultats
- Un **pharmacien** vend des médicaments
- Un **réceptionniste** enregistre les patients et prend des RDV
- Etc.

### **4. LES PATIENTS**
- Peuvent s'inscrire eux-mêmes sur la plateforme
- Choisissent un hôpital de rattachement
- Consultent leurs dossiers médicaux, rendez-vous, examens
- Cherchent des pharmacies et banques de sang

---

## 🏥 FONCTIONNEMENT D'UN HÔPITAL

### **Scénario 1 : Un patient arrive à l'hôpital**

#### **Étape 1 : Inscription du patient**
- Le **réceptionniste** ou l'**admin de l'hôpital** enregistre le patient
- Informations saisies : nom, prénom, email, téléphone, date de naissance, sexe, adresse, groupe sanguin
- Un **médecin traitant** est assigné au patient
- Un **mot de passe** est créé pour que le patient puisse se connecter plus tard

#### **Étape 2 : Prise de rendez-vous**
- Le **réceptionniste** ou le **médecin** crée un rendez-vous
- Le patient reçoit un **rappel automatique** :
  - 24 heures avant le RDV
  - 2 heures avant le RDV
- Le médecin reçoit aussi ces rappels

#### **Étape 3 : Consultation médicale**
Le **médecin** consulte le patient et crée un **dossier médical** avec :
- **Antécédents** (médicaux, familiaux, allergies)
- **Signes vitaux** (poids, taille, température, tension, pouls)
- **Anamnèse** (motif de consultation, histoire de la maladie, symptômes)
- **Examen clinique** (examen général et physique)
- **Diagnostic initial** (avec code CIM-10 si possible)
- **Notes** et observations

À ce stade, le médecin **ne prescrit PAS encore de traitement** car il a besoin des résultats d'examens.

#### **Étape 4 : Prescription d'examens**
Si le médecin a besoin d'examens (radio, analyses, échographie, etc.) :
- Il clique sur **"Prescrire des examens"**
- Il ajoute chaque examen avec :
  - Type d'examen (Radiologie, Analyses de sang, etc.)
  - Nom de l'examen (Radio thorax, Glycémie, etc.)
  - Indication (pourquoi cet examen ?)
- Le **prix est à 0** (c'est le caissier qui le fixera)
- Une **notification** est envoyée au **caissier**

#### **Étape 5 : Paiement (Caissier)**
Le **caissier** reçoit la notification :
- Il voit la liste des examens prescrits
- Il **fixe le prix** de chaque examen
- Il **valide le paiement** (espèces, carte, mobile money, etc.)
- Une **notification** est envoyée au **laborantin**

#### **Étape 6 : Réalisation de l'examen (Laborantin)**
Le **laborantin** reçoit la notification :
- Il clique sur **"Commencer"** pour marquer l'examen en cours
- Il réalise l'examen
- Il **upload les résultats** :
  - Texte : résultats détaillés et interprétation
  - Fichier : image, PDF, etc. (optionnel)
- Dès qu'il valide, les résultats sont **automatiquement ajoutés au diagnostic** du dossier médical
- Une **notification** est envoyée au **médecin**

#### **Étape 7 : Traitement (Médecin)**
Le **médecin** reçoit la notification :
- Il consulte les résultats d'examens dans le dossier
- Il peut maintenant **ajouter un traitement** :
  - Médicaments (nom, posologie, durée)
  - Interventions chirurgicales
  - Recommandations
- Il peut **fixer un prochain rendez-vous** si nécessaire
- Le dossier est complet !

#### **Étape 8 : Consultations ultérieures**
Lors des consultations suivantes, le médecin peut :
- **Ajouter une nouvelle consultation** (le dossier évolue)
- **Modifier le traitement** selon l'évolution
- **Prescrire de nouveaux examens** si besoin
- Le dossier garde **tout l'historique**

---

### **Scénario 2 : Transfert de dossier entre hôpitaux**

#### **Pourquoi un transfert ?**
Un patient suivi à l'Hôpital A doit consulter un spécialiste à l'Hôpital B.

#### **Étape 1 : Recherche du patient**
L'**admin de l'Hôpital A** :
- Va dans **"Transferts" → "Rechercher un patient"**
- Tape le nom du patient
- Sélectionne le patient dans les résultats

#### **Étape 2 : Demande de transfert**
L'**admin de l'Hôpital A** :
- Clique sur **"Demander transfert"**
- Justifie la demande (pourquoi le dossier est nécessaire ?)
- Envoie la demande
- Une **notification** est envoyée à l'**Hôpital B**

#### **Étape 3 : Traitement de la demande**
L'**admin de l'Hôpital B** :
- Reçoit la notification
- Consulte la demande
- Décide : **Accepter** ou **Refuser**

#### **Étape 4A : Si accepté**
- Une **copie du dossier** est créée dans l'Hôpital A
- L'Hôpital A peut maintenant consulter et modifier ce dossier
- Les deux hôpitaux ont le dossier
- Une **notification** de confirmation est envoyée à l'Hôpital A

#### **Étape 4B : Si refusé**
- L'admin donne un **motif de refus**
- Une **notification** est envoyée à l'Hôpital A
- Fin du processus

---

## 💊 FONCTIONNEMENT D'UNE PHARMACIE

### **Scénario 1 : Gestion des médicaments**

#### **Ajout d'un médicament**
L'**admin de la pharmacie** :
- Va dans **"Médicaments" → "Nouveau médicament"**
- Remplit les informations :
  - **Identification** : Code produit, Nom commercial, Nom générique (DCI)
  - **Catégorie** : Antibiotique, Antalgique, Antihypertenseur, etc.
  - **Forme** : Comprimé, Sirop, Injection, etc.
  - **Dosage** : 500mg, 1g, etc.
  - **Prix** : Achat et Vente
  - **Stock** : Quantité actuelle, Stock minimum, Stock critique
  - **Emplacement** : Étagère, Armoire, etc.
  - **Fabricant** : Nom du laboratoire
  - **Lot** : Numéro de lot, Date d'expiration
  - **Prescription** : Requise (Oui/Non)
- Le médicament est créé et visible dans la liste

#### **Alertes automatiques**
Le système surveille automatiquement et envoie des notifications si :
- 🟡 **Stock faible** : Quantité < Stock minimum
- 🔴 **Stock critique** : Quantité < 10 unités
- ⚠️ **Bientôt périmé** : Date d'expiration < 3 mois
- ❌ **Périmé** : Date d'expiration dépassée

---

### **Scénario 2 : Gestion des stocks**

#### **Ajustement manuel**
L'**admin de la pharmacie** :
- Va dans **"Stocks"**
- Clique sur **"Ajuster"** pour un médicament
- Choisit le type :
  - **Entrée** : Réception de livraison, Retour client
  - **Sortie** : Vente, Péremption, Casse
  - **Ajustement** : Correction d'erreur
- Entre la quantité
- Indique le motif
- Valide
- Le stock est **automatiquement mis à jour**
- Un **mouvement de stock** est enregistré (traçabilité complète)

#### **Inventaire physique**
Régulièrement (tous les mois par exemple), la pharmacie fait un inventaire :
- Va dans **"Stocks" → "Inventaire"**
- Le système affiche **tous les médicaments** avec leur stock théorique
- Le personnel compte physiquement
- Entre les **quantités réelles**
- Le système calcule les **écarts** (différence théorique vs réel)
- Au clic sur **"Enregistrer"**, tous les ajustements sont créés automatiquement
- Les stocks sont corrigés

#### **Historique des mouvements**
Pour chaque médicament, on peut voir :
- Tous les mouvements (entrées, sorties, ajustements)
- Date et heure exactes
- Utilisateur qui a fait le mouvement
- Stock avant / Stock après
- Motif du mouvement
- **Traçabilité totale** !

---

### **Scénario 3 : Commandes aux fournisseurs**

#### **Étape 1 : Création de la commande**
L'**admin de la pharmacie** :
- Va dans **"Commandes" → "Nouvelle commande"**
- Sélectionne un **fournisseur**
- Entre la **date de livraison prévue**
- Ajoute des **lignes de commande** :
  - Sélectionne un médicament
  - Entre la quantité commandée
  - Entre le prix unitaire
  - Le total se calcule automatiquement
- Peut ajouter plusieurs médicaments
- Valide → La commande est créée avec statut **"brouillon"**

#### **Étape 2 : Validation**
L'**admin de la pharmacie** :
- Revient sur la commande
- Vérifie tout
- Clique sur **"Valider la commande"**
- Statut passe à **"validee"**
- Une **notification** peut être envoyée (à implémenter : email/SMS au fournisseur)

#### **Étape 3 : Réception**
Quand le fournisseur livre :
- L'**admin de la pharmacie** clique sur **"Réceptionner"**
- Pour chaque ligne, il entre la **quantité reçue** (peut être différente de la quantité commandée)
- Clique sur **"Valider la réception"**
- Les **stocks sont automatiquement mis à jour** pour chaque médicament !
- Des **mouvements de stock** sont créés (traçabilité)
- Statut passe à **"livree"**

#### **Étape 4 : Annulation (si besoin)**
Si la commande est annulée avant livraison :
- L'admin clique sur **"Annuler"**
- Entre le motif
- Statut passe à **"annulee"**

---

## 🩸 FONCTIONNEMENT D'UNE BANQUE DE SANG

### **Scénario 1 : Enregistrement d'un donneur**

#### **Étape 1 : Ajout du donneur**
L'**admin de la banque de sang** ou le **gestionnaire de donneurs** :
- Va dans **"Donneurs" → "Nouveau donneur"**
- Remplit les informations :
  - Identité : Nom, Prénom, Date de naissance, Sexe
  - Contact : Téléphone, Email, Adresse
  - Informations médicales : Groupe sanguin, Rhésus
- Le **numéro de donneur** est généré automatiquement
- Le donneur est créé

#### **Étape 2 : Vérification de l'éligibilité**
Le système calcule automatiquement si le donneur peut donner :
- **Délai entre deux dons** :
  - Hommes : 2 mois minimum
  - Femmes : 3 mois minimum
- Le système affiche si le donneur est **"Éligible"** ou **"Non éligible"** avec la date du prochain don possible

---

### **Scénario 2 : Enregistrement d'un don**

#### **Étape 1 : Création du don**
Le **technicien de labo** ou l'**admin** :
- Va dans **"Dons" → "Nouveau don"**
- Sélectionne un **donneur**
- Entre les informations :
  - **Type de don** : Sang total, Plaquettes, Plasma
  - **Volume** : En millilitres (généralement 450ml)
  - **Examen pré-don** :
    - Tension artérielle
    - Hémoglobine
    - Température
  - **Notes** : Observations éventuelles
- Le **numéro de don** est généré automatiquement
- Statut initial : **"En attente d'analyse"**

#### **Étape 2 : Analyse du sang**
Le **technicien de labo** analyse le sang :
- Tests obligatoires : VIH, Hépatite B, Hépatite C, Syphilis
- Si tout est OK : Statut passe à **"Approuvé"**
- Si problème : Statut passe à **"Rejeté"**

#### **Étape 3 : Mise à jour des réserves**
Si le don est **approuvé** :
- Les **réserves sont automatiquement mises à jour** selon le groupe sanguin du donneur
- Une nouvelle poche est ajoutée au stock
- Le compteur de dons du donneur augmente
- La date de dernière donation est mise à jour

---

### **Scénario 3 : Demande de sang par un hôpital**

#### **Étape 1 : Création de la demande**
L'**admin de l'hôpital** :
- Va dans **"Banques de Sang"** (côté patient)
- OU crée directement une demande (à implémenter)
- Spécifie :
  - **Groupe sanguin** demandé (A, B, AB, O)
  - **Rhésus** (positif, négatif)
  - **Quantité** en ml ou nombre de poches
  - **Niveau d'urgence** : Normal, Urgent, Très urgent
  - **Motif** : Chirurgie, Accident, Anémie sévère, etc.
- La demande est créée avec un **numéro unique**
- Une **notification** est envoyée à la **banque de sang**

#### **Étape 2 : Traitement de la demande**
L'**admin de la banque de sang** :
- Reçoit la notification
- Consulte la demande
- Vérifie les **réserves disponibles** pour ce groupe sanguin
- Décide :
  - **Approuver** (stock suffisant)
  - **Approuver partiellement** (stock insuffisant, on donne ce qu'on a)
  - **Refuser** (pas de stock)

#### **Étape 3 : Si approuvé**
- L'admin entre la **quantité fournie**
- Les **réserves sont automatiquement déduites** !
- Statut : **"Approuvée"**
- Une **notification** est envoyée à l'hôpital
- L'hôpital peut venir récupérer le sang

#### **Étape 4 : Si refusé**
- L'admin entre le **motif de refus**
- Statut : **"Refusée"**
- Une **notification** est envoyée à l'hôpital
- L'hôpital doit chercher ailleurs

---

### **Scénario 4 : Gestion des réserves**

#### **Alertes automatiques**
Le système surveille les réserves et envoie des notifications si :
- 🟡 **Réserve faible** : Quantité < Stock minimum (ex: < 5 litres)
- 🔴 **Réserve critique** : Quantité < Stock critique (ex: < 2 litres)

#### **Affichage des réserves**
Sur la page **"Réserves"**, l'admin voit pour chaque groupe sanguin :
- Groupe sanguin complet (ex: A+, O-, AB+)
- Quantité disponible (en litres)
- Nombre de poches
- Date d'expiration moyenne
- Statut avec couleur :
  - 🟢 **Optimal** : Stock > Stock minimum
  - 🟡 **Faible** : Stock < Stock minimum
  - 🔴 **Critique** : Stock < Stock critique

---

## 👤 FONCTIONNEMENT POUR UN PATIENT

### **Scénario 1 : Inscription**

#### **Option A : Auto-inscription**
Le patient peut s'inscrire lui-même :
- Va sur le site Central+
- Clique sur **"S'inscrire"**
- Sélectionne **"Patient"**
- Remplit le formulaire :
  - Nom, Prénom, Email, Téléphone
  - Date de naissance, Sexe, Adresse
  - Groupe sanguin
  - Mot de passe
  - **Optionnel** : Choisir un hôpital (peut le faire plus tard)
- Le compte est créé immédiatement

#### **Option B : Inscription par l'hôpital**
Le **réceptionniste** ou l'**admin de l'hôpital** enregistre le patient :
- Va dans **"Patients" → "Nouveau patient"**
- Remplit le formulaire (même informations)
- **Obligatoire** : Assigner un médecin traitant
- Crée un mot de passe pour le patient
- Le patient peut se connecter avec son email et ce mot de passe

---

### **Scénario 2 : Choix de l'hôpital (si pas fait à l'inscription)**

#### **Étape 1 : Connexion**
Le patient se connecte avec son email et mot de passe.

#### **Étape 2 : Notification**
Dans le **sidebar**, il voit un menu avec un badge jaune :
```
⚠️ Choisir mon Hôpital
```
Dans le **topbar**, il voit : **"Patient"** (au lieu du nom de l'hôpital)

#### **Étape 3 : Choix**
- Il clique sur **"Choisir mon Hôpital"**
- Il voit la liste de tous les hôpitaux disponibles
- Il clique sur **"Choisir"** pour son hôpital préféré
- Il confirme

#### **Étape 4 : Confirmation**
- Le menu **"Choisir mon Hôpital"** disparaît du sidebar
- Le topbar affiche maintenant : **"Hôpital Saint-Joseph"** (nom de l'hôpital choisi)
- Une **notification** est envoyée à l'**admin de l'hôpital**
- Le patient peut maintenant prendre des rendez-vous !

---

### **Scénario 3 : Utilisation du portail patient**

#### **Dashboard**
Le patient voit :
- Son **prochain rendez-vous**
- Nombre de **dossiers médicaux**
- Nombre d'**examens** (en cours et terminés)
- **Actions rapides** : Mes RDV, Mes dossiers, Pharmacies, Banques de sang

#### **Mes Dossiers Médicaux**
- Liste de tous ses dossiers (un dossier = une consultation)
- Pour chaque dossier :
  - Date de consultation
  - Médecin
  - Motif
  - Diagnostic
  - Traitement
- Peut cliquer pour voir les détails complets (anamnèse, examen clinique, etc.)
- **NE PEUT PAS modifier** (lecture seule)

#### **Mes Rendez-vous**
- Liste de tous ses RDV (passés et futurs)
- Pour chaque RDV :
  - Date et heure
  - Médecin
  - Motif
  - Statut (Confirmé, En attente, Annulé, Terminé)

#### **Mes Examens**
- Liste de tous ses examens prescrits
- Pour chaque examen :
  - Type et nom
  - Date de prescription
  - Médecin prescripteur
  - Prix
  - Statut de paiement (Payé ou Non payé)
  - Statut d'examen (En attente, En cours, Terminé)
  - **Résultats** (si terminé) : texte + fichier téléchargeable

#### **Trouver une Pharmacie**
Le patient cherche où acheter ses médicaments :
- Entre les noms de **plusieurs médicaments** (ex: Doliprane, Amoxicilline)
- Le système cherche les pharmacies qui ont **TOUS** ces médicaments en stock
- Affiche :
  - Nom de la pharmacie
  - Adresse
  - Contact
  - Pour chaque médicament : Stock disponible et Prix
- Le patient peut contacter la pharmacie pour passer commande

#### **Trouver une Banque de Sang**
Le patient (ou sa famille) cherche du sang :
- Sélectionne son **groupe sanguin** (A, B, AB, O)
- Sélectionne son **rhésus** (Positif, Négatif)
- Le système affiche uniquement les banques de sang qui ont **ce groupe en stock**
- Pour chaque banque :
  - Nom
  - Adresse
  - Contact
  - Quantité disponible (en litres)
- Le patient peut contacter la banque pour faire une demande

---

## 🔔 SYSTÈME DE NOTIFICATIONS

### **Comment ça marche ?**

#### **Actualisation automatique**
- Toutes les **30 secondes**, le système vérifie s'il y a de nouvelles notifications
- Si oui, le **badge** sur la cloche s'anime et affiche le nombre
- L'utilisateur voit immédiatement qu'il a des notifications

#### **Filtrage par entité**
- Chaque utilisateur ne voit que **SES notifications**
- Un médecin de l'Hôpital A ne voit pas les notifications de l'Hôpital B
- Le superadmin ne reçoit que des notifications personnelles (pas celles des entités)

#### **Types de notifications**

**Pour l'hôpital :**
- Nouveau patient inscrit
- Demande de transfert reçue
- Transfert complété
- Examen à payer (caissier)
- Examen à réaliser (laborantin)
- Résultats d'examen disponibles (médecin)
- Rappel RDV 24h avant (médecin + patient)
- Rappel RDV 2h avant (médecin + patient)

**Pour la pharmacie :**
- Stock faible/critique
- Médicament bientôt périmé
- Nouvelle commande créée
- Commande validée
- Commande livrée

**Pour la banque de sang :**
- Réserve faible/critique
- Nouveau donneur
- Don enregistré
- Demande de sang reçue
- Demande urgente/très urgente

#### **Actions sur notification**
Quand l'utilisateur clique sur une notification :
1. Elle est **marquée comme lue**
2. Le badge se met à jour (diminue)
3. L'utilisateur est **redirigé** vers la page concernée

---

## 🔐 SÉCURITÉ ET ISOLATION DES DONNÉES

### **Principe de base**
**Chaque entité ne voit QUE ses propres données !**

### **Comment c'est implémenté ?**

#### **1. Dans la base de données**
Chaque table a des colonnes pour identifier l'entité :
- `hopital_id` - Pour les données d'hôpital
- `pharmacie_id` - Pour les données de pharmacie
- `banque_sang_id` - Pour les données de banque de sang
- `entite_id` + `type_utilisateur` - Pour les utilisateurs

#### **2. Dans les modèles (Scopes)**
Les modèles ont des **scopes** qui filtrent automatiquement :
```php
// Exemple : Un médecin ne voit que les dossiers de son hôpital
DossierMedical::ofSameHospital()->get();

// Résultat : Uniquement les dossiers où hopital_id = hopital_id du médecin
```

#### **3. Dans les contrôleurs (Vérifications)**
Avant chaque action, on vérifie que l'utilisateur a le droit :
```php
// Exemple : Admin pharmacie veut modifier un médicament
if ($medicament->pharmacie_id !== $user->entite_id) {
    abort(403); // Accès refusé !
}
```

#### **4. Dans les vues (Conditions)**
Le sidebar, les menus, les boutons s'adaptent au rôle :
```blade
@if(auth()->user()->role === 'medecin')
    <a href="/admin/medecin/patients">Mes Patients</a>
@endif
```

### **Résultat**
- Un médecin de l'Hôpital A **ne peut pas** voir les patients de l'Hôpital B
- Un admin de Pharmacie A **ne peut pas** voir les médicaments de Pharmacie B
- Un admin de Banque de Sang A **ne peut pas** voir les donneurs de Banque de Sang B
- **C'est impossible techniquement** de contourner cette isolation !

---

## 🎨 INTERFACE UTILISATEUR

### **Design adapté au rôle**

#### **Couleurs par entité**
- 🏥 Hôpital : **Bleu** (#007bff)
- 💊 Pharmacie : **Vert** (#28a745)
- 🩸 Banque de sang : **Rouge** (#dc3545)
- 👤 Patient : **Bleu clair** et blanc

#### **Sidebar dynamique**
Le menu de gauche change selon le rôle :

**Superadmin voit :**
- Tableau de bord
- Rôles et Permissions
- Utilisateurs
- Entités
- Paramètres

**Admin Hôpital voit :**
- Tableau de bord
- Rôles et Permissions (de son hôpital)
- Utilisateurs (de son hôpital)
- Patients
- Rendez-vous
- Transferts

**Médecin voit :**
- Tableau de bord
- Mes Patients
- Dossiers Médicaux
- Mes Rendez-vous

**Réceptionniste voit :**
- Tableau de bord
- Patients
- Rendez-vous

**Admin Pharmacie voit :**
- Tableau de bord
- Rôles et Permissions (de sa pharmacie)
- Utilisateurs (de sa pharmacie)
- Médicaments
- Stocks
- Commandes
- Fournisseurs
- Ventes

**Patient voit :**
- Tableau de bord
- Mon Dossier Médical
- Mes Rendez-vous
- Mes Examens
- Trouver une Pharmacie
- Banques de Sang
- Choisir mon Hôpital (si pas encore choisi)

#### **Responsive**
Tout fonctionne sur :
- 💻 **Ordinateur** (Desktop)
- 📱 **Tablette** (Tablet)
- 📱 **Téléphone** (Mobile)

Le sidebar se transforme en menu "hamburger" sur mobile.

---

## 🔄 WORKFLOWS AUTOMATISÉS

### **Ce qui se passe automatiquement**

#### **1. Examens médicaux**
- Médecin prescrit → Notification au caissier ✅
- Caissier valide paiement → Notification au laborantin ✅
- Laborantin upload résultats → Diagnostic mis à jour ✅ → Notification au médecin ✅

#### **2. Commandes pharmacie**
- Réception validée → Stocks mis à jour ✅ → Mouvements créés ✅

#### **3. Demandes de sang**
- Demande approuvée → Réserves déduites ✅

#### **4. Rappels RDV**
- 24h avant → Notification médecin + patient ✅
- 2h avant → Notification médecin + patient ✅

#### **5. Alertes stock**
- Stock < minimum → Notification admin ✅
- Médicament bientôt périmé → Notification admin ✅

#### **6. Choix d'hôpital patient**
- Patient choisit → Notification admin hôpital ✅

**Aucune intervention manuelle nécessaire !**

---

## 📊 STATISTIQUES ET DASHBOARDS

### **Ce que chaque rôle voit sur son dashboard**

#### **Superadmin**
- Total utilisateurs (tous types)
- Total entités (hôpitaux + pharmacies + banques)
- Utilisateurs en attente d'approbation
- Statistiques globales

#### **Admin Hôpital**
- Total patients de l'hôpital
- Rendez-vous du jour
- Examens en attente
- Dossiers médicaux actifs
- Transferts en cours

#### **Médecin**
- Mes patients (que je suis)
- Mes RDV aujourd'hui
- Examens que j'ai prescrits (avec statuts)
- Consultations de la semaine

#### **Admin Pharmacie**
- Total médicaments actifs
- Alertes stock faible (nombre)
- Alertes péremption proche (nombre)
- Commandes en cours
- Ventes du jour/mois

#### **Admin Banque de Sang**
- Total donneurs actifs
- Réserves par groupe sanguin (avec alertes colorées)
- Dons du mois
- Demandes en attente

#### **Patient**
- Prochain rendez-vous
- Total dossiers médicaux
- Total examens (en cours et terminés)

#### **Réceptionniste**
- Patients du jour
- RDV confirmés aujourd'hui
- RDV en attente
- Total patients de l'hôpital

---

## 🚀 POUR DÉMARRER

### **Accéder à Central+**
1. Ouvrir le navigateur
2. Aller sur : `http://localhost:8000` (en développement)
3. Cliquer sur **"Se connecter"**

### **Se connecter**

#### **Superadmin**
- Email: `admin@central.com`
- Mot de passe: `password`

#### **Autres utilisateurs**
Utiliser l'email et le mot de passe créés par le superadmin ou l'admin d'entité.

### **Première utilisation**

#### **En tant que Superadmin**
1. Créer les entités (hôpitaux, pharmacies, banques de sang)
2. Pour chaque entité, créer son administrateur
3. Les administrateurs reçoivent leurs identifiants

#### **En tant qu'Admin d'entité**
1. Se connecter avec les identifiants reçus
2. Créer le personnel (médecins, pharmaciens, etc.)
3. Commencer les opérations quotidiennes

#### **En tant que Patient**
1. S'inscrire sur la plateforme
2. Choisir un hôpital
3. Consulter ses informations médicales

---

## ❓ QUESTIONS FRÉQUENTES

### **Q1 : Que faire si j'oublie mon mot de passe ?**
Cliquer sur "Mot de passe oublié" (fonctionnalité à venir : envoi d'email).

### **Q2 : Un patient peut-il avoir plusieurs hôpitaux ?**
Non, un patient ne peut avoir qu'un seul hôpital de rattachement. Mais il peut changer d'hôpital (à implémenter).

### **Q3 : Peut-on supprimer un dossier médical ?**
Non, les dossiers médicaux ne peuvent pas être supprimés (obligation légale de conservation). On peut les archiver.

### **Q4 : Qui peut voir les dossiers médicaux ?**
- Le médecin qui a créé le dossier
- Les médecins du même hôpital (si permission accordée)
- L'admin de l'hôpital (sans détails médicaux sensibles)
- Le patient lui-même (lecture seule)

### **Q5 : Comment les notifications fonctionnent-elles ?**
Les notifications sont actualisées automatiquement toutes les 30 secondes. Pas besoin de rafraîchir la page !

### **Q6 : Peut-on annuler une commande de médicaments après validation ?**
Oui, tant que la commande n'est pas livrée, elle peut être annulée.

### **Q7 : Que se passe-t-il si un examen est payé mais pas réalisé ?**
Le caissier ou l'admin peut rembourser et marquer l'examen comme "annulé" (à implémenter).

### **Q8 : Les rappels de RDV fonctionnent-ils automatiquement ?**
Oui, si le système est correctement configuré (voir `RAPPELS_RENDEZVOUS.md`). Un script doit tourner en arrière-plan.

---

## 📞 SUPPORT

### **Pour les questions techniques**
- Consulter ce guide
- Consulter `README.md` (documentation technique)
- Consulter `ANALYSE_COMPLETE.md` (analyse détaillée)

### **Pour les bugs**
Contacter l'équipe de développement avec :
- Description du problème
- Étapes pour reproduire
- Captures d'écran si possible
- Rôle de l'utilisateur concerné

---

## ✅ RÉSUMÉ EN 5 POINTS

1. **Central+ connecte 3 types d'établissements** (Hôpitaux, Pharmacies, Banques de Sang) de manière totalement isolée.

2. **Chaque établissement a un admin** qui crée son personnel avec des rôles spécifiques et des permissions limitées.

3. **Les workflows sont automatisés** : prescription → paiement → réalisation → résultats → traitement, tout se fait avec des notifications en temps réel.

4. **Les patients ont leur propre portail** pour consulter leurs dossiers, rendez-vous, examens et chercher des pharmacies/banques de sang.

5. **Tout est tracé et sécurisé** : chaque action est enregistrée, chaque entité ne voit que ses propres données, impossible de contourner l'isolation.

---

**Bon travail sur Central+ ! 🚀**

Si tu as des questions, n'hésite pas à demander à ton collègue ou à consulter les autres fichiers de documentation.

