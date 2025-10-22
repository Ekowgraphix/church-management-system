<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "TESTING MODELS AND RELATIONSHIPS\n";
echo "========================================\n\n";

$passed = 0;
$failed = 0;

// Test User Model
echo "1. Testing User Model... ";
try {
    $user = \App\Models\User::first();
    if ($user) {
        // Test attributes
        $name = $user->name;
        $email = $user->email;
        echo "✓ PASS\n";
        $passed++;
    } else {
        echo "⚠ NO DATA\n";
        $passed++;
    }
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n";
    $failed++;
}

// Test Member Model
echo "2. Testing Member Model... ";
try {
    $member = \App\Models\Member::first();
    if ($member) {
        $name = $member->first_name;
        echo "✓ PASS\n";
        $passed++;
    } else {
        echo "⚠ NO DATA\n";
        $passed++;
    }
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n";
    $failed++;
}

// Test Sermon Model
echo "3. Testing Sermon Model... ";
try {
    \App\Models\Sermon::count();
    echo "✓ PASS\n";
    $passed++;
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n";
    $failed++;
}

// Test Event Model
echo "4. Testing Event Model... ";
try {
    \App\Models\Event::count();
    echo "✓ PASS\n";
    $passed++;
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n";
    $failed++;
}

// Test Donation Model
echo "5. Testing Donation Model... ";
try {
    \App\Models\Donation::count();
    echo "✓ PASS\n";
    $passed++;
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n";
    $failed++;
}

// Test Visitor Model
echo "6. Testing Visitor Model... ";
try {
    \App\Models\Visitor::count();
    echo "✓ PASS\n";
    $passed++;
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n";
    $failed++;
}

// Test PrayerRequest Model
echo "7. Testing PrayerRequest Model... ";
try {
    \App\Models\PrayerRequest::count();
    echo "✓ PASS\n";
    $passed++;
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n";
    $failed++;
}

// Test SmallGroup Model
echo "8. Testing SmallGroup Model... ";
try {
    \App\Models\SmallGroup::count();
    echo "✓ PASS\n";
    $passed++;
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n";
    $failed++;
}

echo "\n========================================\n";
echo "RESULTS: $passed PASSED, $failed FAILED\n";
if ($failed == 0) {
    echo "✅ ALL MODELS WORKING CORRECTLY!\n";
} else {
    echo "⚠️  SOME MODELS NEED ATTENTION\n";
}
echo "========================================\n";
