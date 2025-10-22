<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Service;

// Define all services for the week
$services = [
    [
        'name' => 'Sunday Worship Service',
        'description' => 'Main Sunday worship and praise service',
        'day_of_week' => 'sunday',
        'start_time' => '09:00:00',
        'end_time' => '11:30:00',
    ],
    [
        'name' => 'Sunday Evening Service',
        'description' => 'Evening praise and worship',
        'day_of_week' => 'sunday',
        'start_time' => '17:00:00',
        'end_time' => '19:00:00',
    ],
    [
        'name' => 'Monday Prayer Meeting',
        'description' => 'Corporate prayer and intercession',
        'day_of_week' => 'monday',
        'start_time' => '18:00:00',
        'end_time' => '20:00:00',
    ],
    [
        'name' => 'Tuesday Bible Study',
        'description' => 'Midweek Bible study and teaching',
        'day_of_week' => 'tuesday',
        'start_time' => '18:30:00',
        'end_time' => '20:30:00',
    ],
    [
        'name' => 'Wednesday Revival Service',
        'description' => 'Midweek revival and worship',
        'day_of_week' => 'wednesday',
        'start_time' => '18:00:00',
        'end_time' => '20:00:00',
    ],
    [
        'name' => 'Thursday Youth Service',
        'description' => 'Youth fellowship and worship',
        'day_of_week' => 'thursday',
        'start_time' => '19:00:00',
        'end_time' => '21:00:00',
    ],
    [
        'name' => 'Friday Night Service',
        'description' => 'Friday night praise and worship',
        'day_of_week' => 'friday',
        'start_time' => '19:00:00',
        'end_time' => '21:30:00',
    ],
    [
        'name' => 'Saturday Early Morning Prayer',
        'description' => 'Morning prayer and devotion',
        'day_of_week' => 'saturday',
        'start_time' => '06:00:00',
        'end_time' => '08:00:00',
    ],
];

echo "Creating services...\n\n";

foreach ($services as $serviceData) {
    $service = Service::firstOrCreate(
        [
            'name' => $serviceData['name'],
            'day_of_week' => $serviceData['day_of_week']
        ],
        array_merge($serviceData, ['is_active' => true])
    );
    
    echo "✅ " . $service->name . " (" . ucfirst($service->day_of_week) . ")\n";
    echo "   Time: " . date('h:i A', strtotime($service->start_time)) . " - " . date('h:i A', strtotime($service->end_time)) . "\n";
    echo "   ID: " . $service->id . "\n\n";
}

echo "==========================================\n";
echo "✅ ALL SERVICES CREATED!\n";
echo "==========================================\n";
echo "\nTotal services: " . Service::count() . "\n";
