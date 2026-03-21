# Guide de Migration vers les Wallets Séparés

## 📋 Vue d'ensemble

Cette migration transforme le système de wallet unique en **deux wallets séparés** :
- `freemopay_wallet_balance` : pour Mobile Money (Orange, MTN)
- `paypal_wallet_balance` : pour PayPal

Chaque wallet gère indépendamment ses recharges, retraits et historiques.

---

## 🎯 Problème résolu

### Avant (système actuel)
```
users.wallet_balance = 5000 FCFA
```
- Un seul solde
- Impossibilité de savoir combien vient de FreeMoPay vs PayPal
- Affichage du même solde pour les deux méthodes de retrait
- **Dashboard admin négatif** car mélange transactions plateforme + utilisateur

### Après (nouveau système)
```
users.freemopay_wallet_balance = 3000 FCFA
users.paypal_wallet_balance = 2000 FCFA
```
- Deux soldes distincts
- Chaque méthode de paiement a son propre wallet
- Affichage correct dans les retraits
- Dashboard admin séparé de l'argent des utilisateurs ✅

---

## 🚀 Étapes de migration

### 1. Exécuter les migrations de base de données

```bash
cd /path/to/backend
php artisan migrate
```

Cela va :
- Ajouter `freemopay_wallet_balance` et `paypal_wallet_balance` à la table `users`
- Ajouter la colonne `provider` à la table `wallet_transactions`

---

### 2. Tester en dry-run (RECOMMANDÉ)

```bash
php artisan wallet:migrate-separate --dry-run
```

Cette commande va :
- Analyser toutes les transactions existantes
- Détecter le provider (freemopay/paypal) pour chaque transaction
- Calculer les nouveaux soldes sans les sauvegarder
- Afficher un rapport détaillé

**Vérifiez que les totaux correspondent !**

---

### 3. Exécuter la migration réelle

```bash
php artisan wallet:migrate-separate
```

Cette commande va :
1. **ÉTAPE 1** : Migrer toutes les `WalletTransactions`
   - Ajouter le `provider` (freemopay/paypal) à chaque transaction
   - Basé sur le paiement associé, le retrait, ou la description

2. **ÉTAPE 2** : Calculer et attribuer les soldes
   - Calculer le solde FreeMoPay de chaque utilisateur
   - Calculer le solde PayPal de chaque utilisateur
   - Mettre à jour `freemopay_wallet_balance` et `paypal_wallet_balance`

---

## 🔍 Détection du provider

Le script détecte automatiquement le provider pour chaque transaction selon cet ordre :

1. **PlatformWithdrawal** : Si la transaction est liée à un retrait, on utilise le provider du retrait
2. **Payment** : Si la transaction a un paiement associé, on vérifie `payment.provider` ou `payment.payment_method`
3. **Description** : Si la description contient "paypal"
4. **Défaut** : FreeMoPay (le plus commun)

---

## 📊 Exemple de rapport

```
╔════════════════════════════════════════════════════════════════╗
║ Migration des wallets vers système séparé FreeMoPay/PayPal    ║
╚════════════════════════════════════════════════════════════════╝

📝 ÉTAPE 1: Migration des WalletTransactions...

   Transactions à migrer: 1547
   ✓ FreeMoPay: 1420 transactions
   ✓ PayPal: 127 transactions

💰 ÉTAPE 2: Calcul des soldes par provider...

   Utilisateurs avec solde: 234

   ✓ Total FreeMoPay: 4 567 890 FCFA
   ✓ Total PayPal: 1 234 567 FCFA
   ✓ Total général: 5 802 457 FCFA

✅ Migration terminée avec succès!
```

---

## ⚠️ Important

### Avant la migration

1. **Backup de la base de données** :
   ```bash
   mysqldump -u root -p database_name > backup_$(date +%Y%m%d).sql
   ```

2. **Vérifier qu'il n'y a pas de transactions en cours** (status = 'pending' ou 'processing')

3. **Informer les utilisateurs** qu'il peut y avoir une courte interruption

### Après la migration

1. **Vérifier les soldes** : Comparer l'ancien `wallet_balance` avec `freemopay_wallet_balance + paypal_wallet_balance`

2. **Tester les retraits** pour les deux méthodes

3. **Vérifier le dashboard admin** (`/admin/bank-account`)

---

## 🔄 Rollback (en cas de problème)

Si la migration échoue ou si vous devez revenir en arrière :

```bash
# Rollback des migrations
php artisan migrate:rollback --step=2

# Restaurer le backup
mysql -u root -p database_name < backup_YYYYMMDD.sql
```

---

## 📁 Fichiers modifiés

### Migrations
- `2026_02_27_005003_add_separate_wallet_balances_to_users_table.php`
- `2026_02_27_005107_add_provider_to_wallet_transactions_table.php`

### Commandes
- `app/Console/Commands/MigrateSeparateWallets.php`

### Modèles (à modifier ensuite)
- `app/Models/User.php`
- `app/Models/WalletTransaction.php`

### Services (à modifier ensuite)
- `app/Services/WalletService.php`

### Controllers (à modifier ensuite)
- `app/Http/Controllers/Api/WalletController.php`
- `app/Http/Controllers/Admin/BankAccountController.php`

---

## ✅ Checklist finale

- [ ] Backup de la base de données effectué
- [ ] Migration dry-run exécutée et vérifiée
- [ ] Migration réelle exécutée avec succès
- [ ] Soldes vérifiés (ancien = nouveau total)
- [ ] Tests de recharge FreeMoPay ✅
- [ ] Tests de recharge PayPal ✅
- [ ] Tests de retrait FreeMoPay ✅
- [ ] Tests de retrait PayPal ✅
- [ ] Dashboard admin vérifié (plus de négatifs) ✅
- [ ] Frontend Flutter mis à jour ✅

---

## 🆘 Support

En cas de problème, contactez l'équipe de développement avec :
1. Le rapport complet de la migration
2. Une capture d'écran du problème
3. Les logs Laravel (`storage/logs/laravel.log`)
