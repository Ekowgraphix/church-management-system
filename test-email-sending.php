<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;

echo "=== EMAIL SENDING TEST ===\n\n";

echo "Testing email configuration...\n";
echo "Mail Driver: " . config('mail.default') . "\n";
echo "Mail Host: " . config('mail.mailers.smtp.host') . "\n";
echo "Mail Port: " . config('mail.mailers.smtp.port') . "\n";
echo "From Address: " . config('mail.from.address') . "\n";
echo "From Name: " . config('mail.from.name') . "\n\n";

echo "Attempting to send test email...\n";

try {
    Mail::raw('This is a test email from Church Management System', function ($message) {
        $message->to('test@example.com')
            ->subject('Test Email - Church Management System');
    });
    
    echo "✓ Email queued successfully!\n";
    echo "Note: If using queue, check queue worker. If using sync, email was sent immediately.\n";
} catch (\Exception $e) {
    echo "✗ Email sending failed!\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "\nThis is why signup verification is not working.\n";
    echo "\nSolutions:\n";
    echo "1. Configure valid SMTP credentials in .env\n";
    echo "2. Use Mailtrap for testing (mailtrap.io)\n";
    echo "3. Use Gmail SMTP with app password\n";
    echo "4. Use log driver for development (emails will be written to log file)\n";
}

echo "\n";
