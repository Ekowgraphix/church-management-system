<?php

echo "==========================================\n";
echo "CHECKING MEMBER PROFILE\n";
echo "==========================================\n\n";

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Member;

$email = 'ekowjeremy@gmail.com';

echo "Checking user: $email\n\n";

// Check user
$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ User not found!\n";
    exit(1);
}

echo "✅ User found:\n";
echo "   Name: {$user->name}\n";
echo "   Email: {$user->email}\n";
echo "   ID: {$user->id}\n\n";

// Check if User model has member relationship
echo "Checking User->member relationship...\n";
try {
    $memberViaRelationship = $user->member;
    if ($memberViaRelationship) {
        echo "✅ User->member relationship works!\n";
        echo "   Member: {$memberViaRelationship->full_name}\n";
        echo "   ID: {$memberViaRelationship->id}\n\n";
    } else {
        echo "❌ User->member relationship returns null\n\n";
    }
} catch (\Exception $e) {
    echo "❌ User->member relationship doesn't exist or error:\n";
    echo "   {$e->getMessage()}\n\n";
}

// Check member by email
echo "Checking Member by email...\n";
$member = Member::where('email', $email)->first();

if ($member) {
    echo "✅ Member profile found by email:\n";
    echo "   Name: {$member->full_name}\n";
    echo "   Email: {$member->email}\n";
    echo "   ID: {$member->id}\n\n";
} else {
    echo "❌ No member profile found with this email!\n\n";
    
    echo "Looking for similar emails...\n";
    $similarMembers = Member::where('email', 'LIKE', '%ekow%')->orWhere('email', 'LIKE', '%jeremy%')->get();
    if ($similarMembers->count() > 0) {
        echo "Found similar members:\n";
        foreach ($similarMembers as $m) {
            echo "  • {$m->full_name} ({$m->email})\n";
        }
    }
    echo "\n";
}

// Check all members
echo "All members in database:\n";
echo "------------------------\n";
$allMembers = Member::all();
foreach ($allMembers as $m) {
    echo "  • {$m->full_name} ({$m->email}) [ID: {$m->id}]\n";
}

echo "\n==========================================\n";
echo "DIAGNOSIS:\n";
echo "==========================================\n\n";

if (!$member) {
    echo "❌ PROBLEM FOUND!\n\n";
    echo "User exists but NO member profile with matching email.\n";
    echo "This is why QR Check-In redirects to dashboard!\n\n";
    echo "SOLUTION:\n";
    echo "---------\n";
    echo "1. Create a member profile with email: $email\n";
    echo "2. Or update existing member to use this email\n";
    echo "3. Or link user to existing member using user_id\n\n";
    
    echo "Quick fix - Create member profile:\n";
    echo "php artisan tinker\n";
    echo "Member::create([\n";
    echo "  'full_name' => '{$user->name}',\n";
    echo "  'email' => '{$user->email}',\n";
    echo "  'phone' => '0000000000',\n";
    echo "  'date_of_birth' => '1990-01-01',\n";
    echo "  'gender' => 'male',\n";
    echo "  'membership_status' => 'active'\n";
    echo "]);\n\n";
} else {
    echo "✅ ALL GOOD!\n\n";
    echo "User and Member profile are properly linked.\n";
    echo "QR Check-In should work now.\n\n";
}

echo "==========================================\n";
