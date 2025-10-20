<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Seeding donation categories...\n";

$categories = [
    ['name' => 'Tithes', 'description' => 'Regular tithes', 'is_active' => true],
    ['name' => 'Offerings', 'description' => 'General offerings', 'is_active' => true],
    ['name' => 'Special Offerings', 'description' => 'Special purpose offerings', 'is_active' => true],
    ['name' => 'Building Fund', 'description' => 'Church building fund', 'is_active' => true],
    ['name' => 'Missions', 'description' => 'Missionary support', 'is_active' => true],
];

foreach ($categories as $category) {
    DB::table('donation_categories')->updateOrInsert(
        ['name' => $category['name']],
        array_merge($category, [
            'created_at' => now(),
            'updated_at' => now()
        ])
    );
    echo "✓ Created: {$category['name']}\n";
}

echo "\nSeeding expense categories...\n";

$expenseCategories = [
    ['name' => 'Utilities', 'description' => 'Electricity, water, internet', 'is_active' => true],
    ['name' => 'Salaries', 'description' => 'Staff salaries', 'is_active' => true],
    ['name' => 'Maintenance', 'description' => 'Building maintenance', 'is_active' => true],
    ['name' => 'Office Supplies', 'description' => 'Stationery and supplies', 'is_active' => true],
    ['name' => 'Events', 'description' => 'Church events and programs', 'is_active' => true],
];

foreach ($expenseCategories as $category) {
    DB::table('expense_categories')->updateOrInsert(
        ['name' => $category['name']],
        array_merge($category, [
            'created_at' => now(),
            'updated_at' => now()
        ])
    );
    echo "✓ Created: {$category['name']}\n";
}

echo "\n✅ All categories seeded successfully!\n";
