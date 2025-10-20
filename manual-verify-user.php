<?php

/**
 * Manual User Verification Tool
 * Use this to manually verify users when email is not working
 * Run: php manual-verify-user.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Member;
use Illuminate\Support\Facades\DB;

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║        MANUAL USER VERIFICATION TOOL                      ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

// Get all unverified users
$unverifiedUsers = User::whereNull('email_verified_at')
    ->where('is_active', true)
    ->get();

if ($unverifiedUsers->isEmpty()) {
    echo "✓ No unverified users found. All users are verified!\n\n";
    exit(0);
}

echo "Found " . $unverifiedUsers->count() . " unverified user(s):\n\n";

foreach ($unverifiedUsers as $index => $user) {
    $num = $index + 1;
    echo "[$num] {$user->name} ({$user->email})\n";
    echo "    Created: {$user->created_at->diffForHumans()}\n";
    echo "    Role: " . ($user->roles->first()->name ?? 'No role') . "\n\n";
}

echo "─────────────────────────────────────────────────────────────\n";
echo "Options:\n";
echo "  1. Verify a specific user (enter number)\n";
echo "  2. Verify ALL users\n";
echo "  3. View verification tokens\n";
echo "  4. Exit\n";
echo "─────────────────────────────────────────────────────────────\n";

$handle = fopen("php://stdin", "r");
echo "Enter your choice: ";
$choice = trim(fgets($handle));

switch ($choice) {
    case '1':
        echo "Enter user number to verify: ";
        $userNum = trim(fgets($handle));
        
        if (!is_numeric($userNum) || $userNum < 1 || $userNum > $unverifiedUsers->count()) {
            echo "Invalid user number!\n";
            exit(1);
        }
        
        $user = $unverifiedUsers[$userNum - 1];
        verifyUser($user);
        break;
        
    case '2':
        echo "Are you sure you want to verify ALL {$unverifiedUsers->count()} users? (yes/no): ";
        $confirm = trim(fgets($handle));
        
        if (strtolower($confirm) === 'yes') {
            foreach ($unverifiedUsers as $user) {
                verifyUser($user, false);
            }
            echo "\n✓ All users verified successfully!\n";
        } else {
            echo "Operation cancelled.\n";
        }
        break;
        
    case '3':
        echo "\nVerification Tokens:\n";
        echo "─────────────────────────────────────────────────────────────\n\n";
        
        $tokens = DB::table('email_verifications')
            ->join('users', 'email_verifications.user_id', '=', 'users.id')
            ->select('users.name', 'users.email', 'email_verifications.token', 'email_verifications.created_at')
            ->get();
        
        if ($tokens->isEmpty()) {
            echo "No verification tokens found.\n";
        } else {
            foreach ($tokens as $t) {
                echo "User: {$t->name} ({$t->email})\n";
                echo "Token: {$t->token}\n";
                $verifyUrl = url('/verify-email/' . $t->token);
                echo "Verification URL: {$verifyUrl}\n";
                echo "Created: " . \Carbon\Carbon::parse($t->created_at)->diffForHumans() . "\n\n";
            }
        }
        break;
        
    case '4':
        echo "Exiting...\n";
        exit(0);
        
    default:
        echo "Invalid choice!\n";
        exit(1);
}

fclose($handle);

function verifyUser($user, $showMessage = true)
{
    DB::beginTransaction();
    
    try {
        // Mark email as verified
        $user->update(['email_verified_at' => now()]);
        
        // Update member status to active
        $member = Member::where('email', $user->email)->first();
        if ($member) {
            $member->update(['membership_status' => 'active']);
        }
        
        // Delete verification token
        DB::table('email_verifications')->where('user_id', $user->id)->delete();
        
        DB::commit();
        
        if ($showMessage) {
            echo "\n✓ User verified successfully!\n";
            echo "  - Email marked as verified\n";
            if ($member) {
                echo "  - Member status updated to 'active'\n";
            }
            echo "  - Verification token deleted\n\n";
            echo "User {$user->email} can now log in.\n";
        }
    } catch (\Exception $e) {
        DB::rollBack();
        echo "\n✗ Failed to verify user: " . $e->getMessage() . "\n";
    }
}

echo "\n";
