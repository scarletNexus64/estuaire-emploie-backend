<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    */

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    // Registration / Login
    'identifier_required' => 'An email or a phone number is required.',
    'identifier_required_short' => 'Please provide an email or a phone number.',
    'registration_success' => 'Registration successful',
    'login_success' => 'Login successful',
    'login_failed' => 'Incorrect identifier or password.',
    'logout_success' => 'Logout successful',

    // Device
    'device_locked_pending' => 'This account is linked to another device. You already have a change request being processed.',
    'device_locked' => 'This account is linked to another device. Please submit a device change request which will be validated by an administrator.',

    // Role
    'already_in_role_recruiter' => 'You are already in recruiter mode',
    'already_in_role_candidate' => 'You are already in candidate mode',
    'role_switched' => 'Role switched successfully',
    'role_updated' => 'Role updated successfully',

    // Profile
    'profile_updated' => 'Profile updated successfully',

    // Password
    'no_account_with_email' => 'No account found with this email',
    'email_verified' => 'Email verified successfully',
    'verify_email_otp_first' => 'Please first verify the OTP code sent to your email.',
    'verify_phone_otp_first' => 'Please first verify the OTP code sent to your phone.',
    'no_account_with_identifier' => 'No account found with this identifier',
    'password_reset_success' => 'Password reset successfully',
    'incorrect_password' => 'Incorrect password',
    'old_password_incorrect' => 'The old password is incorrect',
    'new_password_must_differ' => 'The new password must be different from the old one',
    'password_changed' => 'Password changed successfully',

    // Account
    'account_deleted' => 'Account deleted successfully',
    'account_delete_error' => 'An error occurred while deleting the account',

    // Availability
    'provide_email_or_phone' => 'Please provide an email or a phone number',
    'email_already_used' => 'This email address is already in use',
    'phone_already_used' => 'This phone number is already in use',
    'available' => 'Available',
];
