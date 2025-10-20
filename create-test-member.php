<?php

/**
 * Quick Test Member Creator
 * Run with: php create-test-member.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Member;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

echo "Creating test Church Member...\n\n";

$email = 'member@church.test';
$password = 'password123';
$name = 'Test Member';

try {
    // Check if user already exists
    $existingUser = User::where('email', $email)->first();
    if ($existingUser) {
        echo "❌ User with email '$email' already exists.\n";
        echo "   Delete it first or use a different email.\n\n";
        
        echo "To delete existing user, run:\n";
        echo "   php artisan tinker\n";
        echo "   User::where('email', '$email')->forceDelete();\n";
        echo "   Member::where('email', '$email')->forceDelete();\n";
        exit(1);
    }
    
    // Create user
    $user = User::create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
        'phone' => '0123456789',
        'email_verified_at' => now(), // Auto-verify for testing
        'is_active' => true,
    ]);
    
    echo "✅ User created: {$user->name} ({$user->email})\n";
    
    // Assign Church Member role
    $memberRole = Role::where('name', 'Church Member')->first();
    
    if (!$memberRole) {
        echo "❌ 'Church Member' role not found!\n";
        echo "   Run: php artisan db:seed --class=RolesSeeder\n";
        $user->forceDelete();
        exit(1);
    }
    
    $user->assignRole($memberRole);
    echo "✅ Assigned role: Church Member\n";
    
    // Create member profile
    $member = Member::create([
        'member_id' => 'M' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
        'first_name' => 'Test',
        'last_name' => 'Member',
        'email' => $email,
        'phone' => '0123456789',
        'membership_status' => 'active',
        'membership_date' => now(),
    ]);
    
    echo "✅ Member profile created\n\n";
    
    echo "================================================\n";
    echo "   🎉 Test User Created Successfully!\n";
    echo "================================================\n\n";
    
    echo "Login Credentials:\n";
    echo "   Email:    $email\n";
    echo "   Password: $password\n";
    echo "   Role:     Church Member\n\n";
    
    echo "Next Steps:\n";
    echo "   1. Visit: " . url('/login') . "\n";
    echo "   2. Click the 'Church Member' role card\n";
    echo "   3. Enter the credentials above\n";
    echo "   4. You'll be redirected to: " . url('/portal') . "\n\n";
    
    echo "Have fun testing! 🚀\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
}
