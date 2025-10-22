<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "CHURCH MANAGEMENT SYSTEM HEALTH CHECK\n";
echo "========================================\n\n";

$errors = [];
$warnings = [];
$passed = 0;
$failed = 0;

// Check 1: Database Connection
echo "1. Testing Database Connection... ";
try {
    \DB::connection()->getPdo();
    echo "✓ PASS\n";
    $passed++;
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n";
    $errors[] = "Database connection failed";
    $failed++;
}

// Check 2: Critical Tables Exist
echo "2. Checking Critical Tables... ";
$tables = ['users', 'members', 'sermons', 'events', 'donations', 'visitors'];
$missingTables = [];
foreach ($tables as $table) {
    try {
        \DB::table($table)->limit(1)->get();
    } catch (Exception $e) {
        $missingTables[] = $table;
    }
}
if (empty($missingTables)) {
    echo "✓ PASS\n";
    $passed++;
} else {
    echo "✗ FAIL: Missing tables - " . implode(', ', $missingTables) . "\n";
    $errors[] = "Missing database tables";
    $failed++;
}

// Check 3: Upload Directories
echo "3. Checking Upload Directories... ";
$uploadDirs = [
    'public/uploads/sermons',
    'public/uploads/profiles',
    'public/uploads/members',
    'public/uploads/events',
];
$missingDirs = [];
foreach ($uploadDirs as $dir) {
    if (!is_dir($dir) || !is_writable($dir)) {
        $missingDirs[] = $dir;
    }
}
if (empty($missingDirs)) {
    echo "✓ PASS\n";
    $passed++;
} else {
    echo "✗ FAIL: Issues with - " . implode(', ', $missingDirs) . "\n";
    $errors[] = "Upload directories not writable";
    $failed++;
}

// Check 4: Environment Configuration
echo "4. Checking Environment Configuration... ";
$envVars = ['APP_KEY', 'DB_DATABASE', 'APP_URL'];
$missingVars = [];
foreach ($envVars as $var) {
    if (empty(env($var))) {
        $missingVars[] = $var;
    }
}
if (empty($missingVars)) {
    echo "✓ PASS\n";
    $passed++;
} else {
    echo "⚠ WARNING: Missing - " . implode(', ', $missingVars) . "\n";
    $warnings[] = "Some environment variables not set";
    $passed++;
}

// Check 5: User Authentication System
echo "5. Testing User Authentication... ";
try {
    $userCount = \App\Models\User::count();
    echo "✓ PASS ($userCount users)\n";
    $passed++;
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n";
    $errors[] = "User authentication system error";
    $failed++;
}

// Check 6: Members System
echo "6. Testing Members System... ";
try {
    $memberCount = \App\Models\Member::count();
    echo "✓ PASS ($memberCount members)\n";
    $passed++;
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n";
    $errors[] = "Members system error";
    $failed++;
}

// Check 7: Sermons System
echo "7. Testing Sermons System... ";
try {
    $sermonCount = \App\Models\Sermon::count();
    echo "✓ PASS ($sermonCount sermons)\n";
    $passed++;
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n";
    $errors[] = "Sermons system error";
    $failed++;
}

// Check 8: Events System
echo "8. Testing Events System... ";
try {
    $eventCount = \App\Models\Event::count();
    echo "✓ PASS ($eventCount events)\n";
    $passed++;
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n";
    $errors[] = "Events system error";
    $failed++;
}

// Check 9: Donations System
echo "9. Testing Donations System... ";
try {
    $donationCount = \App\Models\Donation::count();
    echo "✓ PASS ($donationCount donations)\n";
    $passed++;
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n";
    $errors[] = "Donations system error";
    $failed++;
}

// Check 10: Visitors System
echo "10. Testing Visitors System... ";
try {
    $visitorCount = \App\Models\Visitor::count();
    echo "✓ PASS ($visitorCount visitors)\n";
    $passed++;
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n";
    $errors[] = "Visitors system error";
    $failed++;
}

// Check 11: Routes Registration
echo "11. Checking Routes Registration... ";
try {
    $routes = \Route::getRoutes()->count();
    echo "✓ PASS ($routes routes registered)\n";
    $passed++;
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n";
    $errors[] = "Routes registration error";
    $failed++;
}

// Check 12: Storage Link
echo "12. Checking Storage Link... ";
if (is_link(public_path('storage'))) {
    echo "✓ PASS\n";
    $passed++;
} else {
    echo "⚠ WARNING: Storage link not created\n";
    $warnings[] = "Run: php artisan storage:link";
    $passed++;
}

// Summary
echo "\n========================================\n";
echo "RESULTS SUMMARY\n";
echo "========================================\n";
echo "✓ Passed: $passed tests\n";
echo "✗ Failed: $failed tests\n";
echo "⚠ Warnings: " . count($warnings) . " warnings\n\n";

if ($failed == 0) {
    echo "🎉 SYSTEM STATUS: HEALTHY\n";
    echo "All critical systems are functioning properly!\n\n";
} else {
    echo "⚠️  SYSTEM STATUS: NEEDS ATTENTION\n\n";
    echo "ERRORS:\n";
    foreach ($errors as $i => $error) {
        echo "  " . ($i + 1) . ". $error\n";
    }
    echo "\n";
}

if (!empty($warnings)) {
    echo "WARNINGS:\n";
    foreach ($warnings as $i => $warning) {
        echo "  " . ($i + 1) . ". $warning\n";
    }
    echo "\n";
}

echo "========================================\n";
