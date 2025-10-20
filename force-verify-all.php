<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Force verifying ALL unverified users...\n\n";

// Get all unverified users
$unverifiedUsers = DB::table('users')
    ->whereNull('email_verified_at')
    ->where('is_active', 1)
    ->get();

if ($unverifiedUsers->isEmpty()) {
    echo "✓ All users are already verified!\n\n";
    exit(0);
}

echo "Found {$unverifiedUsers->count()} unverified user(s)\n\n";

foreach ($unverifiedUsers as $user) {
    echo "Verifying: {$user->name} ({$user->email})... ";
    
    // Direct database update
    DB::table('users')
        ->where('id', $user->id)
        ->update([
            'email_verified_at' => now(),
            'updated_at' => now()
        ]);
    
    // Update member
    DB::table('members')
        ->where('email', $user->email)
        ->update([
            'membership_status' => 'active',
            'updated_at' => now()
        ]);
    
    // Delete verification token
    DB::table('email_verifications')
        ->where('user_id', $user->id)
        ->delete();
    
    echo "✓\n";
}

echo "\n✅ All users verified!\n";
echo "Everyone can now login at: http://127.0.0.1:8000/login\n\n";
