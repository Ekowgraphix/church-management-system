<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking mail configuration...\n\n";
echo "config('mail.default'): " . config('mail.default') . "\n";
echo "env('MAIL_MAILER'): " . env('MAIL_MAILER') . "\n\n";

if (config('mail.default') === 'log') {
    echo "✓ Log driver is active - auto-verification SHOULD work\n";
} else {
    echo "✗ Not using log driver - auto-verification won't work\n";
    echo "  Current driver: " . config('mail.default') . "\n";
}
