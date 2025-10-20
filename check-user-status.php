<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Member;

echo "Checking user verification status...\n\n";

// Check specific email
$email = '98billybeams@beamers.com';
$user = User::where('email', $email)->first();

if (!$user) {
    echo "User not found with email: $email\n";
    exit(1);
}

echo "User: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "email_verified_at: " . ($user->email_verified_at ?? 'NULL') . "\n";
echo "is_active: " . ($user->is_active ? 'YES' : 'NO') . "\n\n";

$member = Member::where('email', $email)->first();
if ($member) {
    echo "Member ID: {$member->member_id}\n";
    echo "Membership Status: {$member->membership_status}\n\n";
}

if ($user->email_verified_at) {
    echo "✓ Email IS verified\n";
} else {
    echo "✗ Email NOT verified - verifying now...\n";
    $user->update(['email_verified_at' => now()]);
    if ($member) {
        $member->update(['membership_status' => 'active']);
    }
    echo "✓ Done!\n";
}

echo "\nYou should be able to login now.\n";
