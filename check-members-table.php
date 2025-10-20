<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Checking members table structure...\n\n";

$columns = DB::select("PRAGMA table_info(members)");

echo "Members Table Columns:\n";
echo str_repeat("=", 80) . "\n";
printf("%-20s %-15s %-10s %-10s %-10s\n", "Column", "Type", "NotNull", "Default", "PK");
echo str_repeat("-", 80) . "\n";

foreach ($columns as $column) {
    printf("%-20s %-15s %-10s %-10s %-10s\n", 
        $column->name, 
        $column->type, 
        $column->notnull ? 'YES' : 'NO',
        $column->dflt_value ?? 'NULL',
        $column->pk ? 'YES' : 'NO'
    );
}

echo "\n";
