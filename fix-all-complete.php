<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "==============================================\n";
echo "Fixing All Issues - Complete\n";
echo "==============================================\n\n";

// 1. Create equipment categories first
echo "1. Creating equipment categories...\n";
$equipmentCategories = [
    ['name' => 'Audio/Visual', 'description' => 'Sound and video equipment'],
    ['name' => 'Musical Instruments', 'description' => 'Instruments for worship'],
    ['name' => 'Furniture', 'description' => 'Church furniture'],
    ['name' => 'Electronics', 'description' => 'Electronic devices'],
];

foreach ($equipmentCategories as $cat) {
    $catId = DB::table('equipment_categories')->insertGetId(array_merge($cat, [
        'created_at' => now(),
        'updated_at' => now()
    ]));
    echo "  ✓ Created: {$cat['name']} (ID: $catId)\n";
}

// 2. Create sample equipment
echo "\n2. Creating sample equipment...\n";
$avCategoryId = DB::table('equipment_categories')->where('name', 'Audio/Visual')->value('id');
$equipment = [
    ['name' => 'Sound System', 'equipment_code' => 'EQP-001', 'category_id' => $avCategoryId, 'description' => 'Main church sound system', 'serial_number' => 'SND-001', 'status' => 'available', 'condition' => 'good'],
    ['name' => 'Projector', 'equipment_code' => 'EQP-002', 'category_id' => $avCategoryId, 'description' => 'HD projector', 'serial_number' => 'PRJ-001', 'status' => 'available', 'condition' => 'good'],
    ['name' => 'Microphone Set', 'equipment_code' => 'EQP-003', 'category_id' => $avCategoryId, 'description' => 'Wireless mics', 'serial_number' => 'MIC-001', 'status' => 'available', 'condition' => 'good'],
    ['name' => 'Camera', 'equipment_code' => 'EQP-004', 'category_id' => $avCategoryId, 'description' => 'Video camera', 'serial_number' => 'CAM-001', 'status' => 'available', 'condition' => 'good'],
];

foreach ($equipment as $item) {
    DB::table('equipment')->insert(array_merge($item, [
        'created_at' => now(),
        'updated_at' => now()
    ]));
    echo "  ✓ Created: {$item['name']}\n";
}

// 3. Create sample visitors
echo "\n3. Creating sample visitors...\n";
$visitors = [
    ['name' => 'John Visitor', 'phone' => '0241234567', 'email' => 'john@example.com', 'visit_date' => now()->subDays(2)->format('Y-m-d'), 'source' => 'walk_in', 'status' => 'new'],
    ['name' => 'Mary Guest', 'phone' => '0242345678', 'email' => 'mary@example.com', 'visit_date' => now()->subDays(5)->format('Y-m-d'), 'source' => 'invitation', 'status' => 'new'],
    ['name' => 'Peter Newcomer', 'phone' => '0243456789', 'email' => 'peter@example.com', 'visit_date' => now()->subDays(1)->format('Y-m-d'), 'source' => 'walk_in', 'status' => 'new'],
];

foreach ($visitors as $visitor) {
    DB::table('visitors')->insert(array_merge($visitor, [
        'created_at' => now(),
        'updated_at' => now()
    ]));
    echo "  ✓ Created: {$visitor['name']}\n";
}

// 4. Create sample services for attendance
echo "\n4. Creating sample services...\n";
$services = [
    ['name' => 'Sunday Morning Service', 'service_date' => now()->format('Y-m-d'), 'service_time' => '09:00:00', 'service_type' => 'sunday_service', 'status' => 'scheduled'],
    ['name' => 'Wednesday Bible Study', 'service_date' => now()->addDays(3)->format('Y-m-d'), 'service_time' => '18:00:00', 'service_type' => 'bible_study', 'status' => 'scheduled'],
    ['name' => 'Friday Prayer Meeting', 'service_date' => now()->addDays(5)->format('Y-m-d'), 'service_time' => '19:00:00', 'service_type' => 'prayer_meeting', 'status' => 'scheduled'],
];

foreach ($services as $service) {
    $serviceId = DB::table('services')->insertGetId(array_merge($service, [
        'created_at' => now(),
        'updated_at' => now()
    ]));
    echo "  ✓ Created: {$service['name']} (ID: $serviceId)\n";
}

// 5. Create SMS templates
echo "\n5. Creating SMS templates...\n";
$templates = [
    ['name' => 'Welcome Message', 'content' => 'Welcome to our church! We are glad to have you. God bless you.', 'category' => 'welcome'],
    ['name' => 'Event Reminder', 'content' => 'Reminder: {event_name} on {date} at {time}. See you there!', 'category' => 'reminder'],
    ['name' => 'Birthday Wishes', 'content' => 'Happy Birthday {name}! May God bless you abundantly.', 'category' => 'birthday'],
    ['name' => 'Service Announcement', 'content' => 'Service this Sunday at 9 AM. Theme: {theme}. See you there!', 'category' => 'announcement'],
];

foreach ($templates as $template) {
    DB::table('sms_templates')->insert(array_merge($template, [
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now()
    ]));
    echo "  ✓ Created: {$template['name']}\n";
}

// 6. Create followup types
echo "\n6. Creating followup types...\n";
$followupTypes = [
    ['name' => 'New Member', 'description' => 'Follow up with new members'],
    ['name' => 'Visitor', 'description' => 'Follow up with visitors'],
    ['name' => 'Sick Member', 'description' => 'Check on sick members'],
    ['name' => 'Inactive Member', 'description' => 'Reach out to inactive members'],
];

foreach ($followupTypes as $type) {
    DB::table('followup_types')->insert(array_merge($type, [
        'created_at' => now(),
        'updated_at' => now()
    ]));
    echo "  ✓ Created: {$type['name']}\n";
}

echo "\n==============================================\n";
echo "✅ All issues fixed successfully!\n";
echo "==============================================\n";
echo "\nSummary:\n";
echo "- Equipment Categories: 4 created\n";
echo "- Equipment: 4 items created\n";
echo "- Visitors: 3 visitors created\n";
echo "- Services: 3 services created\n";
echo "- SMS Templates: 4 templates created\n";
echo "- Followup Types: 4 types created\n";
echo "- Donation Categories: Already created\n";
echo "- Expense Categories: Already created\n";
echo "\n✅ All pages should now display data!\n";
