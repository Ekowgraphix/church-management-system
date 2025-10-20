<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║           GET VERIFICATION LINK FOR YOUR EMAIL            ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

$verifications = DB::table('email_verifications')
    ->join('users', 'email_verifications.user_id', '=', 'users.id')
    ->select('users.name', 'users.email', 'email_verifications.token', 'email_verifications.created_at')
    ->orderBy('email_verifications.created_at', 'desc')
    ->get();

if ($verifications->isEmpty()) {
    echo "No pending verifications found.\n";
    echo "All users are already verified!\n\n";
    exit(0);
}

echo "Pending Email Verifications:\n";
echo str_repeat("=", 80) . "\n\n";

foreach ($verifications as $v) {
    $verifyUrl = url('/verify-email/' . $v->token);
    $age = \Carbon\Carbon::parse($v->created_at)->diffForHumans();
    
    echo "Name: {$v->name}\n";
    echo "Email: {$v->email}\n";
    echo "Created: $age\n";
    echo "\n";
    echo "🔗 VERIFICATION LINK:\n";
    echo "   {$verifyUrl}\n";
    echo "\n";
    echo str_repeat("-", 80) . "\n\n";
}

echo "INSTRUCTIONS:\n";
echo "1. Copy the verification URL above\n";
echo "2. Paste it in your browser\n";
echo "3. You'll be redirected to login\n";
echo "4. Login with your email and password\n\n";

echo "Or use manual verification:\n";
echo "   php manual-verify-user.php\n\n";
