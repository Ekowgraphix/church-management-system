<?php

echo "==========================================\n";
echo "GENERATING QR TOKENS FOR ALL SERVICES\n";
echo "==========================================\n\n";

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Service;
use Illuminate\Support\Str;

$services = Service::all();

echo "Found " . $services->count() . " services\n\n";

foreach ($services as $service) {
    if (empty($service->qr_code_token)) {
        // Generate unique token
        $service->qr_code_token = 'SVC-' . strtoupper(Str::random(13));
        $service->qr_code_generated_at = now();
        $service->save();
        
        echo "✅ Generated token for: {$service->name}\n";
        echo "   Token: {$service->qr_code_token}\n";
        echo "   Day: " . ucfirst($service->day_of_week) . "\n\n";
    } else {
        echo "✓ Already has token: {$service->name}\n";
        echo "   Token: {$service->qr_code_token}\n\n";
    }
}

echo "\n==========================================\n";
echo "✅ ALL TOKENS GENERATED!\n";
echo "==========================================\n\n";

echo "Now you can check in using these tokens!\n\n";

echo "📋 ALL SERVICE TOKENS:\n";
echo "-------------------\n\n";

$dayOrder = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
$services = Service::where('is_active', true)->get()->sortBy(function($service) use ($dayOrder) {
    return array_search($service->day_of_week, $dayOrder);
});

foreach ($services as $service) {
    $day = strtoupper($service->day_of_week);
    echo "{$day}\n";
    echo "• {$service->name}\n";
    echo "  Time: " . date('g:i A', strtotime($service->start_time)) . " - " . date('g:i A', strtotime($service->end_time)) . "\n";
    echo "  Token: {$service->qr_code_token}\n\n";
}

echo "\n💡 TO TEST:\n";
echo "-------------------\n";
echo "1. Go to: http://127.0.0.1:8000/qr-checkin/service/scanner\n";
echo "2. Login as a member\n";
echo "3. Copy any token above\n";
echo "4. Paste in 'Manual Token Entry' field\n";
echo "5. Click 'Check In'\n\n";
