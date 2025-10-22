<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "==========================================\n";
echo "ENSURE MEDIA TEAM ROLE EXISTS\n";
echo "==========================================\n\n";

use Spatie\Permission\Models\Role;

// Check if Media Team role exists
$mediaRole = Role::where('name', 'Media Team')->first();

if (!$mediaRole) {
    echo "❌ Media Team role does NOT exist!\n";
    echo "Creating Media Team role...\n\n";
    
    $mediaRole = Role::create(['name' => 'Media Team']);
    
    echo "✅ Media Team role created successfully!\n";
} else {
    echo "✅ Media Team role already exists!\n";
    echo "Role ID: {$mediaRole->id}\n";
    echo "Role Name: {$mediaRole->name}\n";
}

echo "\n";
echo "==========================================\n";
echo "Checking all roles in system:\n";
echo "==========================================\n";

$allRoles = Role::all();
foreach ($allRoles as $role) {
    echo "• {$role->name} (ID: {$role->id})\n";
}

echo "\n✅ Media Team role is ready to use!\n";
echo "==========================================\n";
