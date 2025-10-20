<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking route conflicts...\n\n";

$routes = \Illuminate\Support\Facades\Route::getRoutes();

$routeNames = ['events.index', 'chat.index', 'devotional.index', 'prayer-requests.index', 'ai.chat', 'notifications.index'];

foreach ($routeNames as $name) {
    $route = $routes->getByName($name);
    
    if ($route) {
        echo "Route: $name\n";
        echo "  URI: " . $route->uri() . "\n";
        echo "  Method: " . implode('|', $route->methods()) . "\n";
        echo "  Action: " . $route->getActionName() . "\n";
        echo "  Middleware: " . implode(', ', $route->middleware()) . "\n";
        echo "\n";
    } else {
        echo "Route: $name - NOT FOUND\n\n";
    }
}

echo "Checking for duplicate routes...\n\n";

$uris = ['events', 'chat', 'devotional', 'prayer-requests'];

foreach ($uris as $uri) {
    $matchingRoutes = [];
    
    foreach ($routes as $route) {
        if (strpos($route->uri(), $uri) === 0) {
            $matchingRoutes[] = [
                'uri' => $route->uri(),
                'method' => implode('|', $route->methods()),
                'name' => $route->getName(),
                'middleware' => $route->middleware()
            ];
        }
    }
    
    if (!empty($matchingRoutes)) {
        echo "Routes starting with '$uri':\n";
        foreach ($matchingRoutes as $r) {
            $middleware = implode(', ', $r['middleware']);
            echo "  {$r['method']} {$r['uri']} ({$r['name']}) [$middleware]\n";
        }
        echo "\n";
    }
}
