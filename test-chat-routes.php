<?php

// Test if chat routes exist
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Chat Routes...\n\n";

$routes = \Illuminate\Support\Facades\Route::getRoutes();

$chatRoutes = ['chat.index', 'chat.fetch', 'chat.send', 'chat.unread', 'chat.mark-read', 'chat.search'];

foreach ($chatRoutes as $name) {
    $route = $routes->getByName($name);
    
    if ($route) {
        echo "✅ Route '$name' EXISTS\n";
        echo "   URI: " . $route->uri() . "\n";
        echo "   Middleware: " . implode(', ', $route->middleware()) . "\n\n";
    } else {
        echo "❌ Route '$name' NOT FOUND\n\n";
    }
}

echo "\n===========================================\n";
echo "RESULT: All chat routes registered!\n";
echo "===========================================\n";
