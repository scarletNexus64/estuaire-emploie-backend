# E-Emploie Backend - Referral Code (Code Parrain) System Documentation

## Executive Summary
The referral system is a commission-based incentive program where users can refer others and earn wallet credits when their referrals make wallet recharges. The system tracks referrers, referred users, and automatically calculates and credits commissions.

---

## 1. DATABASE SCHEMA

### 1.1 Users Table Extensions
**Migrations:** `2026_02_25_222650_add_referral_fields_to_users_table.php`

```sql
ALTER TABLE users ADD COLUMN:
- referral_code VARCHAR(255) UNIQUE NULLABLE -- Unique code for this user (generated auto)
- referred_by_id BIGINT UNSIGNED NULLABLE -- FK to the user who referred this user
  - Foreign key constraints('users', onDelete='set null')
```

**Key Characteristics:**
- `referral_code`: Automatically generated on user creation (8-character uppercase hash)
- `referred_by_id`: Set during registration if a referral code is provided
- Both fields are nullable to support users who weren't referred

### 1.2 Referral Commissions Table
**Migration:** `2026_02_25_222651_create_referral_commissions_table.php`

```sql
CREATE TABLE referral_commissions (
  id BIGINT PRIMARY KEY
  referrer_id BIGINT UNSIGNED -- FK to user who referred (receives commission)
  referred_id BIGINT UNSIGNED -- FK to user who was referred (made the transaction)
  transaction_type VARCHAR(255) -- 'paypal' or 'freemopay'
  transaction_reference VARCHAR(255) NULLABLE -- Payment transaction ID
  transaction_amount DECIMAL(10,2) -- Amount of the transaction that triggered commission
  commission_percentage DECIMAL(5,2) -- Commission rate at the time (e.g., 5%)
  commission_amount DECIMAL(10,2) -- Calculated commission (transaction_amount * commission_percentage / 100)
  timestamps (created_at, updated_at)
);
```

**Constraints:**
- Foreign keys with CASCADE delete on both referrer_id and referred_id
- Links to User model for both parties

### 1.3 Wallet Transactions Table
Records all wallet movements (credits, debits, refunds, bonuses, adjustments)
- `type`: 'credit' | 'debit' | 'refund' | 'bonus' | 'adjustment'
- `metadata`: JSON field storing additional context (including referral_commission_id for commission credits)
- Tracks balance before and after each transaction

---

## 2. DATA MODELS

### 2.1 User Model (`app/Models/User.php`)

**Referral-Related Attributes:**
```php
protected $fillable = [
    'referral_code',      // Generated automatically
    'referred_by_id',     // ID of the user who referred this user
];

// Auto-generation in boot method
protected static function boot() {
    parent::boot();
    static::creating(function ($user) {
        if (empty($user->referral_code)) {
            $user->referral_code = static::generateUniqueReferralCode();
        }
    });
}

// Generate 8-char uppercase hash
public static function generateUniqueReferralCode(): string {
    do {
        $code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
    } while (static::where('referral_code', $code)->exists());
    return $code;
}
```

**Referral Relationships:**
```php
// The referrer who referred this user
public function referrer(): BelongsTo {
    return $this->belongsTo(User::class, 'referred_by_id');
}

// Users this person has referred
public function referrals(): HasMany {
    return $this->hasMany(User::class, 'referred_by_id');
}

// Commissions earned as a referrer
public function earnedCommissions(): HasMany {
    return $this->hasMany(ReferralCommission::class, 'referrer_id');
}

// Commissions generated for the person who referred you
public function generatedCommissions(): HasMany {
    return $this->hasMany(ReferralCommission::class, 'referred_id');
}

// Total commissions earned
public function getTotalEarnedCommissions(): float {
    return $this->earnedCommissions()->sum('commission_amount');
}
```

### 2.2 ReferralCommission Model (`app/Models/ReferralCommission.php`)

```php
class ReferralCommission extends Model {
    protected $fillable = [
        'referrer_id',           // User earning the commission
        'referred_id',           // User who made the transaction
        'transaction_type',      // 'paypal' or 'freemopay'
        'transaction_reference', // Payment transaction ID
        'transaction_amount',    // Original transaction amount
        'commission_percentage', // Rate applied
        'commission_amount',     // Final commission paid out
    ];

    public function referrer(): BelongsTo {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referred(): BelongsTo {
        return $this->belongsTo(User::class, 'referred_id');
    }
}
```

### 2.3 WalletTransaction Model (`app/Models/WalletTransaction.php`)

Records commission credits with metadata:
```php
protected $fillable = [
    'user_id',
    'type',              // 'credit' for commissions
    'amount',            // Commission amount
    'balance_before',
    'balance_after',
    'description',       // "Commission de parrainage - Recharge de {user}"
    'payment_id',        // Original payment ID
    'metadata',          // ['referral_commission_id' => id, 'referred_user_id' => id]
    'status',            // 'completed'
];
```

---

## 3. API ENDPOINTS

### 3.1 Registration with Referral Code
**Endpoint:** `POST /api/register`

**Request Body:**
```json
{
  "name": "Jean Dupont",
  "email": "jean.dupont@example.com",
  "password": "password123",
  "phone": "+237690123456",
  "referral_code": "ABC12345"  // Optional - must exist in database
}
```

**Validation:**
- `referral_code` must exist in `users.referral_code` (validate with `exists:users,referral_code`)
- Must be unique code in the system

**Processing Logic (AuthController::register):**
1. Validate referral code exists
2. Look up referrer by code: `User::where('referral_code', $code)->first()`
3. Set `referred_by_id` to referrer's ID
4. User's referral_code is auto-generated by User::boot()

**Response:**
```json
{
  "user": { /* user object with referral_code and referred_by_id */ },
  "token": "auth-token",
  "message": "Inscription réussie"
}
```

### 3.2 Wallet Recharge (Trigger Point for Commission)
**Endpoints:**
- `POST /api/wallet/recharge` - Initiate recharge
- `POST /api/wallet/paypal/execute` - Execute PayPal payment
- `POST /api/wallet/paypal/capture-native-order` - Capture native PayPal order

**Processing Flow:**
1. User initiates recharge with amount and payment method
2. Payment is created with status 'pending'
3. User completes payment (PayPal or FreeMoPay)
4. Payment status becomes 'completed'
5. **ReferralCommissionService::processReferralCommission()** is called
6. Commission is calculated and wallet is credited

---

## 4. BUSINESS LOGIC

### 4.1 Referral Code Generation

**Location:** `app/Models/User.php` boot method

**When:** Automatically on user creation

**Algorithm:**
```php
// Generate 8-character uppercase hex string from uniqid + random
$code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

// Verify uniqueness
while (User::where('referral_code', $code)->exists()) {
    $code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
}
```

**Example:** `F4A2B8C1`, `D9E3K5L7`

### 4.2 Referral Code Validation During Registration

**Location:** `app/Http/Controllers/Api/AuthController.php` register method

**Validation Rules:**
```php
'referral_code' => 'nullable|string|exists:users,referral_code'
```

**Logic:**
```php
$referrerId = null;
if (!empty($validated['referral_code'])) {
    $referrer = User::where('referral_code', $validated['referral_code'])->first();
    if ($referrer) {
        $referrerId = $referrer->id;
    }
}

$user = User::create([
    // ... other fields
    'referred_by_id' => $referrerId,  // Only set if valid code provided
]);
```

**Validation Errors:**
- If code doesn't exist: 422 Validation error
- No code provided: referred_by_id remains null (not an error)

### 4.3 Commission Calculation

**Location:** `app/Services/ReferralCommissionService::processReferralCommission()`

**Trigger:** When wallet recharge payment completes

**Steps:**
1. Check if referral system is enabled (via Setting)
2. Verify referred user has a referrer (referred_by_id != null)
3. Verify referrer exists in database
4. Get commission percentage from Settings (default 5%)
5. Calculate: `commission = transaction_amount * percentage / 100`
6. Create ReferralCommission record
7. Credit referrer's wallet
8. Log transaction

**Code Flow:**
```php
public function processReferralCommission(User $user, Payment $payment): ?ReferralCommission {
    // 1. Check if enabled
    if (!$this->isReferralSystemEnabled()) {
        return null;
    }

    // 2. Check if user has referrer
    if (!$user->referred_by_id) {
        return null;
    }

    // 3. Get referrer
    $referrer = User::find($user->referred_by_id);
    if (!$referrer) {
        return null;
    }

    // 4. Get percentage from settings
    $commissionPercentage = $this->getCommissionPercentage(); // Default 5%

    // 5. Calculate amount
    $commissionAmount = ($payment->amount * $commissionPercentage) / 100;

    // 6. Create record
    $commission = ReferralCommission::create([
        'referrer_id' => $referrer->id,
        'referred_id' => $user->id,
        'transaction_type' => $payment->payment_method, // 'paypal' or 'freemopay'
        'transaction_reference' => $payment->transaction_id ?? $payment->id,
        'transaction_amount' => $payment->amount,
        'commission_percentage' => $commissionPercentage,
        'commission_amount' => $commissionAmount,
    ]);

    // 7. Credit wallet
    $this->walletService->credit(
        $referrer,
        $commissionAmount,
        null,
        "Commission de parrainage - Recharge de {$user->name}",
        [
            'referral_commission_id' => $commission->id,
            'referred_user_id' => $user->id,
            'recharge_amount' => $payment->amount,
        ]
    );

    return $commission;
}
```

**Settings Used:**
- `referral_enabled`: boolean - Turn system on/off
- `referral_commission_percentage`: float - Commission rate (0-100)

### 4.4 Commission Credit to Wallet

**Location:** `app/Services/WalletService::credit()`

**Wallet Credit Steps:**
1. Calculate new balance: `balance_after = balance_before + amount`
2. Update user's wallet_balance
3. Create WalletTransaction record with:
   - `type: 'credit'`
   - `description: "Commission de parrainage - Recharge de {referred_user}"`
   - `metadata` containing referral_commission_id
   - `status: 'completed'`

**Transaction Record:**
```php
WalletTransaction::create([
    'user_id' => $referrer->id,
    'type' => 'credit',
    'amount' => $commissionAmount,
    'balance_before' => $oldBalance,
    'balance_after' => $newBalance,
    'description' => "Commission de parrainage - Recharge de John Doe",
    'metadata' => [
        'referral_commission_id' => 15,
        'referred_user_id' => 42,
        'recharge_amount' => 10000,
    ],
    'status' => 'completed',
]);
```

### 4.5 Data Flow Diagram

```
User Registration
    |
    +-- Validate referral_code (if provided)
    |
    +-- Look up referrer by code
    |
    +-- Create User with referred_by_id
    |
    +-- Auto-generate referral_code for new user
    
User Wallet Recharge
    |
    +-- Create Payment (status: pending)
    |
    +-- Process with PayPal/FreeMoPay
    |
    +-- Mark Payment as completed
    |
    +-- Credit User's Wallet
    |
    +-- Check if user has referrer (referred_by_id)
    |
    +-- Get commission percentage from Settings
    |
    +-- Calculate commission amount
    |
    +-- Create ReferralCommission record
    |
    +-- Credit Referrer's Wallet
    |
    +-- Create WalletTransaction (type: credit)
```

---

## 5. REWARDS/RECHARGES SYSTEM

### 5.1 Commission Rewards

**What:** When a referred user makes a wallet recharge, the referrer receives a cash commission

**How Much:** Configurable percentage (admin setting)
- Default: 5%
- Example: $100 recharge = $5 commission

**When:** Immediately after recharge payment completes

**How Paid:** Credited to referrer's wallet balance (soft currency)

### 5.2 What Triggers Commission

**Only wallet recharges generate commissions:**
- PayPal wallet recharges
- FreeMoPay wallet recharges
- NOT subscription purchases (separate system)
- NOT addon services

**Payment Types Tracked:**
```php
$payment->payment_method // 'paypal' or 'freemopay'
$payment->payment_type   // 'wallet_recharge'
```

### 5.3 Wallet Management

**User's wallet_balance:**
- `DECIMAL(10, 2)` field on users table
- Updated when:
  - Wallet is recharged (credit)
  - Commission received (credit)
  - Used to pay for services (debit)
  - Admin adjustment (credit/debit)

**Wallet Can Be Used For:**
- Paying for subscriptions
- Paying for addon services
- Potentially other platform services

### 5.4 Commission Records

All commissions are recorded in `referral_commissions` table:
- Immutable historical record
- Tracks every referrer-referred pair transaction
- Includes transaction reference and amounts
- Can be audited from admin panel

---

## 6. ADMIN MANAGEMENT

### 6.1 Settings Management
**Location:** `app/Http/Controllers/Admin/SettingsController.php`

**Endpoint:** `PUT /settings/referral` (Web route, not API)

**Settings Updates:**
```php
public function updateReferralSettings(Request $request): RedirectResponse {
    $validated = $request->validate([
        'referral_enabled' => 'nullable|boolean',
        'referral_commission_percentage' => 'required|numeric|min:0|max:100',
    ]);

    Setting::setMany([
        'referral_enabled' => $request->has('referral_enabled') ? '1' : '0',
        'referral_commission_percentage' => $validated['referral_commission_percentage'],
    ]);
}
```

**Admin Panel Features:**
1. Enable/disable referral system
2. Set commission percentage (0-100%)
3. View all users with referral counts (paginated)
4. View all commission records (paginated)
5. Track referrer-referred relationships

### 6.2 Retrieving Referral Data

**SettingsController::index()**
```php
// Users with referral info
$users = User::with(['referrer', 'referrals'])
    ->withCount('referrals')
    ->orderBy('created_at', 'desc')
    ->paginate(20);

// Commission history
$commissions = ReferralCommission::with(['referrer', 'referred'])
    ->latest()
    ->paginate(20);
```

---

## 7. VALIDATION RULES

### 7.1 Registration Validation

```php
'referral_code' => 'nullable|string|exists:users,referral_code'
```

**Rules:**
- Optional field
- Must be string
- If provided, must exist exactly in users.referral_code column
- Case-sensitive

### 7.2 Commission Settings Validation

```php
'referral_enabled' => 'nullable|boolean'
'referral_commission_percentage' => 'required|numeric|min:0|max:100'
```

**Rules:**
- Percentage between 0 and 100 (inclusive)
- Can be decimal (e.g., 5.5%)

---

## 8. KEY INTEGRATION POINTS

### 8.1 Payment Callback Processing
**Location:** `app/Http/Controllers/PaymentCallbackController.php`

When payment succeeds:
```php
private function completeWalletRecharge(Payment $payment) {
    // 1. Mark payment as completed
    // 2. Credit user's wallet
    // 3. CREATE COMMISSION IF APPLICABLE
    $this->referralCommissionService->processReferralCommission($user, $payment);
}
```

### 8.2 Wallet Controller
**Location:** `app/Http/Controllers/Api/WalletController.php`

Commission processing integrated at:
- Line 812: After successful recharge

```php
private function completeWalletRecharge(Payment $payment) {
    // ... credit wallet ...
    $this->referralCommissionService->processReferralCommission($user, $payment);
}
```

### 8.3 Settings Helper
Uses Laravel's settings helper function:
```php
settings('referral_enabled', false)
settings('referral_commission_percentage', 5)
```

---

## 9. EXAMPLE FLOWS

### 9.1 Complete Registration with Referral

```
1. New user registers with referral code "ABC12345"
   POST /api/register
   {
     "name": "Alice",
     "email": "alice@example.com",
     "password": "password",
     "referral_code": "ABC12345"
   }

2. System validates:
   - Code "ABC12345" exists in users table ✓
   - User 'Bob' (id=5) has this code ✓

3. Create Alice with:
   - referral_code: "F4A2B8C1" (auto-generated)
   - referred_by_id: 5 (Bob's ID)

4. Response returns Alice with her new referral code
```

### 9.2 Complete Commission Flow

```
1. Alice (referred by Bob) recharges wallet with 10,000 FCFA
   POST /api/wallet/recharge
   {
     "amount": 10000,
     "payment_method": "paypal"
   }

2. System creates Payment record (status: pending)

3. User approves payment on PayPal

4. System executes payment:
   POST /api/wallet/paypal/execute
   {
     "payment_id": 18,
     "paymentId": "PAYID-XXX",
     "PayerID": "XXX"
   }

5. System marks payment as 'completed'

6. System credits Alice's wallet:
   - balance: 10,000

7. System processes referral commission:
   - Check: Alice has referred_by_id=5 (Bob) ✓
   - Get: commission_percentage = 5% (from settings)
   - Calculate: 10,000 * 5 / 100 = 500 FCFA
   - Create ReferralCommission:
     * referrer_id: 5 (Bob)
     * referred_id: Alice's ID
     * transaction_amount: 10,000
     * commission_amount: 500
   - Credit Bob's wallet: 500 FCFA
   - Create WalletTransaction for Bob

8. Bob's wallet now shows +500 FCFA with note:
   "Commission de parrainage - Recharge de Alice"
```

---

## 10. SYSTEM SETTINGS

**Settings Table Fields:**
```php
key: 'referral_enabled'
value: '1' or '0'

key: 'referral_commission_percentage'
value: '5' (or any 0-100 number)
```

**Caching:**
Settings are cached for 3600 seconds (1 hour) for performance

---

## 11. TECHNICAL CONSIDERATIONS

### 11.1 Transaction Safety
- ReferralCommissionService uses DB::transaction()
- Wallet updates and commission creation are atomic
- Rollback on error

### 11.2 Logging
- Detailed logging at each step
- Commission processing logged with amounts and user IDs
- Payment callbacks logged with full details

### 11.3 Edge Cases
1. **No referrer:** If referred_by_id is null, no commission created (silent return)
2. **Referrer not found:** If referrer is deleted, commission not created (logged warning)
3. **System disabled:** If referral_enabled is false, no commission created
4. **Multiple recharges:** Each recharge generates separate commission record

---

## Summary Table

| Aspect | Details |
|--------|---------|
| **Code Length** | 8 characters, uppercase hex |
| **Commission Trigger** | Wallet recharge completion |
| **Commission Rate** | Configurable (default 5%) |
| **Commission Payment** | Direct wallet credit |
| **Referral Relation** | One-to-many (one referrer, many referrals) |
| **Data Persistence** | referral_commissions table (immutable audit trail) |
| **Admin Control** | Settings for enable/disable and percentage |
| **Payment Methods** | PayPal, FreeMoPay |
| **Wallet Currency** | FCFA (soft currency) |

