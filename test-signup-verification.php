<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Member;

echo "=== SIGNUP & VERIFICATION DIAGNOSTIC TEST ===\n\n";

// 1. Check database connection
echo "1. Testing Database Connection...\n";
try {
    DB::connection()->getPdo();
    echo "   ✓ Database connected successfully\n\n";
} catch (\Exception $e) {
    echo "   ✗ Database connection failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// 2. Check if email_verifications table exists
echo "2. Checking email_verifications table...\n";
if (Schema::hasTable('email_verifications')) {
    echo "   ✓ Table exists\n";
    
    $columns = Schema::getColumnListing('email_verifications');
    echo "   Columns: " . implode(', ', $columns) . "\n";
    
    $count = DB::table('email_verifications')->count();
    echo "   Records: $count\n\n";
} else {
    echo "   ✗ Table does not exist\n";
    echo "   Run: php artisan migrate\n\n";
}

// 3. Check users table
echo "3. Checking users table...\n";
if (Schema::hasTable('users')) {
    echo "   ✓ Table exists\n";
    
    $columns = Schema::getColumnListing('users');
    if (in_array('email_verified_at', $columns)) {
        echo "   ✓ email_verified_at column exists\n";
    } else {
        echo "   ✗ email_verified_at column missing\n";
    }
    
    $totalUsers = User::count();
    $verifiedUsers = User::whereNotNull('email_verified_at')->count();
    $unverifiedUsers = User::whereNull('email_verified_at')->count();
    
    echo "   Total users: $totalUsers\n";
    echo "   Verified: $verifiedUsers\n";
    echo "   Unverified: $unverifiedUsers\n\n";
} else {
    echo "   ✗ Table does not exist\n\n";
}

// 4. Check members table
echo "4. Checking members table...\n";
if (Schema::hasTable('members')) {
    echo "   ✓ Table exists\n";
    
    $totalMembers = Member::count();
    $activeMembers = Member::where('membership_status', 'active')->count();
    $pendingMembers = Member::where('membership_status', 'pending')->count();
    
    echo "   Total members: $totalMembers\n";
    echo "   Active: $activeMembers\n";
    echo "   Pending: $pendingMembers\n\n";
} else {
    echo "   ✗ Table does not exist\n\n";
}

// 5. Check mail configuration
echo "5. Checking Mail Configuration...\n";
$mailDriver = env('MAIL_MAILER');
$mailHost = env('MAIL_HOST');
$mailPort = env('MAIL_PORT');
$mailUsername = env('MAIL_USERNAME');
$mailFromAddress = env('MAIL_FROM_ADDRESS');

echo "   Driver: " . ($mailDriver ?: 'NOT SET') . "\n";
echo "   Host: " . ($mailHost ?: 'NOT SET') . "\n";
echo "   Port: " . ($mailPort ?: 'NOT SET') . "\n";
echo "   Username: " . ($mailUsername ?: 'NOT SET') . "\n";
echo "   From Address: " . ($mailFromAddress ?: 'NOT SET') . "\n";

if (!$mailDriver || !$mailHost || !$mailFromAddress) {
    echo "   ⚠ WARNING: Mail configuration is incomplete!\n";
    echo "   Email verification will not work without proper mail setup.\n";
}
echo "\n";

// 6. Check routes
echo "6. Checking Routes...\n";
$routes = \Illuminate\Support\Facades\Route::getRoutes();
$signupRoutes = [];
$verifyRoutes = [];

foreach ($routes as $route) {
    $uri = $route->uri();
    if (strpos($uri, 'signup') !== false) {
        $signupRoutes[] = $route->methods()[0] . ' ' . $uri;
    }
    if (strpos($uri, 'verify') !== false) {
        $verifyRoutes[] = $route->methods()[0] . ' ' . $uri;
    }
}

echo "   Signup routes:\n";
foreach ($signupRoutes as $r) {
    echo "      - $r\n";
}

echo "   Verification routes:\n";
foreach ($verifyRoutes as $r) {
    echo "      - $r\n";
}
echo "\n";

// 7. Check AuthController
echo "7. Checking AuthController...\n";
if (class_exists('App\Http\Controllers\AuthController')) {
    echo "   ✓ AuthController exists\n";
    
    $methods = get_class_methods('App\Http\Controllers\AuthController');
    $requiredMethods = ['showSignup', 'signupStore', 'verifyEmail'];
    
    foreach ($requiredMethods as $method) {
        if (in_array($method, $methods)) {
            echo "   ✓ Method $method exists\n";
        } else {
            echo "   ✗ Method $method missing\n";
        }
    }
} else {
    echo "   ✗ AuthController does not exist\n";
}
echo "\n";

// 8. Check email template
echo "8. Checking Email Template...\n";
$emailTemplate = resource_path('views/emails/verify.blade.php');
if (file_exists($emailTemplate)) {
    echo "   ✓ Email template exists: $emailTemplate\n";
} else {
    echo "   ✗ Email template missing: $emailTemplate\n";
}
echo "\n";

// 9. Check pending verifications
echo "9. Checking Pending Verifications...\n";
if (Schema::hasTable('email_verifications')) {
    $pending = DB::table('email_verifications')
        ->join('users', 'email_verifications.user_id', '=', 'users.id')
        ->select('users.name', 'users.email', 'email_verifications.created_at')
        ->get();
    
    if ($pending->count() > 0) {
        echo "   Found {$pending->count()} pending verification(s):\n";
        foreach ($pending as $p) {
            $age = \Carbon\Carbon::parse($p->created_at)->diffForHumans();
            echo "      - {$p->name} ({$p->email}) - Created $age\n";
        }
    } else {
        echo "   No pending verifications\n";
    }
}
echo "\n";

echo "=== DIAGNOSTIC COMPLETE ===\n\n";

// 10. Recommendations
echo "RECOMMENDATIONS:\n";
$issues = [];

if (!Schema::hasTable('email_verifications')) {
    $issues[] = "Run 'php artisan migrate' to create email_verifications table";
}

if (!env('MAIL_MAILER') || !env('MAIL_HOST') || !env('MAIL_FROM_ADDRESS')) {
    $issues[] = "Configure mail settings in .env file";
    $issues[] = "For testing, you can use Mailtrap or Gmail SMTP";
}

if ($unverifiedUsers > 0) {
    $issues[] = "You have $unverifiedUsers unverified user(s) - they need to verify their email";
}

if (empty($issues)) {
    echo "✓ Everything looks good! The signup and verification system should be working.\n";
    echo "\nIf you're still experiencing issues, check:\n";
    echo "  1. Browser console for JavaScript errors\n";
    echo "  2. Network tab to see if signup POST request is failing\n";
    echo "  3. storage/logs/laravel.log for detailed error messages\n";
} else {
    foreach ($issues as $i => $issue) {
        echo ($i + 1) . ". $issue\n";
    }
}

echo "\n";
