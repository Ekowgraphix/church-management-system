<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking users table structure...\n\n";

try {
    // SQLite compatible query
    $columns = DB::select("PRAGMA table_info(users)");
    
    echo "Users Table Columns:\n";
    echo "====================\n";
    foreach ($columns as $column) {
        echo "- {$column->name} ({$column->type})\n";
    }
    
    echo "\n";
    
    // Check if is_active exists
    $hasIsActive = collect($columns)->contains('name', 'is_active');
    if ($hasIsActive) {
        echo "✅ is_active column EXISTS\n";
    } else {
        echo "❌ is_active column MISSING - needs to be added\n";
    }
    
    // Check if phone exists
    $hasPhone = collect($columns)->contains('name', 'phone');
    if ($hasPhone) {
        echo "✅ phone column EXISTS\n";
    } else {
        echo "❌ phone column MISSING - needs to be added\n";
    }
    
    echo "\n";
    echo "Testing User model...\n";
    $user = \App\Models\User::first();
    if($user) {
        echo "✅ Can query users table\n";
        echo "Sample user: {$user->name} ({$user->email})\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
