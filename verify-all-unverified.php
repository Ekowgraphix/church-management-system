<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Member;
use Illuminate\Support\Facades\DB;

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║         VERIFY ALL UNVERIFIED USERS AUTOMATICALLY         ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

$unverifiedUsers = User::whereNull('email_verified_at')
    ->where('is_active', true)
    ->get();

if ($unverifiedUsers->isEmpty()) {
    echo "✓ All users are already verified!\n\n";
    exit(0);
}

echo "Found {$unverifiedUsers->count()} unverified user(s)\n\n";

DB::beginTransaction();

try {
    foreach ($unverifiedUsers as $user) {
        echo "Verifying: {$user->name} ({$user->email})... ";
        
        // Mark email as verified
        $user->update(['email_verified_at' => now()]);
        
        // Update member status to active
        $member = Member::where('email', $user->email)->first();
        if ($member) {
            $member->update(['membership_status' => 'active']);
        }
        
        // Delete verification token
        DB::table('email_verifications')->where('user_id', $user->id)->delete();
        
        echo "✓\n";
    }
    
    DB::commit();
    
    echo "\n✅ SUCCESS! All {$unverifiedUsers->count()} user(s) verified!\n\n";
    echo "═══════════════════════════════════════════════════════════\n";
    echo "All users can now login at: http://127.0.0.1:8000/login\n";
    echo "═══════════════════════════════════════════════════════════\n\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "\n✗ Error: " . $e->getMessage() . "\n\n";
}
