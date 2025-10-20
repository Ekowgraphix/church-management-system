<?php

/**
 * Quick Switch to Log Mail Driver
 * This script updates .env to use log driver for email
 * Perfect for development/testing without SMTP setup
 */

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║     SWITCH TO LOG MAIL DRIVER (Development Mode)         ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

$envFile = __DIR__ . '/.env';

if (!file_exists($envFile)) {
    echo "✗ Error: .env file not found!\n";
    echo "  Please copy .env.example to .env first.\n";
    exit(1);
}

echo "Reading .env file...\n";
$envContent = file_get_contents($envFile);

echo "Updating mail configuration to use log driver...\n\n";

// Update or add MAIL_MAILER
if (preg_match('/^MAIL_MAILER=(.*)$/m', $envContent)) {
    $envContent = preg_replace('/^MAIL_MAILER=(.*)$/m', 'MAIL_MAILER=log', $envContent);
    echo "✓ Updated MAIL_MAILER to 'log'\n";
} else {
    // Add if doesn't exist
    $envContent .= "\nMAIL_MAILER=log\n";
    echo "✓ Added MAIL_MAILER=log\n";
}

// Comment out other mail settings
$mailSettings = [
    'MAIL_HOST',
    'MAIL_PORT',
    'MAIL_USERNAME',
    'MAIL_PASSWORD',
    'MAIL_ENCRYPTION',
];

foreach ($mailSettings as $setting) {
    if (preg_match('/^' . $setting . '=(.*)$/m', $envContent)) {
        $envContent = preg_replace('/^(' . $setting . '=.*)$/m', '# $1', $envContent);
        echo "✓ Commented out $setting\n";
    }
}

// Ensure MAIL_FROM settings exist
if (!preg_match('/^MAIL_FROM_ADDRESS=/m', $envContent)) {
    $envContent .= "MAIL_FROM_ADDRESS=\"noreply@yourchurch.com\"\n";
    echo "✓ Added MAIL_FROM_ADDRESS\n";
}

if (!preg_match('/^MAIL_FROM_NAME=/m', $envContent)) {
    $envContent .= 'MAIL_FROM_NAME="${APP_NAME}"' . "\n";
    echo "✓ Added MAIL_FROM_NAME\n";
}

// Write back to .env
if (file_put_contents($envFile, $envContent)) {
    echo "\n✓ Successfully updated .env file!\n\n";
    
    echo "╔═══════════════════════════════════════════════════════════╗\n";
    echo "║                    CONFIGURATION COMPLETE                 ║\n";
    echo "╚═══════════════════════════════════════════════════════════╝\n\n";
    
    echo "What this means:\n";
    echo "  • All emails will be written to storage/logs/laravel.log\n";
    echo "  • No actual emails will be sent\n";
    echo "  • Perfect for development and testing\n";
    echo "  • You can copy verification URLs from the log file\n\n";
    
    echo "Next steps:\n";
    echo "  1. Clear Laravel cache:\n";
    echo "     php artisan config:clear\n";
    echo "     php artisan cache:clear\n\n";
    echo "  2. Test signup at: http://localhost/churchmang/public/signup\n\n";
    echo "  3. After signup, check the verification URL in:\n";
    echo "     storage/logs/laravel.log\n\n";
    echo "  4. Or use manual verification:\n";
    echo "     php manual-verify-user.php\n\n";
    
    echo "To switch back to SMTP later, edit .env and update MAIL_MAILER\n";
    echo "and uncomment the mail settings.\n\n";
    
} else {
    echo "\n✗ Error: Could not write to .env file!\n";
    echo "  Please check file permissions.\n";
    exit(1);
}

echo "═══════════════════════════════════════════════════════════\n\n";
