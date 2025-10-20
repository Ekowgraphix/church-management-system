<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "Checking user role assignments...\n\n";

$email = '98billybeams@beamers.com';
$user = User::where('email', $email)->first();

if (!$user) {
    echo "User not found!\n";
    exit(1);
}

echo "User: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "Active: " . ($user->is_active ? 'YES' : 'NO') . "\n";
echo "Email Verified: " . ($user->email_verified_at ? 'YES' : 'NO') . "\n\n";

echo "Roles:\n";
$roles = $user->roles;

if ($roles->isEmpty()) {
    echo "  ✗ NO ROLES ASSIGNED!\n\n";
    
    echo "Assigning 'Church Member' role...\n";
    $memberRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Church Member']);
    $user->assignRole($memberRole);
    echo "  ✓ Role assigned!\n\n";
    
} else {
    foreach ($roles as $role) {
        echo "  - {$role->name}\n";
    }
    echo "\n";
}

// Check directly in database
$modelHasRoles = DB::table('model_has_roles')
    ->where('model_type', 'App\\Models\\User')
    ->where('model_id', $user->id)
    ->get();

echo "Database role entries:\n";
if ($modelHasRoles->isEmpty()) {
    echo "  ✗ No entries in model_has_roles table!\n";
} else {
    foreach ($modelHasRoles as $entry) {
        $roleName = DB::table('roles')->where('id', $entry->role_id)->value('name');
        echo "  - Role ID {$entry->role_id}: {$roleName}\n";
    }
}

echo "\n";
