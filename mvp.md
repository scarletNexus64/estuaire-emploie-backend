Voici un **scénario clair, professionnel et complet** pour ton application **Estuaire Emploi**, couvrant les **2 modes principaux :**
✔ **Mode POST (Entreprise → Publie une offre)**
✔ **Mode RECRUTEMENT (Candidat → cherche et postule)**

Cela te servira pour :

* documenter ton projet,
* faire ton cahier des charges,
* expliquer le fonctionnement à ton équipe,
* définir tes écrans et API.

---

# 🌐 **SCÉNARIO GLOBAL DE L’APPLICATION – ESTUAIRE EMPLOI**

---

# 🟦 **MODE 1 : POST — Côté Entreprise / Recruteur**

## **1️⃣ Inscription de l’entreprise**

1. Une entreprise arrive sur la plateforme.
2. Elle clique sur **“Créer un compte entreprise”**.
3. Elle saisit :

   * Nom de l’entreprise
   * Email professionnel
   * Secteur d’activité
   * Numéro de téléphone
   * Logo
4. L’entreprise reçoit un email de confirmation et son compte passe en **“En attente de vérification”**.
5. L’admin valide ou rejette le compte.

---

## **2️⃣ Connexion du recruteur**

L’entreprise se connecte via son **email + mot de passe**.
Elle arrive sur son **Dashboard Entreprise**.

---

## **3️⃣ Publication d’une offre d’emploi (POST)**

1. Le recruteur clique sur **“Publier une nouvelle offre”**.
2. Il remplit :

   * Titre du poste
   * Description détaillée
   * Compétences requises
   * Niveau d’expérience
   * Salaire (optionnel)
   * Type de contrat
   * Localisation du poste
   * Date limite de candidature
3. Le recruteur soumet l’offre.

### Deux possibilités :

* Si validation automatique activée → **l’offre est publiée directement**.
* Si validation manuelle (recommandé pour éviter la fraude) →
  L’offre passe en **“En attente de validation”**.

---

## **4️⃣ Gestion des offres**

L’entreprise voit la liste de ses offres avec :

📍 **Statuts** : Publiée, Expirée, En attente, Suspendue
📍 **Statistiques** :

* Nombre de vues
* Nombre de candidatures
* Performance (faible / moyenne / forte)

---

## **5️⃣ Réception des candidatures**

Lorsqu’un candidat postule :

1. Le recruteur reçoit une **notification**.
2. Il accède aux détails :

   * Profil du candidat
   * CV / Portfolio
   * Message de motivation
3. Il peut classer la candidature :

   * **En attente**
   * **Retenue**
   * **Rejetée**
4. Il peut ajouter une **note interne**.

---

## **6️⃣ Prise de contact**

Le recruteur peut :

* envoyer un message au candidat,
* proposer un entretien,
* demander un complément de documents.

---

## **7️⃣ Clôture du recrutement**

Une fois le poste pourvu :

1. Le recruteur marque l’offre comme **“Fermée”**.
2. Les candidats reçoivent :

   * un email
   * ou une notification de mise à jour de statut.

---

# 🟩 **MODE 2 : RECRUTEMENT — Côté Candidat**

## **1️⃣ Inscription du candidat**

Le candidat clique sur **Créer un compte candidat**.

Il saisit :

* Nom
* Email
* Téléphone
* Mot de passe
* CV (PDF optionnel)
* Expérience
* Compétences
* Photo de profil

Le candidat crée un **profil professionnel complet**.

---

## **2️⃣ Navigation sur les offres**

Le candidat arrive sur la page des emplois :

Il peut filtrer par :

* Localisation
* Type de contrat
* Salaire
* Niveau d’expérience
* Entreprise
* Date de publication

Il clique sur une offre pour voir les détails.

---

## **3️⃣ Consultation d'une offre**

La fiche d’offre contient :

* Description du poste
* Profil recherché
* Avantages
* Informations sur l’entreprise
* Date limite
* Nombre de candidats déjà postulés

---

## **4️⃣ Postulation**

Le candidat clique sur **POSTULER**.

Il peut :

* joindre un CV
* ajouter une lettre de motivation
* modifier ses informations
* expliquer pourquoi il pense être le bon choix

Sa candidature est envoyée à l’entreprise.

---

## **5️⃣ Suivi des candidatures**

Le candidat voit un tableau :

| Offre             | Entreprise      | Statut     | Date  |
| ----------------- | --------------- | ---------- | ----- |
| Développeur front | FinTech Global  | Retenue    | 12/11 |
| Assistant RH      | CamHR Solutions | En attente | 10/11 |

Statuts possibles :

* **Envoyée**
* **Vue**
* **Retenue**
* **Rejetée**
* **Entretien prévu**

---

## **6️⃣ Notifications**

Le candidat reçoit une alerte lorsqu’un recruteur :

* consulte son CV,
* modifie le statut,
* envoie un message,
* programme un entretien.

---

## **7️⃣ Gestion du profil**

Le candidat peut :

* mettre à jour son CV
* ajouter un portfolio
* compléter ses compétences
* améliorer son score de visibilité
* activer la recherche d’emploi automatique (option premium)

---

## 🟥 **8️⃣ Messagerie interne**

Optionnel mais puissant :
Un système interne de communication entre recruteur et candidat.

---

# 🏁 **SCÉNARIO FINAL RÉSUMÉ**

### **Côté entreprise (POST)**

1. Création compte → Vérification admin
2. Connexion → Dashboard
3. Publication d’offre
4. Réception candidatures
5. Sélection et tri
6. Contact candidat
7. Clôture poste

---

### **Côté candidat (Recrutement)**

1. Création compte
2. Recherche d’offres
3. Consultation
4. Postulation
5. Suivi
6. Notifications
7. Profil pro complet

---

# 🎯 **Objectif du Dashboard Estuaire Emploi**

Permettre à l’administrateur et aux entreprises de gérer facilement :

* les offres d’emploi,
* les recruteurs,
* les candidatures,
* les paiements (si tu offres des plans premium),
* les statistiques.

---

# 🧩 **Fonctionnalités du Dashboard (Admin + Entreprises)**

## ✅ **1. Tableau de Bord (Dashboard général)**

### **Admin**

* Nombre total :

  * d’offres publiées
  * de candidatures
  * d’entreprises inscrites
  * de comptes recruteurs
* Graphiques :

  * Offres par mois
  * Candidatures par secteur
  * Nouveaux comptes cette semaine
* Alertes :

  * Offres en attente de validation
  * Entreprises non vérifiées
  * Signalements

### **Entreprise / Recruteur**

* Total :

  * Offres postées
  * Candidatures reçues
  * Taux de visibilité (vues)
* Graphiques :

  * Évolution des candidatures par offre
  * Performances des offres
* Raccourcis :

  * Créer une nouvelle offre
  * Gérer les candidatures

---

## ✅ **2. Gestion des Offres d’Emploi**

### Fonctionnalités :

* Créer / Modifier / Supprimer une offre
* Définir :

  * titre,
  * description,
  * compétences,
  * salaire,
  * localisation,
  * type de contrat (CDI, Stage, Intérim…)
* Publier, dépublier une offre
* Voir les statistiques :

  * Nombre de vues
  * Candidatures reçues

### Bonus :

* Offre “mise en avant” (payante)
* Validation des offres par admin

---

## ✅ **3. Gestion des Candidatures**

* Consulter les candidatures par offre
* Voir le CV (upload PDF)
* Voir le profil du candidat
* Prendre une décision :

  * Accepté
  * En attente
  * Refusé
* Notes internes du recruteur
* Messagerie rapide avec le candidat

---

## ✅ **4. Gestion des Entreprises**

(Admin)

* Liste des entreprises
* Vérification d'identité
* Statut (approuvé / suspendu)
* Historique de publication
* Plan d’abonnement (gratuit / premium)

---

## ✅ **5. Gestion des Recruteurs**

* Ajouter / Supprimer un recruteur dans une entreprise
* Assigner des permissions :

  * Peut publier ?
  * Peut voir les candidatures ?
  * Peut modifier le profil entreprise ?

---

## ✅ **6. Comptes Candidats (si admin)**

* Liste des candidats
* Profil détaillé
* CV + diplômes
* Historique des candidatures
* Comptes signalés / faux profils

---

## ✅ **7. Paiements et Abonnements (si tu veux monétiser)**

* Abonnement premium entreprise :

  * Nombre d’offres illimitées
  * Mise en avant
  * Accès aux CV sans candidature
* Historique paiements
* Méthodes de paiement (Mobile Money, Orange Money, etc.)

---

## ✅ **8. Gestion des Paramètres Système**

(Admin)

* Catégories de métiers
* Localisations
* Types de contrats
* Échelle de salaires
* Paramètres SEO
* Bannière et contenus statiques

---

## ✅ **9. Support et Signalements**

* Entreprises signalées
* Offres frauduleuses
* Candidats suspectés
* Conversations support/admin

---

## ✅ **10. Audit Log**

* Historique de toutes les actions admin
* Historique des actions recruteurs
* Tracking des suppressions et modifications

---

# 🔌 **API à Prévoir (Laravel REST API)**

## **1. Authentification**

* POST `/auth/login`
* POST `/auth/register`
* POST `/auth/logout`
* POST `/auth/forgot-password`
* POST `/auth/reset-password`

## **2. Entreprises**

* GET `/companies`
* GET `/companies/{id}`
* POST `/companies`
* PUT `/companies/{id}`
* DELETE `/companies/{id}`
* PATCH `/companies/{id}/verify`

## **3. Offres d'emploi (Jobs)**

* GET `/jobs`
* GET `/jobs/{id}`
* POST `/jobs`
* PUT `/jobs/{id}`
* DELETE `/jobs/{id}`
* PATCH `/jobs/{id}/publish`
* PATCH `/jobs/{id}/feature` (mise en avant)

## **4. Candidatures**

* GET `/jobs/{id}/applications`
* GET `/applications/{id}`
* POST `/jobs/{id}/apply`
* PATCH `/applications/{id}/status`
* DELETE `/applications/{id}`

## **5. Recruteurs**

* GET `/recruiters`
* POST `/recruiters`
* PUT `/recruiters/{id}`
* DELETE `/recruiters/{id}`

## **6. Utilisateurs (candidats)**

* GET `/users`
* GET `/users/{id}`
* DELETE `/users/{id}`

## **7. Catégories / Settings**

* GET `/categories`
* POST `/categories`
* GET `/locations`
* POST `/locations`

## **8. Paiements**

* POST `/payments/initiate`
* POST `/payments/verify`
* GET `/subscriptions`
* POST `/subscriptions/activate`

## **9. Statistiques**

* GET `/stats/dashboard`
* GET `/stats/company/{id}`

---

# 🧱 **Architecture Recommandée**

Pour que Blade reste propre :

```
resources/views/dashboard/
    layouts/
    components/
    admin/
    company/
    jobs/
    applications/
    settings/
```