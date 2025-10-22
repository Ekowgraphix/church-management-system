<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Service;

// Get today's day
$today = strtolower(now()->format('l')); // e.g., "monday"

echo "Today is: " . ucfirst($today) . "\n\n";

// Create a service for today
$service = Service::firstOrCreate(
    ['name' => ucfirst($today) . ' Service'],
    [
        'description' => 'Service for ' . ucfirst($today),
        'day_of_week' => $today,
        'start_time' => '18:00:00',
        'end_time' => '20:00:00',
        'is_active' => true,
    ]
);

echo "✅ Test service created for TODAY!\n";
echo "ID: {$service->id}\n";
echo "Name: {$service->name}\n";
echo "Day: {$service->day_of_week}\n";
echo "Time: " . date('h:i A', strtotime($service->start_time)) . " - " . date('h:i A', strtotime($service->end_time)) . "\n";
echo "\n🎯 Now you can test:\n";
echo "1. Refresh scanner page\n";
echo "2. Allow camera permission\n";
echo "3. Visit: http://192.168.249.253:8000/qr-checkin/service/{$service->id}/qr\n";
