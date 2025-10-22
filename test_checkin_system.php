<?php

echo "==========================================\n";
echo "QR CHECK-IN SYSTEM TEST\n";
echo "==========================================\n\n";

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Service;
use App\Models\Member;
use App\Models\AttendanceRecord;

echo "1. Checking Services...\n";
echo "-------------------\n";

$services = Service::where('is_active', true)->get();

if ($services->count() > 0) {
    echo "✅ Found " . $services->count() . " active services:\n\n";
    
    foreach ($services as $service) {
        echo "   • {$service->name}\n";
        echo "     Day: " . ucfirst($service->day_of_week) . "\n";
        echo "     Time: {$service->start_time} - {$service->end_time}\n";
        echo "     Token: {$service->qr_code_token}\n";
        echo "     Active: " . ($service->is_active ? 'Yes' : 'No') . "\n\n";
    }
} else {
    echo "❌ No active services found!\n";
    echo "   Run: php create_all_services.php\n\n";
}

echo "\n2. Checking Members...\n";
echo "-------------------\n";

$members = Member::take(5)->get();

if ($members->count() > 0) {
    echo "✅ Found " . Member::count() . " members (showing first 5):\n\n";
    
    foreach ($members as $member) {
        echo "   • {$member->full_name}\n";
        echo "     Email: {$member->email}\n";
        echo "     ID: {$member->id}\n\n";
    }
} else {
    echo "❌ No members found!\n\n";
}

echo "\n3. Checking Attendance Records...\n";
echo "-------------------\n";

$records = AttendanceRecord::with('member', 'service')->latest()->take(5)->get();

if ($records->count() > 0) {
    echo "✅ Found " . AttendanceRecord::count() . " attendance records (showing last 5):\n\n";
    
    foreach ($records as $record) {
        $memberName = $record->member ? $record->member->full_name : 'Unknown';
        $serviceName = $record->service ? $record->service->name : 'Unknown';
        
        echo "   • {$memberName}\n";
        echo "     Service: {$serviceName}\n";
        echo "     Date: {$record->attendance_date}\n";
        echo "     Time: {$record->check_in_time}\n";
        echo "     Method: {$record->check_in_method}\n\n";
    }
} else {
    echo "⚠️  No attendance records yet\n";
    echo "   This is normal if no one has checked in\n\n";
}

echo "\n4. Testing Routes...\n";
echo "-------------------\n";

// Check if routes are loaded
try {
    $route = \Route::getRoutes()->getByName('qr.service.scanner');
    if ($route) {
        echo "✅ QR Scanner route exists\n";
        echo "   URL: /qr-checkin/service/scanner\n";
        echo "   Method: GET\n\n";
    } else {
        echo "❌ QR Scanner route NOT found!\n\n";
    }
} catch (\Exception $e) {
    echo "⚠️  Could not check routes: " . $e->getMessage() . "\n\n";
}

echo "\n5. Quick Test Token\n";
echo "-------------------\n";

if ($services->count() > 0) {
    $testService = $services->first();
    echo "✅ Test this token in manual entry:\n\n";
    echo "   Token: {$testService->qr_code_token}\n";
    echo "   Service: {$testService->name}\n\n";
    echo "   Steps:\n";
    echo "   1. Go to: http://127.0.0.1:8000/qr-checkin/service/scanner\n";
    echo "   2. Find 'Manual Token Entry' field\n";
    echo "   3. Paste: {$testService->qr_code_token}\n";
    echo "   4. Click 'Check In'\n\n";
} else {
    echo "❌ No services to test with\n\n";
}

echo "==========================================\n";
echo "TEST COMPLETE\n";
echo "==========================================\n\n";

echo "📋 SUMMARY:\n";
echo "-----------\n";
echo "Services: " . Service::where('is_active', true)->count() . " active\n";
echo "Members: " . Member::count() . " total\n";
echo "Check-ins: " . AttendanceRecord::count() . " total\n\n";

echo "🔗 LINKS TO TEST:\n";
echo "-----------\n";
echo "Desktop:\n";
echo "  http://127.0.0.1:8000/qr-checkin/service/scanner\n\n";
echo "Mobile (same Wi-Fi):\n";
echo "  http://192.168.249.253:8000/qr-checkin/service/scanner\n\n";

echo "💡 TIPS:\n";
echo "-----------\n";
echo "• Make sure you're logged in as a member\n";
echo "• Camera may not work - use manual token entry instead\n";
echo "• Copy a token from the list on the page\n";
echo "• Paste it in 'Enter token manually' field\n\n";
