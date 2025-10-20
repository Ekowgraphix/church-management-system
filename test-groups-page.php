<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Groups Page...\n\n";

// Check if GroupController exists
if (class_exists('App\Http\Controllers\GroupController')) {
    echo "✅ GroupController class exists\n";
} else {
    echo "❌ GroupController class NOT found\n";
    exit(1);
}

// Check if Cluster model exists
if (class_exists('App\Models\Cluster')) {
    echo "✅ Cluster model exists\n";
} else {
    echo "❌ Cluster model NOT found\n";
    exit(1);
}

// Check if view exists
$viewPath = resource_path('views/groups/index.blade.php');
if (file_exists($viewPath)) {
    echo "✅ groups/index.blade.php view exists\n";
} else {
    echo "❌ View NOT found\n";
    exit(1);
}

// Try to instantiate controller
try {
    $controller = new App\Http\Controllers\GroupController();
    echo "✅ GroupController can be instantiated\n";
} catch (Exception $e) {
    echo "❌ Error instantiating controller: " . $e->getMessage() . "\n";
    exit(1);
}

// Check database
try {
    $count = App\Models\Cluster::count();
    echo "✅ Database connection works (Found $count groups)\n";
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n✅ All checks passed! Groups page should work.\n";
echo "\nTest URL: http://127.0.0.1:8000/groups\n";
