<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking gender constraint on members table...\n\n";

$sql = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name='members'");

if (!empty($sql)) {
    $createStatement = $sql[0]->sql;
    
    echo "Members Table Definition:\n";
    echo str_repeat("=", 80) . "\n";
    echo $createStatement . "\n";
    echo str_repeat("=", 80) . "\n\n";
    
    // Look for gender constraint
    if (preg_match('/gender[^,]*CHECK[^)]+\)/i', $createStatement, $matches)) {
        echo "Gender Constraint Found:\n";
        echo $matches[0] . "\n\n";
    } else if (preg_match('/gender[^,\n]+/i', $createStatement, $matches)) {
        echo "Gender Field Definition:\n";
        echo $matches[0] . "\n\n";
    }
}
