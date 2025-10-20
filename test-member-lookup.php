<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Member;

echo "Testing member lookup for portal access...\n\n";

$email = '98billybeams@beamers.com';
$user = User::where('email', $email)->first();

if (!$user) {
    echo "✗ User not found!\n";
    exit(1);
}

echo "User: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "Role: " . ($user->roles->first()->name ?? 'No role') . "\n\n";

// Check for member profile (this is what portal controller does)
$member = Member::where('email', $user->email)->first();

if (!$member) {
    echo "✗ NO MEMBER PROFILE FOUND!\n";
    echo "  This is why portal redirects!\n\n";
    echo "Creating member profile now...\n";
    
    // Create member profile
    $nameParts = explode(' ', $user->name);
    $firstName = $nameParts[0] ?? '';
    $lastName = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : '';
    
    // Get next member ID
    $year = date('Y');
    $lastMember = Member::whereYear('created_at', $year)
        ->orderBy('id', 'desc')
        ->first();
    
    if ($lastMember && preg_match('/MEM' . $year . '(\d+)/', $lastMember->member_id, $matches)) {
        $nextNumber = intval($matches[1]) + 1;
    } else {
        $nextNumber = 1;
    }
    
    $memberId = 'MEM' . $year . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    
    $member = Member::create([
        'member_id' => $memberId,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $user->email,
        'phone' => $user->phone ?? '0000000000',
        'membership_status' => 'active',
        'membership_date' => now(),
    ]);
    
    echo "✓ Member profile created: {$memberId}\n\n";
    
} else {
    echo "✓ Member profile found!\n";
    echo "  Member ID: {$member->member_id}\n";
    echo "  Status: {$member->membership_status}\n\n";
}

echo "═══════════════════════════════════════════════════════════\n";
echo "Portal should work now!\n";
echo "Try: http://127.0.0.1:8000/login\n";
echo "═══════════════════════════════════════════════════════════\n\n";
