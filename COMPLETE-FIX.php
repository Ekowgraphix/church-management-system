<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n";
echo "╔══════════════════════════════════════════════╗\n";
echo "║   COMPLETE FIX - All Issues                  ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

try {
    // 1. Equipment Categories
    echo "📦 Creating Equipment Categories...\n";
    $equipCats = [
        ['name' => 'Audio/Visual', 'description' => 'Sound and video equipment'],
        ['name' => 'Musical Instruments', 'description' => 'Instruments'],
        ['name' => 'Furniture', 'description' => 'Church furniture'],
    ];
    foreach ($equipCats as $cat) {
        DB::table('equipment_categories')->insertOrIgnore(array_merge($cat, [
            'created_at' => now(), 'updated_at' => now()
        ]));
    }
    echo "   ✅ Equipment categories created\n\n";

    // 2. Equipment
    echo "🔧 Creating Equipment...\n";
    $avCat = DB::table('equipment_categories')->where('name', 'Audio/Visual')->first();
    if ($avCat) {
        $equipment = [
            ['name' => 'Sound System', 'equipment_code' => 'EQP-001', 'category_id' => $avCat->id, 'description' => 'Main sound system', 'status' => 'available', 'condition' => 'good'],
            ['name' => 'Projector', 'equipment_code' => 'EQP-002', 'category_id' => $avCat->id, 'description' => 'HD projector', 'status' => 'available', 'condition' => 'good'],
            ['name' => 'Microphones', 'equipment_code' => 'EQP-003', 'category_id' => $avCat->id, 'description' => 'Wireless mics', 'status' => 'available', 'condition' => 'good'],
        ];
        foreach ($equipment as $item) {
            DB::table('equipment')->insertOrIgnore(array_merge($item, [
                'created_at' => now(), 'updated_at' => now()
            ]));
        }
        echo "   ✅ Equipment items created\n\n";
    }

    // 3. Visitors
    echo "👥 Creating Visitors...\n";
    $visitors = [
        ['first_name' => 'John', 'last_name' => 'Visitor', 'phone' => '0241234567', 'email' => 'john@example.com', 'visit_date' => now()->subDays(2)->format('Y-m-d'), 'visit_type' => 'first_time'],
        ['first_name' => 'Mary', 'last_name' => 'Guest', 'phone' => '0242345678', 'email' => 'mary@example.com', 'visit_date' => now()->subDays(5)->format('Y-m-d'), 'visit_type' => 'returning'],
        ['first_name' => 'Peter', 'last_name' => 'Newcomer', 'phone' => '0243456789', 'email' => 'peter@example.com', 'visit_date' => now()->subDays(1)->format('Y-m-d'), 'visit_type' => 'first_time'],
    ];
    foreach ($visitors as $visitor) {
        DB::table('visitors')->insertOrIgnore(array_merge($visitor, [
            'created_at' => now(), 'updated_at' => now()
        ]));
    }
    echo "   ✅ Visitors created\n\n";

    // 4. Services
    echo "⛪ Creating Services...\n";
    $services = [
        ['name' => 'Sunday Morning Service', 'service_date' => now()->format('Y-m-d'), 'service_time' => '09:00:00', 'service_type' => 'sunday_service', 'status' => 'scheduled'],
        ['name' => 'Wednesday Bible Study', 'service_date' => now()->addDays(3)->format('Y-m-d'), 'service_time' => '18:00:00', 'service_type' => 'bible_study', 'status' => 'scheduled'],
    ];
    foreach ($services as $service) {
        DB::table('services')->insertOrIgnore(array_merge($service, [
            'created_at' => now(), 'updated_at' => now()
        ]));
    }
    echo "   ✅ Services created\n\n";

    // 5. SMS Templates
    echo "📱 Creating SMS Templates...\n";
    $templates = [
        ['name' => 'Welcome', 'content' => 'Welcome to our church! God bless you.', 'category' => 'welcome', 'is_active' => true],
        ['name' => 'Reminder', 'content' => 'Service reminder: Sunday 9 AM', 'category' => 'reminder', 'is_active' => true],
        ['name' => 'Birthday', 'content' => 'Happy Birthday! God bless you.', 'category' => 'birthday', 'is_active' => true],
    ];
    foreach ($templates as $template) {
        DB::table('sms_templates')->insertOrIgnore(array_merge($template, [
            'created_at' => now(), 'updated_at' => now()
        ]));
    }
    echo "   ✅ SMS templates created\n\n";

    // 6. Followup Types
    echo "📋 Creating Followup Types...\n";
    $followupTypes = [
        ['name' => 'New Member', 'description' => 'Follow up with new members'],
        ['name' => 'Visitor', 'description' => 'Follow up with visitors'],
        ['name' => 'Sick Member', 'description' => 'Check on sick members'],
    ];
    foreach ($followupTypes as $type) {
        DB::table('followup_types')->insertOrIgnore(array_merge($type, [
            'created_at' => now(), 'updated_at' => now()
        ]));
    }
    echo "   ✅ Followup types created\n\n";

    echo "╔══════════════════════════════════════════════╗\n";
    echo "║   ✅ ALL FIXES COMPLETED SUCCESSFULLY!       ║\n";
    echo "╚══════════════════════════════════════════════╝\n\n";
    
    echo "📊 Summary:\n";
    echo "   • Equipment Categories: ✅\n";
    echo "   • Equipment Items: ✅\n";
    echo "   • Visitors: ✅\n";
    echo "   • Services: ✅\n";
    echo "   • SMS Templates: ✅\n";
    echo "   • Followup Types: ✅\n";
    echo "   • Donation Categories: ✅ (already created)\n";
    echo "   • Expense Categories: ✅ (already created)\n\n";
    
    echo "🎉 All pages should now display data!\n\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
