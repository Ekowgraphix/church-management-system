<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Service;

// Create a Sunday service for testing
$service = Service::firstOrCreate(
    ['name' => 'Sunday Worship Service'],
    [
        'description' => 'Main Sunday worship service',
        'day_of_week' => 'sunday',
        'start_time' => '09:00:00',
        'end_time' => '11:00:00',
        'is_active' => true,
    ]
);

echo "✅ Test service created!\n";
echo "ID: {$service->id}\n";
echo "Name: {$service->name}\n";
echo "Day: {$service->day_of_week}\n";
echo "Time: {$service->start_time} - {$service->end_time}\n";
echo "\nNow refresh the scanner page!\n";
