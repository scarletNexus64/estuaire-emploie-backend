# Referral System - Quick Reference Guide

## File Locations Summary

### Models
- `/app/Models/User.php` - Referral code generation, relationships
- `/app/Models/ReferralCommission.php` - Commission records
- `/app/Models/WalletTransaction.php` - Wallet transaction history
- `/app/Models/Payment.php` - Payment records
- `/app/Models/Setting.php` - Admin settings storage

### Services
- `/app/Services/ReferralCommissionService.php` - Core commission logic
- `/app/Services/WalletService.php` - Wallet credit/debit operations

### Controllers
- `/app/Http/Controllers/Api/AuthController.php` - User registration with referral code
- `/app/Http/Controllers/Api/WalletController.php` - Wallet recharge (commission trigger)
- `/app/Http/Controllers/PaymentCallbackController.php` - Payment completion handling
- `/app/Http/Controllers/Admin/SettingsController.php` - Admin referral settings

### Database
- `/database/migrations/2026_02_25_222650_add_referral_fields_to_users_table.php` - User referral fields
- `/database/migrations/2026_02_25_222651_create_referral_commissions_table.php` - Commission table

---

## Database Schema at a Glance

### users table (additions)
```
referral_code VARCHAR(255) UNIQUE - User's shareable code
referred_by_id BIGINT UNSIGNED - Who referred this user
```

### referral_commissions table
```
id, referrer_id, referred_id, transaction_type, transaction_reference
transaction_amount, commission_percentage, commission_amount, timestamps
```

### wallet_transactions table
```
Records commission credits with metadata pointing to referral_commission_id
```

---

## API Endpoints

### Registration (with optional referral code)
```
POST /api/register
Body: {
  "name": string,
  "email": string,
  "password": string,
  "phone": string,
  "referral_code": "ABC12345"  // Optional but must exist if provided
}
Returns: user with auto-generated referral_code and referred_by_id
```

### Wallet Recharge (triggers commission)
```
POST /api/wallet/recharge
POST /api/wallet/paypal/execute
POST /api/wallet/paypal/capture-native-order
Automatically processes commission when payment completes
```

---

## Commission Flow (Simplified)

```
User A registers with User B's referral code
                    ↓
User A's referred_by_id = User B's ID
User A gets new referral code (auto-generated)
                    ↓
User A recharges wallet (PayPal/FreeMoPay)
                    ↓
Payment marked as 'completed'
                    ↓
ReferralCommissionService.processReferralCommission() called
                    ↓
Calculate: amount * percentage / 100
                    ↓
Create ReferralCommission record
Create WalletTransaction (credit) for User B
User B's wallet_balance += commission_amount
```

---

## Key Classes & Methods

### User Model
```php
// Auto-generation on create
User::generateUniqueReferralCode() // Returns 8-char uppercase code

// Relationships
$user->referrer()              // The person who referred me
$user->referrals()             // People I referred
$user->earnedCommissions()     // My commissions as referrer
$user->generatedCommissions()  // Commissions I generated for my referrer
$user->getTotalEarnedCommissions() // Sum of all my commissions
```

### ReferralCommissionService
```php
processReferralCommission(User $user, Payment $payment)
  // Returns: ReferralCommission or null
  // Does: Create commission record + credit wallet
  // Checks:
  // 1. System enabled?
  // 2. User has referrer?
  // 3. Referrer exists?
  // Then: Calculate → Create record → Credit wallet
```

### WalletService
```php
credit(
  User $user,
  float $amount,
  ?Payment $payment,
  string $description,
  array $metadata
)
  // Updates user.wallet_balance
  // Creates WalletTransaction (type: 'credit')
  // Returns: WalletTransaction
```

---

## Admin Settings

### Configurable Via Admin Panel
```
referral_enabled: boolean
  Enable/disable entire system (default: false)

referral_commission_percentage: numeric (0-100)
  Commission rate (default: 5%)
```

### Endpoint
```
PUT /settings/referral (web route, not API)
Body: {
  "referral_enabled": true,
  "referral_commission_percentage": 5
}
```

---

## Important Constants

| Field | Type | Example |
|-------|------|---------|
| referral_code | VARCHAR(255) | "F4A2B8C1" |
| Commission rate | DECIMAL(5,2) | 5.00 (%) |
| Commission amount | DECIMAL(10,2) | 500.00 (FCFA) |
| Transaction amount | DECIMAL(10,2) | 10000.00 (FCFA) |

---

## Validation Rules

### Registration
```php
'referral_code' => 'nullable|string|exists:users,referral_code'
```
Must exist in users table if provided

### Settings
```php
'referral_commission_percentage' => 'required|numeric|min:0|max:100'
```
Between 0 and 100%

---

## Transaction Safety

Uses Laravel database transactions (atomic operations):
```php
DB::transaction(function () {
  // Create commission record
  // Update wallet balance
  // Create transaction record
  // If any step fails, all rollback
});
```

---

## Error Handling

### Silent Returns (No Error Raised)
- User has no referrer (referred_by_id is null)
- System is disabled (referral_enabled = false)
- Referrer deleted from database

### Validation Errors
- Referral code doesn't exist (422 error during registration)
- Invalid commission percentage (422 error in settings)

---

## How to Debug

### Check if Commission Exists
```sql
SELECT * FROM referral_commissions
WHERE referred_id = {user_id}
ORDER BY created_at DESC;
```

### Check Wallet Transaction
```sql
SELECT * FROM wallet_transactions
WHERE user_id = {referrer_id}
AND type = 'credit'
AND description LIKE 'Commission de parrainage%'
ORDER BY created_at DESC;
```

### Check Settings
```sql
SELECT * FROM settings
WHERE key IN ('referral_enabled', 'referral_commission_percentage');
```

### Check User Relationships
```sql
SELECT id, name, referral_code, referred_by_id FROM users
WHERE referred_by_id = {referrer_id};
```

---

## Payment Flow Details

### When Commission is Triggered
1. Payment status = 'completed' (after PayPal/FreeMoPay confirmation)
2. User has referred_by_id (not null)
3. System enabled (referral_enabled = true)

### Calculation
```
commission_amount = transaction_amount * (commission_percentage / 100)
Example: 10,000 FCFA * (5 / 100) = 500 FCFA
```

### What Counts
- Only wallet_recharge payments
- Both PayPal and FreeMoPay
- Does NOT include subscription payments or addon services

---

## Key Files to Review

Priority order for understanding the system:

1. **User Model** - Understand referral code generation
   `/app/Models/User.php` (Lines 567-629)

2. **ReferralCommissionService** - Core business logic
   `/app/Services/ReferralCommissionService.php`

3. **AuthController** - Registration flow
   `/app/Http/Controllers/Api/AuthController.php` (Lines 57-129)

4. **WalletController** - Commission trigger point
   `/app/Http/Controllers/Api/WalletController.php` (Lines 776-813)

5. **PaymentCallbackController** - Payment completion
   `/app/Http/Controllers/PaymentCallbackController.php` (Lines 158-195)

6. **SettingsController** - Admin management
   `/app/Http/Controllers/Admin/SettingsController.php` (Lines 210-225)

---

## Common Queries

### Get All Commissions for a User
```php
$user->earnedCommissions()->with('referred')->get();
```

### Get Total Earned
```php
$user->getTotalEarnedCommissions(); // Returns float
```

### Get Referral Count
```php
$user->referrals()->count();
```

### Get Referrer Info
```php
$user->referrer; // BelongsTo relationship
$referrerCode = $user->referrer->referral_code ?? null;
```

---

## Testing Commission Flow

```php
// Create referrer
$referrer = User::factory()->create();
$referrerCode = $referrer->referral_code;

// Create referred user
$referred = User::factory()->create([
  'referred_by_id' => $referrer->id
]);

// Create payment
$payment = Payment::create([
  'user_id' => $referred->id,
  'amount' => 10000,
  'payment_method' => 'paypal',
  'status' => 'completed'
]);

// Process commission
$commissionService = app(ReferralCommissionService::class);
$commission = $commissionService->processReferralCommission($referred, $payment);

// Check results
assert($commission !== null);
assert($referrer->fresh()->wallet_balance > 0);
assert($commission->commission_amount == 500); // 5% of 10000
```

