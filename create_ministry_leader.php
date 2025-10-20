<?php
/**
 * Quick Script to Create Ministry Leader Test Account
 * 
 * Run this from your browser: http://127.0.0.1:8000/create_ministry_leader.php
 * Or run via command line: php create_ministry_leader.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

echo "==============================================\n";
echo "Creating Ministry Leader Test Account\n";
echo "==============================================\n\n";

// Check if Ministry Leader role exists
$role = Role::where('name', 'Ministry Leader')->first();

if (!$role) {
    echo "❌ ERROR: Ministry Leader role does not exist in database.\n";
    echo "Please run: php artisan db:seed --class=RolesSeeder\n";
    exit(1);
}

echo "✅ Ministry Leader role found (ID: {$role->id})\n\n";

// Check if user already exists
$existingUser = User::where('email', 'leader@church.com')->first();

if ($existingUser) {
    echo "⚠️  User already exists with email: leader@church.com\n";
    echo "Checking role assignment...\n";
    
    if ($existingUser->hasRole('Ministry Leader')) {
        echo "✅ User already has Ministry Leader role\n\n";
    } else {
        echo "Assigning Ministry Leader role...\n";
        $existingUser->roles()->attach($role->id);
        echo "✅ Ministry Leader role assigned\n\n";
    }
    
    echo "==============================================\n";
    echo "LOGIN CREDENTIALS:\n";
    echo "==============================================\n";
    echo "Email: leader@church.com\n";
    echo "Password: password\n";
    echo "Role: Ministry Leader\n";
    echo "==============================================\n\n";
    echo "Login URL: http://127.0.0.1:8000/login\n";
    echo "Portal URL: http://127.0.0.1:8000/ministry-leader/dashboard\n\n";
    exit(0);
}

// Create new Ministry Leader user
try {
    $user = User::create([
        'name' => 'John Leader',
        'email' => 'leader@church.com',
        'password' => Hash::make('password'),
        'verified' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "✅ User created successfully (ID: {$user->id})\n";
    
    // Assign Ministry Leader role
    $user->roles()->attach($role->id);
    echo "✅ Ministry Leader role assigned\n\n";
    
    echo "==============================================\n";
    echo "✅ MINISTRY LEADER ACCOUNT CREATED!\n";
    echo "==============================================\n\n";
    
    echo "LOGIN CREDENTIALS:\n";
    echo "==============================================\n";
    echo "Name: John Leader\n";
    echo "Email: leader@church.com\n";
    echo "Password: password\n";
    echo "Role: Ministry Leader\n";
    echo "==============================================\n\n";
    
    echo "TESTING INSTRUCTIONS:\n";
    echo "==============================================\n";
    echo "1. Visit: http://127.0.0.1:8000/login\n";
    echo "2. Click on 'Ministry Leader' role card\n";
    echo "3. Enter email: leader@church.com\n";
    echo "4. Enter password: password\n";
    echo "5. Click 'Sign In'\n";
    echo "6. You will be redirected to: /ministry-leader/dashboard\n";
    echo "==============================================\n\n";
    
    echo "AVAILABLE PORTAL PAGES:\n";
    echo "==============================================\n";
    echo "✅ Dashboard: /ministry-leader/dashboard\n";
    echo "✅ Members: /ministry-leader/members\n";
    echo "✅ Groups: /ministry-leader/groups\n";
    echo "✅ Events: /ministry-leader/events\n";
    echo "✅ Prayer Requests: /ministry-leader/prayer-requests\n";
    echo "✅ Reports: /ministry-leader/reports\n";
    echo "✅ Communication: /ministry-leader/communication\n";
    echo "✅ AI Assistant: /ministry-leader/ai-assistant\n";
    echo "✅ Settings: /ministry-leader/settings\n";
    echo "==============================================\n\n";
    
    echo "🎉 SUCCESS! Ministry Leader portal is ready to test!\n\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
