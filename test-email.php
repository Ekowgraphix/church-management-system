<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;

echo "Testing Email Configuration...\n\n";

// Check mail configuration
echo "Mail Driver: " . config('mail.default') . "\n";
echo "Mail Host: " . config('mail.mailers.smtp.host') . "\n";
echo "Mail Port: " . config('mail.mailers.smtp.port') . "\n";
echo "Mail From: " . config('mail.from.address') . "\n\n";

// Test email address - CHANGE THIS TO YOUR EMAIL
$testEmail = 'ekowjeremy@gmail.com'; // ⚠️ CHANGE THIS!

echo "Sending test email to: {$testEmail}\n";

try {
    Mail::raw('This is a test email from your Church Management System! 🎉', function($message) use ($testEmail) {
        $message->to($testEmail)
                ->subject('Test Email - Church Management System');
    });
    
    echo "\n✅ Email sent successfully!\n";
    echo "Check your inbox (and spam folder) for the test email.\n";
} catch (\Exception $e) {
    echo "\n❌ Error sending email:\n";
    echo $e->getMessage() . "\n";
    echo "\nPlease check:\n";
    echo "1. Your .env file has correct SendGrid API key\n";
    echo "2. MAIL_PASSWORD is set to your SendGrid API key\n";
    echo "3. MAIL_USERNAME is set to 'apikey'\n";
    echo "4. Run: php artisan config:clear\n";
}
