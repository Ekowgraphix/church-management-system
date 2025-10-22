<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "==========================================\n";
echo "FIX ADMIN ACCESS TO ALL PORTALS\n";
echo "==========================================\n\n";

use App\Models\User;
use Spatie\Permission\Models\Role;

// Required roles for full system access
$requiredRoles = ['Admin', 'Staff', 'Media Team'];

foreach ($requiredRoles as $roleName) {
    $role = Role::where('name', $roleName)->first();
    if (!$role) {
        echo "❌ {$roleName} role doesn't exist. Creating it...\n";
        Role::create(['name' => $roleName]);
        echo "✅ {$roleName} role created!\n";
    } else {
        echo "✅ {$roleName} role exists (ID: {$role->id})\n";
    }
}
echo "\n";

// Find admin user
$adminUser = User::where('email', 'admin@church.com')->first();

if (!$adminUser) {
    echo "❌ Admin user not found. Checking for other users...\n\n";
    
    // Show all users
    $users = User::all();
    if ($users->count() > 0) {
        echo "Available users:\n";
        foreach ($users as $user) {
            $roles = $user->roles->pluck('name')->implode(', ');
            echo "  • ID: {$user->id} | Email: {$user->email} | Name: {$user->name} | Roles: {$roles}\n";
        }
        
        echo "\nAssigning Media Team role to first user...\n";
        $firstUser = $users->first();
        if (!$firstUser->hasRole('Media Team')) {
            $firstUser->assignRole('Media Team');
            echo "✅ Media Team role assigned to: {$firstUser->email}\n";
        } else {
            echo "✅ User already has Media Team role\n";
        }
        
        echo "\n==========================================\n";
        echo "LOGIN CREDENTIALS:\n";
        echo "==========================================\n";
        echo "Email: {$firstUser->email}\n";
        echo "Password: password (default)\n";
        echo "\nURL: http://127.0.0.1:8000/login\n";
        echo "==========================================\n";
    } else {
        echo "❌ No users found in database!\n";
        echo "Please run seeders or create a user first.\n";
    }
} else {
    echo "✅ Admin user found: {$adminUser->email}\n\n";
    
    // Assign all required roles
    echo "Checking and assigning roles...\n";
    foreach ($requiredRoles as $roleName) {
        if ($adminUser->hasRole($roleName)) {
            echo "  ✅ Already has {$roleName} role\n";
        } else {
            $adminUser->assignRole($roleName);
            echo "  ➕ Added {$roleName} role\n";
        }
    }
    
    echo "\n==========================================\n";
    echo "CURRENT ROLES:\n";
    echo "==========================================\n";
    foreach ($adminUser->roles as $role) {
        echo "  • {$role->name}\n";
    }
    
    echo "\n==========================================\n";
    echo "LOGIN CREDENTIALS:\n";
    echo "==========================================\n";
    echo "Email: {$adminUser->email}\n";
    echo "Password: password (default)\n";
    echo "\n==========================================\n";
    echo "ACCESS TO:\n";
    echo "==========================================\n";
    echo "• Admin Portal:    http://127.0.0.1:8000/settings/dashboard\n";
    echo "• Main Dashboard:  http://127.0.0.1:8000/dashboard\n";
    echo "• Media Portal:    http://127.0.0.1:8000/media/dashboard\n";
    echo "• Team Management: http://127.0.0.1:8000/media/team\n";
    echo "==========================================\n";
}

echo "\n✅ Done! You now have full access to all portals.\n";
echo "\n🔐 Please log out and log back in for changes to take effect.\n";
