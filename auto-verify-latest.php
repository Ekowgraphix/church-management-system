<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Member;
use Illuminate\Support\Facades\DB;

echo "Auto-verifying latest signup...\n\n";

// Get the most recent unverified user
$latestUser = User::whereNull('email_verified_at')
    ->orderBy('created_at', 'desc')
    ->first();

if (!$latestUser) {
    echo "No unverified users found!\n";
    exit(0);
}

echo "Found: {$latestUser->name} ({$latestUser->email})\n";
echo "Verifying...\n\n";

DB::beginTransaction();

try {
    // Mark email as verified
    $latestUser->update(['email_verified_at' => now()]);
    
    // Update member status to active
    $member = Member::where('email', $latestUser->email)->first();
    if ($member) {
        $member->update(['membership_status' => 'active']);
    }
    
    // Delete verification token
    DB::table('email_verifications')->where('user_id', $latestUser->id)->delete();
    
    DB::commit();
    
    echo "✅ SUCCESS! Account verified!\n\n";
    echo "═══════════════════════════════════════════════════════════\n";
    echo "You can now login:\n";
    echo "═══════════════════════════════════════════════════════════\n\n";
    echo "🔗 Login URL: http://127.0.0.1:8000/login\n\n";
    echo "Login Details:\n";
    echo "  Role: Church Member\n";
    echo "  Email: {$latestUser->email}\n";
    echo "  Password: (the one you created during signup)\n\n";
    echo "═══════════════════════════════════════════════════════════\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "✗ Error: " . $e->getMessage() . "\n";
}
