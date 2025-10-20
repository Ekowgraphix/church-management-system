<?php
/**
 * Create All Test User Accounts for All Portals
 * 
 * This script creates test accounts for:
 * - Admin (already exists from seeder)
 * - Pastor
 * - Ministry Leader (already exists)
 * - Organization
 * - Volunteer (already exists)
 * - Church Member
 * 
 * Run: php create_all_test_users.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

echo "==============================================\n";
echo "CREATING ALL TEST USER ACCOUNTS\n";
echo "==============================================\n\n";

// Define all test users
$testUsers = [
    [
        'name' => 'Admin User',
        'email' => 'admin@church.com',
        'password' => 'password',
        'role' => 'Admin',
        'phone' => '+1234567890',
    ],
    [
        'name' => 'Pastor John',
        'email' => 'pastor@church.com',
        'password' => 'password',
        'role' => 'Pastor',
        'phone' => '+1234567891',
    ],
    [
        'name' => 'Ministry Leader Sarah',
        'email' => 'leader@church.com',
        'password' => 'password',
        'role' => 'Ministry Leader',
        'phone' => '+1234567892',
    ],
    [
        'name' => 'Organization Admin',
        'email' => 'org@church.com',
        'password' => 'password',
        'role' => 'Organization',
        'phone' => '+1234567893',
    ],
    [
        'name' => 'Volunteer Michael',
        'email' => 'volunteer@church.com',
        'password' => 'password',
        'role' => 'Volunteer',
        'phone' => '+1234567894',
    ],
    [
        'name' => 'Church Member',
        'email' => 'member@church.com',
        'password' => 'password',
        'role' => 'Church Member',
        'phone' => '+1234567895',
    ],
];

echo "📋 Creating test users for all portals...\n\n";

foreach ($testUsers as $userData) {
    echo "Processing: {$userData['name']} ({$userData['role']})\n";
    echo "Email: {$userData['email']}\n";
    
    // Check if role exists
    $role = Role::where('name', $userData['role'])->first();
    
    if (!$role) {
        echo "   ⚠️  Creating role: {$userData['role']}\n";
        $role = Role::create(['name' => $userData['role']]);
    }
    
    // Check if user already exists
    $existingUser = User::where('email', $userData['email'])->first();
    
    if ($existingUser) {
        echo "   ⚠️  User already exists\n";
        
        // Check if user has the role
        if ($existingUser->hasRole($userData['role'])) {
            echo "   ✅ User already has {$userData['role']} role\n";
        } else {
            echo "   📝 Assigning {$userData['role']} role...\n";
            $existingUser->assignRole($role);
            echo "   ✅ Role assigned\n";
        }
        
        // Ensure user is active and verified (for test accounts)
        if (!$existingUser->is_active || !$existingUser->email_verified_at) {
            $existingUser->update([
                'is_active' => true,
                'email_verified_at' => now(), // Test accounts don't need email verification
            ]);
            echo "   ✅ Account activated and verified\n";
        }
        
    } else {
        // Create new user
        try {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'phone' => $userData['phone'],
                'is_active' => true,
                'email_verified_at' => now(), // Test accounts don't need email verification
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            echo "   ✅ User created (ID: {$user->id})\n";
            
            // Assign role
            $user->assignRole($role);
            echo "   ✅ {$userData['role']} role assigned\n";
            
        } catch (\Exception $e) {
            echo "   ❌ Error: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n";
}

echo "==============================================\n";
echo "✅ ALL TEST USERS PROCESSED!\n";
echo "==============================================\n\n";

echo "LOGIN CREDENTIALS:\n";
echo "==============================================\n\n";

foreach ($testUsers as $userData) {
    echo "{$userData['role']} Portal:\n";
    echo "   URL: http://127.0.0.1:8000/login\n";
    echo "   Role: {$userData['role']}\n";
    echo "   Email: {$userData['email']}\n";
    echo "   Password: {$userData['password']}\n";
    echo "\n";
}

echo "==============================================\n";
echo "IMPORTANT NOTES:\n";
echo "==============================================\n";
echo "✅ All test accounts are pre-verified (no email verification needed)\n";
echo "✅ Admin, Pastor, Ministry Leader, Organization, Volunteer: Login directly\n";
echo "✅ Church Member: New signups need admin approval, test account logs in directly\n";
echo "✅ All accounts are active and ready to use\n";
echo "✅ Default password for all: 'password'\n\n";

echo "TESTING INSTRUCTIONS:\n";
echo "==============================================\n";
echo "1. Visit: http://127.0.0.1:8000/login\n";
echo "2. Click the role you want to test\n";
echo "3. Enter email and password\n";
echo "4. Login immediately (no verification needed!)\n\n";

echo "🎉 All test users are ready!\n\n";
?>
