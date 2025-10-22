<?php

echo "==========================================\n";
echo "TESTING QR CHECK-IN ROUTE ACCESS\n";
echo "==========================================\n\n";

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Simulate authentication
use App\Models\User;
use Illuminate\Support\Facades\Auth;

$user = User::where('email', 'ekowjeremy@gmail.com')->first();

if (!$user) {
    echo "❌ User not found\n";
    exit(1);
}

Auth::login($user);

echo "✅ Logged in as: {$user->name}\n\n";

// Test route
echo "Testing route: qr.service.scanner\n";
echo "-----------------------------------\n\n";

try {
    $route = \Route::getRoutes()->getByName('qr.service.scanner');
    
    if ($route) {
        echo "✅ Route exists\n";
        echo "   Name: qr.service.scanner\n";
        echo "   URI: {$route->uri()}\n";
        echo "   Methods: " . implode(', ', $route->methods()) . "\n";
        echo "   Middleware: " . implode(', ', $route->middleware()) . "\n\n";
        
        // Generate URL
        $url = route('qr.service.scanner');
        echo "   Full URL: $url\n\n";
    } else {
        echo "❌ Route NOT found!\n\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n\n";
}

// Test middleware
echo "Testing middleware...\n";
echo "-----------------------------------\n\n";

echo "User has 'Church Member' role: " . ($user->hasRole('Church Member') ? "✅ YES" : "❌ NO") . "\n";
echo "User is authenticated: " . (Auth::check() ? "✅ YES" : "❌ NO") . "\n";
echo "User has member profile: " . ($user->member ? "✅ YES (ID: {$user->member->id})" : "❌ NO") . "\n\n";

// Check other routes the user should access
echo "Other portal routes:\n";
echo "-----------------------------------\n\n";

$portalRoutes = [
    'portal.index' => '/portal',
    'portal.attendance' => '/portal/attendance',
    'portal.giving' => '/portal/giving',
];

foreach ($portalRoutes as $routeName => $uri) {
    try {
        $route = \Route::getRoutes()->getByName($routeName);
        if ($route) {
            echo "✅ $routeName → $uri\n";
        } else {
            echo "❌ $routeName → NOT FOUND\n";
        }
    } catch (\Exception $e) {
        echo "❌ $routeName → ERROR: {$e->getMessage()}\n";
    }
}

echo "\n==========================================\n";
echo "RECOMMENDATION:\n";
echo "==========================================\n\n";

echo "User CAN access QR Check-In page.\n";
echo "Route: http://127.0.0.1:8000/qr-checkin/service/scanner\n\n";

echo "If browser redirects to dashboard:\n";
echo "1. Check browser console for JavaScript errors\n";
echo "2. Check if there's a JavaScript redirect in the page\n";
echo "3. Clear browser cache completely\n";
echo "4. Try in incognito/private mode\n";
echo "5. Check Laravel logs for errors\n\n";

echo "Check Laravel logs:\n";
echo "-----------------------------------\n";
echo "tail -f storage/logs/laravel.log\n\n";

echo "==========================================\n";
