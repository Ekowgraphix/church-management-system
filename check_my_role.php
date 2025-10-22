<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "==========================================\n";
echo "CHECK YOUR USER ROLE\n";
echo "==========================================\n\n";

// Get the Media Team Lead user
$user = \App\Models\User::where('email', 'media@church.com')->first();

if (!$user) {
    echo "❌ User 'media@church.com' not found\n";
    echo "Creating Media Team user...\n\n";
    
    // Create the user
    $user = \App\Models\User::create([
        'name' => 'Media Team Lead',
        'email' => 'media@church.com',
        'password' => Hash::make('password'),
        'is_active' => true,
    ]);
    
    // Check if Media Team role exists
    $role = Spatie\Permission\Models\Role::where('name', 'Media Team')->first();
    
    if (!$role) {
        echo "Creating Media Team role...\n";
        $role = Spatie\Permission\Models\Role::create(['name' => 'Media Team']);
    }
    
    // Assign role
    $user->assignRole('Media Team');
    
    echo "✅ User created and role assigned!\n\n";
}

echo "User Information:\n";
echo "=================\n";
echo "ID: {$user->id}\n";
echo "Name: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "Active: " . ($user->is_active ? 'Yes' : 'No') . "\n\n";

echo "Roles:\n";
echo "======\n";
$roles = $user->roles;
if ($roles->count() > 0) {
    foreach ($roles as $role) {
        echo "✅ {$role->name}\n";
    }
} else {
    echo "❌ No roles assigned!\n";
    echo "\nAssigning Media Team role...\n";
    
    $mediaRole = Spatie\Permission\Models\Role::where('name', 'Media Team')->first();
    if ($mediaRole) {
        $user->assignRole('Media Team');
        echo "✅ Media Team role assigned!\n";
    } else {
        echo "❌ Media Team role doesn't exist. Creating it...\n";
        $mediaRole = Spatie\Permission\Models\Role::create(['name' => 'Media Team']);
        $user->assignRole('Media Team');
        echo "✅ Role created and assigned!\n";
    }
}

echo "\n";
echo "Has Media Team Role: " . ($user->hasRole('Media Team') ? '✅ YES' : '❌ NO') . "\n";

echo "\n==========================================\n";
echo "Login with:\n";
echo "Email: media@church.com\n";
echo "Password: password\n";
echo "==========================================\n";
