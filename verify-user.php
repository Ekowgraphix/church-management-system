<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "Verifying user email...\n\n";

$user = User::where('email', 'member@church.test')->first();

if ($user) {
    $user->email_verified_at = now();
    $user->save();
    
    echo "✅ Email verified for: {$user->email}\n";
    echo "✅ User can now login!\n\n";
    
    echo "Login again with:\n";
    echo "   Email: member@church.test\n";
    echo "   Password: password123\n";
    echo "   Role: Church Member\n\n";
} else {
    echo "❌ User not found!\n";
}
