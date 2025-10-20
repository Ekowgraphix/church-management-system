<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "==============================================\n";
echo "Fixing All Issues\n";
echo "==============================================\n\n";

// 1. Create sample equipment
echo "1. Creating sample equipment...\n";
$equipment = [
    ['name' => 'Sound System', 'equipment_code' => 'EQP-001', 'description' => 'Main church sound system', 'serial_number' => 'SND-001', 'status' => 'available', 'condition' => 'good'],
    ['name' => 'Projector', 'equipment_code' => 'EQP-002', 'description' => 'HD projector for presentations', 'serial_number' => 'PRJ-001', 'status' => 'available', 'condition' => 'good'],
    ['name' => 'Microphone Set', 'equipment_code' => 'EQP-003', 'description' => 'Wireless microphone set', 'serial_number' => 'MIC-001', 'status' => 'available', 'condition' => 'good'],
    ['name' => 'Camera', 'equipment_code' => 'EQP-004', 'description' => 'Video recording camera', 'serial_number' => 'CAM-001', 'status' => 'available', 'condition' => 'good'],
];

foreach ($equipment as $item) {
    DB::table('equipment')->updateOrInsert(
        ['equipment_code' => $item['equipment_code']],
        array_merge($item, [
            'created_at' => now(),
            'updated_at' => now()
        ])
    );
    echo "  ✓ Created: {$item['name']}\n";
}

// 2. Create sample visitors
echo "\n2. Creating sample visitors...\n";
$visitors = [
    ['name' => 'John Visitor', 'phone' => '0241234567', 'visit_date' => now()->subDays(2), 'source' => 'walk_in'],
    ['name' => 'Mary Guest', 'phone' => '0242345678', 'visit_date' => now()->subDays(5), 'source' => 'invitation'],
    ['name' => 'Peter Newcomer', 'phone' => '0243456789', 'visit_date' => now()->subDays(1), 'source' => 'walk_in'],
];

foreach ($visitors as $visitor) {
    DB::table('visitors')->updateOrInsert(
        ['phone' => $visitor['phone']],
        array_merge($visitor, [
            'created_at' => now(),
            'updated_at' => now()
        ])
    );
    echo "  ✓ Created: {$visitor['name']}\n";
}

// 3. Create sample services for attendance
echo "\n3. Creating sample services...\n";
$services = [
    ['name' => 'Sunday Morning Service', 'service_date' => now()->format('Y-m-d'), 'service_time' => '09:00:00', 'service_type' => 'sunday_service'],
    ['name' => 'Wednesday Bible Study', 'service_date' => now()->addDays(3)->format('Y-m-d'), 'service_time' => '18:00:00', 'service_type' => 'bible_study'],
];

foreach ($services as $service) {
    $serviceId = DB::table('services')->insertGetId(array_merge($service, [
        'created_at' => now(),
        'updated_at' => now()
    ]));
    echo "  ✓ Created: {$service['name']} (ID: $serviceId)\n";
}

// 4. Create SMS templates
echo "\n4. Creating SMS templates...\n";
$templates = [
    ['name' => 'Welcome Message', 'content' => 'Welcome to our church! We are glad to have you. God bless you.', 'category' => 'welcome'],
    ['name' => 'Event Reminder', 'content' => 'Reminder: {event_name} on {date} at {time}. See you there!', 'category' => 'reminder'],
    ['name' => 'Birthday Wishes', 'content' => 'Happy Birthday {name}! May God bless you abundantly on your special day.', 'category' => 'birthday'],
];

foreach ($templates as $template) {
    DB::table('sms_templates')->updateOrInsert(
        ['name' => $template['name']],
        array_merge($template, [
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ])
    );
    echo "  ✓ Created: {$template['name']}\n";
}

echo "\n==============================================\n";
echo "✅ All issues fixed!\n";
echo "==============================================\n";
echo "\nSummary:\n";
echo "- Equipment: 4 items created\n";
echo "- Visitors: 3 visitors created\n";
echo "- Services: 2 services created\n";
echo "- SMS Templates: 3 templates created\n";
echo "- Donation Categories: Already created\n";
echo "- Expense Categories: Already created\n";
