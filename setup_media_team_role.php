<?php

echo "==========================================\n";
echo "SETTING UP MEDIA TEAM ROLE\n";
echo "==========================================\n\n";

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "1. Creating 'Media Team' role...\n";

$role = Role::firstOrCreate(['name' => 'Media Team']);

echo "✅ Role created/exists: {$role->name} (ID: {$role->id})\n\n";

echo "2. Creating permissions for Media Team...\n";

$permissions = [
    'access_media_portal',
    'upload_media',
    'manage_media_library',
    'manage_galleries',
    'control_livestream',
    'schedule_media_events',
    'use_ai_tools',
    'create_announcements',
    'manage_media_team',
    'view_media_analytics',
];

foreach ($permissions as $permissionName) {
    $permission = Permission::firstOrCreate(['name' => $permissionName]);
    echo "  ✓ Created permission: {$permissionName}\n";
    
    // Assign to role
    if (!$role->hasPermissionTo($permissionName)) {
        $role->givePermissionTo($permissionName);
    }
}

echo "\n✅ All permissions assigned to Media Team role\n\n";

echo "3. Creating test media team user...\n";

use App\Models\User;

$mediaUser = User::firstOrCreate(
    ['email' => 'media@church.com'],
    [
        'name' => 'Media Team Lead',
        'password' => bcrypt('password'),
        'is_active' => true,
    ]
);

if (!$mediaUser->hasRole('Media Team')) {
    $mediaUser->assignRole('Media Team');
    echo "✅ Assigned 'Media Team' role to: {$mediaUser->email}\n";
} else {
    echo "✓ User already has Media Team role\n";
}

echo "\n==========================================\n";
echo "✅ MEDIA TEAM SETUP COMPLETE!\n";
echo "==========================================\n\n";

echo "Login Credentials:\n";
echo "-------------------\n";
echo "Email: media@church.com\n";
echo "Password: password\n\n";

echo "Role: Media Team\n";
echo "Permissions: " . count($permissions) . " permissions granted\n\n";

echo "Next: Access media portal at /media/dashboard\n\n";
