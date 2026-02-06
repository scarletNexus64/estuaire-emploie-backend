#!/bin/bash

# Script to patch PayPal SDK for PHP 8+ compatibility
# This fixes the "sizeof(): Argument #1 ($value) must be of type Countable|array" error

echo "Patching PayPal SDK for PHP 8+ compatibility..."

PAYPAL_MODEL_FILE="vendor/paypal/rest-api-sdk-php/lib/PayPal/Common/PayPalModel.php"

if [ ! -f "$PAYPAL_MODEL_FILE" ]; then
    echo "❌ PayPal SDK not found. Run 'composer install' first."
    exit 1
fi

# Check if already patched
if grep -q "is_array(\$v) && sizeof(\$v) <= 0" "$PAYPAL_MODEL_FILE"; then
    echo "✅ PayPal SDK already patched!"
    exit 0
fi

# Apply patch: Change "sizeof($v) <= 0 && is_array($v)" to "is_array($v) && sizeof($v) <= 0"
sed -i.bak 's/sizeof(\$v) <= 0 \&\& is_array(\$v)/is_array(\$v) \&\& sizeof(\$v) <= 0/g' "$PAYPAL_MODEL_FILE"

if [ $? -eq 0 ]; then
    echo "✅ PayPal SDK patched successfully!"
    rm -f "${PAYPAL_MODEL_FILE}.bak"
else
    echo "❌ Failed to patch PayPal SDK"
    exit 1
fi
