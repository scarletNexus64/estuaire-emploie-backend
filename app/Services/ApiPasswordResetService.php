<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ApiPasswordResetService
{
    public function issue(string $model, string $field, string $identifier, string $code): ?string
    {
        $key = 'password-reset-verify:'.hash('sha256', $field.':'.$identifier);
        if (RateLimiter::tooManyAttempts($key, 10)) return null;
        RateLimiter::hit($key, 600);
        return DB::transaction(function () use ($model, $field, $identifier, $code) {
            $user = User::where($field, $identifier)->lockForUpdate()->first();
            $record = $model::where($field, $identifier)->where('code', $code)->where('verified', false)
                ->where('expires_at', '>', now())->lockForUpdate()->first();
            if (!$record || !$user || !$user->is_active) return null;
            // Pre-created identities must first pass the administrative recovery workflow.
            if (\App\Models\Company::where('recovery_user_id', $user->id)->whereNull('recovery_claimed_at')->exists()) return null;
            $record->update(['verified' => true]);
            $token = Str::random(64);
            DB::table('api_password_reset_grants')->insert([
                'token_hash' => hash('sha256', $token), 'user_id' => $user->id, 'expires_at' => now()->addMinutes(10),
            ]);
            return $token;
        });
    }
}
