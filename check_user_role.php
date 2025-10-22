<?php

echo "==========================================\n";
echo "CHECKING USER ROLES\n";
echo "==========================================\n\n";

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "Enter user email to check:\n";
echo "(Or press Enter for: ekowjeremy@gmail.com)\n\n";

$email = trim(fgets(STDIN));
if (empty($email)) {
    $email = 'ekowjeremy@gmail.com';
}

$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ User not found: $email\n\n";
    echo "Available users:\n";
    $users = User::take(10)->get();
    foreach ($users as $u) {
        echo "  • {$u->name} ({$u->email})\n";
    }
    exit;
}

echo "✅ Found user: {$user->name}\n";
echo "   Email: {$user->email}\n";
echo "   ID: {$user->id}\n\n";

echo "Roles:\n";
echo "------\n";

$roles = $user->roles;

if ($roles->count() > 0) {
    foreach ($roles as $role) {
        echo "  ✓ {$role->name}\n";
    }
} else {
    echo "  ❌ NO ROLES ASSIGNED!\n";
}

echo "\n";

// Check specific permissions
echo "Permissions Check:\n";
echo "------------------\n";

if ($user->hasRole('Church Member')) {
    echo "  ✅ HAS 'Church Member' role - Can access member portal\n";
} else {
    echo "  ❌ MISSING 'Church Member' role - CANNOT access member portal\n";
    echo "     This is why you're being redirected!\n";
}

if ($user->hasRole('Admin')) {
    echo "  ✅ HAS 'Admin' role\n";
}

if ($user->hasRole('Pastor')) {
    echo "  ✅ HAS 'Pastor' role\n";
}

echo "\n";

echo "Member Profile Check:\n";
echo "---------------------\n";

$member = \App\Models\Member::where('email', $user->email)->first();

if ($member) {
    echo "  ✅ Has member profile\n";
    echo "     Name: {$member->full_name}\n";
    echo "     ID: {$member->id}\n";
} else {
    echo "  ❌ NO member profile found\n";
    echo "     Email mismatch between user and member?\n";
}

echo "\n==========================================\n";
echo "RECOMMENDATION:\n";
echo "==========================================\n\n";

if (!$user->hasRole('Church Member')) {
    echo "This user needs the 'Church Member' role!\n\n";
    echo "To fix, run this SQL:\n";
    echo "INSERT INTO model_has_roles (role_id, model_type, model_id)\n";
    echo "SELECT 6, 'App\\\\Models\\\\User', {$user->id}\n";
    echo "WHERE NOT EXISTS (\n";
    echo "  SELECT 1 FROM model_has_roles \n";
    echo "  WHERE role_id = 6 AND model_id = {$user->id}\n";
    echo ");\n\n";
    
    echo "Or use this script:\n";
    echo "php assign_member_role.php {$user->email}\n\n";
}

echo "==========================================\n";
