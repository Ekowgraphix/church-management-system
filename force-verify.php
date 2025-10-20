<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Force verifying user with direct database update...\n\n";

$email = '98billybeams@beamers.com';

// Direct database update
$affected = DB::table('users')
    ->where('email', $email)
    ->update([
        'email_verified_at' => now(),
        'updated_at' => now()
    ]);

echo "Updated $affected row(s)\n";

// Update member
DB::table('members')
    ->where('email', $email)
    ->update([
        'membership_status' => 'active',
        'updated_at' => now()
    ]);

echo "Updated member status\n\n";

// Verify
$user = DB::table('users')->where('email', $email)->first();
echo "Verification check:\n";
echo "  email_verified_at: " . ($user->email_verified_at ?? 'NULL') . "\n\n";

if ($user->email_verified_at) {
    echo "✅ SUCCESS! User is now verified.\n";
    echo "You can login at: http://127.0.0.1:8000/login\n\n";
} else {
    echo "✗ Still not verified. Checking database...\n";
}
