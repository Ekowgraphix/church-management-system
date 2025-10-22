<?php

echo "==========================================\n";
echo "ASSIGN 'Church Member' ROLE TO USER\n";
echo "==========================================\n\n";

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

$email = $argv[1] ?? 'ekowjeremy@gmail.com';

echo "Looking for user: $email\n\n";

$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ User not found: $email\n";
    exit(1);
}

echo "✅ Found user: {$user->name} ({$user->email})\n\n";

// Check if role exists
$role = Role::where('name', 'Church Member')->first();

if (!$role) {
    echo "❌ 'Church Member' role doesn't exist!\n";
    echo "Creating it...\n\n";
    $role = Role::create(['name' => 'Church Member']);
    echo "✅ Created 'Church Member' role\n\n";
}

// Check if user already has role
if ($user->hasRole('Church Member')) {
    echo "✓ User already has 'Church Member' role\n\n";
} else {
    echo "Assigning 'Church Member' role...\n";
    $user->assignRole('Church Member');
    echo "✅ Role assigned successfully!\n\n";
}

// Verify
$user = $user->fresh();
echo "Current roles:\n";
foreach ($user->roles as $role) {
    echo "  • {$role->name}\n";
}

echo "\n==========================================\n";
echo "✅ COMPLETE!\n";
echo "==========================================\n\n";

echo "User {$user->email} now has 'Church Member' role.\n";
echo "They can now access:\n";
echo "  • Member portal\n";
echo "  • QR Check-In\n";
echo "  • Member dashboard\n\n";

echo "Test it: http://127.0.0.1:8000/qr-checkin/service/scanner\n\n";
